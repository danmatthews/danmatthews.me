<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SharesPostMeta;
use App\Models\BlogPost;
use Illuminate\Contracts\View\View;
use Inertia\Inertia;
use Inertia\Response;

class BlogPostController extends Controller
{
    use SharesPostMeta;

    public function index(): Response
    {
        $posts = fn() => BlogPost::orderBy("date", "DESC")
            ->paginate(50)
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

        $page = request("page", 1);

        return Inertia::render("Posts/Index", [
            "posts" => $posts,
            "pageTitle" => $page == 1 ? "Posts" : "Posts (Page {$page})",
            "intro" => config("site.posts"),
        ]);
    }

    public function show(BlogPost $blogPost): Response
    {
        $blogPost->load("tags");

        $this->sharePostMeta(
            title: $blogPost->title,
            excerpt: $blogPost->excerpt,
            url: url()->current(),
            ogImage: asset("storage/opengraph/" . $blogPost->id . ".png"),
        );

        return Inertia::render("Posts/Show", [
            'git_repo_url' => config('site.git_repo_url'),
            "post" => [
                "id" => $blogPost->id,
                "title" => $blogPost->title,
                "excerpt" => $blogPost->excerpt,
                "content" => $blogPost->content,
                "url" => url()->current(),
                "og_image" => asset(
                    "storage/opengraph/" . $blogPost->id . ".png",
                ),
                "date" => [
                    "iso" => $blogPost->date->format("c"),
                    "formatted" => $blogPost->date->format("jS F Y"),
                ],
                "monthsAgo" => now()->diffInMonths($blogPost->date),
                "tags" => $blogPost->tags?->pluck("tag") ?? [],
            ],
        ]);
    }

    public function ogImage(BlogPost $blogPost): View
    {
        return view("og-image", [
            "title" => $blogPost->title,
            "excerpt" => $blogPost->excerpt,
            "url" => route("posts.show", $blogPost),
        ]);
    }
}
