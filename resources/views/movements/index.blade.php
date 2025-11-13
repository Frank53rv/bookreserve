@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Movimientos</h1>
    <a href="{{ route('web.movements.create') }}" class="btn btn-primary">Nuevo movimiento</a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Libro</th>
                        <th>Tipo</th>
                        <th>Fecha</th>
                        <th>Cantidad</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($movements as $movement)
                    <tr>
                        <td>{{ $movement->id_movimiento }}</td>
                        <td>{{ $movement->client?->nombre }} {{ $movement->client?->apellido }}</td>
                        <td>{{ $movement->book?->titulo }}</td>
                        <td>{{ $movement->tipo_movimiento }}</td>
                        <td>{{ $movement->fecha_movimiento?->format('d/m/Y H:i') }}</td>
                        <td>{{ $movement->cantidad }}</td>
                        <td class="text-end">
                            <a href="{{ route('web.movements.show', $movement) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                            <a href="{{ route('web.movements.edit', $movement) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form action="{{ route('web.movements.destroy', $movement) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar movimiento?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">No hay movimientos registrados.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($movements->hasPages())
        <div class="card-footer">
            {{ $movements->links() }}
        </div>
    @endif
</div>
@endsection
