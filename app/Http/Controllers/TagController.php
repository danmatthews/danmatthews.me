<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use App\Models\PostTag;
use Inertia\Inertia;
use Inertia\Response;

class TagController extends Controller
{
    public function index(): Response
    {
        $tags = PostTag::get()
            ->groupBy("tag")
            ->map(function ($posts, $tag) {
                return [
                    "tag" => $tag,
                    "count" => $posts->count(),
                ];
            })
            ->values();

        return Inertia::render("Tags/Index", [
            "tags" => $tags,
        ]);
    }

    public function show(string $id): Response
    {
        $posts = BlogPost::orderBy("date", "DESC")
            ->whereHas("tags", fn($query) => $query->where("tag", $id))
            ->paginate(10)
            ->through(
                fn(BlogPost $post) => [
                    "id" => $post->id,
                    "title" => $post->title,
                    "slug" => $post->slug,
                    "excerpt" => $post->excerpt,
                    "date" => [
                        "iso" => $post->date->format("c"),
                        "formatted" => $post->date->format("jS F Y"),
                    ],
                    "url" => route("posts.show", ["blog_post" => $post]),
                ],
            )
            ->withQueryString();

        return Inertia::render("Tags/Show", [
            "tag" => $id,
            "posts" => $posts,
        ]);
    }
}
