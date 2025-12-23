<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $posts = fn() => BlogPost::orderBy("date", "DESC")->limit(3)->get()->map(
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
        );

        return Inertia::render("Index", [
            "posts" => $posts,
            "pageTitle" => "Welcome",
            "intro" => config("site.posts"),
        ]);
    }
}
