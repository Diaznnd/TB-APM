<?php

use App\Http\Controllers\ForecastingController;

Route::get('/', function () {
    return view('welcome');
})->name('dashboard');

Route::get('/transaksi', function () {
    return view('transaksi.index');
})->name('transaksi.index');

Route::get('/inventory', function () {
    return view('inventory.index');
})->name('inventory.index');

Route::get('/laporan', function () {
    return view('laporan.index');
})->name('laporan.index');

Route::prefix('forecasting')->group(function () {
    Route::get('/',          [ForecastingController::class, 'index'])->name('forecasting.index');
    Route::post('/refresh',  [ForecastingController::class, 'refresh'])->name('forecasting.refresh');
    Route::get('/metrics',   [ForecastingController::class, 'metrics'])->name('forecasting.metrics');
});

