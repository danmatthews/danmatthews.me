<?php

namespace App\Graphein\Processors;

use App\Data\GrapheinPost;
use App\Graphein\Contracts\PostProcessor;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class GenerateOgImage implements PostProcessor
{
    public function __invoke(GrapheinPost $post, string $content): void
    {
        File::ensureDirectoryExists(storage_path('app/public/opengraph'));

        $html = View::make('og-image', [
            'title' => $post->title,
            'excerpt' => $post->excerpt,
            'url' => route('posts.show', ['blog_post' => "{$post->slug}-{$post->id}"]),
        ])->render();

        Browsershot::html($html)
            ->windowSize(1200, 630)
            ->noSandbox()
            ->waitUntilNetworkIdle()
            ->save(storage_path("app/public/opengraph/{$post->id}.png"));
    }
}
