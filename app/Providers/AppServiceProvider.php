<?php

namespace App\Providers;

use App\Services\SearchPagesService;
use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

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
        Inertia::share('searchPages', function () {
            if (request()->routeIs('admin.*') || request()->is('admin*')) {
                return [];
            }

            return app(SearchPagesService::class)->buildSearchPages();
        });
    }
}
