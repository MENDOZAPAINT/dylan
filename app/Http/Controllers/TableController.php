<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('tables/Index', [
            'tables' => Table::with('mozo:id,name')
                ->orderBy('number')
                ->get(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $this->validateTable($request);

        Table::create([
            'number' => $validated['number'],
            'zone' => $validated['zone'] ?? null,
            'capacity' => $validated['capacity'] ?? 4,
            'status' => $validated['status'] ?? 'free',
            'mozo_id' => $validated['mozo_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('tables.index');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Table $table)
    {
        $validated = $this->validateTable($request, $table);

        $table->update([
            'number' => $validated['number'],
            'zone' => $validated['zone'] ?? null,
            'capacity' => $validated['capacity'] ?? 4,
            'status' => $validated['status'] ?? 'free',
            'mozo_id' => $validated['mozo_id'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        return redirect()->route('tables.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        $table->delete();

        return redirect()->route('tables.index');
    }

    private function validateTable(Request $request, ?Table $table = null): array
    {
        return $request->validate([
            'number' => [
                'required',
                'integer',
                'min:1',
                Rule::unique('tables', 'number')->ignore($table),
            ],
            'zone' => ['nullable', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'status' => ['nullable', Rule::in(['free', 'occupied'])],
            'mozo_id' => ['nullable', Rule::exists('users', 'id')],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
