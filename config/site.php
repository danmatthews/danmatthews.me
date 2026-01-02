<?php

return [
    'home' => [
        'headline' => "Dan Matthews",
        'subheader' => "I'm a full stack developer, working with Laravel and JS. I'm based in the UK, and currently i'm building charity software at [Social Sync](https://socialsync.io)."
    ],
    'about' => file_get_contents(base_path('content/about.md')),
    'posts' => [
        'headline' => 'Posting about web development, with a little bit of personal stuff thrown in here and there.',
        'subheading' => 'Talking about Laravel, Svelte, and more.'
    ],
    'projects' => [
        new \App\Data\ProjectData(
            title: "Over Engineered Podcast - 28th March 2025",
            description: "Chris invited myself and John Drexler to speak about running small engineering teams.",
            url: "https://www.youtube.com/watch?v=ReYU6YkhLbE&t=4958s",
            button_text: "Watch On Youtube",
        ),
        new \App\Data\ProjectData(
            title: "Laravel Package - Enum Attribute",
            description: "Add descriptions and titles to your enum cases.",
            url: "https://github.com/intrfce/enum-attribute-descriptors",
            button_text: "View on Github",
        ),
        new \App\Data\ProjectData(
            title: "Laravel Package - Frontend Enums",
            description: "Publish your PHP Enums to the frontend for use in your Javascript files.",
            url: "https://github.com/intrfce/laravel-frontend-enums",
            button_text: "View on Github",
        ),
        new \App\Data\ProjectData(
            title: "Where I Work - Social Sync",
            description: "Where I work currently heading up the development team.",
            url: "https://socialsync.io/",
            button_text: "Visit Website",
        ),
        new \App\Data\ProjectData(
            title: "The Guild Coworking Teaser Video",
            description: "I knocked up a little teaser video for the downstairs space at the fantastic Guild Coworking space i use.",
            url: "https://youtu.be/WmBPpnc-DuQ",
            button_text: "Watch On Youtube",
        ),
        new \App\Data\ProjectData(
            title: "Intrfce on Github",
            description: "Intrfce is the name for all my open source packages on Github",
            url: "https://github.com/intrfce",
            button_text: "View on Github",
        ),
    ],
    'job_history' => [
        new \App\Data\ResumeEntryData(
            'images/social-sync.png',
            "Social Sync",
            'Head Of Engineering',
            '2022',
            'Present',
            'https://socialsync.io/'
        ),
        new \App\Data\ResumeEntryData(
            'images/black-lab-software.png',
            "Black Lab Software",
            'Owner',
            '2016',
            '2022',
            'https://blacklabsoftware.co.uk/'
        ),
        new \App\Data\ResumeEntryData(
            'images/fika-apps.png',
            "Fika Apps",
            'Mobile Developer',
            '2018',
            '2019',
        ),
        new \App\Data\ResumeEntryData(
            'images/hydrant.png',
            "Hydrant Ltd",
            'Developer',
            '2012',
            '2016'
        )
    ],
    'images' => [

    ],
    'navigation' => [
        new \App\Data\NavigationItem(
            title: 'Home',
            url: '/',
            isActive: function (\Illuminate\Http\Request $request) {
                return $request->path() == '/';
            }
        ),
        new \App\Data\NavigationItem(
            title: 'Posts',
            url: '/posts',
            isActive: function (\Illuminate\Http\Request $request) {

                return in_array($request->route()->getName(), [
                    'posts.index', 'posts.show', 'tags.show'
                ]);
            }
        ),
        new \App\Data\NavigationItem(
            'About',
            'about'
        ),
        new \App\Data\NavigationItem(
            'Work',
            'work',
        )
    ],
    'social-links' => [
        'bluesky' => 'https://bsky.app/profile/danmatthews.me',
        'twitter' => 'https://twitter.com/danmatthews',
        'instagram' => 'https://www.instagram.com/danmatthews',
        'linkedin' => 'https://www.linkedin.com/',
        'github' => 'https://www.github.com/danmatthews',
    ],
];
