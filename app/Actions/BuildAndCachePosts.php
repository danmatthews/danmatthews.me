<?php

namespace App\Actions;

use App\Models\BlogPost;
use App\Models\PostTag;
use App\Service\PostContentParser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class BuildAndCachePosts
{
    public function handle(): Collection
    {
        $this->truncatePosts();
        return $this->shouldCache() ? Cache::rememberForever('posts.index', fn() => $this->getPosts()) : $this->getPosts();
    }

    public function getPosts(): Collection
    {
        $parser = new PostContentParser();
        return collect(File::allFiles(base_path('content/posts')))
            ->map(function ($file) use ($parser) {
                return $parser->parse(file_get_contents($file->getRealPath()));
            });
    }

    private function shouldCache(): bool
    {
        return config('graphein.enable_cache');
    }

    private function truncatePosts(): void
    {
        PostTag::truncate();
        BlogPost::truncate();
    }
}
