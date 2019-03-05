<?php

namespace Webkul\Dropship\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Dropship\Contracts\AliExpressAttribute as AliExpressAttributeContract;
use Webkul\Attribute\Models\AttributeProxy;

class AliExpressAttribute extends Model implements AliExpressAttributeContract
{
    public $timestamps = false;
    
    protected $table = 'dropship_ali_express_attributes';

    protected $fillable = ['ali_express_attribute_id', 'attribute_id'];

    /**
     * Get the attribute that mapped to the AliExpress attribute.
     */
    public function attribute()
    {
        return $this->belongsTo(AttributeProxy::modelClass());
    }

    /**
     * Get the options.
     */
    public function ali_express_attribute_options()
    {
        return $this->hasMany(AliExpressAttributeOptionProxy::modelClass());
    }
}