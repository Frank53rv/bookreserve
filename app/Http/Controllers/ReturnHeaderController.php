<?php

namespace App\Http\Controllers;

use App\Models\ReturnHeader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReturnHeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $returns = ReturnHeader::query()
            ->with(['client', 'details.book'])
            ->orderByDesc('fecha_devolucion')
            ->get();

        return response()->json($returns);
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
            'fecha_devolucion' => ['required', 'date'],
            'estado' => ['required', Rule::in(['Completa', 'Parcial'])],
        ]);

        $returnHeader = ReturnHeader::create($data);

        return response()->json($returnHeader->load(['client', 'details']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ReturnHeader $return): JsonResponse
    {
        return response()->json($return->load(['client', 'details.book']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReturnHeader $returnHeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReturnHeader $return): JsonResponse
    {
        $data = $request->validate([
            'id_cliente' => ['sometimes', 'required', 'exists:clients,id_cliente'],
            'fecha_devolucion' => ['sometimes', 'required', 'date'],
            'estado' => ['sometimes', 'required', Rule::in(['Completa', 'Parcial'])],
        ]);

        $return->update($data);

        return response()->json($return->load(['client', 'details.book']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReturnHeader $return): JsonResponse
    {
        $return->delete();

        return response()->json(null, 204);
    }
}
