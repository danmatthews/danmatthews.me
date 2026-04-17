<?php

namespace App\Providers;

use App\Processors\GenerateOgImage;
use Illuminate\Support\ServiceProvider;
use Intrfce\Graphein\Facades\Graphein;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
