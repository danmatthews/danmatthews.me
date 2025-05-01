<?php

namespace App\Actions;
use App\Service\MarkdownRenderer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
class BuildAndCachePosts {

    public function handle(): array
    {
        return Cache::rememberForever('posts.index', fn() => collect(File::allFiles(resource_path('views/posts')))
        ->map(function ($file) {
            return (new MarkdownRenderer())->render(file_get_contents($file->getRealPath()));
        })
        ->sortByDesc(fn($s) => $s->date->timestamp)
        ->map(fn($s) => array_merge($s->toArray(), [
            'published' => $s->published ?? true,
            'date' => $s->date->format('F jS, Y'),
            'months_ago' => $s->date->diffInMonths(now()),
        ]))
        ->filter(fn($p) => app()->environment('production') ? $p['published'] : true)
        ->all()
        );
    }
}