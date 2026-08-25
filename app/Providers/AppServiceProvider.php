<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        // The UI uses Bootstrap, so render Laravel's paginator with matching
        // Bootstrap markup instead of the default Tailwind classes.
        Paginator::useBootstrapFive();
    }
}
