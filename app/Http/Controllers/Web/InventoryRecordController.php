<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\InventoryRecord;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

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

        return view('inventory-records.create', compact('books'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_libro' => ['required', 'exists:books,id_libro'],
            'fecha_ingreso' => ['required', 'date'],
            'cantidad_ingresada' => ['required', 'integer', 'min:1'],
            'proveedor' => ['nullable', 'string', 'max:100'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ]);

        InventoryRecord::create($data);

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

        return view('inventory-records.edit', compact('inventoryRecord', 'books'));
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
