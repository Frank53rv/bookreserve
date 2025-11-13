<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Client;
use App\Models\ReservationHeader;
use App\Models\ReturnHeader;
use App\Services\MovementRecorder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReturnController extends Controller
{
    public function index(): View
    {
        $returns = ReturnHeader::with([
                'client',
                'details.book',
                'reservation.client',
                'reservation.details.book',
            ])
            ->latest('fecha_devolucion')
            ->paginate(10);

        return view('returns.index', compact('returns'));
    }

    public function create(): View
    {
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');
        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');
        $reservations = ReservationHeader::with('client')
            ->latest('fecha_reserva')
            ->get()
            ->mapWithKeys(fn ($reservation) => [
                $reservation->id_reserva => sprintf('Reserva #%d - %s %s (%s)',
                    $reservation->id_reserva,
                    $reservation->client?->nombre,
                    $reservation->client?->apellido,
                    optional($reservation->fecha_reserva)->format('d/m/Y H:i')
                ),
            ]);

        return view('returns.create', compact('clients', 'books', 'reservations'));
    }

    public function store(Request $request, MovementRecorder $movementRecorder): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'id_reserva' => ['required', 'exists:reservation_headers,id_reserva'],
            'fecha_devolucion' => ['required', 'date'],
            'estado' => ['required', 'in:Completa,Parcial'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_libro' => ['required', 'exists:books,id_libro'],
            'items.*.cantidad_devuelta' => ['required', 'integer', 'min:1'],
        ]);

        $return = DB::transaction(function () use ($data, $movementRecorder) {
            $reservation = ReservationHeader::with(['details'])->findOrFail($data['id_reserva']);

            if ((int) $reservation->id_cliente !== (int) $data['id_cliente']) {
                throw ValidationException::withMessages([
                    'id_cliente' => 'El cliente seleccionado debe coincidir con el de la reserva vinculada.',
                ]);
            }

            $return = ReturnHeader::create(collect($data)->except('items')->all());
            $return->load('client');

            $items = collect($data['items'])->map(fn ($item) => [
                'id_libro' => (int) $item['id_libro'],
                'cantidad_devuelta' => (int) $item['cantidad_devuelta'],
            ]);

            if ($items->pluck('id_libro')->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'No se puede repetir el mismo libro en la devolución.',
                ]);
            }

            $items->each(function (array $item) use ($return, $reservation, $movementRecorder) {
                $reservationDetail = $reservation->details->firstWhere('id_libro', (int) $item['id_libro']);

                if (! $reservationDetail) {
                    throw ValidationException::withMessages([
                        'items' => 'El libro seleccionado no forma parte de la reserva vinculada.',
                    ]);
                }

                if ((int) $item['cantidad_devuelta'] > $reservationDetail->cantidad) {
                    throw ValidationException::withMessages([
                        'items' => 'La cantidad devuelta supera la cantidad reservada.',
                    ]);
                }

                $detail = $return->details()->create([
                    'id_libro' => $item['id_libro'],
                    'cantidad_devuelta' => $item['cantidad_devuelta'],
                    'id_detalle_reserva' => $reservationDetail->id_detalle_reserva,
                ])->load('book');

                $movementRecorder->recordReturnMovement($return, $detail);
            });

            if ($return->estado === 'Completa') {
                $reservation->update(['estado' => 'Retirado']);
            }

            return $return;
        });

        return redirect()->route('web.returns.show', $return)->with('status', 'Devolución creada correctamente.');
    }

    public function show(ReturnHeader $return): View
    {
        $return->load([
            'client',
            'details.book',
            'reservation.client',
            'reservation.details.book',
        ]);

        return view('returns.show', compact('return'));
    }

    public function edit(ReturnHeader $return): View
    {
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');
        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');
        $reservations = ReservationHeader::with('client')
            ->latest('fecha_reserva')
            ->get()
            ->mapWithKeys(fn ($reservation) => [
                $reservation->id_reserva => sprintf('Reserva #%d - %s %s (%s)',
                    $reservation->id_reserva,
                    $reservation->client?->nombre,
                    $reservation->client?->apellido,
                    optional($reservation->fecha_reserva)->format('d/m/Y H:i')
                ),
            ]);

        $return->load(['details']);

        return view('returns.edit', compact('return', 'clients', 'books', 'reservations'));
    }

    public function update(Request $request, ReturnHeader $return): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'id_reserva' => ['required', 'exists:reservation_headers,id_reserva'],
            'fecha_devolucion' => ['required', 'date'],
            'estado' => ['required', 'in:Completa,Parcial'],
            'items' => ['nullable', 'array'],
            'items.*.id_libro' => ['required_with:items', 'exists:books,id_libro'],
            'items.*.cantidad_devuelta' => ['required_with:items', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($return, $data) {
            $reservation = ReservationHeader::with('details')->findOrFail($data['id_reserva']);

            if ((int) $reservation->id_cliente !== (int) $data['id_cliente']) {
                throw ValidationException::withMessages([
                    'id_cliente' => 'El cliente seleccionado debe coincidir con el de la reserva vinculada.',
                ]);
            }

            $return->update(collect($data)->except('items')->all());

            if (! empty($data['items'])) {
                $items = collect($data['items'])->map(fn ($item) => [
                    'id_libro' => (int) $item['id_libro'],
                    'cantidad_devuelta' => (int) $item['cantidad_devuelta'],
                ]);

                if ($items->pluck('id_libro')->duplicates()->isNotEmpty()) {
                    throw ValidationException::withMessages([
                        'items' => 'No se puede repetir el mismo libro en la devolución.',
                    ]);
                }

                $return->details()->delete();
                $return->details()->createMany($items->map(function (array $item) use ($reservation) {
                    $reservationDetail = $reservation->details->firstWhere('id_libro', $item['id_libro']);

                    return [
                        'id_libro' => $item['id_libro'],
                        'cantidad_devuelta' => $item['cantidad_devuelta'],
                        'id_detalle_reserva' => $reservationDetail?->id_detalle_reserva,
                    ];
                })->all());
            }
        });

        return redirect()->route('web.returns.show', $return)->with('status', 'Devolución actualizada correctamente.');
    }

    public function destroy(ReturnHeader $return): RedirectResponse
    {
        $return->delete();

        return redirect()->route('web.returns.index')->with('status', 'Devolución eliminada correctamente.');
    }
}
