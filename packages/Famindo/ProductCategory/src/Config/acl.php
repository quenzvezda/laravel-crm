<?php

return [
    [
        'key'   => 'products.product_categories',
        'name'  => 'product-category::app.index.title',
        'route' => 'admin.products.categories.index',
        'sort'  => 1,
    ], [
        'key'   => 'products.product_categories.create',
        'name'  => 'admin::app.acl.create',
        'route' => ['admin.products.categories.create', 'admin.products.categories.store'],
        'sort'  => 2,
    ], [
        'key'   => 'products.product_categories.edit',
        'name'  => 'admin::app.acl.edit',
        'route' => ['admin.products.categories.edit', 'admin.products.categories.update'],
        'sort'  => 3,
    ], [
        'key'   => 'products.product_categories.delete',
        'name'  => 'admin::app.acl.delete',
        'route' => ['admin.products.categories.delete'],
        'sort'  => 4,
    ],
];
