<?php

use Illuminate\Support\Facades\Route;
use Famindo\AnalyticalCRM\Http\Controllers\Admin\AprioriController;

Route::controller(AprioriController::class)->prefix('analytics/market-basket')->group(function () {
    Route::get('', 'index')->name('admin.analytics.market_basket.index');

    Route::post('run', 'run')->name('admin.analytics.market_basket.run');

    Route::post('runs/{run}/activate', 'activate')->name('admin.analytics.market_basket.activate');
});

// Recommendations API for Quotes (Apriori)
Route::get('analytics/recommendations', [AprioriController::class, 'recommendations'])
    ->name('admin.analytics.recommendations');
