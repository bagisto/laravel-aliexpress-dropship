<?php

return [
    [
        'key' => 'dropship',
        'name' => 'dropship::app.admin.system.dropship',
        'sort' => 1
    ], [
        'key' => 'dropship.settings',
        'name' => 'dropship::app.admin.system.settings',
        'sort' => 1,
    ], [
        'key' => 'dropship.settings.credentials',
        'name' => 'dropship::app.admin.system.credentials',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'username',
                'title' => 'dropship::app.admin.system.username',
                'info' => 'dropship::app.admin.system.username-info',
                'type' => 'text',
                'validation' => 'required'
            ], [
                'name' => 'token',
                'title' => 'dropship::app.admin.system.token',
                'info' => 'dropship::app.admin.system.token-info',
                'type' => 'text',
                'validation' => 'required'
            ]
        ]
    ], [
        'key' => 'dropship.settings.product',
        'name' => 'dropship::app.admin.system.product',
        'sort' => 2,
        'fields' => [
            [
                'name' => 'product_status',
                'title' => 'dropship::app.admin.system.product-status',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'dropship::app.admin.system.enabled',
                        'value' => 1
                    ], [
                        'title' => 'dropship::app.admin.system.disabled',
                        'value' => 0
                    ]
                ]
            ], [
                'name' => 'default_channel',
                'title' => 'dropship::app.admin.system.default-channel',
                'type' => 'select',
                'repository' => 'Webkul\Dropship\Repositories\ChannelRepository@getChannels'
            ], [
                'name' => 'default_locale',
                'title' => 'dropship::app.admin.system.default-locale',
                'type' => 'select',
                'repository' => 'Webkul\Dropship\Repositories\LocaleRepository@getLocales'
            ], [
                'name' => 'default_attribute_family',
                'title' => 'dropship::app.admin.system.default-attribute-family',
                'type' => 'select',
                'repository' => 'Webkul\Dropship\Repositories\AttributeFamilyRepository@getAttributeFamilies'
            ], [
                'name' => 'default_category',
                'title' => 'dropship::app.admin.system.default-category',
                'type' => 'select',
                'repository' => 'Webkul\Dropship\Repositories\CategoryRepository@getCategories'
            ], [
                'name' => 'default_tax_category',
                'title' => 'dropship::app.admin.system.default-tax-category',
                'type' => 'select',
                'repository' => 'Webkul\Dropship\Repositories\TaxCategoryRepository@getTaxCategories'
            ], [
                'name' => 'set_new',
                'title' => 'dropship::app.admin.system.set-as-new',
                'type' => 'boolean'
            ], [
                'name' => 'set_featured',
                'title' => 'dropship::app.admin.system.set-as-featured',
                'type' => 'boolean'
            ], [
                'name' => 'weight',
                'title' => 'dropship::app.admin.system.default-weight',
                'type' => 'text',
                'validation' => 'decimal'
            ]
        ]
    ], [
        'key' => 'dropship.settings.product_price',
        'name' => 'dropship::app.admin.system.product-price',
        'sort' => 3,
        'fields' => [
            [
                'name' => 'price',
                'title' => 'dropship::app.admin.system.price',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'dropship::app.admin.system.same-as-ali-express',
                        'value' => 1
                    ], [
                        'title' => 'dropship::app.admin.system.custom-price',
                        'value' => 2,
                    ], [
                        'title' => 'dropship::app.admin.system.increase',
                        'value' => 3
                    ], [
                        'title' => 'dropship::app.admin.system.decrease',
                        'value' => 4
                    ]
                ]
            ], [
                'name' => 'custom_price',
                'title' => 'dropship::app.admin.system.custom-price',
                'info' => 'dropship::app.admin.system.custom-price-info',
                'type' => 'text',
                'validation' => 'decimal'
            ], [
                'name' => 'increase_price',
                'title' => 'dropship::app.admin.system.increase-price',
                'info' => 'dropship::app.admin.system.increase-price-info',
                'type' => 'text',
                'validation' => 'decimal'
            ], [
                'name' => 'decrease_price',
                'title' => 'dropship::app.admin.system.decrease-price',
                'info' => 'dropship::app.admin.system.decrease-price-info',
                'type' => 'text',
                'validation' => 'decimal'
            ]
        ]
    ], [
        'key' => 'dropship.settings.product_quantity',
        'name' => 'dropship::app.admin.system.product-quantity',
        'sort' => 4,
        'fields' => [
            [
                'name' => 'product_quantity',
                'title' => 'dropship::app.admin.system.quantity',
                'type' => 'select',
                'options' => [
                    [
                        'title' => 'dropship::app.admin.system.same-as-ali-express',
                        'value' => 1
                    ], [
                        'title' => 'dropship::app.admin.system.custom-quantity',
                        'value' => 2
                    ]
                ]
            ], [
                'name' => 'custom_quantity',
                'title' => 'dropship::app.admin.system.custom-quantity',
                'info' => 'dropship::app.admin.system.custom-quantity-info',
                'type' => 'text',
                'validation' => 'numeric'
            ], [
                'name' => 'default_inventory_source',
                'title' => 'dropship::app.admin.system.default-inventory-source',
                'type' => 'select',
                'repository' => 'Webkul\Dropship\Repositories\InventorySourceRepository@getInventorySources',

            ]
        ]
    ]
    // , [
    //     'key' => 'dropship.settings.auto_updation',
    //     'name' => 'dropship::app.admin.system.auto-updation',
    //     'sort' => 5,
    //     'fields' => [
    //         [
    //             'name' => 'quantity',
    //             'title' => 'dropship::app.admin.system.quantity',
    //             'type' => 'boolean',
    //         ], [
    //             'name' => 'price',
    //             'title' => 'dropship::app.admin.system.price',
    //             'type' => 'boolean',
    //         ]
    //     ]
    // ]
];

?>