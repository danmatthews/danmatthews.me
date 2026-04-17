<?php

namespace App\Providers;

use App\Facades\Graphein;
use App\Graphein\Processors\GenerateOgImage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(\App\Graphein\Graphein::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Graphein::postProcessors([
            GenerateOgImage::class,
        ]);
    }
}
