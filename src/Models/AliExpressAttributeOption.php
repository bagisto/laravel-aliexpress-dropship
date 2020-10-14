<?php

namespace Webkul\Dropship\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Dropship\Contracts\AliExpressAttributeOption as AliExpressAttributeOptionContract;
use Webkul\Attribute\Models\AttributeOptionProxy;

class AliExpressAttributeOption extends Model implements AliExpressAttributeOptionContract
{
    public $timestamps = false;
    
    protected $table = 'dropship_ali_express_attribute_options';

    protected $fillable = ['ali_express_swatch_name', 'ali_express_swatch_image', 'ali_express_attribute_option_id', 'ali_express_attribute_id', 'attribute_option_id'];

    /**
     * Get the attribute option that mapped to the AliExpress attribute.
     */
    public function attribute_option()
    {
        return $this->belongsTo(AttributeOptionProxy::modelClass());
    }

    /**
     * Get the AliExpress attribute option that belongs to the AliExpress attribute option.
     */
    public function ali_express_attribute()
    {
        return $this->belongsTo(AliExpressAttributeProxy::modelClass());
    }
}