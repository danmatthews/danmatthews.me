<?php

namespace App\Models;

use App\Service\MarkdownRenderer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Lukeraymonddowning\SelfHealingUrls\Concerns\HasSelfHealingUrls;
use Sushi\Sushi;

class BlogPost extends Model
{
    use HasFactory, Sushi;


    public $incrementing = false;

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

        return app()->environment('production', 'local')
            ? Cache::rememberForever(implode(',', [
                __CLASS__,
                __FUNCTION__
            ]), $getPosts)
            : $getPosts();
    }

    public function getRouteKey()
    {
        return ($this->slug) . '-' . $this->id;
    }

    public function resolveRouteBinding($value, $field = null)
    {
        $id = last(explode('-', $value));
        $model = parent::resolveRouteBinding($id, $field);

        // Check to see if the model's route key matches
        // the URL value, or if the model wasn't found.
        // (Laravel will handle throwing the 404.)
        if (!$model || $model->getRouteKey() === $value) {
            // If so, return to Laravel.
            return $model;
        }

        // If not, redirect to the right URL.
        throw new HttpResponseException(
            redirect()->route('posts.show', ['blog_post' => $model])
        );
    }

}
