<?php

namespace App\Http\Controllers\Console;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Outlet;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use App\DataTables\CategoryDataTable;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable->render('console.categories.index');
    }

    public function create()
    {
        $outlets = Outlet::all();
        return view('console.categories.create', compact('outlets'));
    }

    public function store(StoreCategoryRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = handleUpload('image', 'categories/images');
        }

        Category::create($data);

        return redirect()->route('categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        return view('console.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $outlets = Outlet::all();
        return view('console.categories.edit', compact('category', 'outlets'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = handleUpload('image', 'categories/images', $category->image);
        } elseif (isset($data['remove_image']) && $data['remove_image'] == 1) {
            if ($category->image) {
                deleteFileIfExist(public_path($category->image));
                $data['image'] = null;
            }
        }

        $category->update($data);

        return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        if ($category->image) {
            deleteFileIfExist($category->image);
        }
        $category->delete();

        return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
    }
}
