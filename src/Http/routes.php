<?php

use Illuminate\Support\Facades\Route;
use Webkul\Dropship\Http\Controllers\{SecurityController,AttributeController,ProductController,ReviewController,OrderController};

Route::group(['middleware' => ['web']], function () {

    Route::prefix('dropship/aliexpress')->group(function () {

        Route::get('validate-url', [SecurityController::class, 'validateUrl'])
            ->name('dropship.aliexpress.validate_url');

        Route::get('authenticate-user', [SecurityController::class, 'authenticateUser'])
            ->name('dropship.aliexpress.authenticate_user');

        Route::get('import-super-attributes', [AttributeController::class, 'importSuperAttributes'])
            ->name('dropship.aliexpress.attribute.import_super_attributes');

        Route::get('import-product', [ProductController::class, 'importProduct'])
            ->name('dropship.aliexpress.products.import_product');

        Route::get('import-variation', [ProductController::class, 'importVariation'])
            ->name('dropship.aliexpress.products.import_variation');

        Route::get('import-reviews', [ReviewController::class, 'import'])
            ->name('dropship.aliexpress.reviews.import');

        Route::get('orders', [OrderController::class, 'index'])
            ->name('dropship.aliexpress.orders.index');

        Route::get('place-order', [OrderController::class, 'placeOrder'])
            ->name('dropship.aliexpress.orders.place_order');

        Route::get('order-details', [OrderController::class, 'orderDetails'])
            ->name('dropship.aliexpress.orders.order_details');
    });

});