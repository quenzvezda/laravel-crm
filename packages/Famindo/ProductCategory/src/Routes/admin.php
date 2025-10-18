<?php

use Illuminate\Support\Facades\Route;
use Famindo\ProductCategory\Http\Controllers\ProductCategoryController;

Route::prefix('products')->group(function () {
    Route::controller(ProductCategoryController::class)->prefix('product-categories')->group(function () {
        Route::get('', 'index')->name('admin.products.categories.index');

        Route::get('create', 'create')->name('admin.products.categories.create');

        Route::post('create', 'store')->name('admin.products.categories.store');

        Route::get('edit/{id}', 'edit')->name('admin.products.categories.edit');

        Route::put('edit/{id}', 'update')->name('admin.products.categories.update');

        Route::delete('{id}', 'destroy')->name('admin.products.categories.delete');
    });
});

