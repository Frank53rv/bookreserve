<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Client;
use App\Models\ReservationHeader;
use App\Models\ReturnHeader;
use App\Models\Movement;
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
            $reservation = ReservationHeader::with(['details.book', 'details.returnDetails'])->findOrFail($data['id_reserva']);

            if ((int) $reservation->id_cliente !== (int) $data['id_cliente']) {
                throw ValidationException::withMessages([
                    'id_cliente' => 'El cliente seleccionado debe coincidir con el de la reserva vinculada.',
                ]);
            }

            $items = collect($data['items'])->map(fn ($item) => [
                'id_libro' => (int) $item['id_libro'],
                'cantidad_devuelta' => (int) $item['cantidad_devuelta'],
            ]);

            if ($items->pluck('id_libro')->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'No se puede repetir el mismo libro en la devolución.',
                ]);
            }

            $bookIds = $items->pluck('id_libro')->unique();
            $books = Book::whereIn('id_libro', $bookIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id_libro');

            $detailsByBook = $reservation->details->keyBy('id_libro');

            foreach ($items as $item) {
                $reservationDetail = $detailsByBook->get($item['id_libro']);

                if (! $reservationDetail) {
                    throw ValidationException::withMessages([
                        'items' => 'El libro seleccionado no forma parte de la reserva vinculada.',
                    ]);
                }

                $remaining = $reservationDetail->remainingQuantity();

                if ($item['cantidad_devuelta'] > $remaining) {
                    throw ValidationException::withMessages([
                        'items' => sprintf('La devolución de "%s" supera lo pendiente. Restante: %d.', $reservationDetail->book?->titulo, $remaining),
                    ]);
                }

                if (! $books->has($item['id_libro'])) {
                    throw ValidationException::withMessages([
                        'items' => 'El libro seleccionado no existe.',
                    ]);
                }
            }

            $payload = collect($data)->except('items');
            $payload['estado'] = $payload['estado'] ?? 'Parcial';

            $return = ReturnHeader::create($payload->all());
            $return->load('client');

            foreach ($items as $item) {
                /** @var \App\Models\ReservationDetail $reservationDetail */
                $reservationDetail = $detailsByBook->get($item['id_libro']);
                $detail = $return->details()->create([
                    'id_libro' => $item['id_libro'],
                    'cantidad_devuelta' => $item['cantidad_devuelta'],
                    'id_detalle_reserva' => $reservationDetail?->id_detalle_reserva,
                ])->load('book');

                $books->get($item['id_libro'])?->increment('stock_actual', $item['cantidad_devuelta']);
                $movementRecorder->recordReturnMovement($return, $detail);
            }

            $reservation->refreshLoanState();

            $return->update(['estado' => $reservation->estado === 'Completado' ? 'Completa' : 'Parcial']);

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

    public function update(Request $request, ReturnHeader $return, MovementRecorder $movementRecorder): RedirectResponse
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

        DB::transaction(function () use ($return, $data, $movementRecorder) {
            $reservation = ReservationHeader::with(['details.book', 'details.returnDetails'])->findOrFail($data['id_reserva']);

            if ((int) $reservation->id_cliente !== (int) $data['id_cliente']) {
                throw ValidationException::withMessages([
                    'id_cliente' => 'El cliente seleccionado debe coincidir con el de la reserva vinculada.',
                ]);
            }

            $items = collect($data['items'])->map(fn ($item) => [
                'id_libro' => (int) $item['id_libro'],
                'cantidad_devuelta' => (int) $item['cantidad_devuelta'],
            ]);

            if ($items->pluck('id_libro')->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'No se puede repetir el mismo libro en la devolución.',
                ]);
            }

            $return->loadMissing('details.book');

            $bookIds = $items->pluck('id_libro')
                ->merge($return->details->pluck('id_libro'))
                ->unique();

            $books = Book::whereIn('id_libro', $bookIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id_libro');

            foreach ($return->details as $detail) {
                $book = $books->get($detail->id_libro);

                if (! $book) {
                    throw ValidationException::withMessages([
                        'items' => 'El libro asociado a la devolución ya no existe.',
                    ]);
                }

                if ($book->stock_actual < $detail->cantidad_devuelta) {
                    throw ValidationException::withMessages([
                        'items' => sprintf('No hay stock disponible para revertir la devolución de "%s". Disponible: %d.', $book->titulo, $book->stock_actual),
                    ]);
                }

                $book->decrement('stock_actual', $detail->cantidad_devuelta);
            }

            Movement::where('id_devolucion', $return->id_devolucion)->delete();
            $return->details()->delete();

            $return->update(collect($data)->except('items')->all());

            $reservation->load('details.returnDetails');
            $detailsByBook = $reservation->details->keyBy('id_libro');

            foreach ($items as $item) {
                $reservationDetail = $detailsByBook->get($item['id_libro']);

                if (! $reservationDetail) {
                    throw ValidationException::withMessages([
                        'items' => 'El libro seleccionado no forma parte de la reserva vinculada.',
                    ]);
                }

                $remaining = $reservationDetail->remainingQuantity();

                if ($item['cantidad_devuelta'] > $remaining) {
                    throw ValidationException::withMessages([
                        'items' => sprintf('La devolución de "%s" supera lo pendiente. Restante: %d.', $reservationDetail->book?->titulo, $remaining),
                    ]);
                }

                if (! $books->has($item['id_libro'])) {
                    throw ValidationException::withMessages([
                        'items' => 'El libro seleccionado no existe.',
                    ]);
                }

                $detail = $return->details()->create([
                    'id_libro' => $item['id_libro'],
                    'cantidad_devuelta' => $item['cantidad_devuelta'],
                    'id_detalle_reserva' => $reservationDetail->id_detalle_reserva,
                ])->load('book');

                $books->get($item['id_libro'])?->increment('stock_actual', $item['cantidad_devuelta']);
                $movementRecorder->recordReturnMovement($return, $detail);
            }

            $reservation->refreshLoanState();
            $return->update(['estado' => $reservation->estado === 'Completado' ? 'Completa' : 'Parcial']);
        });

        return redirect()->route('web.returns.show', $return)->with('status', 'Devolución actualizada correctamente.');
    }

    public function destroy(ReturnHeader $return): RedirectResponse
    {
        DB::transaction(function () use ($return) {
            $return->load(['details.book', 'reservation.details.returnDetails']);

            $bookIds = $return->details->pluck('id_libro')->unique();
            $books = Book::whereIn('id_libro', $bookIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id_libro');

            foreach ($return->details as $detail) {
                $book = $books->get($detail->id_libro);

                if (! $book) {
                    throw ValidationException::withMessages([
                        'items' => 'El libro asociado a la devolución ya no existe.',
                    ]);
                }

                if ($book->stock_actual < $detail->cantidad_devuelta) {
                    throw ValidationException::withMessages([
                        'items' => sprintf('No hay stock disponible para revertir la devolución de "%s". Disponible: %d.', $book->titulo, $book->stock_actual),
                    ]);
                }

                $book->decrement('stock_actual', $detail->cantidad_devuelta);
            }

            $reservation = $return->reservation;

            Movement::where('id_devolucion', $return->id_devolucion)->delete();
            $return->delete();

            if ($reservation) {
                $reservation->refreshLoanState();
            }
        });

        return redirect()->route('web.returns.index')->with('status', 'Devolución eliminada correctamente.');
    }
}
