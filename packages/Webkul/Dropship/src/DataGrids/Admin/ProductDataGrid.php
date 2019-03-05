<?php

namespace Webkul\Dropship\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Product Data Grid class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class ProductDataGrid extends DataGrid
{
    /**
     *
     * @var integer
     */
    public $index = 'product_id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('products_grid')
                ->leftJoin('products', 'products_grid.product_id', '=', 'products.id')
                ->join('dropship_ali_express_products', 'products_grid.product_id', '=', 'dropship_ali_express_products.product_id')

                ->addSelect('dropship_ali_express_products.id as dropship_ali_express_product_id', 'products_grid.product_id', 'products_grid.sku', 'products_grid.name', 'products_grid.price', 'products_grid.quantity');
        

        $this->addFilter('sku', 'products_grid.sku');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'product_id',
            'label' => trans('dropship::app.admin.products.product-id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true
        ]);

        $this->addColumn([
            'index' => 'sku',
            'label' => trans('dropship::app.admin.products.sku'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true
        ]);

        $this->addColumn([
            'index' => 'name',
            'label' => trans('dropship::app.admin.products.name'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true
        ]);

        $this->addColumn([
            'index' => 'price',
            'label' => trans('dropship::app.admin.products.price'),
            'type' => 'price',
            'sortable' => true,
            'searchable' => false
        ]);

        $this->addColumn([
            'index' => 'quantity',
            'label' => trans('dropship::app.admin.products.quantity'),
            'type' => 'number',
            'sortable' => true,
            'searchable' => false,
        ]);
    }

    public function prepareActions() {
        $this->addAction([
            'type' => 'Edit',
            'route' => 'admin.catalog.products.edit',
            'icon' => 'icon pencil-lg-icon'
        ]);
    }
}