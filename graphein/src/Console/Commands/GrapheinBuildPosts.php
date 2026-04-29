<?php

namespace Intrfce\Graphein\Console\Commands;

use Illuminate\Console\Command;
use Intrfce\Graphein\Actions\BuildAndCachePosts;
use Laravel\Prompts\Support\Logger;

use function Laravel\Prompts\info;
use function Laravel\Prompts\task;

class GrapheinBuildPosts extends Command
{
    protected $signature = 'graphein:build-posts {--json : Emit the build result as JSON on stdout for machine consumption}';

    protected $description = 'Parse markdown posts and write the Graphein manifest, page JSON, and per-post HTML/meta files.';

    public function handle(): int
    {
        $action = new BuildAndCachePosts;

        if ($this->option('json')) {
            $action->handle();

            $this->line(json_encode([
                'posts_built' => $action->postsBuilt(),
                'failures' => $action->failures(),
            ], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));

            return $action->failures() === [] ? self::SUCCESS : self::FAILURE;
        }

        task(
            label: 'Building Graphein',
            callback: fn (Logger $logger) => $action->handle(
                fn (string $type, string $message) => $this->dispatchProgress($logger, $type, $message),
            ),
            keepSummary: true,
        );

        $failures = $action->failures();

        if ($failures === []) {
            info("Graphein build complete — {$action->postsBuilt()} post(s) ready to serve.");

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

    private function dispatchProgress(Logger $logger, string $type, string $message): void
    {
        match ($type) {
            'label' => $logger->label($message),
            'success' => $logger->success($message),
            'warning' => $logger->warning($message),
            'error' => $logger->error($message),
            default => $logger->line($message),
        };
    }

    private function summariseMessage(string $message): string
    {
        $firstLine = trim(strtok($message, "\n"));

        return strlen($firstLine) > 140
            ? substr($firstLine, 0, 137).'...'
            : $firstLine;
    }
}
