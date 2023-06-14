<?php

namespace Webkul\Dropship\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Sales\Models\OrderProxy;
use Webkul\Dropship\Contracts\AliExpressOrder as AliExpressOrderContract;

class AliExpressOrder extends Model implements AliExpressOrderContract
{
    protected $table = 'dropship_ali_express_orders';

    protected $fillable = ['order_id', 'is_placed', 'ali_express_add_cart_url'];

    /**
     * Get the order that mapped to the AliExpress order.
     */
    public function order()
    {
        return $this->belongsTo(OrderProxy::modelClass());
    }

    /**
     * Get the order items record associated with the order.
     */
    public function items()
    {
        return $this->hasMany(AliExpressOrderItemProxy::modelClass(), 'ali_express_order_id')->whereNull('parent_id');
    }
}