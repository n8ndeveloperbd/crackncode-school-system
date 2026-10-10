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
        \Illuminate\Pagination\Paginator::useBootstrap();
        \Illuminate\Pagination\Paginator::defaultView("vendor.pagination.default");

        // Enforce HTTPS behind reverse proxies (Render / Cloudflare / Heroku)
        if (app()->environment('production') || request()->header('X-Forwarded-Proto') === 'https' || str_contains(request()->url(), 'onrender.com')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
