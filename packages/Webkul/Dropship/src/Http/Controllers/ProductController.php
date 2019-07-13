<?php

namespace Webkul\Dropship\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Dropship\Repositories\AliExpressProductRepository;

/**
 * Product controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
{
    /**
     * AliExpressProductRepository object
     *
     * @var array
     */
    protected $aliExpressProductRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Dropship\Repositories\AliExpressProductRepository $aliExpressProductRepository
     * @return void
     */
    public function __construct(
        AliExpressProductRepository  $aliExpressProductRepository
    )
    {
        $this->aliExpressProductRepository = $aliExpressProductRepository;
    }

    /**
     * Import AliExpress products into you shop
     *
     * @return \Illuminate\Http\Response
     */
    public function importProduct()
    {
        $callback = request()->input('callback');

        try {
            $this->validate(request(), [
                'id' => 'required',
                'name' => 'required',
                'price' => 'required'
            ]);

            $aliExpressProduct = $this->aliExpressProductRepository->findOneWhere([
                    'ali_express_product_id' => request()->input('id'),
                ]);

            if ($aliExpressProduct) {
                $response = response($callback . '(' . json_encode([
                        'success' => false,
                        'message' => 'Product Already Imported.',
                    ]) . ')');
            } else {
                $aliExpressProduct = $this->aliExpressProductRepository->create(request()->all());

                $response = response($callback . '(' . json_encode([
                        'success' => true,
                        'message' => 'Product Successfully Imported.',
                        'product_id' => $aliExpressProduct->product_id
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
     * Import AliExpress product variation into you shop
     *
     * @return \Illuminate\Http\Response
     */
    public function importVariation()
    {
        $callback = request()->input('callback');

        try {
            $this->validate(request(), [
                'product_id' => 'required',
                'custom_option.comb' => 'required',
                'custom_option.price' => 'required'
            ]);

            $aliExpressProduct = $this->aliExpressProductRepository->findOneWhere([
                    'product_id' => request()->input('product_id'),
                ]);

            if (! $aliExpressProduct) {

                $response = response($callback . '(' . json_encode([
                        'success' => false,
                        'message' => 'Product import error.',
                    ]) . ')');
            } else {
                $productVariant = $this->aliExpressProductRepository->createVariant($aliExpressProduct, request()->all());


                $response = response($callback . '(' . json_encode([
                        'success' => true,
                        'message' => 'Product Successfully Imported.',
                        'product_id' => $productVariant->parent_id
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
}