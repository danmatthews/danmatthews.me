<?php

namespace Intrfce\Graphein;

use Illuminate\Support\ServiceProvider;
use Intrfce\Graphein\Console\Commands\GrapheinAddLink;
use Intrfce\Graphein\Console\Commands\GrapheinBuildPosts;
use Intrfce\Graphein\Console\Commands\GrapheinInit;
use Intrfce\Graphein\Console\Commands\MakePostCommand;

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
                GrapheinAddLink::class,
                MakePostCommand::class,
            ]);

            $this->publishes([
                __DIR__.'/../config/graphein.php' => config_path('graphein.php'),
            ], 'graphein-config');
        }
    }
}
