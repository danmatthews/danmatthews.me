<?php

namespace App\Http\Controllers;

use App\Models\ResumeEntry;
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
        $projects = collect(config("site.projects"))->map(
            fn($project) => $project->toArray(),
        );

        return Inertia::render("Work/Index", [
            "projects" => $projects,
        ]);
    }

    public function uses(): Response
    {
        return Inertia::render("Uses/Index");
    }
}
