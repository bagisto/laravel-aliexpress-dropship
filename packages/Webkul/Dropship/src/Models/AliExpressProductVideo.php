<?php

namespace Webkul\Dropship\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Dropship\Contracts\AliExpressProductVideo as AliExpressProductVideoContract;
use Webkul\Product\Models\ProductVideoProxy;

class AliExpressProductVideo extends Model implements AliExpressProductVideoContract
{
    public $timestamps = false;

    protected $table = 'dropship_ali_express_product_videos';

    protected $fillable = ['url', 'product_video_id'];

    /**
     * Get the product video that belongs to the product.
     */
    public function product_video()
    {
        return $this->belongsTo(ProductVideoProxy::modelClass());
    }
}