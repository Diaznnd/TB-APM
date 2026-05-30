<?php

use App\Http\Controllers\ForecastingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\OneWayAuth;

// Authentication routes (accessible without login)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Secure protected routes group
Route::middleware([OneWayAuth::class])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/transactions', [TransactionController::class, 'index'])
        ->name('transactions.index');

    Route::post('/transactions/store', [TransactionController::class, 'store'])
        ->name('transactions.store');

    Route::get('/transactions/history', [TransactionController::class, 'history'])
        ->name('transactions.history');

    Route::get('/inventory', [ProductController::class, 'index'])->name('inventory.index');

    Route::post('/inventory/products', [ProductController::class, 'store'])
        ->name('inventory.products.store');

    Route::get('/inventory/products/{id}', [ProductController::class, 'show'])
        ->name('inventory.products.show');

    Route::put('/inventory/products/{id}', [ProductController::class, 'update'])
        ->name('inventory.products.update');

    Route::delete('/inventory/products/{id}', [ProductController::class, 'destroy'])
        ->name('inventory.products.destroy');

    Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/pdf', [ReportController::class, 'exportPdf'])->name('laporan.pdf');
    Route::get('/laporan/excel', [ReportController::class, 'exportExcel'])->name('laporan.excel');

    Route::prefix('forecasting')->group(function () {
        Route::get('/',          [ForecastingController::class, 'index'])->name('forecasting.index');
        Route::post('/refresh',  [ForecastingController::class, 'refresh'])->name('forecasting.refresh');
        Route::get('/metrics',   [ForecastingController::class, 'metrics'])->name('forecasting.metrics');
    });
});

