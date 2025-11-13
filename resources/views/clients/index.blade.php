@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Clientes</h1>
    <a href="{{ route('web.clients.create') }}" class="btn btn-primary">Nuevo cliente</a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>DNI</th>
                        <th>Correo</th>
                        <th>Teléfono</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($clients as $client)
                    <tr>
                        <td>{{ $client->nombre }} {{ $client->apellido }}</td>
                        <td>{{ $client->dni }}</td>
                        <td>{{ $client->correo }}</td>
                        <td>{{ $client->telefono ?? 'Sin registrar' }}</td>
                        <td class="text-end">
                            <a href="{{ route('web.clients.show', $client) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                            <a href="{{ route('web.clients.edit', $client) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form action="{{ route('web.clients.destroy', $client) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar cliente?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No hay clientes registrados.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($clients->hasPages())
        <div class="card-footer">
            {{ $clients->links() }}
        </div>
    @endif
</div>
@endsection
