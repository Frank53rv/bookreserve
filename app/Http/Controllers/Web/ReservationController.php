<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ReservationHeader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index(): View
    {
        $reservations = ReservationHeader::with('client')
            ->latest('fecha_reserva')
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create(): View
    {
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');

        return view('reservations.create', compact('clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'fecha_reserva' => ['required', 'date'],
            'estado' => ['required', 'in:Pendiente,Retirado,Cancelado'],
        ]);

        $reservation = ReservationHeader::create($data);

        return redirect()->route('web.reservations.show', $reservation)->with('status', 'Reserva creada correctamente.');
    }

    public function show(ReservationHeader $reservation): View
    {
        $reservation->load(['client', 'details.book']);

        return view('reservations.show', compact('reservation'));
    }

    public function edit(ReservationHeader $reservation): View
    {
        $reservation->load('client');
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');

        return view('reservations.edit', compact('reservation', 'clients'));
    }

    public function update(Request $request, ReservationHeader $reservation): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'fecha_reserva' => ['required', 'date'],
            'estado' => ['required', 'in:Pendiente,Retirado,Cancelado'],
        ]);

        $reservation->update($data);

        return redirect()->route('web.reservations.show', $reservation)->with('status', 'Reserva actualizada correctamente.');
    }

    public function destroy(ReservationHeader $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()->route('web.reservations.index')->with('status', 'Reserva eliminada correctamente.');
    }
}
