<?php

namespace Famindo\ProductCategory\DataGrids;

use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Webkul\DataGrid\DataGrid;

class ProductCategoryDataGrid extends DataGrid
{
    public function prepareQueryBuilder(): Builder
    {
        $queryBuilder = DB::table('product_categories')->select(
            'id',
            'code',
            'name',
            'description',
            'created_at'
        );

        $this->addFilter('code', 'code');
        $this->addFilter('name', 'name');
        $this->addFilter('description', 'description');

        return $queryBuilder;
    }

    public function prepareColumns(): void
    {
        $this->addColumn([
            'index'      => 'code',
            'label'      => trans('product-category::app.index.datagrid.code'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'name',
            'label'      => trans('product-category::app.index.datagrid.name'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => true,
            'filterable' => true,
        ]);

        $this->addColumn([
            'index'      => 'description',
            'label'      => trans('product-category::app.index.datagrid.description'),
            'type'       => 'string',
            'searchable' => true,
            'sortable'   => false,
            'filterable' => true,
            'closure'    => fn ($row) => $row->description ?? '--',
        ]);
    }

    public function prepareActions(): void
    {
        if (bouncer()->hasPermission('products.product_categories.edit')) {
            $this->addAction([
                'index'  => 'edit',
                'icon'   => 'icon-edit',
                'title'  => trans('product-category::app.index.datagrid.edit'),
                'method' => 'GET',
                'url'    => fn ($row) => route('admin.products.categories.edit', $row->id),
            ]);
        }

        if (bouncer()->hasPermission('products.product_categories.delete')) {
            $this->addAction([
                'index'  => 'delete',
                'icon'   => 'icon-delete',
                'title'  => trans('product-category::app.index.datagrid.delete'),
                'method' => 'DELETE',
                'url'    => fn ($row) => route('admin.products.categories.delete', $row->id),
            ]);
        }
    }
}

