<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

use function Laravel\Prompts\confirm;
use function Laravel\Prompts\info;
use function Laravel\Prompts\text;
use function Laravel\Prompts\textarea;
use function Laravel\Prompts\warning;

class GrapheinAddLink extends Command
{
    protected $signature = 'graphein:add-link';

    protected $description = 'Interactively add a link to content/links.json.';

    public function handle(): int
    {
        $path = base_path('content/links.json');
        $links = File::exists($path)
            ? (json_decode(File::get($path), true) ?? [])
            : [];

        $url = text(
            label: 'URL',
            required: true,
            validate: fn (string $value) => filter_var($value, FILTER_VALIDATE_URL)
                ? null
                : 'Must be a valid URL',
        );

        $existing = collect($links)->firstWhere('url', $url);
        if ($existing !== null) {
            warning("Link already exists (added {$existing['date']}): {$existing['title']}");
            if (! confirm('Add it again anyway?', default: false)) {
                return self::SUCCESS;
            }
        }

        $title = text(label: 'Title', required: true);
        $description = textarea(label: 'Description (optional)', required: false);

        $date = confirm("Use today's date?", default: true)
            ? now()
            : Carbon::createFromFormat('Y-m-d', text(
                label: 'Date',
                placeholder: 'Y-m-d',
                required: true,
                validate: 'date_format:Y-m-d',
            ));

        $links[] = [
            'url' => $url,
            'title' => $title,
            'description' => $description !== '' ? $description : null,
            'date' => $date->format('Y-m-d H:i:s'),
        ];

        File::ensureDirectoryExists(dirname($path));
        File::put($path, json_encode($links, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)."\n");

        info("Link saved: {$title} ({$url})");

        return self::SUCCESS;
    }
}
