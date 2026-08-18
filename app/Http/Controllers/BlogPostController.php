<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\SharesPostMeta;
use App\Http\Controllers\Concerns\TransformsGrapheinEntries;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Exceptions\HttpResponseException;
use Inertia\Inertia;
use Inertia\Response;
use Intrfce\Graphein\Data\GrapheinPostWithContent;
use Intrfce\Graphein\Facades\Graphein;

class BlogPostController extends Controller
{
    use SharesPostMeta;
    use TransformsGrapheinEntries;

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
                "topics" => $this->transformTopics($meta->topics),
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
            "url" => route("posts.show", [
                "blog_post" => "{$meta->slug}-{$meta->id}",
            ]),
        ]);
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
                redirect()->route("posts.show", ["blog_post" => $canonical]),
            );
        }

        return $post;
    }
}
