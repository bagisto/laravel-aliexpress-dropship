<?php

namespace Webkul\Dropship\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Dropship\Contracts\AliExpressOrderItem as AliExpressOrderItemContract;
use Webkul\Sales\Models\OrderItemProxy;

class AliExpressOrderItem extends Model implements AliExpressOrderItemContract
{
    protected $table = 'dropship_ali_express_order_items';

    protected $fillable = ['ali_express_product_id', 'order_item_id', 'ali_express_order_id', 'parent_id'];

    /**
     * Get the order item that mapped to the AliExpress order.
     */
    public function order_item()
    {
        return $this->belongsTo(OrderItemProxy::modelClass());
    }

    /**
     * Get the order that mapped to the AliExpress order item.
     */
    public function ali_express_order()
    {
        return $this->belongsTo(AliExpressOrderProxy::modelClass());
    }

    /**
     * Get the child item record associated with the order item.
     */
    public function child()
    {
        return $this->hasOne(AliExpressOrderItemProxy::modelClass(), 'parent_id');
    }
}