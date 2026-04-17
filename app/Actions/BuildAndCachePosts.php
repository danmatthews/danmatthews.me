<?php

namespace App\Actions;

use App\Service\PostContentParser;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use League\CommonMark\Extension\FrontMatter\Output\RenderedContentWithFrontMatter;

class BuildAndCachePosts
{
    private const BASE_DIR = 'graphein';
    private const POSTS_DIR = 'graphein/posts';
    private const PAGES_DIR = 'graphein/pages';
    private const MANIFEST_PATH = 'graphein/graphein-manifest.json';

    public function handle(): Collection
    {
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

        return collect(File::allFiles(base_path('content/posts')))
            ->map(function ($file) use ($converter, $disk) {
                $result = $converter->convert(file_get_contents($file->getRealPath()));

                if (! $result instanceof RenderedContentWithFrontMatter) {
                    throw new \RuntimeException("Post {$file->getFilename()} is missing required front matter");
                }

                $frontMatter = $result->getFrontMatter();
                $id = $frontMatter['id'];
                $contentPath = self::POSTS_DIR."/{$id}-content.html";
                $metaPath = self::POSTS_DIR."/{$id}-meta.json";

                $disk->put($contentPath, $result->getContent());

                $meta = [
                    'frontMatter' => $frontMatter,
                    'content_url' => $disk->url($contentPath),
                ];

                $disk->put($metaPath, json_encode($meta, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

                return [
                    ...$meta,
                    'content_path' => $contentPath,
                    'meta_url' => $disk->url($metaPath),
                    'meta_path' => $metaPath,
                ];
            });
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
