<?php

namespace Webkul\Dropship\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Dropship\Contracts\AliExpressProduct as AliExpressProductContract;
use Webkul\Product\Models\ProductProxy;

class AliExpressProduct extends Model implements AliExpressProductContract
{
    protected $table = 'dropship_ali_express_products';

    protected $fillable = ['ali_express_product_url', 'ali_express_product_description_url', 'ali_express_product_id', 'combination_id', 'product_id', 'parent_id'];

    /**
     * Get the product that belongs to the product.
     */
    public function product()
    {
        return $this->belongsTo(ProductProxy::modelClass());
    }

    /**
     * Get the product variants that owns the product.
     */
    public function variants()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    /**
     * Get the product that owns the product.
     */
    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }
}