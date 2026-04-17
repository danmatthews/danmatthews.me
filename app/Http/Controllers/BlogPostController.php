<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SharesPostMeta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Exceptions\HttpResponseException;
use Inertia\Inertia;
use Inertia\Response;
use Intrfce\Graphein\Data\GrapheinEntry;
use Intrfce\Graphein\Data\GrapheinLink;
use Intrfce\Graphein\Data\GrapheinPost;
use Intrfce\Graphein\Data\GrapheinPostWithContent;
use Intrfce\Graphein\Enums\ContentType;
use Intrfce\Graphein\Facades\Graphein;

class BlogPostController extends Controller
{
    use SharesPostMeta;

    public function index(): Response
    {
        $posts = fn () => Graphein::getPaginatedPosts()
            ->through(fn (GrapheinEntry $entry) => $this->transformEntry($entry))
            ->withQueryString();

        $page = request("page", 1);

        return Inertia::render("Posts/Index", [
            "posts" => $posts,
            "pageTitle" => $page == 1 ? "Posts" : "Posts (Page {$page})",
            "intro" => config("site.posts"),
        ]);
    }

    public function show(string $blog_post): Response
    {
        $post = $this->resolvePost($blog_post);
        $meta = $post->meta;

        $this->sharePostMeta(
            title: $meta->title,
            excerpt: $meta->excerpt,
            url: url()->current(),
            ogImage: asset("storage/opengraph/{$meta->id}.png"),
        );

        return Inertia::render("Posts/Show", [
            "git_repo_url" => config("site.git_repo_url"),
            "post" => [
                "id" => $meta->id,
                "title" => $meta->title,
                "excerpt" => $meta->excerpt,
                "content" => $post->content,
                "url" => url()->current(),
                "og_image" => asset("storage/opengraph/{$meta->id}.png"),
                "date" => [
                    "iso" => $meta->date->format("c"),
                    "formatted" => $meta->date->format("jS F Y"),
                ],
                "monthsAgo" => now()->diffInMonths($meta->date),
                "tags" => $meta->tags,
                "updated" => $meta->updated,
            ],
        ]);
    }

    public function ogImage(string $blog_post): View
    {
        $meta = $this->resolvePost($blog_post)->meta;

        return view("og-image", [
            "title" => $meta->title,
            "excerpt" => $meta->excerpt,
            "url" => route("posts.show", ["blog_post" => "{$meta->slug}-{$meta->id}"]),
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

    private function resolvePost(string $routeKey): GrapheinPostWithContent
    {
        $id = last(explode("-", $routeKey));

        try {
            $post = Graphein::loadPostById($id);
        } catch (\RuntimeException) {
            abort(404);
        }

        $canonical = "{$post->meta->slug}-{$post->meta->id}";

        if ($canonical !== $routeKey) {
            throw new HttpResponseException(
                redirect()->route("posts.show", ["blog_post" => $canonical])
            );
        }

        return $post;
    }
}
