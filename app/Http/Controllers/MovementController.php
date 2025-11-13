<?php

namespace App\Http\Controllers;

use App\Models\Movement;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MovementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $movements = Movement::query()
            ->with(['client', 'book'])
            ->orderByDesc('fecha_movimiento')
            ->get();

        return response()->json($movements);
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
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'id_libro' => ['required', 'exists:books,id_libro'],
            'tipo_movimiento' => ['required', Rule::in(['Entrada', 'Salida', 'Devolucion'])],
            'fecha_movimiento' => ['required', 'date'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ]);

        $movement = Movement::create($data);

        return response()->json($movement->load(['client', 'book']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Movement $movement): JsonResponse
    {
        return response()->json($movement->load(['client', 'book']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Movement $movement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Movement $movement): JsonResponse
    {
        $data = $request->validate([
            'id_cliente' => ['sometimes', 'required', 'exists:clients,id_cliente'],
            'id_libro' => ['sometimes', 'required', 'exists:books,id_libro'],
            'tipo_movimiento' => ['sometimes', 'required', Rule::in(['Entrada', 'Salida', 'Devolucion'])],
            'fecha_movimiento' => ['sometimes', 'required', 'date'],
            'cantidad' => ['sometimes', 'required', 'integer', 'min:1'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ]);

        $movement->update($data);

        return response()->json($movement->load(['client', 'book']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Movement $movement): JsonResponse
    {
        $movement->delete();

        return response()->json(null, 204);
    }
}
