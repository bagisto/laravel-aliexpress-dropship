<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('admin/dropship')->group(function () {

        Route::group(['middleware' => ['admin']], function () {

            Route::get('products', 'Webkul\Dropship\Http\Controllers\Admin\ProductController@index')->defaults('_config', [
                'view' => 'dropship::admin.products.index'
            ])->name('admin.dropship.products.index');

            Route::get('orders', 'Webkul\Dropship\Http\Controllers\Admin\OrderController@index')->defaults('_config', [
                'view' => 'dropship::admin.orders.index'
            ])->name('admin.dropship.orders.index');

            Route::post('products/massdelete', 'Webkul\Dropship\Http\Controllers\Admin\ProductController@massDestroy')->defaults('_config', [
                'redirect' => 'admin.dropship.products.index'
            ])->name('dropship.catalog.products.massdelete');
        });
    });
});