<?php

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intrfce\Graphein\Actions\BuildAndCachePosts;
use Intrfce\Graphein\Facades\Graphein;

beforeEach(function () {
    Storage::fake('public');
    Graphein::postProcessors([]);

    $this->postsDir = base_path('content/posts');
    $this->tmpDir = base_path('content/posts-test-'.uniqid());
    File::ensureDirectoryExists($this->tmpDir);

    File::moveDirectory($this->postsDir, $this->tmpDir.'/_real');

    File::ensureDirectoryExists($this->postsDir);

    File::put($this->postsDir.'/2024-01-01-laravel-one.md', <<<'MD'
        ---
        id: aaa11
        title: 'Laravel One'
        slug: laravel-one
        date: '2024-01-01 10:00:00'
        excerpt: 'First laravel post'
        topics: ['Laravel', 'PHP']
        ---

        Body one.
        MD
    );

    File::put($this->postsDir.'/2024-02-01-laravel-two.md', <<<'MD'
        ---
        id: bbb22
        title: 'Laravel Two'
        slug: laravel-two
        date: '2024-02-01 10:00:00'
        excerpt: 'Second laravel post'
        topics: ['laravel']
        ---

        Body two.
        MD
    );

    File::put($this->postsDir.'/2024-03-01-untagged.md', <<<'MD'
        ---
        id: ccc33
        title: 'Untagged'
        slug: untagged
        date: '2024-03-01 10:00:00'
        excerpt: 'No topics'
        ---

        Body three.
        MD
    );
});

afterEach(function () {
    File::deleteDirectory($this->postsDir);
    File::moveDirectory($this->tmpDir.'/_real', $this->postsDir);
    File::deleteDirectory($this->tmpDir);
});

it('writes topics index and per-topic paginated pages', function () {
    app(BuildAndCachePosts::class)->handle();

    $disk = Storage::disk('public');

    expect($disk->exists('graphein/topics/index.json'))->toBeTrue();

    $index = json_decode($disk->get('graphein/topics/index.json'), true);

    expect($index)->toHaveCount(2);

    $bySlug = collect($index)->keyBy('slug');

    expect($bySlug['laravel']['count'])->toBe(2);
    expect($bySlug['php']['count'])->toBe(1);
    expect($bySlug['php']['name'])->toBe('PHP');

    expect($disk->exists('graphein/topics/laravel/page-1.json'))->toBeTrue();
    expect($disk->exists('graphein/topics/php/page-1.json'))->toBeTrue();

    $laravelPage = json_decode($disk->get('graphein/topics/laravel/page-1.json'), true);
    expect($laravelPage)->toHaveCount(2);
    expect(collect($laravelPage)->pluck('data.id')->all())->toBe(['bbb22', 'aaa11']);
});

it('exposes a manifest entry for each topic with pagination metadata', function () {
    app(BuildAndCachePosts::class)->handle();

    $manifest = json_decode(Storage::disk('public')->get('graphein/graphein-manifest.json'), true);

    expect($manifest)->toHaveKey('topics');
    expect($manifest['topics'])->toHaveKey('laravel');
    expect($manifest['topics']['laravel']['count'])->toBe(2);
    expect($manifest['topics']['laravel']['pagination']['1']['total'])->toBe(2);

    $topicFiles = collect($manifest['files'])->where('type', 'topic_page');
    expect($topicFiles)->toHaveCount(2);
});

it('paginates posts by topic via the facade', function () {
    app(BuildAndCachePosts::class)->handle();

    $paginator = Graphein::getPaginatedPostsByTopic('laravel');

    expect($paginator->total())->toBe(2);
    expect($paginator->items())->toHaveCount(2);
});

it('returns null for an unknown topic', function () {
    app(BuildAndCachePosts::class)->handle();

    expect(Graphein::getTopic('does-not-exist'))->toBeNull();
});

it('throws when paginating an unknown topic', function () {
    app(BuildAndCachePosts::class)->handle();

    Graphein::getPaginatedPostsByTopic('does-not-exist');
})->throws(RuntimeException::class);
