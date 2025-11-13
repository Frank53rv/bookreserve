<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\ReservationDetail;
use App\Models\ReservationHeader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReservationDetailController extends Controller
{
    public function create(Request $request): View
    {
        $reservations = ReservationHeader::with('client')
            ->orderByDesc('fecha_reserva')
            ->get()
            ->mapWithKeys(function ($reservation) {
                $client = optional($reservation->client);
                $label = sprintf('%s (%s)', $reservation->id_reserva, trim(($client->nombre ?? '') . ' ' . ($client->apellido ?? '')) ?: 'Sin cliente');

                return [$reservation->getKey() => $label];
            });

        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');
        $reservationId = $request->query('reservation');

        return view('reservation-details.create', compact('reservations', 'books', 'reservationId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_reserva' => ['required', 'exists:reservation_headers,id_reserva'],
            'id_libro' => ['required', 'exists:books,id_libro'],
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $detail = ReservationDetail::create($data);

        return redirect()->route('web.reservations.show', $detail->reservation)->with('status', 'Detalle de reserva creado correctamente.');
    }

    public function edit(ReservationDetail $reservationDetail): View
    {
        $reservationDetail->load('reservation.client', 'book');
        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');
        $reservations = ReservationHeader::with('client')
            ->orderByDesc('fecha_reserva')
            ->get()
            ->mapWithKeys(function ($reservation) {
                $client = optional($reservation->client);
                $label = sprintf('%s (%s)', $reservation->id_reserva, trim(($client->nombre ?? '') . ' ' . ($client->apellido ?? '')) ?: 'Sin cliente');

                return [$reservation->getKey() => $label];
            });

        return view('reservation-details.edit', compact('reservationDetail', 'books', 'reservations'));
    }

    public function update(Request $request, ReservationDetail $reservationDetail): RedirectResponse
    {
        $data = $request->validate([
            'id_libro' => ['required', 'exists:books,id_libro'],
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $reservationDetail->update($data);

        return redirect()->route('web.reservations.show', $reservationDetail->reservation)->with('status', 'Detalle de reserva actualizado correctamente.');
    }

    public function destroy(ReservationDetail $reservationDetail): RedirectResponse
    {
        $reservation = $reservationDetail->reservation;
        $reservationDetail->delete();

        return redirect()->route('web.reservations.show', $reservation)->with('status', 'Detalle de reserva eliminado correctamente.');
    }
}
