<?php

namespace Webkul\Dropship\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Dropship\Repositories\AliExpressProductReviewRepository;

/**
 * Review controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ReviewController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Dropship\Repositories\AliExpressProductReviewRepository $aliExpressProductReviewRepository
     * @return void
     */
    public function __construct(
        protected AliExpressProductReviewRepository  $aliExpressProductReviewRepository
    ) {}

    /**
     * Import AliExpress product reviews into you shop
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $callback = request()->input('callback');

        try {
            $this->aliExpressProductReviewRepository->importReviews(request()->all());

            $response = response($callback . '(' . json_encode([
                    'success'    => true,
                    'message'    => 'Reviews Successfully Imported.',
                    'product_id' => request()->input('product_id')
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
}