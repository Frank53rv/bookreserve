@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h1 class="h3 mb-0">Ingresos de inventario</h1>
    <a href="{{ route('web.inventory-records.create') }}" class="btn btn-primary">Nuevo ingreso</a>
</div>
<div class="card shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Libro</th>
                        <th>Fecha</th>
                        <th>Cantidad</th>
                        <th>Proveedor</th>
                        <th class="text-end">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                @forelse ($inventoryRecords as $record)
                    <tr>
                        <td>{{ $record->id_inventario }}</td>
                        <td>{{ $record->book?->titulo }}</td>
                        <td>{{ $record->fecha_ingreso?->format('d/m/Y H:i') }}</td>
                        <td>{{ $record->cantidad_ingresada }}</td>
                        <td>{{ $record->proveedor ?? 'Sin registrar' }}</td>
                        <td class="text-end">
                            <a href="{{ route('web.inventory-records.show', $record) }}" class="btn btn-sm btn-outline-secondary">Ver</a>
                            <a href="{{ route('web.inventory-records.edit', $record) }}" class="btn btn-sm btn-outline-primary">Editar</a>
                            <form action="{{ route('web.inventory-records.destroy', $record) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('¿Eliminar ingreso?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">No hay ingresos registrados.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if ($inventoryRecords->hasPages())
        <div class="card-footer">
            {{ $inventoryRecords->links() }}
        </div>
    @endif
</div>
@endsection
