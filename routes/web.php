<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |----------------------------------------------------------
    | Branch & User — Owner only
    |----------------------------------------------------------
    */
    Route::middleware('role:Owner')->group(function () {
        Route::resource('branches', BranchController::class);
        Route::resource('users', UserController::class);
    });

    /*
    |----------------------------------------------------------
    | Products — /create wajib sebelum /{product}
    |----------------------------------------------------------
    */
    Route::get('/products', [ProductController::class, 'index'])
        ->middleware('role:Owner,Manajer Toko,Supervisor')
        ->name('products.index');

    Route::get('/products/create', [ProductController::class, 'create'])
        ->middleware('role:Owner')
        ->name('products.create');

    Route::post('/products', [ProductController::class, 'store'])
        ->middleware('role:Owner')
        ->name('products.store');

    Route::get('/products/{product}', [ProductController::class, 'show'])
        ->middleware('role:Owner,Manajer Toko,Supervisor')
        ->name('products.show');

    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
        ->middleware('role:Owner')
        ->name('products.edit');

    Route::put('/products/{product}', [ProductController::class, 'update'])
        ->middleware('role:Owner')
        ->name('products.update');

    Route::patch('/products/{product}', [ProductController::class, 'update']);

    Route::delete('/products/{product}', [ProductController::class, 'destroy'])
        ->middleware('role:Owner')
        ->name('products.destroy');

    /*
    |----------------------------------------------------------
    | Stock — Pegawai Gudang & Manajer Toko
    |----------------------------------------------------------
    */
    Route::post('/stock', [StockController::class, 'store'])
        ->middleware('role:Pegawai Gudang,Manajer Toko')
        ->name('stock.store');

    /*
    |----------------------------------------------------------
    | Transactions
    |----------------------------------------------------------
    */
    Route::get('/transactions', [TransactionController::class, 'index'])
        ->middleware('role:Owner,Manajer Toko,Supervisor')
        ->name('transactions.index');

    Route::post('/transactions', [TransactionController::class, 'store'])
        ->middleware('role:Kasir')
        ->name('transactions.store');

    /*
    |----------------------------------------------------------
    | POS — Kasir
    |----------------------------------------------------------
    */
    Route::get('/pos', [TransactionController::class, 'pos'])
        ->middleware('role:Kasir')
        ->name('pos');

    /*
    |----------------------------------------------------------
    | Reports — Owner & Manajer Toko
    |----------------------------------------------------------
    */
    Route::middleware('role:Owner,Manajer Toko')->group(function () {
        Route::get('/reports/transactions', [ReportController::class, 'transactions'])
            ->name('reports.transactions');
        Route::get('/reports/transactions/export-pdf', [ReportController::class, 'exportTransactionsPdf'])
            ->name('reports.transactions.pdf');
        Route::get('/reports/transactions/export-excel', [ReportController::class, 'exportTransactionsExcel'])
            ->name('reports.transactions.excel');

        Route::get('/reports/stock', [ReportController::class, 'stock'])
            ->name('reports.stock');
        Route::get('/reports/stock/export-pdf', [ReportController::class, 'exportStockPdf'])
            ->name('reports.stock.pdf');
        Route::get('/reports/stock/export-excel', [ReportController::class, 'exportStockExcel'])
            ->name('reports.stock.excel');
    });
});

require __DIR__ . '/auth.php';