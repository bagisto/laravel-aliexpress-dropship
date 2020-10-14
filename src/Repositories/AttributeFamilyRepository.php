<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Attribute\Repositories\AttributeFamilyRepository as BaseAttributeFamilyRepository;

/**
 * Category Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
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