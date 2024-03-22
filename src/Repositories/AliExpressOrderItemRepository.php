<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Core\Eloquent\Repository;

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