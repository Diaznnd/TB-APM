<?php

use App\Http\Controllers\ForecastingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReportController;

Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/transaksi', function () {
    return view('transaksi.index');
})->name('transaksi.index');

Route::get('/inventory', function () {
    return view('inventory.index');
})->name('inventory.index');

Route::get('/laporan', [ReportController::class, 'index'])->name('laporan.index');
Route::get('/laporan/pdf', [ReportController::class, 'exportPdf'])->name('laporan.pdf');
Route::get('/laporan/excel', [ReportController::class, 'exportExcel'])->name('laporan.excel');

Route::prefix('forecasting')->group(function () {
    Route::get('/',          [ForecastingController::class, 'index'])->name('forecasting.index');
    Route::post('/refresh',  [ForecastingController::class, 'refresh'])->name('forecasting.refresh');
    Route::get('/metrics',   [ForecastingController::class, 'metrics'])->name('forecasting.metrics');
});

