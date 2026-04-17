<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use Intrfce\Graphein\Data\GrapheinEntry;
use Intrfce\Graphein\Data\GrapheinLink;
use Intrfce\Graphein\Data\GrapheinPost;
use Intrfce\Graphein\Enums\ContentType;
use Intrfce\Graphein\Facades\Graphein;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $posts = fn () => Graphein::getPaginatedPosts()
            ->getCollection()
            ->take(3)
            ->map(fn (GrapheinEntry $entry) => $this->transformEntry($entry))
            ->values();

        return Inertia::render("Index", [
            "posts" => $posts,
            "pageTitle" => "Welcome",
            "intro" => config("site.posts"),
        ]);
    }

    private function transformEntry(GrapheinEntry $entry): array
    {
        return match ($entry->type) {
            ContentType::POST => $this->transformPost($entry->data),
            ContentType::LINK => $this->transformLink($entry->data),
        };
    }

    private function transformPost(GrapheinPost $post): array
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
            "url" => route("posts.show", ["blog_post" => "{$post->slug}-{$post->id}"]),
        ];
    }

    private function transformLink(GrapheinLink $link): array
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
            "url" => $link->url,
        ];
    }
}
