<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Client;
use App\Models\Movement;
use App\Models\ReservationHeader;
use App\Services\MovementRecorder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ReservationController extends Controller
{
    public function index(): View
    {
        $reservations = ReservationHeader::with(['client', 'details.book'])
            ->latest('fecha_reserva')
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create(): View
    {
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');
        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');

        return view('reservations.create', compact('clients', 'books'));
    }

    public function store(Request $request, MovementRecorder $movementRecorder): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'fecha_reserva' => ['required', 'date'],
            'estado' => ['required', Rule::in(ReservationHeader::STATES)],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_libro' => ['required', 'exists:books,id_libro'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        $reservation = DB::transaction(function () use ($data, $movementRecorder) {
            $items = collect($data['items'])->map(fn ($item) => [
                'id_libro' => (int) $item['id_libro'],
                'cantidad' => (int) $item['cantidad'],
            ]);

            if ($items->pluck('id_libro')->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'No se puede repetir el mismo libro en la reserva.',
                ]);
            }

            $reservation = ReservationHeader::create(collect($data)->except('items')->all());
            $reservation->load('client');

            $items->each(function (array $item) use ($reservation, $movementRecorder) {
                $detail = $reservation->details()->create($item)->load('book');
                $movementRecorder->recordReservationMovement($reservation, $detail);
            });

            return $reservation;
        });

        return redirect()->route('web.reservations.show', $reservation)->with('status', 'Reserva creada correctamente.');
    }

    public function show(ReservationHeader $reservation): View
    {
        $reservation->load(['client', 'details.book']);

        return view('reservations.show', compact('reservation'));
    }

    public function edit(ReservationHeader $reservation): View
    {
        $reservation->load(['client', 'details']);
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');
        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');

        return view('reservations.edit', compact('reservation', 'clients', 'books'));
    }

    public function update(Request $request, ReservationHeader $reservation): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'fecha_reserva' => ['required', 'date'],
            'estado' => ['required', Rule::in(ReservationHeader::STATES)],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_libro' => ['required', 'exists:books,id_libro'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($reservation, $data) {
            $reservation->update(collect($data)->except('items')->all());

            $items = collect($data['items'])->map(fn ($item) => [
                'id_libro' => (int) $item['id_libro'],
                'cantidad' => (int) $item['cantidad'],
            ]);

            if ($items->pluck('id_libro')->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'No se puede repetir el mismo libro en la reserva.',
                ]);
            }

            $reservation->details()->delete();
            $reservation->details()->createMany($items->all());
        });

        return redirect()->route('web.reservations.show', $reservation)->with('status', 'Reserva actualizada correctamente.');
    }

    public function updateStatus(Request $request, ReservationHeader $reservation): RedirectResponse
    {
        $data = $request->validate([
            'estado' => ['required', Rule::in(ReservationHeader::STATES)],
        ]);

        DB::transaction(function () use ($reservation, $data) {
            $reservation->update(['estado' => $data['estado']]);
            $reservation->loadMissing('client');

            $movements = Movement::with('logs')
                ->where('id_reserva', $reservation->id_reserva)
                ->get();

            foreach ($movements as $movement) {
                $metadata = $movement->metadata ?? [];
                $metadata['estado_reserva'] = $data['estado'];

                $movement->update([
                    'metadata' => $metadata,
                    'observacion' => sprintf(
                        'Reserva #%d actualizada a %s',
                        $reservation->id_reserva,
                        $data['estado']
                    ),
                ]);

                $movement->logs()->create([
                    'descripcion' => sprintf('Estado de la reserva actualizado a %s', $data['estado']),
                    'contexto' => array_filter([
                        'reserva' => $reservation->id_reserva,
                        'cliente' => trim(($reservation->client?->nombre . ' ' . $reservation->client?->apellido) ?? ''),
                    ]),
                ]);
            }
        });

        return back()->with('status', 'Estado de la reserva actualizado.');
    }

    public function destroy(ReservationHeader $reservation): RedirectResponse
    {
        $reservation->delete();

        return redirect()->route('web.reservations.index')->with('status', 'Reserva eliminada correctamente.');
    }
}
