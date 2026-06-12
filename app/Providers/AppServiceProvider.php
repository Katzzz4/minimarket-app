<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Models\StockMovement;
use App\Models\ProductStock;
use App\Observers\TransactionObserver;
use App\Observers\StockMovementObserver;
use App\Observers\ProductStockObserver;
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
        Transaction::observe(TransactionObserver::class);
        StockMovement::observe(StockMovementObserver::class);
        // ProductStock::observe(ProductStockObserver::class);
    }
}
