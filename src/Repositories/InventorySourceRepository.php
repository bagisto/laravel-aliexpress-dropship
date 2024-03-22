<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Inventory\Repositories\InventorySourceRepository as BaseInventorySourceRepository;

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