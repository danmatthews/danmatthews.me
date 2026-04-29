<?php

namespace Intrfce\Graphein\Actions;

use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
    private const MANIFEST_PATH = 'graphein/graphein-manifest.json';
    private const LINKS_PATH = 'content/links.json';

    /** @var array<int, array{processor: string, post_id: string, post_title: string, message: string}> */
    private array $failures = [];

    /**
     * Processor failures collected during the last build, in the order they occurred.
     *
     * @return array<int, array{processor: string, post_id: string, post_title: string, message: string}>
     */
    public function failures(): array
    {
        return $this->failures;
    }

    public function handle(): Collection
    {
        $this->failures = [];

        $disk = Storage::disk('public');

        $this->resetOutput($disk);

        $posts = $this->writePostContents($disk);
        $links = $this->loadLinks();

        $entries = $this->buildEntries($posts, $links);

        $pagination = $this->writePages($disk, $entries);

        $this->writeManifest($disk, $posts, $pagination);

        return $entries;
    }

    private function resetOutput(Filesystem $disk): void
    {
        $disk->deleteDirectory(self::BASE_DIR);
        $disk->makeDirectory(self::POSTS_DIR);
        $disk->makeDirectory(self::PAGES_DIR);
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

                $this->runProcessors($processors, $postData, $html);

                return [
                    'data' => $postData,
                    'content_path' => $contentPath,
                    'meta_url' => $disk->url($metaPath),
                    'meta_path' => $metaPath,
                ];
            });
    }

    private function rootDomain(?string $url): ?string
    {
        if (! $url) {
            return null;
        }

        $host = parse_url($url, PHP_URL_HOST);

        if (! $host) {
            return null;
        }

        return preg_replace('/^www\./', '', $host);
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
                'data' => [...$link, 'root_domain' => $this->rootDomain($link['url'] ?? null)],
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
            }
        }
    }

    private function writePages(Filesystem $disk, Collection $entries): array
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
            $pagePath = self::PAGES_DIR."/page-{$page}.json";

            $disk->put($pagePath, json_encode($items, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            $pagination[(string) $page] = [
                'current_page' => $page,
                'first_page_url' => $disk->url(self::PAGES_DIR.'/page-1.json'),
                'from' => ($page - 1) * $perPage + 1,
                'last_page' => $lastPage,
                'last_page_url' => $disk->url(self::PAGES_DIR."/page-{$lastPage}.json"),
                'next_page_url' => $page < $lastPage
                    ? $disk->url(self::PAGES_DIR.'/page-'.($page + 1).'.json')
                    : null,
                'path' => $disk->url($pagePath),
                'per_page' => $perPage,
                'prev_page_url' => $page > 1
                    ? $disk->url(self::PAGES_DIR.'/page-'.($page - 1).'.json')
                    : null,
                'to' => min($page * $perPage, $total),
                'total' => $total,
            ];
        }

        return $pagination;
    }

    private function writeManifest(Filesystem $disk, Collection $posts, array $pagination): void
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

        $manifest = [
            'generated_at' => Carbon::now()->toIso8601String(),
            'pagination' => $pagination,
            'files' => $files,
        ];

        $disk->put(self::MANIFEST_PATH, json_encode($manifest, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}
