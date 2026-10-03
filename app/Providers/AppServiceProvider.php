<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');

            // public/storage is gitignored, so recreate the link on fresh deploys
            // or uploaded photos (dentists, patients, profiles) return 404.
            if (! file_exists(public_path('storage'))) {
                @symlink(storage_path('app/public'), public_path('storage'));
            }
        }
    }
}
