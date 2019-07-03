<?php

namespace Webkul\Dropship\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Webkul\Dropship\Http\Controllers\Controller;
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
    protected $aliExpressProductRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Dropship\Repositories\AliExpressProductRepository $aliExpressProductRepository
     * @return void
     */
    public function __construct(
        AliExpressProductRepository $aliExpressProductRepository
    )
    {
        $this->_config = request('_config');

        $this->aliExpressProductRepository = $aliExpressProductRepository;
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
}