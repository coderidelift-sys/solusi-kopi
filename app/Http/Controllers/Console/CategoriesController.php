<?php

namespace App\Http\Controllers\Console;

use App\DataTables\CategoryDataTable;
use App\Http\Controllers\Controller;
use App\Http\Requests\RequestStoreCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CategoriesController extends Controller
{
    public function index(CategoryDataTable $dataTable)
    {
        return $dataTable
            ->render('console.categories.index');
    }

    public function create()
    {
        return view('console.categories.create');
    }

    public function store(RequestStoreCategory $request)
    {
        DB::beginTransaction();
        try {
            $categoryPayload = $request->validated();

            Category::create($categoryPayload);

            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category created successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to create Category.');
        }
    }

    public function edit(Category $category)
    {
        return view('console.categories.edit', compact('category'));
    }

    public function update(RequestStoreCategory $request, Category $category)
    {
        DB::beginTransaction();
        try {
            $categoryPayload = $request->validated();
            $category->update($categoryPayload);

            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to update Category.');
        }
    }

    public function destroy(Category $category)
    {
        DB::beginTransaction();
        try {
            $category->delete();

            DB::commit();
            return redirect()->route('categories.index')->with('success', 'Category deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return back()->with('error', 'Failed to delete Category.');
        }
    }
}
