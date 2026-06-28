<?php

namespace App\Providers;

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
        // Ensure a sane default for session driver in local environment
        // This forces file sessions when environment variables are not picked up.
        if (config('session.driver') !== 'file') {
            config(['session.driver' => env('SESSION_DRIVER', 'file')]);
        }
    }
}
