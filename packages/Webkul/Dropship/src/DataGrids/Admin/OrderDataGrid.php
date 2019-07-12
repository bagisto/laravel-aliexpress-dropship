<?php

namespace Webkul\Dropship\DataGrids\Admin;

use DB;
use Webkul\Ui\DataGrid\DataGrid;

/**
 * Order Data Grid class
 *
 * @author Jitendra Singh <jitendra@webkul.com>
 * @copyright 2018 Webkul Software Pvt Ltd (http://www.webkul.com)
 */
class OrderDataGrid extends DataGrid
{
    /**
     *
     * @var integer
     */
    public $index = 'order_id';

    public function prepareQueryBuilder()
    {
        $queryBuilder = DB::table('dropship_ali_express_orders')
                ->leftJoin('orders', 'dropship_ali_express_orders.order_id', '=', 'orders.id')
                ->select('orders.id', 'dropship_ali_express_orders.order_id', 'dropship_ali_express_orders.ali_express_add_cart_url', 'orders.base_grand_total', 'dropship_ali_express_orders.created_at', 'orders.status', 'dropship_ali_express_orders.is_placed')
                ->addSelect(DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name) as customer_name, orders.customer_email'))->orderBy('created_at','desc');

        $this->addFilter('customer_name', DB::raw('CONCAT(orders.customer_first_name, " ", orders.customer_last_name)'));
        $this->addFilter('created_at', 'dropship_ali_express_orders.created_at');
        $this->addFilter('id', 'orders.id');

        $this->setQueryBuilder($queryBuilder);
    }

    public function addColumns()
    {
        $this->addColumn([
            'index' => 'id',
            'label' => trans('dropship::app.admin.orders.order-id'),
            'type' => 'number',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);


        $this->addColumn([
            'index' => 'base_grand_total',
            'label' => trans('dropship::app.admin.orders.grand-total'),
            'type' => 'price',
            'searchable' => false,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_email',
            'label' => 'Email',
            'type' => 'string',
            'searchable' => true,
            'sortable' => false,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'customer_name',
            'label' => trans('dropship::app.admin.orders.billed-to'),
            'type' => 'string',
            'searchable' => true,
            'sortable' => true,
            'filterable' => true
        ]);

        $this->addColumn([
            'index' => 'status',
            'label' => trans('dropship::app.admin.orders.status'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($row) {
                if ($row->status == 'processing')
                    return '<span class="badge badge-md badge-success">' . trans('dropship::app.admin.orders.processing') . '</span>';
                else if ($row->status == 'completed')
                    return '<span class="badge badge-md badge-success">' . trans('dropship::app.admin.orders.completed') . '</span>';
                else if ($row->status == 'canceled')
                    return '<span class="badge badge-md badge-danger">' . trans('dropship::app.admin.orders.canceled') . '</span>';
                else if ($row->status == 'closed')
                    return '<span class="badge badge-md badge-info">' . trans('dropship::app.admin.orders.closed') . '</span>';
                else if ($row->status == 'pending')
                    return '<span class="badge badge-md badge-warning">' . trans('dropship::app.admin.orders.pending') . '</span>';
                else if ($row->status == 'pending_payment')
                    return '<span class="badge badge-md badge-warning">' . trans('dropship::app.admin.orders.pending-payment') . '</span>';
                else if ($row->status == 'fraud')
                    return '<span class="badge badge-md badge-danger">' . trans('dropship::app.admin.orders.fraud') . '</span>';
            }
        ]);

// code starts
        $this->addColumn([
            'index' => 'is_placed',
            'label' => trans('dropship::app.admin.orders.place-order'),
            'type' => 'string',
            'sortable' => false,
            'searchable' => false,
            'filterable' => true,
            'closure' => true,
            'wrapper' => function ($row) {
                if ($row->is_placed)
                    return trans('dropship::app.admin.orders.already-placed');

                return '<a href="https://' . $row->ali_express_add_cart_url . '" target="_blank">' . trans('dropship::app.admin.orders.checkout-on-aliexpress') . '</a>';
            }
        ]);

        $this->addColumn([
            'index' => 'created_at',
            'label' => trans('dropship::app.admin.orders.order-date'),
            'type' => 'string',
            'sortable' => true,
            'searchable' => false,
            'filterable' => true
        ]);
    }

    public function prepareActions()
    {
        $this->addAction([
            'type' => 'View',
            'method' => 'GET', // use GET request only for redirect purposes
            'route' => 'admin.sales.orders.view',
            'icon' => 'icon eye-icon'
        ]);
    }
}