<?php

namespace App\Models;


use App\Data\FrontMatter;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Cache;
use App\Actions\BuildAndCachePosts;
use Sushi\Sushi;

class BlogPost extends Model
{
    use HasFactory;

    public $incrementing = false;

    protected $guarded = [];

    protected function casts(): array
    {
        return [
            'frontMatter' => FrontMatter::class,
            'tags' => AsCollection::class,
            'published' => 'bool',
            'date' => 'datetime',
        ];
    }

    public function tags(): HasMany
    {
        return $this->hasMany(PostTag::class);
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
