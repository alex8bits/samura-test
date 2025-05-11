<?php

namespace App\Providers;

use App\Http\Controllers\OzonController;
use App\Services\Ozon\Product\OzonProductInterface;
use App\Services\Ozon\Product\OzonProductShowService;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->when(OzonController::class)
            ->needs(OzonProductInterface::class)
            ->give(OzonProductShowService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();
    }
}
