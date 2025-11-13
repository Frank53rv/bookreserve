<?php

namespace App\Http\Controllers;

use App\Models\InventoryRecord;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InventoryRecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $records = InventoryRecord::query()
            ->with('book')
            ->orderByDesc('fecha_ingreso')
            ->get();

        return response()->json($records);
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
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id_libro' => ['required', 'exists:books,id_libro'],
            'fecha_ingreso' => ['required', 'date'],
            'cantidad_ingresada' => ['required', 'integer', 'min:1'],
            'proveedor' => ['nullable', 'string', 'max:100'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ]);

        $record = InventoryRecord::create($data);

        return response()->json($record->load('book'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(InventoryRecord $inventoryRecord): JsonResponse
    {
        return response()->json($inventoryRecord->load('book'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(InventoryRecord $inventoryRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, InventoryRecord $inventoryRecord): JsonResponse
    {
        $data = $request->validate([
            'id_libro' => ['sometimes', 'required', 'exists:books,id_libro'],
            'fecha_ingreso' => ['sometimes', 'required', 'date'],
            'cantidad_ingresada' => ['sometimes', 'required', 'integer', 'min:1'],
            'proveedor' => ['nullable', 'string', 'max:100'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ]);

        $inventoryRecord->update($data);

        return response()->json($inventoryRecord->load('book'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(InventoryRecord $inventoryRecord): JsonResponse
    {
        $inventoryRecord->delete();

        return response()->json(null, 204);
    }
}
