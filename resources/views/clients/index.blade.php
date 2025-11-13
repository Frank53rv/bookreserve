@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-people"></i> Clientes</h1>
            <p class="panel-subtitle">Centraliza la información de tus lectores y mantén el contacto siempre a mano.</p>
        </div>
        <div class="panel-actions">
            <span class="status-pill"><i class="bi bi-person-lines-fill"></i> Registrados: {{ $clients->total() }}</span>
            <a href="{{ route('web.clients.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Nuevo cliente</a>
        </div>
    </div>

    <div class="data-panel-body">
        @if ($clients->count())
            <div class="table-responsive modern-table-wrapper">
                <table class="table-modern">
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
                    @foreach ($clients as $client)
                        <tr>
                            <td>
                                <span class="table-cell-title"><i class="bi bi-person-circle"></i>{{ $client->nombre }} {{ $client->apellido }}</span>
                            </td>
                            <td class="table-cell-note"><i class="bi bi-credit-card-2-front"></i> {{ $client->dni }}</td>
                            <td class="table-cell-note"><i class="bi bi-envelope"></i> {{ $client->correo }}</td>
                            <td class="table-cell-note"><i class="bi bi-telephone"></i> {{ $client->telefono ?? 'Sin registrar' }}</td>
                            <td class="text-end">
                                <div class="panel-actions justify-content-end">
                                    <a href="{{ route('web.clients.show', $client) }}" class="btn btn-outline-soft"><i class="bi bi-eye"></i> Ver</a>
                                    <a href="{{ route('web.clients.edit', $client) }}" class="btn btn-primary btn-elevated"><i class="bi bi-pencil"></i> Editar</a>
                                    <form action="{{ route('web.clients.destroy', $client) }}" method="POST" onsubmit="return confirm('¿Eliminar cliente?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-soft btn-outline-danger"><i class="bi bi-trash"></i> Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="panel-empty">
                <i class="bi bi-people"></i>
                <h3>No hay clientes registrados</h3>
                <p>Agrega tus primeros clientes para comenzar a gestionar reservas y devoluciones.</p>
                <a href="{{ route('web.clients.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Registrar cliente</a>
            </div>
        @endif
    </div>

    @if ($clients->hasPages())
        <div class="d-flex justify-content-center">
            {{ $clients->links() }}
        </div>
    @endif
</section>
@endsection
