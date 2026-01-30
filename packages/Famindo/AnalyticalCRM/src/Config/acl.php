<?php

return [
    [
        'key'   => 'analytics',
        'name'  => 'Analytics',
        'route' => 'admin.analytics.market_basket.index',
        'sort'  => 8,
    ], [
        'key'   => 'analytics.market_basket',
        'name'  => 'Market Basket (Apriori)',
        'route' => 'admin.analytics.market_basket.index',
        'sort'  => 1,
    ], [
        'key'   => 'analytics.market_basket.view',
        'name'  => 'View',
        'route' => 'admin.analytics.market_basket.index',
        'sort'  => 1,
    ], [
        'key'   => 'analytics.market_basket.create',
        'name'  => 'Run Analysis',
        'route' => 'admin.analytics.market_basket.run',
        'sort'  => 2,
    ], [
        'key'   => 'analytics.market_basket.edit',
        'name'  => 'Activate Snapshot',
        'route' => 'admin.analytics.market_basket.activate',
        'sort'  => 3,
    ], [
        'key'   => 'analytics.market_basket.print',
        'name'  => 'Export PDF',
        'route' => 'admin.analytics.market_basket.export_pdf',
        'sort'  => 4,
    ],
];
