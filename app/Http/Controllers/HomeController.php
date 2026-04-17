<?php

namespace App\Http\Controllers;

use App\Data\GrapheinPost;
use App\Facades\Graphein;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $posts = fn () => Graphein::getPaginatedPosts()
            ->getCollection()
            ->take(3)
            ->map(fn (GrapheinPost $post) => [
                "id" => $post->id,
                "title" => $post->title,
                "slug" => $post->slug,
                "excerpt" => $post->excerpt,
                "date" => [
                    "iso" => $post->date->format("c"),
                    "formatted" => $post->date->format("jS F Y"),
                ],
                "url" => route("posts.show", ["blog_post" => "{$post->slug}-{$post->id}"]),
            ])
            ->values();

        return Inertia::render("Index", [
            "posts" => $posts,
            "pageTitle" => "Welcome",
            "intro" => config("site.posts"),
        ]);
    }
}
