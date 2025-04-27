<?php

namespace App\Providers;

use App\Http\Controllers\OzonController;
use App\Services\Ozon\OzonProductInterface;
use App\Services\Ozon\OzonShowService;
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
            ->give(OzonShowService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
