<?php

namespace Webkul\Dropship\Providers;

use Konekt\Concord\BaseModuleServiceProvider;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        \Webkul\Dropship\Models\AliExpressProduct::class,
        \Webkul\Dropship\Models\AliExpressProductReview::class,
        \Webkul\Dropship\Models\AliExpressProductImage::class,
        \Webkul\Dropship\Models\AliExpressAttribute::class,
        \Webkul\Dropship\Models\AliExpressAttributeOption::class,
        \Webkul\Dropship\Models\AliExpressOrder::class,
        \Webkul\Dropship\Models\AliExpressOrderItem::class,
    ];
}