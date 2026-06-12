<?php

use App\Http\Controllers\BranchController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'role:Owner'])->group(function () {
    Route::resource('branches', BranchController::class);
    Route::resource('users', UserController::class);
});

Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class)
        ->middleware('role:Owner,Manajer Toko');

    Route::post('/stock', [StockController::class, 'store'])
        ->middleware('role:Pegawai Gudang,Manajer Toko');

    Route::post('/transactions', [TransactionController::class, 'store'])
        ->middleware('role:Kasir');

    Route::get('/transactions', [TransactionController::class, 'index'])
        ->middleware('role:Owner,Manajer Toko,Supervisor');
});

Route::middleware('auth')->group(function () {
    Route::get('/reports/transactions', [ReportController::class, 'transactions'])
        ->middleware('role:Owner,Manajer Toko')
        ->name('reports.transactions');

    Route::get('/reports/transactions/export-pdf', [ReportController::class, 'exportTransactionsPdf'])
        ->middleware('role:Owner,Manajer Toko')
        ->name('reports.transactions.pdf');

    Route::get('/reports/transactions/export-excel', [ReportController::class, 'exportTransactionsExcel'])
        ->middleware('role:Owner,Manajer Toko')
        ->name('reports.transactions.excel');

    Route::get('/reports/stock', [ReportController::class, 'stock'])
        ->middleware('role:Owner,Manajer Toko')
        ->name('reports.stock');

    Route::get('/reports/stock/export-pdf', [ReportController::class, 'exportStockPdf'])
        ->middleware('role:Owner,Manajer Toko')
        ->name('reports.stock.pdf');

    Route::get('/reports/stock/export-excel', [ReportController::class, 'exportStockExcel'])
        ->middleware('role:Owner,Manajer Toko')
        ->name('reports.stock.excel');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__ . '/auth.php';
