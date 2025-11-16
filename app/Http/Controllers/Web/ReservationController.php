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
        $reservations = ReservationHeader::with(['client', 'details.book', 'details.returnDetails'])
            ->latest('fecha_reserva')
            ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create(): View
    {
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');
        $books = Book::orderBy('titulo')
            ->get(['id_libro', 'titulo', 'stock_actual']);

        return view('reservations.create', compact('clients', 'books'));
    }

    public function store(Request $request, MovementRecorder $movementRecorder): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'fecha_reserva' => ['required', 'date'],
            'fecha_estimada_devolucion' => ['nullable', 'date', 'after_or_equal:fecha_reserva'],
            'estado' => ['nullable', Rule::in(ReservationHeader::STATES)],
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

            $books = Book::whereIn('id_libro', $items->pluck('id_libro'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id_libro');

            foreach ($items as $item) {
                $book = $books->get($item['id_libro']);
                if (! $book) {
                    throw ValidationException::withMessages([
                        'items' => 'El libro seleccionado no existe.',
                    ]);
                }

                if ($book->stock_actual < $item['cantidad']) {
                    throw ValidationException::withMessages([
                        'items' => sprintf('"%s" no tiene stock suficiente. Disponible: %d.', $book->titulo, $book->stock_actual),
                    ]);
                }
            }

            $reservation = ReservationHeader::create(
                collect($data)->except('items')
                    ->put('estado', $data['estado'] ?? 'Reservado')
                    ->all()
            );
            $reservation->load('client');

            $items->each(function (array $item) use ($reservation, $movementRecorder, $books) {
                $detail = $reservation->details()->create($item)->load('book');
                $books->get($item['id_libro'])?->decrement('stock_actual', $item['cantidad']);
                $movementRecorder->recordReservationMovement($reservation, $detail);
            });

            $reservation->refreshLoanState();

            return $reservation;
        });

        return redirect()->route('web.reservations.show', $reservation)->with('status', 'Reserva creada correctamente.');
    }

    public function show(ReservationHeader $reservation): View
    {
        $reservation->load(['client', 'details.book', 'details.returnDetails']);

        return view('reservations.show', compact('reservation'));
    }

    public function edit(ReservationHeader $reservation): View
    {
        $reservation->load(['client', 'details']);
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');
        $books = Book::orderBy('titulo')->get(['id_libro', 'titulo', 'stock_actual']);

        return view('reservations.edit', compact('reservation', 'clients', 'books'));
    }

    public function update(Request $request, ReservationHeader $reservation, MovementRecorder $movementRecorder): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'fecha_reserva' => ['required', 'date'],
            'fecha_estimada_devolucion' => ['nullable', 'date', 'after_or_equal:fecha_reserva'],
            'estado' => ['nullable', Rule::in(ReservationHeader::STATES)],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_libro' => ['required', 'exists:books,id_libro'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
        ]);

        DB::transaction(function () use ($reservation, $data, $movementRecorder) {
            $reservation->loadMissing('details.returnDetails', 'client');

            if ($reservation->details->contains(fn ($detail) => $detail->returnDetails->isNotEmpty())) {
                throw ValidationException::withMessages([
                    'items' => 'No se puede modificar una reserva que ya tiene devoluciones registradas.',
                ]);
            }

            $items = collect($data['items'])->map(fn ($item) => [
                'id_libro' => (int) $item['id_libro'],
                'cantidad' => (int) $item['cantidad'],
            ]);

            if ($items->pluck('id_libro')->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'No se puede repetir el mismo libro en la reserva.',
                ]);
            }

            $bookIds = $items->pluck('id_libro')
                ->merge($reservation->details->pluck('id_libro'))
                ->unique();

            $books = Book::whereIn('id_libro', $bookIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id_libro');

            foreach ($reservation->details as $detail) {
                $book = $books->get($detail->id_libro);

                if (! $book) {
                    throw ValidationException::withMessages([
                        'items' => 'El libro asociado a la reserva ya no existe.',
                    ]);
                }

                $book->increment('stock_actual', $detail->cantidad);
            }

            Movement::where('id_reserva', $reservation->id_reserva)
                ->where('tipo_movimiento', 'Salida')
                ->delete();

            $reservation->details()->delete();

            $payload = collect($data)->except('items');
            if (blank($payload->get('estado'))) {
                $payload->forget('estado');
            }

            $reservation->update($payload->all());

            $items->each(function (array $item) use ($reservation, $movementRecorder, $books) {
                $book = $books->get($item['id_libro']);

                if (! $book) {
                    throw ValidationException::withMessages([
                        'items' => 'El libro seleccionado no existe.',
                    ]);
                }

                if ($book->stock_actual < $item['cantidad']) {
                    throw ValidationException::withMessages([
                        'items' => sprintf('"%s" no tiene stock suficiente. Disponible: %d.', $book->titulo, $book->stock_actual),
                    ]);
                }

                $detail = $reservation->details()->create($item)->load('book');
                $book->decrement('stock_actual', $item['cantidad']);
                $movementRecorder->recordReservationMovement($reservation, $detail);
            });

            $reservation->refreshLoanState();
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
        DB::transaction(function () use ($reservation) {
            $reservation->loadMissing('details.returnDetails');

            if ($reservation->details->contains(fn ($detail) => $detail->returnDetails->isNotEmpty())) {
                throw ValidationException::withMessages([
                    'items' => 'No se puede eliminar una reserva que ya tiene devoluciones registradas.',
                ]);
            }

            $bookIds = $reservation->details->pluck('id_libro')->unique();
            $books = Book::whereIn('id_libro', $bookIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id_libro');

            foreach ($reservation->details as $detail) {
                $books->get($detail->id_libro)?->increment('stock_actual', $detail->cantidad);
            }

            Movement::where('id_reserva', $reservation->id_reserva)->delete();

            $reservation->delete();
        });

        return redirect()->route('web.reservations.index')->with('status', 'Reserva eliminada correctamente.');
    }
}
