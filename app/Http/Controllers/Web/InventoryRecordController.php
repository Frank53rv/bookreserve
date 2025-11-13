<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Client;
use App\Models\InventoryRecord;
use App\Services\MovementRecorder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryRecordController extends Controller
{
    public function index(): View
    {
        $inventoryRecords = InventoryRecord::with('book')
            ->latest('fecha_ingreso')
            ->paginate(10);

        return view('inventory-records.index', compact('inventoryRecords'));
    }

    public function create(): View
    {
        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');

        return view('inventory-records.create', compact('books', 'clients'));
    }

    public function store(Request $request, MovementRecorder $movementRecorder): RedirectResponse
    {
        $data = $request->validate([
            'id_libro' => ['required', 'exists:books,id_libro'],
            'fecha_ingreso' => ['required', 'date'],
            'cantidad_ingresada' => ['required', 'integer', 'min:1'],
            'proveedor' => ['nullable', 'string', 'max:100'],
            'observacion' => ['nullable', 'string', 'max:255'],
            'movement_client_id' => ['required', 'exists:clients,id_cliente'],
        ]);

        $record = InventoryRecord::create(collect($data)->except('movement_client_id')->all());

        $movementRecorder->recordInventoryMovement([
            'id_cliente' => $data['movement_client_id'],
            'id_libro' => $record->id_libro,
            'tipo_movimiento' => 'Entrada',
            'fecha_movimiento' => $record->fecha_ingreso,
            'cantidad' => $record->cantidad_ingresada,
            'observacion' => $data['observacion'] ?? null,
            'metadata' => [
                'origen' => 'inventory_record',
                'id_inventario' => $record->id_inventario,
                'proveedor' => $record->proveedor,
            ],
        ], sprintf('Ingreso de inventario #%d', $record->id_inventario));

        return redirect()->route('web.inventory-records.index')->with('status', 'Ingreso de inventario registrado correctamente.');
    }

    public function show(InventoryRecord $inventoryRecord): View
    {
        $inventoryRecord->load('book');

        return view('inventory-records.show', compact('inventoryRecord'));
    }

    public function edit(InventoryRecord $inventoryRecord): View
    {
        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');

        return view('inventory-records.edit', compact('inventoryRecord', 'books', 'clients'));
    }

    public function update(Request $request, InventoryRecord $inventoryRecord): RedirectResponse
    {
        $data = $request->validate([
            'id_libro' => ['required', 'exists:books,id_libro'],
            'fecha_ingreso' => ['required', 'date'],
            'cantidad_ingresada' => ['required', 'integer', 'min:1'],
            'proveedor' => ['nullable', 'string', 'max:100'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ]);

        $inventoryRecord->update($data);

        return redirect()->route('web.inventory-records.index')->with('status', 'Ingreso de inventario actualizado correctamente.');
    }

    public function destroy(InventoryRecord $inventoryRecord): RedirectResponse
    {
        $inventoryRecord->delete();

        return redirect()->route('web.inventory-records.index')->with('status', 'Ingreso de inventario eliminado correctamente.');
    }
}
