<?php

namespace App\Actions;

use App\Models\BlogPost;
use App\Models\Tag;
use App\Service\ContentParser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class BuildAndCacheLinks
{
    public function handle(): Collection
    {
        $this->truncatePosts();
        return $this->shouldCache() ? Cache::rememberForever('links.index', fn() => $this->getPosts()) : $this->getPosts();
    }

    public function getPosts(): Collection
    {
        $parser = new ContentParser();
        return collect(File::allFiles(base_path('content/links')))
            ->map(function ($file) use ($parser) {
                return $parser->parse(file_get_contents($file->getRealPath()));
            });
    }

    private function shouldCache(): bool
    {
        return config('graphein.enable_cache');
    }

    private function truncatePosts()
    {
        Tag::truncate();
        BlogPost::truncate();
    }
}
