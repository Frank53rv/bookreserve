<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Client;
use App\Models\Movement;
use App\Models\SaleHeader;
use App\Services\MovementRecorder;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SaleController extends Controller
{
    public function index(): View
    {
        $sales = SaleHeader::with(['client'])
            ->withCount('details')
            ->latest('fecha_venta')
            ->paginate(10);

        return view('sales.index', compact('sales'));
    }

    public function create(): View
    {
        $clients = Client::orderBy('nombre')->get()->mapWithKeys(function ($client) {
            return [$client->id_cliente => trim($client->nombre . ' ' . $client->apellido) ?: 'Sin nombre'];
        });
        $books = Book::orderBy('titulo')->get(['id_libro', 'titulo', 'stock_actual', 'precio_venta']);

        return view('sales.create', compact('clients', 'books'));
    }

    public function store(Request $request, MovementRecorder $movementRecorder): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['nullable', 'exists:clients,id_cliente'],
            'fecha_venta' => ['required', 'date'],
            'estado' => ['required', Rule::in(SaleHeader::STATES)],
            'metodo_pago' => ['nullable', 'string', 'max:80'],
            'notas' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_libro' => ['required', 'exists:books,id_libro'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
            'items.*.precio_unitario' => ['nullable', 'numeric', 'min:0'],
        ]);

        $sale = DB::transaction(function () use ($data, $movementRecorder) {
            $items = collect($data['items'])->map(function ($item) {
                return [
                    'id_libro' => (int) $item['id_libro'],
                    'cantidad' => (int) $item['cantidad'],
                    'precio_unitario' => isset($item['precio_unitario']) ? (float) $item['precio_unitario'] : null,
                ];
            });

            if ($items->pluck('id_libro')->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'No se puede repetir el mismo libro en la venta.',
                ]);
            }

            $bookLocks = Book::whereIn('id_libro', $items->pluck('id_libro'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id_libro');

            $detailsPayload = [];
            $total = 0;

            foreach ($items as $item) {
                $book = $bookLocks->get($item['id_libro']);

                if (! $book) {
                    throw ValidationException::withMessages([
                        'items' => 'Al menos uno de los libros seleccionados ya no existe.',
                    ]);
                }

                if ($book->stock_actual < $item['cantidad']) {
                    throw ValidationException::withMessages([
                        'items' => sprintf('"%s" no cuenta con stock suficiente. Disponible: %d.', $book->titulo, $book->stock_actual),
                    ]);
                }

                $unitPrice = $item['precio_unitario'] ?? (float) $book->precio_venta;
                $subtotal = $unitPrice * $item['cantidad'];

                $detailsPayload[] = [
                    'id_libro' => $item['id_libro'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $unitPrice,
                    'subtotal' => $subtotal,
                ];

                $total += $subtotal;
            }

            $sale = SaleHeader::create(array_merge(
                collect($data)->except('items')->all(),
                ['total' => $total]
            ));

            $sale->load('client');

            foreach ($detailsPayload as $detailPayload) {
                $book = $bookLocks->get($detailPayload['id_libro']);
                $detail = $sale->details()->create($detailPayload)->load('book');
                $book->decrement('stock_actual', $detailPayload['cantidad']);
                $movementRecorder->recordSaleMovement($sale, $detail);
            }

            return $sale;
        });

        return redirect()->route('web.sales.show', $sale)->with('status', 'Venta registrada correctamente.');
    }

    public function show(SaleHeader $sale): View
    {
        $sale->load(['client', 'details.book']);

        return view('sales.show', compact('sale'));
    }

    public function destroy(SaleHeader $sale): RedirectResponse
    {
        DB::transaction(function () use ($sale) {
            $sale->load(['details.book']);

            $bookIds = $sale->details->pluck('id_libro')->unique();
            $books = Book::whereIn('id_libro', $bookIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id_libro');

            foreach ($sale->details as $detail) {
                $books->get($detail->id_libro)?->increment('stock_actual', $detail->cantidad);
            }

            Movement::where('metadata->venta', $sale->id_venta)->delete();

            $sale->delete();
        });

        return redirect()->route('web.sales.index')->with('status', 'Venta eliminada correctamente.');
    }

    public function ticket(SaleHeader $sale): BinaryFileResponse|Response
    {
        $sale->load(['client', 'details.book']);

        $pdf = Pdf::loadView('sales.ticket', [
            'sale' => $sale,
            'company' => [
                'name' => config('app.name', 'BookReserve'),
                'issued_at' => now(),
                'address' => config('app.company_address'),
                'phone' => config('app.company_phone'),
            ],
        ])->setPaper('a5', 'portrait');

        $filename = sprintf('ticket_venta_%s.pdf', str_pad((string) $sale->id_venta, 4, '0', STR_PAD_LEFT));

        return $pdf->download($filename);
    }
}
