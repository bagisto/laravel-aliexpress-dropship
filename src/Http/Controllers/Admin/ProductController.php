<?php

namespace Webkul\Dropship\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Dropship\Http\Controllers\Controller;
use Webkul\Dropship\Repositories\AliExpressProductRepository;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Dropship\DataGrids\Admin\ProductDataGrid;

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
     * Create a new controller instance.
     *
     * @param  Webkul\Dropship\Repositories\AliExpressProductRepository $aliExpressProductRepository
     * @param  Webkul\Product\Repositories\ProductRepository as Product $product
     * @return void
     */
    public function __construct(
        protected AliExpressProductRepository $aliExpressProductRepository,
        protected Product $product
    )
    {
        $this->_config = request('_config');

        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (request()->ajax()) {
            return app(ProductDataGrid::class)->toJson();
        }

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