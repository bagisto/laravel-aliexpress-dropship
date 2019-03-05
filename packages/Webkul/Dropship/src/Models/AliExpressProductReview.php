<?php

namespace Webkul\Dropship\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Dropship\Contracts\AliExpressProductReview as AliExpressProductReviewContract;
use Webkul\Product\Models\ProductReviewProxy;

class AliExpressProductReview extends Model implements AliExpressProductReviewContract
{
    protected $table = 'dropship_ali_express_product_reviews';

    protected $fillable = ['ali_express_review_id', 'product_review_id'];

    /**
     * Get the product review that belongs to the ali express product review.
     */
    public function review()
    {
        return $this->belongsTo(ProductReviewProxy::modelClass());
    }
}