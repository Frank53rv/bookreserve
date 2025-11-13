<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::orderBy('nombre')->orderBy('apellido')->paginate(10);

        return view('clients.index', compact('clients'));
    }

    public function create(): View
    {
        return view('clients.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'dni' => ['required', 'string', 'max:20', 'unique:clients,dni'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'correo' => ['required', 'email', 'max:100', 'unique:clients,correo'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'fecha_registro' => ['required', 'date'],
        ]);

        Client::create($data);

        return redirect()->route('web.clients.index')->with('status', 'Cliente creado correctamente.');
    }

    public function show(Client $client): View
    {
        return view('clients.show', compact('client'));
    }

    public function edit(Client $client): View
    {
        return view('clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'dni' => ['required', 'string', 'max:20', 'unique:clients,dni,' . $client->getKey() . ',id_cliente'],
            'telefono' => ['nullable', 'string', 'max:20'],
            'correo' => ['required', 'email', 'max:100', 'unique:clients,correo,' . $client->getKey() . ',id_cliente'],
            'direccion' => ['nullable', 'string', 'max:255'],
            'fecha_registro' => ['required', 'date'],
        ]);

        $client->update($data);

        return redirect()->route('web.clients.index')->with('status', 'Cliente actualizado correctamente.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();

        return redirect()->route('web.clients.index')->with('status', 'Cliente eliminado correctamente.');
    }
}
