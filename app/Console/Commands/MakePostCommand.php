<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\text;
use function Laravel\Prompts\textarea;
use function Termwind\ask;

class MakePostCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:post';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a blog post, in the correct month folder, in the resources/views/posts directory.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $title = text('What\'s the title of this blog post?');
        $excerpt = textarea('Give a short description of this post:');

        $data = [
            'id' => Str::of(sha1(Str::random(10)))->substr(0, 5)->value,
            'title' => $title,
            'date' => now()->format('Y-m-d h:i:s'),
            'slug' => Str::slug($title),
            'excerpt' => $excerpt,
        ];

        $dataYaml = Yaml::dump($data);

        $folderName = strtolower(now()->format('F-Y'));

        $contents = <<<MARKDOWN
        ---
        {$dataYaml}---
        # {$data['title']}

        Write your post here.
        MARKDOWN;

            File::ensureDirectoryExists(
                resource_path('views/posts/')
            );

            $path =  collect([
                    resource_path('views/posts'),
                    date('Y-m-d').'-'.$data['slug'].'.md',
                ])->implode(DIRECTORY_SEPARATOR);

            File::put(
                $path,
                $contents
            );

            \Laravel\Prompts\info("New post saved to $path");

    }
}
