<?php

return [
    "git_repo_url" =>
        "https://github.com/danmatthews/danmatthews.me/tree/main/content",
    "home" => [
        "headline" => "Dan Matthews",
        "subheader" =>
            "I'm a full stack developer, working with Laravel and JS. I'm based in the UK, and currently i'm building charity software at [Social Sync](https://socialsync.io).",
    ],
    "about" => file_get_contents(base_path("content/about.md")),
    "posts" => [
        "headline" =>
            "Posting about web development, with a little bit of personal stuff thrown in here and there.",
        "subheading" => "Talking about Laravel, Svelte, and more.",
    ],
    "work" => [
        "current_position" => [
            "job_title" => "Head Of Engineering",
            "company" => "Social Sync",
            "company_url" => "https://socialsync.io/",
            "description" => "Heading up the development team building charity software that helps organisations do more good.",
        ],
        "open_source" => [
            "description" => "All my open source packages live under the [Intrfce](https://github.com/intrfce) organisation on Github. Most are Laravel packages solving small, specific problems I've run into.",
            "packages" => [
                new \App\Data\ProjectData(
                    title: "Enum Attribute Descriptors",
                    description: "Add descriptions and titles to your enum cases.",
                    url: "https://github.com/intrfce/enum-attribute-descriptors",
                    button_text: "View on Github",
                ),
                new \App\Data\ProjectData(
                    title: "Laravel Frontend Enums",
                    description: "Publish your PHP Enums to the frontend for use in your Javascript files.",
                    url: "https://github.com/intrfce/laravel-frontend-enums",
                    button_text: "View on Github",
                ),
            ],
        ],
        "everything_else" => [
            new \App\Data\ProjectData(
                title: "Over Engineered Podcast - 28th March 2025",
                description: "Chris invited myself and John Drexler to speak about running small engineering teams.",
                url: "https://www.youtube.com/watch?v=ReYU6YkhLbE&t=4958s",
                button_text: "Watch On Youtube",
            ),
            new \App\Data\ProjectData(
                title: "The Guild Coworking Teaser Video",
                description: "I knocked up a little teaser video for the downstairs space at the fantastic Guild Coworking space i use.",
                url: "https://youtu.be/WmBPpnc-DuQ",
                button_text: "Watch On Youtube",
            ),
        ],
    ],
    "job_history" => [
        new \App\Data\ResumeEntryData(
            "images/social-sync.png",
            "Social Sync",
            "Head Of Engineering",
            "2022",
            "Present",
            "https://socialsync.io/",
        ),
        new \App\Data\ResumeEntryData(
            "images/black-lab-software.png",
            "Black Lab Software",
            "Owner",
            "2016",
            "2022",
            "https://blacklabsoftware.co.uk/",
        ),
        new \App\Data\ResumeEntryData(
            "images/fika-apps.png",
            "Fika Apps",
            "Mobile Developer",
            "2018",
            "2019",
        ),
        new \App\Data\ResumeEntryData(
            "images/hydrant.png",
            "Hydrant Ltd",
            "Developer",
            "2012",
            "2016",
        ),
    ],
    "images" => [],
    "navigation" => [
        new \App\Data\NavigationItem(
            title: "Home",
            url: "/",
            isActive: function (\Illuminate\Http\Request $request) {
                return $request->path() == "/";
            },
        ),
        new \App\Data\NavigationItem(
            title: "Posts",
            url: "/posts",
            isActive: function (\Illuminate\Http\Request $request) {
                return in_array($request->route()->getName(), [
                    "posts.index",
                    "posts.show",
                ]);
            },
        ),
        new \App\Data\NavigationItem("About", "about"),
        new \App\Data\NavigationItem("Work", "work"),
    ],
    "social-links" => [
        "bluesky" => "https://bsky.app/profile/danmatthews.me",
        "twitter" => "https://twitter.com/danmatthews",
        "instagram" => "https://www.instagram.com/danmatthews",
        "linkedin" => "https://www.linkedin.com/",
        "github" => "https://www.github.com/danmatthews",
    ],
];
