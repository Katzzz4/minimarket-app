<?php

namespace App\Providers;

use App\Models\Transaction;
use App\Models\StockMovement;
use App\Observers\TransactionObserver;
use App\Observers\StockMovementObserver;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Carbon\Carbon;


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
        Carbon::setLocale('id');
        setlocale(LC_TIME, 'id_ID');

        Transaction::observe(TransactionObserver::class);
        StockMovement::observe(StockMovementObserver::class);
    }
}
