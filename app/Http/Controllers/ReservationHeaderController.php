<?php

namespace App\Http\Controllers;

use App\Models\ReservationHeader;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservationHeaderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $reservations = ReservationHeader::query()
            ->with(['client', 'details.book'])
            ->orderByDesc('fecha_reserva')
            ->get();

        return response()->json($reservations);
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
            'fecha_reserva' => ['required', 'date'],
            'estado' => ['required', Rule::in(ReservationHeader::STATES)],
        ]);

        $reservation = ReservationHeader::create($data);

        return response()->json($reservation->load(['client', 'details']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ReservationHeader $reservation): JsonResponse
    {
        return response()->json($reservation->load(['client', 'details.book']));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReservationHeader $reservationHeader)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReservationHeader $reservation): JsonResponse
    {
        $data = $request->validate([
            'id_cliente' => ['sometimes', 'required', 'exists:clients,id_cliente'],
            'fecha_reserva' => ['sometimes', 'required', 'date'],
            'estado' => ['sometimes', 'required', Rule::in(ReservationHeader::STATES)],
        ]);

        $reservation->update($data);

        return response()->json($reservation->load(['client', 'details.book']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReservationHeader $reservation): JsonResponse
    {
        $reservation->delete();

        return response()->json(null, 204);
    }
}
