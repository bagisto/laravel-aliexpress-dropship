<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Tax\Repositories\TaxCategoryRepository as BaseTaxCategoryRepository;

class TaxCategoryRepository extends BaseTaxCategoryRepository
{
    /**
     * Return all tax categories
     *
     * @return mixed
     */
    public function getTaxCategories()
    {
        $taxCategories = [];

        foreach ($this->all() as $taxCategory) {
            $taxCategories[$taxCategory->id] = $taxCategory->name;
        }
        array_unshift($taxCategories, " ");

        return $taxCategories;
    }
}