<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GrapheinInit extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'graphein:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure graphein content and build directories exist';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $directories = [
            storage_path('app/public/graphein'),
            storage_path('app/public/graphein/build'),
            base_path('content'),
            base_path('content/posts'),
            base_path('content/links'),
        ];

        foreach ($directories as $directory) {
            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
                $this->info("Created: {$directory}");
            }

            $gitkeep = $directory.'/.gitkeep';

            if (! File::exists($gitkeep)) {
                File::put($gitkeep, '');
                $this->info("Added .gitkeep: {$gitkeep}");
            }
        }

        return self::SUCCESS;
    }
}
