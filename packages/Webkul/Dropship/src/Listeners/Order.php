<?php

namespace Webkul\Dropship\Listeners;

use Illuminate\Support\Facades\Mail;
use Webkul\Dropship\Repositories\AliExpressOrderRepository;

/**
 * Order event handler
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class Order
{
    /**
     * AliExpressOrderRepository object
     *
     * @var AliExpressOrderRepository
    */
    protected $aliExpressOrderRepository;

    /**
     * Create a new customer event listener instance.
     *
     * @param  Webkul\Dropship\Repositories\AliExpressOrderRepository $aliExpressOrderRepository
     * @return void
     */
    public function __construct(
        AliExpressOrderRepository $aliExpressOrderRepository
    )
    {
        $this->aliExpressOrderRepository = $aliExpressOrderRepository;
    }

    /**
     * After sales order creation, add entry to dropship order table
     *
     * @param mixed $order
     */
    public function afterPlaceOrder($order)
    {
        $this->aliExpressOrderRepository->create(['order' => $order]);
    }
}