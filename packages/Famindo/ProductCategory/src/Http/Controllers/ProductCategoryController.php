<?php

namespace Famindo\ProductCategory\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Webkul\Admin\Http\Controllers\Controller;
use Famindo\ProductCategory\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ProductCategory::orderBy('name')->paginate(20);

        return view('product-category::index', compact('categories'));
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

        session()->flash('success', 'Category created successfully');

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

        session()->flash('success', 'Category updated successfully');

        return redirect()->route('admin.products.categories.index');
    }

    public function destroy(int $id)
    {
        $category = ProductCategory::findOrFail($id);

        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}

