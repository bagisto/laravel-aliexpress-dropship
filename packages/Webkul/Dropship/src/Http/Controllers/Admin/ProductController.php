<?php

namespace Webkul\Dropship\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Dropship\Http\Controllers\Controller;
use Webkul\Dropship\Repositories\AliExpressProductRepository;
use Webkul\Product\Repositories\ProductRepository as Product;


/**
 * Product controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductController extends Controller
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * AliExpressProductRepository object
     *
     * @var array
    */

      /**
     * ProductRepository object
     *
     * @var array
     */
    protected $product;

    protected $aliExpressProductRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Dropship\Repositories\AliExpressProductRepository $aliExpressProductRepository
     * @return void
     */
    public function __construct(
        AliExpressProductRepository $aliExpressProductRepository,
        Product $product
    )
    {
        $this->_config = request('_config');

        $this->middleware('admin');

        $this->aliExpressProductRepository = $aliExpressProductRepository;

        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view($this->_config['view']);
    }

    /**
     * Mass Delete the products
     *
     * @return response
     */
    public function massDestroy()
    {
        $productIds = explode(',', request()->input('indexes'));

        foreach ($productIds as $productId) {
            $product = $this->product->find($productId);

            if (isset($product)) {
                $this->product->delete($productId);
            }
        }

        session()->flash('success', trans('admin::app.catalog.products.mass-delete-success'));

        return redirect()->route($this->_config['redirect']);
    }
}