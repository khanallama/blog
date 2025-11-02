<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\DashboardStatsService;

class BusinessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(DashboardStatsService::class, function($app) {
            return new DashboardStatsService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
