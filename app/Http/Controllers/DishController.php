<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dish\CreateRequest;
use App\Models\Category;
use App\Models\Dish;
use App\Services\DishService;

class DishController extends Controller
{
    public function __construct(protected DishService $dishService) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('dishes/Index', [
            'dishes' => Dish::with('category')->orderBy('order')->get(),
            'categories' => Category::orderBy('order', 'asc')->get(['id', 'name']),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateRequest $request)
    {
        $this->dishService->create($request->validated());

        return redirect()->route('dishes.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreateRequest $request, Dish $dish)
    {
        $this->dishService->update($dish, $request->validated());

        return redirect()->route('dishes.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dish $dish)
    {
        $this->dishService->delete($dish);

        return redirect()->route('dishes.index');
    }
}
