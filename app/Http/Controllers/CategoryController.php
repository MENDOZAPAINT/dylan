<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::active()->get();
        return inertia('categories/Index', [
            'categories' => $categories,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        Category::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'order' => $validated['order'] ?? 0,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('categories.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $category->update([
            'name'        => $validated['name'],
            'description' => $validated['description'] ?? null,
            'order'       => $validated['order'] ?? 0,
            'is_active'   => $request->boolean('is_active'),
        ]);

        return redirect()->route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        $category->delete($category->id);

        return redirect()->route('categories.index');
    }
}
