<?php

use Illuminate\Support\Facades\Route;
use Webkul\Dropship\Http\Controllers\Admin\{ProductController,OrderController};

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin/dropship')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            Route::get('products', [ProductController::class, 'index'])->defaults('_config', [
                'view' => 'dropship::admin.products.index'
            ])->name('admin.dropship.products.index');

            Route::get('orders', [OrderController::class, 'index'])->defaults('_config', [
                'view' => 'dropship::admin.orders.index'
            ])->name('admin.dropship.orders.index');

            Route::post('products/massdelete', [ProductController::class, 'massDestroy'])->defaults('_config', [
                'redirect' => 'admin.dropship.products.index'
            ])->name('dropship.catalog.products.massdelete');
        });
    });
});