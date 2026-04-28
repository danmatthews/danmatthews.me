<?php

namespace Intrfce\Graphein\Actions;

use Carbon\Carbon;
use Closure;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intrfce\Graphein\Data\GrapheinPost;
use Intrfce\Graphein\Enums\ContentType;
use Intrfce\Graphein\Graphein;
use Intrfce\Graphein\Service\PostContentParser;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class BuildAndCachePosts
{
    private const BASE_DIR = 'graphein';
    private const POSTS_DIR = 'graphein/posts';
    private const PAGES_DIR = 'graphein/pages';
    private const TOPICS_DIR = 'graphein/topics';
    private const MANIFEST_PATH = 'graphein/graphein-manifest.json';
    private const TOPICS_INDEX_PATH = 'graphein/topics/index.json';
    private const LINKS_PATH = 'content/links.json';

    /** @var array<int, array{processor: string, post_id: string, post_title: string, message: string}> */
    private array $failures = [];

    private int $postsBuilt = 0;

    private ?Closure $onProgress = null;

    /**
     * Processor failures collected during the last build, in the order they occurred.
     *
     * @return array<int, array{processor: string, post_id: string, post_title: string, message: string}>
     */
    public function failures(): array
    {
        return $this->failures;
    }

    public function postsBuilt(): int
    {
        return $this->postsBuilt;
    }

    /**
     * @param  Closure(string $type, string $message): void|null  $onProgress
     */
    public function handle(?Closure $onProgress = null): Collection
    {
        $this->failures = [];
        $this->postsBuilt = 0;
        $this->onProgress = $onProgress;

        $disk = Storage::disk('public');

        $this->report('label', 'Resetting output directory');
        $this->resetOutput($disk);

        $this->report('label', 'Parsing and writing posts');
        $posts = $this->writePostContents($disk);
        $this->postsBuilt = $posts->count();

        $this->report('label', 'Loading links');
        $links = $this->loadLinks();

        $entries = $this->buildEntries($posts, $links);

        $this->report('label', 'Writing paginated pages');
        $pagination = $this->writePaginatedEntries($disk, $entries, self::PAGES_DIR);

        $this->report('label', 'Writing topic pages');
        $topics = $this->writeTopicPages($disk, $entries);

        $this->report('label', 'Writing manifest');
        $this->writeManifest($disk, $posts, $pagination, $topics);

        $this->report('success', "Built {$posts->count()} post(s)");

        return $entries;
    }

    private function report(string $type, string $message): void
    {
        if ($this->onProgress === null) {
            return;
        }

        ($this->onProgress)($type, $message);
    }

    private function resetOutput(Filesystem $disk): void
    {
        $disk->deleteDirectory(self::BASE_DIR);
        $disk->makeDirectory(self::POSTS_DIR);
        $disk->makeDirectory(self::PAGES_DIR);
        $disk->makeDirectory(self::TOPICS_DIR);
    }

    private function writePostContents(Filesystem $disk): Collection
    {
        $converter = (new PostContentParser())->converter;
        $processors = app(Graphein::class)->getPostProcessors();

        return collect(File::allFiles(base_path('content/posts')))
            ->map(function ($file) use ($converter, $disk, $processors) {
                $result = $converter->convert(file_get_contents($file->getRealPath()));

                if (! $result instanceof RenderedContentWithFrontMatter) {
                    throw new \RuntimeException("Post {$file->getFilename()} is missing required front matter");
                }

                $frontMatter = $result->getFrontMatter();
                $id = $frontMatter['id'];
                $html = $result->getContent();
                $contentPath = self::POSTS_DIR."/{$id}-content.html";
                $metaPath = self::POSTS_DIR."/{$id}-meta.json";
                $contentUrl = $disk->url($contentPath);

                $disk->put($contentPath, $html);

                $postData = [...$frontMatter, 'content_url' => $contentUrl];

                $disk->put($metaPath, json_encode($postData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

                $this->report('line', "  · {$postData['title']}");

                $this->runProcessors($processors, $postData, $html);

                return [
                    'data' => $postData,
                    'content_path' => $contentPath,
                    'meta_url' => $disk->url($metaPath),
                    'meta_path' => $metaPath,
                ];
            });
    }

    private function loadLinks(): Collection
    {
        $path = base_path(self::LINKS_PATH);

        if (! File::exists($path)) {
            return collect();
        }

        return collect(json_decode(File::get($path), true) ?? []);
    }

    private function buildEntries(Collection $posts, Collection $links): Collection
    {
        return $posts
            ->map(fn (array $post) => [
                'type' => ContentType::POST->value,
                'data' => $post['data'],
                'sort_date' => $post['data']['date'],
            ])
            ->concat($links->map(fn (array $link) => [
                'type' => ContentType::LINK->value,
                'data' => $link,
                'sort_date' => $link['date'],
            ]))
            ->sortByDesc('sort_date')
            ->values();
    }

    private function runProcessors(array $processors, array $postData, string $html): void
    {
        if ($processors === []) {
            return;
        }

        $post = GrapheinPost::from($postData);

        foreach ($processors as $processor) {
            try {
                app($processor)($post, $html);
            } catch (\Throwable $e) {
                $this->failures[] = [
                    'processor' => $processor,
                    'post_id' => $post->id,
                    'post_title' => $post->title,
                    'message' => $e->getMessage(),
                ];

                Log::error("Graphein processor [{$processor}] failed for post [{$post->id}]: {$e->getMessage()}", [
                    'exception' => $e,
                ]);

                $this->report('warning', class_basename($processor)." failed on {$post->title}");
            }
        }
    }

    private function writePaginatedEntries(Filesystem $disk, Collection $entries, string $dir): array
    {
        $perPage = (int) config('graphein.per_page');
        $total = $entries->count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        $pagination = [];

        for ($page = 1; $page <= $lastPage; $page++) {
            $items = $entries
                ->slice(($page - 1) * $perPage, $perPage)
                ->map(fn (array $entry) => ['type' => $entry['type'], 'data' => $entry['data']])
                ->values();
            $pagePath = $dir."/page-{$page}.json";

            $disk->put($pagePath, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            $pagination[(string) $page] = [
                'current_page' => $page,
                'first_page_url' => $disk->url($dir.'/page-1.json'),
                'from' => ($page - 1) * $perPage + 1,
                'last_page' => $lastPage,
                'last_page_url' => $disk->url($dir."/page-{$lastPage}.json"),
                'next_page_url' => $page < $lastPage
                    ? $disk->url($dir.'/page-'.($page + 1).'.json')
                    : null,
                'path' => $disk->url($pagePath),
                'per_page' => $perPage,
                'prev_page_url' => $page > 1
                    ? $disk->url($dir.'/page-'.($page - 1).'.json')
                    : null,
                'to' => min($page * $perPage, $total),
                'total' => $total,
            ];
        }

        return $pagination;
    }

    /**
     * Group entries by topic slug, write per-topic paginated pages, return per-topic metadata.
     *
     * @return array<string, array{name: string, slug: string, count: int, pagination: array}>
     */
    private function writeTopicPages(Filesystem $disk, Collection $entries): array
    {
        $bySlug = [];

        foreach ($entries as $entry) {
            $topics = $entry['data']['topics'] ?? [];

            foreach ($topics as $topic) {
                $name = trim((string) $topic);

                if ($name === '') {
                    continue;
                }

                $slug = Str::slug($name);

                if (! isset($bySlug[$slug])) {
                    $bySlug[$slug] = ['name' => $name, 'entries' => []];
                }

                $bySlug[$slug]['entries'][] = $entry;
            }
        }

        $topics = [];
        $indexEntries = [];

        foreach ($bySlug as $slug => $bucket) {
            $topicEntries = collect($bucket['entries']);
            $dir = self::TOPICS_DIR."/{$slug}";

            $disk->makeDirectory($dir);

            $pagination = $this->writePaginatedEntries($disk, $topicEntries, $dir);

            $this->report('line', "  · #{$bucket['name']} ({$topicEntries->count()})");

            $topics[$slug] = [
                'name' => $bucket['name'],
                'slug' => $slug,
                'count' => $topicEntries->count(),
                'pagination' => $pagination,
            ];

            $indexEntries[] = [
                'name' => $bucket['name'],
                'slug' => $slug,
                'count' => $topicEntries->count(),
            ];
        }

        usort($indexEntries, fn ($a, $b) => $b['count'] <=> $a['count'] ?: strcmp($a['name'], $b['name']));

        $disk->put(
            self::TOPICS_INDEX_PATH,
            json_encode($indexEntries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES),
        );

        return $topics;
    }

    private function writeManifest(Filesystem $disk, Collection $posts, array $pagination, array $topics): void
    {
        $files = [];

        foreach ($posts as $post) {
            $files[] = [
                'type' => 'post_content',
                'id' => $post['data']['id'],
                'url' => $post['data']['content_url'],
                'meta_url' => $post['meta_url'],
            ];
        }

        foreach ($pagination as $page => $meta) {
            $files[] = [
                'type' => 'page',
                'page' => (int) $page,
                'url' => $meta['path'],
            ];
        }

        foreach ($topics as $slug => $topic) {
            foreach ($topic['pagination'] as $page => $meta) {
                $files[] = [
                    'type' => 'topic_page',
                    'topic' => $slug,
                    'page' => (int) $page,
                    'url' => $meta['path'],
                ];
            }
        }

        $manifest = [
            'generated_at' => Carbon::now()->toIso8601String(),
            'pagination' => $pagination,
            'topics' => $topics,
            'files' => $files,
        ];

        $disk->put(self::MANIFEST_PATH, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
