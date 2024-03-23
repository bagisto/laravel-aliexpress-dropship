<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Attribute\Repositories\AttributeFamilyRepository as BaseAttributeFamilyRepository;

class AttributeFamilyRepository extends BaseAttributeFamilyRepository
{
    /**
     * Return all attribute families
     *
     * @return mixed
     */
    public function getAttributeFamilies()
    {
        $attributeFamilies = [];

        foreach ($this->all() as $attributeFamily) {
            $attributeFamilies[$attributeFamily->id] = $attributeFamily->name;
        }

        return $attributeFamilies;
    }
}