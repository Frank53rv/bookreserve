<?php

namespace App\Http\Controllers;

use App\Models\ReturnDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReturnDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $details = ReturnDetail::query()
            ->with(['returnHeader.client', 'book'])
            ->orderByDesc('created_at')
            ->get();

        return response()->json($details);
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
            'id_devolucion' => ['required', 'exists:return_headers,id_devolucion'],
            'id_libro' => [
                'required',
                'exists:books,id_libro',
                Rule::unique('return_details', 'id_libro')->where(
                    fn ($query) => $query->where('id_devolucion', $request->input('id_devolucion'))
                ),
            ],
            'cantidad_devuelta' => ['required', 'integer', 'min:1'],
        ]);

        $detail = ReturnDetail::create($data);

        return response()->json($detail->load(['returnHeader.client', 'book']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ReturnDetail $returnDetail): JsonResponse
    {
        return response()->json($returnDetail->load(['returnHeader.client', 'book']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReturnDetail $returnDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReturnDetail $returnDetail): JsonResponse
    {
        $data = $request->validate([
            'id_devolucion' => ['sometimes', 'required', 'exists:return_headers,id_devolucion'],
            'id_libro' => ['sometimes', 'required', 'exists:books,id_libro'],
            'cantidad_devuelta' => ['sometimes', 'required', 'integer', 'min:1'],
        ]);

        if (isset($data['id_devolucion']) || isset($data['id_libro'])) {
            $returnId = $data['id_devolucion'] ?? $returnDetail->id_devolucion;
            $bookId = $data['id_libro'] ?? $returnDetail->id_libro;

            $request->validate([
                'id_libro' => [
                    Rule::unique('return_details', 'id_libro')
                        ->ignore($returnDetail->getKey(), $returnDetail->getKeyName())
                        ->where(fn ($query) => $query->where('id_devolucion', $returnId)),
                ],
            ]);

            $data['id_devolucion'] = $returnId;
            $data['id_libro'] = $bookId;
        }

        $returnDetail->update($data);

        return response()->json($returnDetail->load(['returnHeader.client', 'book']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReturnDetail $returnDetail): JsonResponse
    {
        $returnDetail->delete();

        return response()->json(null, 204);
    }
}
