<?php

namespace App\Actions;

use App\Data\GrapheinPost;
use App\Graphein\Graphein;
use App\Service\PostContentParser;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class BuildAndCachePosts
{
    private const BASE_DIR = 'graphein';
    private const POSTS_DIR = 'graphein/posts';
    private const PAGES_DIR = 'graphein/pages';
    private const MANIFEST_PATH = 'graphein/graphein-manifest.json';

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

        $posts = $this->writePostContents($disk)
            ->sortByDesc(fn (array $post) => $post['frontMatter']['date'])
            ->values();

        $pagination = $this->writePages($disk, $posts);

        $this->writeManifest($disk, $posts, $pagination);

        return $posts;
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

                $disk->put($contentPath, $html);

                $meta = [
                    'frontMatter' => $frontMatter,
                    'content_url' => $disk->url($contentPath),
                ];

                $disk->put($metaPath, json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

                $this->runProcessors($processors, $frontMatter, $disk->url($contentPath), $html);

                return [
                    ...$meta,
                    'content_path' => $contentPath,
                    'meta_url' => $disk->url($metaPath),
                    'meta_path' => $metaPath,
                ];
            });
    }

    private function runProcessors(array $processors, array $frontMatter, string $contentUrl, string $html): void
    {
        if ($processors === []) {
            return;
        }

        $post = GrapheinPost::from([...$frontMatter, 'content_url' => $contentUrl]);

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

    private function writePages(Filesystem $disk, Collection $posts): array
    {
        $perPage = (int) config('graphein.per_page');
        $total = $posts->count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        $pagination = [];

        for ($page = 1; $page <= $lastPage; $page++) {
            $items = $posts->slice(($page - 1) * $perPage, $perPage)->values();
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
                'id' => $post['frontMatter']['id'],
                'url' => $post['content_url'],
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
