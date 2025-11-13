<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ReturnHeader;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReturnController extends Controller
{
    public function index(): View
    {
        $returns = ReturnHeader::with('client')
            ->latest('fecha_devolucion')
            ->paginate(10);

        return view('returns.index', compact('returns'));
    }

    public function create(): View
    {
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');

        return view('returns.create', compact('clients'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'fecha_devolucion' => ['required', 'date'],
            'estado' => ['required', 'in:Completa,Parcial'],
        ]);

        $return = ReturnHeader::create($data);

        return redirect()->route('web.returns.show', $return)->with('status', 'Devolución creada correctamente.');
    }

    public function show(ReturnHeader $return): View
    {
        $return->load(['client', 'details.book']);

        return view('returns.show', compact('return'));
    }

    public function edit(ReturnHeader $return): View
    {
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');

        return view('returns.edit', compact('return', 'clients'));
    }

    public function update(Request $request, ReturnHeader $return): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'fecha_devolucion' => ['required', 'date'],
            'estado' => ['required', 'in:Completa,Parcial'],
        ]);

        $return->update($data);

        return redirect()->route('web.returns.show', $return)->with('status', 'Devolución actualizada correctamente.');
    }

    public function destroy(ReturnHeader $return): RedirectResponse
    {
        $return->delete();

        return redirect()->route('web.returns.index')->with('status', 'Devolución eliminada correctamente.');
    }
}
