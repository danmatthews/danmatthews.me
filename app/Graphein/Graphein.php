<?php

namespace App\Graphein;

use App\Data\GrapheinPost;
use App\Data\GrapheinPostWithContent;
use App\Graphein\Contracts\PostProcessor;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;

class Graphein
{
    private const MANIFEST_PATH = 'graphein/graphein-manifest.json';
    private const PAGES_DIR = 'graphein/pages';
    private const POSTS_DIR = 'graphein/posts';

    /** @var array<class-string<PostProcessor>> */
    private array $postProcessors = [];

    /**
     * Set the list of post processors to run, in order, after each post is built.
     *
     * @param  array<class-string<PostProcessor>>  $processors
     */
    public function postProcessors(array $processors): self
    {
        foreach ($processors as $processor) {
            if (! is_subclass_of($processor, PostProcessor::class)) {
                throw new \InvalidArgumentException(
                    "Graphein post processor [{$processor}] must implement " . PostProcessor::class
                );
            }
        }

        $this->postProcessors = array_values($processors);

        return $this;
    }

    /** @return array<class-string<PostProcessor>> */
    public function getPostProcessors(): array
    {
        return $this->postProcessors;
    }

    public function getPaginatedPosts(): LengthAwarePaginator
    {
        $manifest = $this->loadManifest();
        $currentPage = max(1, (int) Paginator::resolveCurrentPage());

        $meta = $manifest['pagination']['1'] ?? [
            'total' => 0,
            'per_page' => (int) config('graphein.per_page'),
        ];

        $items = isset($manifest['pagination'][(string) $currentPage])
            ? collect($this->loadPage($currentPage))->map(fn (array $item) => GrapheinPost::from([
                ...$item['frontMatter'],
                'content_url' => $item['content_url'],
            ]))
            : collect();

        return new LengthAwarePaginator(
            items: $items,
            total: (int) $meta['total'],
            perPage: (int) $meta['per_page'],
            currentPage: $currentPage,
            options: [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ],
        );
    }

    public function loadPostById(string $id): GrapheinPostWithContent
    {
        $disk = $this->disk();
        $metaPath = self::POSTS_DIR."/{$id}-meta.json";

        if (! $disk->exists($metaPath)) {
            throw new \RuntimeException("Graphein post [{$id}] not found");
        }

        $meta = json_decode($disk->get($metaPath), true);

        return new GrapheinPostWithContent(
            meta: GrapheinPost::from([
                ...$meta['frontMatter'],
                'content_url' => $meta['content_url'],
            ]),
            content: $disk->get(self::POSTS_DIR."/{$id}-content.html"),
        );
    }

    private function loadManifest(): array
    {
        return json_decode($this->disk()->get(self::MANIFEST_PATH), true);
    }

    private function loadPage(int $page): array
    {
        return json_decode($this->disk()->get(self::PAGES_DIR."/page-{$page}.json"), true);
    }

    private function disk(): Filesystem
    {
        return Storage::disk('public');
    }
}
