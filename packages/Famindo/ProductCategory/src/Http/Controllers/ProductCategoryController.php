<?php

namespace Famindo\ProductCategory\Http\Controllers;

use Famindo\ProductCategory\DataGrids\ProductCategoryDataGrid;
use Famindo\ProductCategory\Models\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;

class ProductCategoryController extends Controller
{
    public function index(): View|JsonResponse
    {
        if (request()->ajax()) {
            return datagrid(ProductCategoryDataGrid::class)->process();
        }

        return view('product-category::index');
    }

    public function create(): View
    {
        return view('product-category::create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'code'        => 'required|string|max:64|unique:product_categories,code',
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);

        ProductCategory::create($data);

        session()->flash('success', trans('product-category::app.messages.create-success'));

        return redirect()->route('admin.products.categories.index');
    }

    public function edit(int $id): View
    {
        $category = ProductCategory::findOrFail($id);

        return view('product-category::edit', compact('category'));
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $category = ProductCategory::findOrFail($id);

        $data = $request->validate([
            'code'        => 'required|string|max:64|unique:product_categories,code,'.$category->id,
            'name'        => 'required|string|max:150',
            'description' => 'nullable|string',
        ]);

        $category->update($data);

        session()->flash('success', trans('product-category::app.messages.update-success'));

        return redirect()->route('admin.products.categories.index');
    }

    public function destroy(int $id): JsonResponse
    {
        $category = ProductCategory::findOrFail($id);

        $category->delete();

        return new JsonResponse([
            'message' => trans('product-category::app.messages.delete-success'),
        ]);
    }
}

