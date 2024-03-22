<?php

namespace Webkul\Dropship\Repositories;

use Webkul\Category\Repositories\CategoryRepository as BaseCategoryRepository;

class CategoryRepository extends BaseCategoryRepository
{
    /**
     * Return all categories
     *
     * @return mixed
     */
    public function getCategories()
    {
        $categories = [];

        foreach ($this->all() as $category) {
            if ($category->slug) {
                $categories[$category->id] = $category->name;
            }
        }

        return $categories;
    }
}