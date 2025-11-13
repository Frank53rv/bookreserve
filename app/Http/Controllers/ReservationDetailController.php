<?php

namespace App\Http\Controllers;

use App\Models\ReservationDetail;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $details = ReservationDetail::query()
            ->with(['reservation.client', 'book'])
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
            'id_reserva' => ['required', 'exists:reservation_headers,id_reserva'],
            'id_libro' => [
                'required',
                'exists:books,id_libro',
                Rule::unique('reservation_details', 'id_libro')->where(
                    fn ($query) => $query->where('id_reserva', $request->input('id_reserva'))
                ),
            ],
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $detail = ReservationDetail::create($data);

        return response()->json($detail->load(['reservation.client', 'book']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ReservationDetail $reservationDetail): JsonResponse
    {
        return response()->json($reservationDetail->load(['reservation.client', 'book']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReservationDetail $reservationDetail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReservationDetail $reservationDetail): JsonResponse
    {
        $data = $request->validate([
            'id_reserva' => ['sometimes', 'required', 'exists:reservation_headers,id_reserva'],
            'id_libro' => ['sometimes', 'required', 'exists:books,id_libro'],
            'cantidad' => ['sometimes', 'required', 'integer', 'min:1'],
        ]);

        if (isset($data['id_reserva']) || isset($data['id_libro'])) {
            $reservationId = $data['id_reserva'] ?? $reservationDetail->id_reserva;
            $bookId = $data['id_libro'] ?? $reservationDetail->id_libro;

            $request->validate([
                'id_libro' => [
                    Rule::unique('reservation_details', 'id_libro')
                        ->ignore($reservationDetail->getKey(), $reservationDetail->getKeyName())
                        ->where(fn ($query) => $query->where('id_reserva', $reservationId)),
                ],
            ]);

            $data['id_reserva'] = $reservationId;
            $data['id_libro'] = $bookId;
        }

        $reservationDetail->update($data);

        return response()->json($reservationDetail->load(['reservation.client', 'book']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReservationDetail $reservationDetail): JsonResponse
    {
        $reservationDetail->delete();

        return response()->json(null, 204);
    }
}
