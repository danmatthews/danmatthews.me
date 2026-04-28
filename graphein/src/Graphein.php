<?php

namespace Intrfce\Graphein;

use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Storage;
use Intrfce\Graphein\Contracts\PostProcessor;
use Intrfce\Graphein\Data\GrapheinEntry;
use Intrfce\Graphein\Data\GrapheinPost;
use Intrfce\Graphein\Data\GrapheinPostWithContent;

class Graphein
{
    private const MANIFEST_PATH = 'graphein/graphein-manifest.json';
    private const PAGES_DIR = 'graphein/pages';
    private const POSTS_DIR = 'graphein/posts';
    private const TOPICS_DIR = 'graphein/topics';
    private const TOPICS_INDEX_PATH = 'graphein/topics/index.json';

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

        return $this->paginatorFromMeta(
            $manifest['pagination'] ?? [],
            self::PAGES_DIR,
        );
    }

    public function getPaginatedPostsByTopic(string $slug): LengthAwarePaginator
    {
        $manifest = $this->loadManifest();
        $topic = $manifest['topics'][$slug] ?? null;

        if ($topic === null) {
            throw new \RuntimeException("Graphein topic [{$slug}] not found");
        }

        return $this->paginatorFromMeta(
            $topic['pagination'] ?? [],
            self::TOPICS_DIR."/{$slug}",
        );
    }

    public function getTopic(string $slug): ?array
    {
        $manifest = $this->loadManifest();
        $topic = $manifest['topics'][$slug] ?? null;

        if ($topic === null) {
            return null;
        }

        return [
            'name' => $topic['name'],
            'slug' => $topic['slug'],
            'count' => $topic['count'],
        ];
    }

    /** @return array<int, array{name: string, slug: string, count: int}> */
    public function getAllTopics(): array
    {
        $disk = $this->disk();

        if (! $disk->exists(self::TOPICS_INDEX_PATH)) {
            return [];
        }

        return json_decode($disk->get(self::TOPICS_INDEX_PATH), true) ?? [];
    }

    public function loadPostById(string $id): GrapheinPostWithContent
    {
        $disk = $this->disk();
        $metaPath = self::POSTS_DIR."/{$id}-meta.json";

        if (! $disk->exists($metaPath)) {
            throw new \RuntimeException("Graphein post [{$id}] not found");
        }

        return new GrapheinPostWithContent(
            meta: GrapheinPost::from(json_decode($disk->get($metaPath), true)),
            content: $disk->get(self::POSTS_DIR."/{$id}-content.html"),
        );
    }

    private function paginatorFromMeta(array $pagination, string $dir): LengthAwarePaginator
    {
        $currentPage = max(1, (int) Paginator::resolveCurrentPage());

        $meta = $pagination['1'] ?? [
            'total' => 0,
            'per_page' => (int) config('graphein.per_page'),
        ];

        $items = isset($pagination[(string) $currentPage])
            ? collect($this->loadPage($dir, $currentPage))->map(fn (array $item) => GrapheinEntry::fromPageEntry($item))
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

    private function loadManifest(): array
    {
        return json_decode($this->disk()->get(self::MANIFEST_PATH), true);
    }

    private function loadPage(string $dir, int $page): array
    {
        return json_decode($this->disk()->get($dir."/page-{$page}.json"), true);
    }

    private function disk(): Filesystem
    {
        return Storage::disk('public');
    }
}
