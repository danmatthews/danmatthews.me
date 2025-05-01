<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Cache;
use App\Actions\BuildAndCachePosts;
use Sushi\Sushi;

class BlogPost extends Model
{
    use HasFactory, Sushi;


    public $incrementing = false;

    public function getRows()
    {
       

        return Cache::has('posts.index') ?
        Cache::get('posts.index')
        : (new BuildAndCachePosts)->handle();
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
