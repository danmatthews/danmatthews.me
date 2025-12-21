<?php

use App\Models\BlogPost;
use App\Models\PostTag;
use App\Models\ResumeEntry;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::feeds();

Route::get('/', function () {
    $posts = fn() => BlogPost::orderBy('date', 'DESC')
        ->limit(3)
        ->get()
        ->map(fn(BlogPost $post) => [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'date' => [
                'iso' => $post->date->format('c'),
                'formatted' => $post->date->format('jS F Y'),
            ],
            'url' => route('posts.show', ['blog_post' => $post]),
        ]);

    $page = request('page', 1);

    return Inertia::render('Index', [
        'posts' => $posts,
        'pageTitle' => "Welcome",
        'intro' => config('site.posts'),
    ]);
    
})->name('home');

Route::get('posts', function () {
    $posts = fn() => BlogPost::orderBy('date', 'DESC')
        ->paginate(20)
        ->through(fn(BlogPost $post) => [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'date' => [
                'iso' => $post->date->format('c'),
                'formatted' => $post->date->format('jS F Y'),
            ],
            'url' => route('posts.show', ['blog_post' => $post]),
        ])
        ->withQueryString();

    $page = request('page', 1);

    return Inertia::render('Posts/Index', [
        'posts' => $posts,
        'pageTitle' => $page == 1 ? 'Posts' : "Posts (Page {$page})",
        'intro' => config('site.posts'),
    ]);
})->name('posts.index');

Route::get('posts/{blog_post}', function (BlogPost $blogPost) {
    $blogPost->load('tags');

    return Inertia::render('Posts/Show', [
        'post' => [
            'id' => $blogPost->id,
            'title' => $blogPost->title,
            'excerpt' => $blogPost->excerpt,
            'content' => $blogPost->content,
            'url' => url()->current(),
            'og_image' => asset('storage/opengraph/' . $blogPost->id . '.png'),
            'date' => [
                'iso' => $blogPost->date->format('c'),
                'formatted' => $blogPost->date->format('jS F Y'),
            ],
            'monthsAgo' => now()->diffInMonths($blogPost->date),
            'tags' => $blogPost->tags?->pluck('tag') ?? [],
        ],
    ]);
})->name('posts.show');

Route::get('/tags', function () {
    $tags = PostTag::get()->groupBy('tag')->map(function ($posts, $tag) {
        return [
            'tag' => $tag,
            'count' => $posts->count(),
        ];
    })->values();

    return Inertia::render('Tags/Index', [
        'tags' => $tags,
    ]);
})->name('tags.index');

Route::get('/tags/{id}', function (string $id) {
    $posts = BlogPost::orderBy('date', 'DESC')
        ->whereHas('tags', fn($query) => $query->where('tag', $id))
        ->paginate(10)
        ->through(fn(BlogPost $post) => [
            'id' => $post->id,
            'title' => $post->title,
            'slug' => $post->slug,
            'excerpt' => $post->excerpt,
            'date' => [
                'iso' => $post->date->format('c'),
                'formatted' => $post->date->format('jS F Y'),
            ],
            'url' => route('posts.show', ['blog_post' => $post]),
        ])
        ->withQueryString();

    return Inertia::render('Tags/Show', [
        'tag' => $id,
        'posts' => $posts,
    ]);
})->name('tags.show');

Route::get('/about', function () {
    $resume = ResumeEntry::all()->map(fn(ResumeEntry $entry) => [
        'companyName' => $entry->companyName,
        'jobTitle' => $entry->jobTitle,
        'start' => $entry->start,
        'end' => $entry->end,
        'url' => $entry->url,
    ]);

    return Inertia::render('About/Index', [
        'about' => markdown(config('site.about')),
        'resume' => $resume,
    ]);
})->name('about');

Route::get('/work', function () {
    $projects = collect(config('site.projects'))
        ->map(fn($project) => $project->toArray());

    return Inertia::render('Work/Index', [
        'projects' => $projects,
    ]);
})->name('work');

Route::get('/uses', fn() => Inertia::render('Uses/Index'))->name('uses');

