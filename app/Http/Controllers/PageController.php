<?php

namespace App\Http\Controllers;

use App\MiniPaintings;
use App\Models\ResumeEntry;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function about(): Response
    {
        $resume = ResumeEntry::all()->map(
            fn(ResumeEntry $entry) => [
                "companyName" => $entry->companyName,
                "jobTitle" => $entry->jobTitle,
                "start" => $entry->start,
                "end" => $entry->end,
                "url" => $entry->url,
            ],
        );

        return Inertia::render("About/Index", [
            "about" => markdown(config("site.about")),
            "resume" => $resume,
        ]);
    }

    public function work(): Response
    {
        $openSource = config("site.work.open_source");
        $everythingElse = collect(
            config("site.work.everything_else"),
        )->map(fn($project) => $project->toArray());

        return Inertia::render("Work/Index", [
            "currentPosition" => config("site.work.current_position"),
            "openSource" => [
                "description" => markdown($openSource["description"]),
                "packages" => collect($openSource["packages"])->map(
                    fn($project) => $project->toArray(),
                ),
            ],
            "everythingElse" => $everythingElse,
        ]);
    }

    public function uses(): Response
    {
        return Inertia::render("Uses/Index");
    }

    public function gallery(Request $request): Response
    {
        $all = MiniPaintings::all();
        $total = $all->count();
        $photos = $all
            ->forPage($page = max(1, $request->integer("page", 1)), $perPage = 15)
            ->map(fn($photo) => [
                "src" => MiniPaintings::url($photo),
                "width" => $photo["width"],
                "height" => $photo["height"],
                "caption" => $photo["caption"],
                "date" => $photo["date"] ?? null,
            ]);

        return Inertia::render("Gallery/Index", [
            "intro" => markdown(config("site.gallery.intro")),
            // Merged on "Load more" so later pages append to the grid.
            "photos" => Inertia::merge($photos->values()),
            "total" => $total,
            "nextPage" => $page * $perPage < $total ? $page + 1 : null,
        ]);
    }
}
