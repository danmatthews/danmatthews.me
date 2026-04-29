<?php

namespace Intrfce\Graphein;

use Illuminate\Support\ServiceProvider;
use Intrfce\Graphein\Console\Commands\GrapheinBuildPosts;
use Intrfce\Graphein\Console\Commands\GrapheinInit;
use Intrfce\Graphein\Console\Commands\GrapheinLink;
use Intrfce\Graphein\Console\Commands\GrapheinPost;

class GrapheinServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/graphein.php', 'graphein');
        $this->app->singleton(Graphein::class);
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                GrapheinBuildPosts::class,
                GrapheinInit::class,
                GrapheinLink::class,
                GrapheinPost::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/graphein.php' => config_path('graphein.php'),
            ], 'graphein-config');
        }
    }
}
