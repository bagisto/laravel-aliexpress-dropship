<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Tax\Repositories\TaxCategoryRepository as BaseTaxCategoryRepository;

/**
 * Tax Category Reposotory
 *
 * @author    Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
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

        return $taxCategories;
    }
}