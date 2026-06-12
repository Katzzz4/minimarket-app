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

/*
  Public Routes
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

/*
  Authenticated Routes
*/

Route::middleware('auth')->group(function () {

    // Dashboard (role-aware: Owner vs Branch staff)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile (default Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
      Branch & User Management — Owner only
    */

    Route::middleware('role:Owner')->group(function () {
        Route::resource('branches', BranchController::class);
        Route::resource('users', UserController::class);
    });

    /*
      Product Catalog
      - Owner: full CRUD
      - Manajer Toko & Supervisor: read-only (index, show)
    */

    Route::resource('products', ProductController::class)
        ->only(['index', 'show'])
        ->middleware('role:Owner,Manajer Toko,Supervisor');

    Route::resource('products', ProductController::class)
        ->except(['index', 'show'])
        ->middleware('role:Owner');

    /*
      Stock Management
      - Pegawai Gudang & Manajer Toko: input stok masuk/keluar/adjustment
    */

    Route::middleware('role:Pegawai Gudang,Manajer Toko')->group(function () {
        Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
    });

    /*
       POS Transactions
     - Kasir: create transaction
     - Owner, Manajer Toko, Supervisor: view transaction history
    */

    Route::middleware('role:Kasir')->group(function () {
        Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
    });

    Route::middleware('role:Owner,Manajer Toko,Supervisor')->group(function () {
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
    });

    /*
      Reports — Owner & Manajer Toko
    */

    Route::middleware('role:Owner,Manajer Toko')->group(function () {
        Route::get('/reports/transactions', [ReportController::class, 'transactions'])->name('reports.transactions');
        Route::get('/reports/transactions/export-pdf', [ReportController::class, 'exportTransactionsPdf'])->name('reports.transactions.pdf');
        Route::get('/reports/transactions/export-excel', [ReportController::class, 'exportTransactionsExcel'])->name('reports.transactions.excel');

        Route::get('/reports/stock', [ReportController::class, 'stock'])->name('reports.stock');
        Route::get('/reports/stock/export-pdf', [ReportController::class, 'exportStockPdf'])->name('reports.stock.pdf');
        Route::get('/reports/stock/export-excel', [ReportController::class, 'exportStockExcel'])->name('reports.stock.excel');
    });
});

require __DIR__ . '/auth.php';