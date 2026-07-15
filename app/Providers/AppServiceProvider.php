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

        // Force HTTPS for all generated URLs in production.
        // Railway's proxy terminates SSL and forwards requests to the app as HTTP,
        // so without this, Laravel generates http:// asset/API URLs and the browser
        // blocks them as mixed content on an https:// page.
        if ($this->app->environment('production')) {
            \URL::forceScheme('https');
        }
    }
}