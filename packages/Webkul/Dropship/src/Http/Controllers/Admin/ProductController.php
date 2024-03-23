<?php

namespace Webkul\Dropship\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Product\Repositories\ProductRepository as Product;
use Webkul\Dropship\Http\Controllers\Controller;
use Webkul\Dropship\Repositories\AliExpressProductRepository;

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
     * @param  \Webkul\Dropship\Repositories\AliExpressProductRepository  $aliExpressProductRepository
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