<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\View;
use Spatie\Browsershot\Browsershot;

class GenerateOGImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:og';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate OG images for blog posts using Browsershot';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        File::ensureDirectoryExists(storage_path('app/public/opengraph'));

        BlogPost::all()->each(function ($post) {
            $this->info("Generating OG image for {$post->id}");

            try {
                $html = View::make('og-image', [
                    'title' => $post->title,
                    'excerpt' => $post->excerpt,
                ])->render();

                $outputPath = storage_path('app/public/opengraph') . "/{$post->id}.png";

                Browsershot::html($html)
                    ->windowSize(1200, 630)
                    ->noSandbox()
                    ->waitUntilNetworkIdle()
                    ->save($outputPath);

                $this->info("Generated at " . Storage::disk('public')->url('opengraph/' . $post->id . '.png'));
            } catch (\Throwable $e) {
                $this->error($e->getMessage());
                $this->error($e->getTraceAsString());
            }
        });
    }
}
