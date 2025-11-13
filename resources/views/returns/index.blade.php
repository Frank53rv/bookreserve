@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Devoluciones</h1>
    <a href="{{ route('web.returns.create') }}" class="btn btn-primary">Nueva devolución</a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($returns as $return)
                    <tr>
                        <td>{{ $return->id_devolucion }}</td>
                        <td>{{ $return->client?->nombre }} {{ $return->client?->apellido }}</td>
                        <td>{{ $return->fecha_devolucion?->format('d/m/Y H:i') }}</td>
                        <td>{{ $return->estado }}</td>
                        <td class="text-end">
                            <a href="{{ route('web.returns.show', $return) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                            <a href="{{ route('web.returns.edit', $return) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form action="{{ route('web.returns.destroy', $return) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar devolución?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">No hay devoluciones registradas.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($returns->hasPages())
        <div class="card-footer">
            {{ $returns->links() }}
        </div>
    @endif
</div>
@endsection
