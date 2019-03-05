<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Core\Eloquent\Repository;

/**
 * Order Item Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class AliExpressOrderItemRepository extends Repository
{
    /**
     * Specify Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'Webkul\Dropship\Contracts\AliExpressOrderItem';
    }
}