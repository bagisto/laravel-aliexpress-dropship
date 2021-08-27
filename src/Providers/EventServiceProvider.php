<?php

namespace Webkul\Dropship\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen('bagisto.admin.layout.head', function($viewRenderEventManager) {
            $viewRenderEventManager->addTemplate('dropship::admin.layouts.style');
        });

        Event::listen('checkout.order.save.after', 'Webkul\Dropship\Listeners\Order@afterPlaceOrder');
    }
}