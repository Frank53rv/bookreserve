<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Client;
use App\Models\Movement;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class MovementController extends Controller
{
    public function index(): View
    {
        $movements = Movement::with(['client', 'book'])
            ->latest('fecha_movimiento')
            ->paginate(10);

        return view('movements.index', compact('movements'));
    }

    public function create(): View
    {
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');
        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');
        $movementTypes = ['Entrada' => 'Entrada', 'Salida' => 'Salida', 'Devolucion' => 'Devolución'];

        return view('movements.create', compact('clients', 'books', 'movementTypes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'id_libro' => ['required', 'exists:books,id_libro'],
            'tipo_movimiento' => ['required', 'in:Entrada,Salida,Devolucion'],
            'fecha_movimiento' => ['required', 'date'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ]);

        Movement::create($data);

        return redirect()->route('web.movements.index')->with('status', 'Movimiento registrado correctamente.');
    }

    public function show(Movement $movement): View
    {
        $movement->load(['client', 'book']);

        return view('movements.show', compact('movement'));
    }

    public function edit(Movement $movement): View
    {
        $clients = Client::selectRaw("CONCAT(nombre, ' ', apellido) AS nombre_completo, id_cliente")
            ->orderBy('nombre_completo')
            ->pluck('nombre_completo', 'id_cliente');
        $books = Book::orderBy('titulo')->pluck('titulo', 'id_libro');
        $movementTypes = ['Entrada' => 'Entrada', 'Salida' => 'Salida', 'Devolucion' => 'Devolución'];

        return view('movements.edit', compact('movement', 'clients', 'books', 'movementTypes'));
    }

    public function update(Request $request, Movement $movement): RedirectResponse
    {
        $data = $request->validate([
            'id_cliente' => ['required', 'exists:clients,id_cliente'],
            'id_libro' => ['required', 'exists:books,id_libro'],
            'tipo_movimiento' => ['required', 'in:Entrada,Salida,Devolucion'],
            'fecha_movimiento' => ['required', 'date'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'observacion' => ['nullable', 'string', 'max:255'],
        ]);

        $movement->update($data);

        return redirect()->route('web.movements.index')->with('status', 'Movimiento actualizado correctamente.');
    }

    public function destroy(Movement $movement): RedirectResponse
    {
        $movement->delete();

        return redirect()->route('web.movements.index')->with('status', 'Movimiento eliminado correctamente.');
    }
}
