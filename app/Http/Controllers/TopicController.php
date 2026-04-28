<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\TransformsGrapheinEntries;
use Inertia\Inertia;
use Inertia\Response;
use Intrfce\Graphein\Data\GrapheinEntry;
use Intrfce\Graphein\Facades\Graphein;

class TopicController extends Controller
{
    use TransformsGrapheinEntries;

    public function show(string $topic): Response
    {
        $meta = Graphein::getTopic($topic);

        if ($meta === null) {
            abort(404);
        }

        $posts = Inertia::scroll(fn() => Graphein::getPaginatedPostsByTopic($topic)
            ->through(fn(GrapheinEntry $entry) => $this->transformEntry($entry))
            ->withQueryString()
        );

        $page = request("page", 1);
        $title = "#{$meta['name']}";

        return Inertia::render("Topics/Show", [
            "topic" => $meta,
            "posts" => $posts,
            "pageTitle" => $page == 1 ? $title : "{$title} (Page {$page})",
        ]);
    }
}
