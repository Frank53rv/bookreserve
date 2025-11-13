<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
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
        // Align Laravel's paginator with Bootstrap 5 markup used in the views.
        Paginator::useBootstrapFive();

        // Share navigation items so layouts and dashboards remain in sync.
        View::share('navigationItems', config('navigation.items', []));
    }
}
