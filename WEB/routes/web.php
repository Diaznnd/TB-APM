<?php

use App\Http\Controllers\ForecastingController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('forecasting')->group(function () {
    Route::get('/',          [ForecastingController::class, 'index'])->name('forecasting.index');
    Route::post('/refresh',  [ForecastingController::class, 'refresh'])->name('forecasting.refresh');
    Route::get('/metrics',   [ForecastingController::class, 'metrics'])->name('forecasting.metrics');
});
