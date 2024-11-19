<?php

return [
    'home' => [
        'headline' => "Dan Matthews",
        'subheader' => "I'm a full stack developer, working with Laravel and JS. I'm based in the UK, and currently i'm building charity software at [Social Sync](https://socialsync.io)."
    ],
    'about' => file_get_contents(resource_path('views/content/about.md')),
    'posts' => [
        'headline' => 'Posting about web development, with a little bit of personal stuff thrown in here and there.',
        'subheading' => 'Talking about Laravel, Svelte, and more.'
    ],
    'projects' => [
        new \App\Data\ProjectData(
            "Enum Attribute Descriptors",
            "Add descriptions and titles to your enum cases.",
            "https://github.com/intrfce/enum-attribute-descriptors",
        ),
        new \App\Data\ProjectData(
            "Laravel Frontend Enums",
            "Publish your PHP Enums to the frontend for use in your Javascript files.",
            "https://github.com/intrfce/laravel-frontend-enums",
        ),
        new \App\Data\ProjectData(
            "Social Sync",
            "Where I work currently heading up the development team.",
            "https://socialsync.io/",
        ),
        new \App\Data\ProjectData(
            "The Guild Coworking Teaser Video",
            "I knocked up a little teaser video for the downstairs space at the fantastic Guild Coworking space i use.",
            "https://youtu.be/WmBPpnc-DuQ",
        ),
        new \App\Data\ProjectData(
            "Intrfce on Github",
            "Intrfce is the name for all my open source packages on Github",
            "https://github.com/intrfce",
        ),
    ],
    'job_history' => [
        new \App\Data\ResumeEntryData(
            'images/social-sync.png',
            "Social Sync",
            'Head Of Engineering',
            '2022',
            'Present'
        ),
        new \App\Data\ResumeEntryData(
            'images/black-lab-software.png',
            "Black Lab Software",
            'Owner',
            '2016',
            '2022'
        ),
        new \App\Data\ResumeEntryData(
            'images/fika-apps.png',
            "Fika Apps",
            'Mobile Developer',
            '2018',
            '2019'
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
            'Home',
            '/'
        ),
        new \App\Data\NavigationItem(
            'About',
            'about'
        ),
        new \App\Data\NavigationItem(
            'Posts',
            'posts'
        ),
        new \App\Data\NavigationItem(
            'Projects',
            'projects',
        ),
    ],
    'social-links' => [
        'bluesky' => 'https://bsky.app/profile/danmatthews.me',
        'twitter' => 'https://twitter.com/danmatthews',
        'instagram' => 'https://www.instagram.com/danmatthews',
        'linkedin' => 'https://www.linkedin.com/',
        'github' => 'https://www.github.com/danmatthews',
    ],
];
