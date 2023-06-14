<?php

return [
    [
        'key'        => 'dropship',
        'name'       => 'dropship::app.admin.layouts.dropship',
        'route'      => 'admin.dropship.orders.index',
        'sort'       => 2,
        'icon-class' => 'dropship-icon',
    ], [
        'key'   => 'dropship.products',
        'name'  => 'dropship::app.admin.layouts.products',
        'route' => 'admin.dropship.products.index',
        'sort'  => 1,
    ], [
        'key'   => 'dropship.orders',
        'name'  => 'dropship::app.admin.layouts.orders',
        'route' => 'admin.dropship.orders.index',
        'sort'  => 2,
    ]
];

?>