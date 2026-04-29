<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Support\Str;
use Intrfce\Graphein\Data\GrapheinEntry;
use Intrfce\Graphein\Data\GrapheinLink;
use Intrfce\Graphein\Data\GrapheinPost;
use Intrfce\Graphein\Enums\ContentType;

trait TransformsGrapheinEntries
{
    protected function transformEntry(GrapheinEntry $entry): array
    {
        return match ($entry->type) {
            ContentType::POST => $this->transformPost($entry->data),
            ContentType::LINK => $this->transformLink($entry->data),
        };
    }

    protected function transformPost(GrapheinPost $post): array
    {
        return [
            "type" => ContentType::POST->value,
            "id" => $post->id,
            "title" => $post->title,
            "slug" => $post->slug,
            "excerpt" => $post->excerpt,
            "date" => [
                "iso" => $post->date->format("c"),
                "formatted" => $post->date->format("jS F Y"),
            ],
            "topics" => $this->transformTopics($post->topics),
            "url" => route("posts.show", ["blog_post" => "{$post->slug}-{$post->id}"]),
        ];
    }

    protected function transformLink(GrapheinLink $link): array
    {
        return [
            "type" => ContentType::LINK->value,
            "id" => md5($link->url),
            "title" => $link->title,
            "excerpt" => $link->description,
            "date" => [
                "iso" => $link->date->format("c"),
                "formatted" => $link->date->format("jS F Y"),
            ],
            "topics" => $this->transformTopics($link->topics),
            "url" => $link->url,
        ];
    }

    /**
     * @param  array<int, string>  $topics
     * @return array<int, array{name: string, slug: string, url: string}>
     */
    protected function transformTopics(array $topics): array
    {
        return collect($topics)
            ->map(fn (string $name) => trim($name))
            ->filter()
            ->map(fn (string $name) => [
                "name" => $name,
                "slug" => Str::slug($name),
                "url" => route("topics.show", ["topic" => Str::slug($name)]),
            ])
            ->values()
            ->all();
    }
}
