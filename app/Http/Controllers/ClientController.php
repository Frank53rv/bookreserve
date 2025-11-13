<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $clients = Client::query()
            ->withCount(['reservationHeaders as reservas_count', 'returnHeaders as devoluciones_count'])
            ->orderBy('apellido')
            ->orderBy('nombre')
            ->get();

        return response()->json($clients);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'apellido' => ['required', 'string', 'max:100'],
            'dni' => ['required', 'string', 'max:20', Rule::unique('clients', 'dni')],
            'telefono' => ['nullable', 'string', 'max:20'],
            'correo' => ['required', 'string', 'email', 'max:100', Rule::unique('clients', 'correo')],
            'direccion' => ['nullable', 'string', 'max:255'],
            'fecha_registro' => ['required', 'date'],
        ]);

        $client = Client::create($data);

        return response()->json($client, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client): JsonResponse
    {
        $client->load(['reservationHeaders.details', 'returnHeaders.details']);

        return response()->json($client);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Client $client): JsonResponse
    {
        $data = $request->validate([
            'nombre' => ['sometimes', 'required', 'string', 'max:100'],
            'apellido' => ['sometimes', 'required', 'string', 'max:100'],
            'dni' => [
                'sometimes',
                'required',
                'string',
                'max:20',
                Rule::unique('clients', 'dni')->ignore($client->getKey(), $client->getKeyName()),
            ],
            'telefono' => ['nullable', 'string', 'max:20'],
            'correo' => [
                'sometimes',
                'required',
                'string',
                'email',
                'max:100',
                Rule::unique('clients', 'correo')->ignore($client->getKey(), $client->getKeyName()),
            ],
            'direccion' => ['nullable', 'string', 'max:255'],
            'fecha_registro' => ['sometimes', 'required', 'date'],
        ]);

        $client->update($data);

        return response()->json($client);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client): JsonResponse
    {
        $client->delete();

        return response()->json(null, 204);
    }
}
