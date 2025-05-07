<?php

namespace App\Console\Commands;

use App\Actions\BuildAndCachePosts;
use Illuminate\Console\Command;

class BuildPosts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'build:posts';

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
        (new BuildAndCachePosts)->handle();
    }
}
