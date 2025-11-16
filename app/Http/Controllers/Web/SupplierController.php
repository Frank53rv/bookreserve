<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    public function index(): View
    {
        $suppliers = Supplier::orderBy('nombre_comercial')->paginate(12);

        return view('suppliers.index', compact('suppliers'));
    }

    public function create(): View
    {
        return view('suppliers.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre_comercial' => ['required', 'string', 'max:150'],
            'contacto' => ['nullable', 'string', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'correo' => ['nullable', 'email', 'max:150'],
            'identificacion' => ['nullable', 'string', 'max:60'],
            'condiciones_pago' => ['nullable', 'string', 'max:120'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        Supplier::create($data);

        return redirect()->route('web.suppliers.index')->with('status', 'Proveedor creado correctamente.');
    }

    public function show(Supplier $supplier): View
    {
        $supplier->loadCount('batches');

        return view('suppliers.show', compact('supplier'));
    }

    public function edit(Supplier $supplier): View
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier): RedirectResponse
    {
        $data = $request->validate([
            'nombre_comercial' => ['required', 'string', 'max:150'],
            'contacto' => ['nullable', 'string', 'max:150'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'correo' => ['nullable', 'email', 'max:150'],
            'identificacion' => ['nullable', 'string', 'max:60'],
            'condiciones_pago' => ['nullable', 'string', 'max:120'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ]);

        $supplier->update($data);

        return redirect()->route('web.suppliers.index')->with('status', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Supplier $supplier): RedirectResponse
    {
        if ($supplier->batches()->exists()) {
            return back()->withErrors([
                'supplier' => 'No se puede eliminar el proveedor porque tiene lotes registrados.',
            ]);
        }

        $supplier->delete();

        return redirect()->route('web.suppliers.index')->with('status', 'Proveedor eliminado correctamente.');
    }
}
