<?php

namespace Webkul\Dropship\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Dropship\Contracts\AliExpressProductImage as AliExpressProductImageContract;
use Webkul\Product\Models\ProductImageProxy;

class AliExpressProductImage extends Model implements AliExpressProductImageContract
{
    public $timestamps = false;
    
    protected $table = 'dropship_ali_express_product_images';

    protected $fillable = ['url', 'product_image_id'];

    /**
     * Get the product image that belongs to the product.
     */
    public function product_image()
    {
        return $this->belongsTo(ProductImageProxy::modelClass());
    }
}