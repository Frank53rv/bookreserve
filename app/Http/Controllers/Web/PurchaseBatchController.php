<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Movement;
use App\Models\PurchaseBatch;
use App\Models\Supplier;
use App\Services\MovementRecorder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PurchaseBatchController extends Controller
{
    public function index(): View
    {
        $batches = PurchaseBatch::with(['supplier'])
            ->withCount('items')
            ->latest('fecha_recepcion')
            ->paginate(10);

        return view('purchase-batches.index', compact('batches'));
    }

    public function create(): View
    {
        $suppliers = Supplier::orderBy('nombre_comercial')->pluck('nombre_comercial', 'id_proveedor');
        $books = Book::orderBy('titulo')->get(['id_libro', 'titulo', 'stock_actual', 'precio_venta']);

        return view('purchase-batches.create', compact('suppliers', 'books'));
    }

    public function store(Request $request, MovementRecorder $movementRecorder): RedirectResponse
    {
        $data = $request->validate([
            'id_proveedor' => ['nullable', 'exists:suppliers,id_proveedor'],
            'codigo_lote' => ['required', 'string', 'max:50', 'unique:purchase_batches,codigo_lote'],
            'fecha_recepcion' => ['required', 'date'],
            'documento_referencia' => ['nullable', 'string', 'max:100'],
            'notas' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.id_libro' => ['required', 'exists:books,id_libro'],
            'items.*.cantidad' => ['required', 'integer', 'min:1'],
            'items.*.costo_unitario' => ['required', 'numeric', 'min:0'],
            'items.*.fecha_vencimiento' => ['nullable', 'date'],
        ]);

        $batch = DB::transaction(function () use ($data, $movementRecorder) {
            $items = collect($data['items'])->map(function ($item) {
                return [
                    'id_libro' => (int) $item['id_libro'],
                    'cantidad' => (int) $item['cantidad'],
                    'costo_unitario' => (float) $item['costo_unitario'],
                    'fecha_vencimiento' => $item['fecha_vencimiento'] ?? null,
                ];
            });

            if ($items->pluck('id_libro')->duplicates()->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'items' => 'No se puede repetir el mismo libro en el lote.',
                ]);
            }

            $bookLocks = Book::whereIn('id_libro', $items->pluck('id_libro'))
                ->lockForUpdate()
                ->get()
                ->keyBy('id_libro');

            $batch = PurchaseBatch::create(collect($data)->except('items')->all());
            $batch->load('supplier');

            foreach ($items as $item) {
                $book = $bookLocks->get($item['id_libro']);

                if (! $book) {
                    throw ValidationException::withMessages([
                        'items' => 'Al menos uno de los libros seleccionados ya no existe.',
                    ]);
                }

                $detail = $batch->items()->create($item)->load('book');
                $book->increment('stock_actual', $item['cantidad']);
                $movementRecorder->recordPurchaseMovement($batch, $detail);
            }

            return $batch;
        });

        return redirect()->route('web.purchase-batches.show', $batch)->with('status', 'Lote registrado correctamente.');
    }

    public function show(PurchaseBatch $purchase_batch): View
    {
        $purchase_batch->load(['supplier', 'items.book']);

        return view('purchase-batches.show', ['batch' => $purchase_batch]);
    }

    public function destroy(PurchaseBatch $purchase_batch): RedirectResponse
    {
        DB::transaction(function () use ($purchase_batch) {
            $purchase_batch->load(['items.book']);

            $bookIds = $purchase_batch->items->pluck('id_libro')->unique();
            $books = Book::whereIn('id_libro', $bookIds)
                ->lockForUpdate()
                ->get()
                ->keyBy('id_libro');

            foreach ($purchase_batch->items as $item) {
                $book = $books->get($item->id_libro);

                if (! $book || $book->stock_actual < $item->cantidad) {
                    throw ValidationException::withMessages([
                        'items' => 'No se puede eliminar el lote porque afectaría el stock actual.',
                    ]);
                }

                $book->decrement('stock_actual', $item->cantidad);
            }

            Movement::where('metadata->lote', $purchase_batch->id_lote)->delete();

            $purchase_batch->delete();
        });

        return redirect()->route('web.purchase-batches.index')->with('status', 'Lote eliminado correctamente.');
    }
}
