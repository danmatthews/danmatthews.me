<?php

namespace App\Models;

use App\Service\MarkdownRenderer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Lukeraymonddowning\SelfHealingUrls\Concerns\HasSelfHealingUrls;
use Sushi\Sushi;

class BlogPost extends Model
{
    use HasFactory, Sushi;
    use HasSelfHealingUrls;

    public $incrementing = false;

    protected string $slug = 'title';

    public function getRows()
    {
        $getPosts = (fn() => collect(File::allFiles(resource_path('views/posts')))
            ->map(function ($file) {
                return (new MarkdownRenderer())->render(file_get_contents($file->getRealPath()));
            })
            ->sortByDesc(fn($s) => $s->date->timestamp)
            ->map(fn($s) => array_merge($s->toArray(), [
                'published' => $s->published ?? true,
                'date' => $s->date->format('F jS, Y')
            ]))
            ->filter(fn($p) => app()->environment('production') ? $p['published'] : true)
            ->toArray()
        );

        return app()->environment('production')
            ? Cache::rememberForever(implode(',', [
                __CLASS__,
                __FUNCTION__
            ]), $getPosts)
            : $getPosts();
    }
}
