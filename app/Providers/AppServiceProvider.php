<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\StockAdjustment;
use App\Observers\StockAdjustmentObserver;

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
        StockAdjustment::observe(StockAdjustmentObserver::class);
    }
}
