<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Symfony\Component\Yaml\Yaml;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\text;
use function Laravel\Prompts\textarea;
use function Termwind\ask;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Illuminate\Support\Facades\Log;

class MakeLinkCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:link';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a link, stored in the content/links folder.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = text(label: 'Paste the link URL', validate: 'required|url');

        $title = $this->getPageTitle($url);
        $title = text(label: 'What\'s the title of this link?', default: $title, required: true, validate: 'required|string');
        $description = textarea('Give a short description of this link:');
        $date = now();

        $slug = Str::slug($title, '-');

        $data = [
            'id' => Str::of(sha1(Str::random(10)))->substr(0, 5)->value,
            'title' => $title,
            'url' => $url,
            'slug' => $slug,
            'date' => $date->format('Y-m-d h:i:s'),
        ];

        $dataYaml = Yaml::dump($data);
        $folderName = strtolower(now()->format('F-Y'));

        $contents = <<<MARKDOWN
        ---
        {$dataYaml}---
        {$description}
        MARKDOWN;

        File::ensureDirectoryExists(
            base_path('content/links/')
        );

        $path = collect([
            base_path('content/links'),
            date('Y-m-d') . '-' . $slug . '.md',
        ])->implode(DIRECTORY_SEPARATOR);

        File::put(
            $path,
            $contents
        );

        \Laravel\Prompts\info("New post saved to $path");

    }


    function getPageTitle(string $url): ?string
    {
        try {
            $client = new Client();
            $response = $client->get($url);
            $html = $response->getBody()->getContents();

            $crawler = new Crawler($html);
            $title = $crawler->filter('title')->first()->text();

            return trim($title);
        } catch (\Exception $e) {
            Log::error("Error fetching title for {$url}: " . $e->getMessage());
            return null;
        }
    }

}
