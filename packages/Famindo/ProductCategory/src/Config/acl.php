<?php

return [
    [
        'key'   => 'products.product_categories',
        'name'  => 'Product Categories',
        'route' => 'admin.products.categories.index',
        'sort'  => 1,
    ], [
        'key'   => 'products.product_categories.create',
        'name'  => 'Create',
        'route' => ['admin.products.categories.create', 'admin.products.categories.store'],
        'sort'  => 2,
    ], [
        'key'   => 'products.product_categories.edit',
        'name'  => 'Edit',
        'route' => ['admin.products.categories.edit', 'admin.products.categories.update'],
        'sort'  => 3,
    ], [
        'key'   => 'products.product_categories.delete',
        'name'  => 'Delete',
        'route' => ['admin.products.categories.delete'],
        'sort'  => 4,
    ],
];

