<?php

Route::group(['middleware' => ['web']], function () {

    Route::prefix('dropship/aliexpress')->group(function () {

        Route::get('validate-url', 'Webkul\Dropship\Http\Controllers\SecurityController@validateUrl')
            ->name('dropship.aliexpress.validate_url');

        Route::get('authenticate-user', 'Webkul\Dropship\Http\Controllers\SecurityController@authenticateUser')
            ->name('dropship.aliexpress.authenticate_user');

        Route::get('import-super-attributes', 'Webkul\Dropship\Http\Controllers\AttributeController@importSuperAttributes')
            ->name('dropship.aliexpress.attribute.import_super_attributes');

        Route::get('import-product', 'Webkul\Dropship\Http\Controllers\ProductController@importProduct')
            ->name('dropship.aliexpress.products.import_product');

        Route::get('import-variation', 'Webkul\Dropship\Http\Controllers\ProductController@importVariation')
            ->name('dropship.aliexpress.products.import_variation');

        Route::get('import-reviews', 'Webkul\Dropship\Http\Controllers\ReviewController@import')
            ->name('dropship.aliexpress.reviews.import');

        Route::get('orders', 'Webkul\Dropship\Http\Controllers\OrderController@index')
            ->name('dropship.aliexpress.orders.index');

        Route::get('place-order', 'Webkul\Dropship\Http\Controllers\OrderController@placeOrder')
            ->name('dropship.aliexpress.orders.place_order');

        Route::get('order-details', 'Webkul\Dropship\Http\Controllers\OrderController@orderDetails')
            ->name('dropship.aliexpress.orders.order_details');
    });
});