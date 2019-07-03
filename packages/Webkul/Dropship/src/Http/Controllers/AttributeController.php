<?php

namespace Webkul\Dropship\Http\Controllers;

use Illuminate\Http\Request;
use Webkul\Dropship\Repositories\AliExpressAttributeRepository;

/**
 * Attribute controller
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AttributeController extends Controller
{
    /**
     * AliExpressAttributeRepository object
     *
     * @var array
     */
    protected $aliExpressAttributeRepository;

    /**
     * Create a new controller instance.
     *
     * @param  Webkul\Dropship\Repositories\AliExpressAttributeRepository $aliExpressAttributeRepository
     * @return void
     */
    public function __construct(
        AliExpressAttributeRepository  $aliExpressAttributeRepository
    )
    {
        $this->aliExpressAttributeRepository = $aliExpressAttributeRepository;
    }

    /**
     * Import super attributes
     *
     * @return \Illuminate\Http\Response
     */
    public function importSuperAttributes()
    {
        $callback = request()->input('callback');

        $data = request()->all();

        if (isset($data['super_attributes'])) {
            $result = $this->aliExpressAttributeRepository->importSuperAttributes($data['super_attributes']);

            $response = response($callback . '(' . json_encode([
                    'success' => true,
                    'data' => $result
                ]) . ')');
        } else {
            $response = response($callback . '(' . json_encode([
                    'success' => false,
                    'message' => 'No attributes available.',
                ]) . ')');
        }

        $response->header('Content-Type', 'application/javascript');

        return $response;
    }
}