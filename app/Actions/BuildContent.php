<?php

namespace App\Actions;

use App\Data\FrontMatter;
use App\Data\LinkFrontMatter;
use App\Models\BlogLink;
use App\Models\BlogPost;
use App\Models\Content;
use App\Models\Tag;
use App\Service\ContentParser;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class BuildContent
{
    public function handle(): void
    {
        $this->truncateContent();
        $parser = new ContentParser();
        $this->buildPosts($parser);
        $this->buildLinks($parser);
//        $this->shouldCache() ? Cache::rememberForever('posts.index', fn() => $this->getPosts()) : $this->getPosts();
//        $this->shouldCache() ? Cache::rememberForever('links.index', fn() => $this->getPosts()) : $this->getPosts();
    }

    public function buildPosts(ContentParser $parser): Collection
    {
        return collect(File::allFiles(base_path('content/posts')))
            ->map(function ($file) use ($parser) {
                return $parser->parse(file_get_contents($file->getRealPath()));
            })
            ->each(function (FrontMatter $frontMatter) {
                BlogPost::create([
                    ...$frontMatter->toArray()
//                'date' => $frontMatter->date,
//                'title' => $frontMatter->title,
//                'slug' => $frontMatter->slug,
//                'published' => $frontMatter->published,
//                'content' => $frontMatter->content,
                ]);
            });
    }

    public function buildLinks(ContentParser $parser): Collection
    {
        return collect(File::allFiles(base_path('content/links')))
            ->map(function ($file) use ($parser) {
                return $parser->parseLink(file_get_contents($file->getRealPath()));
            })
            ->each(function (LinkFrontMatter $frontMatter) {
                BlogLink::create($frontMatter->toArray());
            });
    }

    private function shouldCache(): bool
    {
        return config('graphein.enable_cache');
    }

    private function truncateContent()
    {
        Tag::truncate();
        BlogPost::truncate();
        BlogLink::truncate();
    }
}
