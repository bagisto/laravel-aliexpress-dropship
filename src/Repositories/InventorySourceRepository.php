<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Inventory\Repositories\InventorySourceRepository as BaseInventorySourceRepository;

/**
 * Inventory Source Repository
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class InventorySourceRepository extends BaseInventorySourceRepository
{
    /**
     * Return all inventory sources
     *
     * @return mixed
     */
    public function getInventorySources()
    {
        $inventorySources = [];

        foreach ($this->all() as $inventorySource) {
            $inventorySources[$inventorySource->id] = $inventorySource->name;
        }

        return $inventorySources;
    }
}