<?php

namespace App\Models;


use App\Data\FrontMatter;
use Illuminate\Database\Eloquent\Casts\AsCollection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Cache;
use App\Actions\BuildContent;
use Spatie\Feed\Feedable;
use Spatie\Feed\FeedItem;
use Sushi\Sushi;

class BlogPost extends Model implements Feedable
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

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function getRouteKey()
    {
        return ($this->slug) . '-' . $this->id;
    }

    public function toFeedItem(): FeedItem
    {
        return FeedItem::create()
            ->id($this->id)
            ->title($this->title)
            ->summary($this->excerpt)
            ->updated($this->date)
            ->link(route('posts.show', ['blog_post' => $this]))
            ->authorName("Dan Matthews")
            ->authorEmail("dan@danmatthews.me");
    }

    public function getFeedItems()
    {
        return self::orderBy('date', 'desc')->get();
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
