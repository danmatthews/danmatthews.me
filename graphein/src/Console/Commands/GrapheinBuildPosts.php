<?php

namespace Intrfce\Graphein\Console\Commands;

use Illuminate\Console\Command;
use Intrfce\Graphein\Actions\BuildAndCachePosts;

class GrapheinBuildPosts extends Command
{
    protected $signature = 'graphein:build-posts {--json : Emit the build result as JSON on stdout for machine consumption}';

    protected $description = 'Parse markdown posts and write the Graphein manifest, page JSON, and per-post HTML/meta files.';

    public function handle(): int
    {
        $action = new BuildAndCachePosts;
        $posts = $action->handle();
        $failures = $action->failures();

        if ($this->option('json')) {
            $this->line(json_encode([
                'posts_built' => $posts->count(),
                'failures' => $failures,
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return $failures === [] ? self::SUCCESS : self::FAILURE;
        }

        $this->info("Built {$posts->count()} post(s).");

        if ($failures === []) {
            return self::SUCCESS;
        }

        $this->newLine();
        $this->error(sprintf('%d processor failure(s):', count($failures)));

        $this->table(
            ['Processor', 'Post', 'Error'],
            array_map(fn (array $f) => [
                class_basename($f['processor']),
                "{$f['post_title']} ({$f['post_id']})",
                $this->summariseMessage($f['message']),
            ], $failures),
        );

        $this->newLine();
        $this->line('Full traces written to storage/logs/laravel.log.');

        return self::FAILURE;
    }

    private function summariseMessage(string $message): string
    {
        $firstLine = trim(strtok($message, "\n"));

        return strlen($firstLine) > 140
            ? substr($firstLine, 0, 137).'...'
            : $firstLine;
    }
}
