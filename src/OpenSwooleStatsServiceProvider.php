<?php

declare(strict_types=1);

namespace Dandysi\Laravel\OpenSwooleStats;

use Illuminate\Contracts\Foundation\CachesRoutes;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Dandysi\Laravel\OpenSwooleStats\Http\Controllers\StatsController;

class OpenSwooleStatsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('openswoole-stats.php'),
            ], 'config');
        }

        $this->app->booted(function () {
            $this->routes();
        });
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'openswoole-stats');
    }

    /**
     * Register the application routes.
     */
    protected function routes()
    {
        if ($this->app instanceof CachesRoutes and $this->app->routesAreCached()) {
            return;
        }

        if (!config('openswoole-stats.enabled')) {
            return;
        }

        Route::middleware(config('openswoole-stats.middleware'))
            ->get(config('openswoole-stats.url'), StatsController::class)
        ;
    }
}
