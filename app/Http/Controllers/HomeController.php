<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\TransformsGrapheinEntries;
use Inertia\Inertia;
use Inertia\Response;
use Intrfce\Graphein\Data\GrapheinEntry;
use Intrfce\Graphein\Facades\Graphein;

class HomeController extends Controller
{
    use TransformsGrapheinEntries;

    public function __invoke(): Response
    {
        $posts = Inertia::scroll(
            fn() => Graphein::getPaginatedPosts()
                ->through(
                    fn(GrapheinEntry $entry) => $this->transformEntry($entry),
                )
                ->withQueryString(),
        );

        return Inertia::render("Index", [
            "posts" => $posts,
            "pageTitle" => "Welcome",
            "intro" => config("site.posts"),
            "bio" => markdown(config("site.home.bio")),
        ]);
    }
}
