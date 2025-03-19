<?php

namespace App\Console\Commands;

use App\Models\BlogPost;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Colors\Rgb\Color;
use SimonHamp\TheOg\Background;
use SimonHamp\TheOg\Image;
use SimonHamp\TheOg\Theme\Fonts\Inter;
use SimonHamp\TheOg\Theme\Theme;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        File::ensureDirectoryExists(storage_path('app/public/opengraph'));
        BlogPost::all()->each(function ($post) {

            $this->info("Generating OG image for {$post->id}");
            try {
                (new Image())
                    ->accentColor('#cc0000')
                    ->theme(
                        new Theme(
                            accentColor: '#14b8a6',
                            baseFont: Inter::bold(),
                            baseColor: '#ffffff',
                            backgroundColor: '#272727',
                            background: new \SimonHamp\TheOg\Theme\Background(public_path('images/og-background.png')),
                            callToActionBackgroundColor: '#153B50',
                            callToActionColor: '#ECEBE4',
                            descriptionColor: '#cccccc',
                            descriptionFont: Inter::light(),
                            titleFont: Inter::black(),
                        )
                    )
                    ->border(color: Color::create('#272727'), width: 0)
                    ->url(route('posts.show', ['blogPost' => $post]))
                    ->title($post->title)
                    ->description($post->excerpt)
                    ->save(storage_path('app/public/opengraph') . "/{$post->id}.png");
                $this->info("Generated at " . Storage::disk('public')->url('opengraph/' . $post->id . '.png'));
            } catch (\Throwable $e) {
                $this->error($e->getMessage());
            }
        });
    }
}
