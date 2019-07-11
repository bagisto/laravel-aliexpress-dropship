<?php

namespace Webkul\Dropship\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Dropship\Repositories\AliExpressOrderRepository;

/**
 * Order controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderController extends Controller
{
    /**
     * AliExpressOrderRepository object
     *
     * @var array
     */
    protected $aliExpressOrderRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Dropship\Repositories\AliExpressOrderRepository $aliExpressOrderRepository
     * @return void
     */
    public function __construct(
        AliExpressOrderRepository  $aliExpressOrderRepository
    )
    {
        $this->aliExpressOrderRepository = $aliExpressOrderRepository;
    }

    /**
     * Returns AliExpress orders
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $callback = request()->input('callback');

        try {
            $orders = [];

            foreach ($this->aliExpressOrderRepository->findWhere(['is_placed' => 0]) as $aliExpressOrder) {
                $orders[] = $aliExpressOrder->id . "_" . $aliExpressOrder->order_id . "_" . $aliExpressOrder->order->customer_full_name . "_0";
            }
            $response = response($callback . '(' . json_encode([
                    'orders' => implode("+", $orders)
                ]) . ')');
        } catch(\Exception $e) {
            $response = response($callback . '(' . json_encode([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]) . ')');
        }

        $response->header('Content-Type', 'application/javascript');
        return $response;
    }

    /**
     * Sets is_place column to 1
     *
     * @return \Illuminate\Http\Response
     */
    public function placeOrder()
    {
        $callback = request()->input('callback');

        try {
            if ($order = request()->input('order_id')) {
                $this->aliExpressOrderRepository->update([
                        'is_placed' => 1
                    ], request()->input('order_id'));

                $response = response($callback . '(' . json_encode([
                        'success' => true,
                        'message' => 'Order successfully updated.'
                    ]) . ')');
            } else {
                $response = response($callback . '(' . json_encode([
                        'success' => false,
                        'message' => 'Order id not exist.'
                    ]) . ')');
            }
        } catch(\Exception $e) {
            $response = response($callback . '(' . json_encode([
                    'success' => false,
                    'message' => $e->getMessage(),
                ]) . ')');
        }

        $response->header('Content-Type', 'application/javascript');
        return $response;
    }

    /**
     * Returns the details of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function orderDetails()
    {
        $callback = request()->input('callback');

        try {
            if ($orderId = request()->input('order_id')) {
                $aliExpressOrder = $this->aliExpressOrderRepository->findOneByField('order_id', $orderId);

                if ($aliExpressOrder) {
                    $address = $aliExpressOrder->order->shipping_address ?? $aliExpressOrder->order->billing_address;

                    $state = app('Webkul\Core\Repositories\CountryStateRepository')->findOneByField('code', $address->state);

                    $result = [
                            'contact_name' => $address->name,
                            'contact_email' => $address->email,
                            'shipping_address_1' => $address->address1,
                            'shipping_address_2' => $address->address2,
                            'shipping_city' => $address->city,
                            'telephone' => $address->phone,
                            'iso_code_2' => $address->country,
                            'zipcode' => $address->postcode,
                            'state' => $state->default_name,
                            'success' => true
                        ];
                } else {
                    $result = [
                            'success' => false,
                            'message' => 'Order not exist.'
                        ];
                }
            } else {
                $result = [
                        'success' => false,
                        'message' => 'Order id not exist.'
                    ];
            }
        } catch(\Exception $e) {
            $result = [
                    'success' => false,
                    'message' => $e->getMessage()
                ];
        }


        $response = response($callback . '(' . json_encode($result) . ')');
        $response->header('Content-Type', 'application/javascript');
        return $response;
    }
}