@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-box"></i> Ingresos de inventario</h1>
            <p class="panel-subtitle">Monitorea los ingresos de stock y mantén un historial claro de proveedores.</p>
        </div>
        <div class="panel-actions">
            <span class="status-pill"><i class="bi bi-clipboard-check"></i> Movimientos: {{ $inventoryRecords->total() }}</span>
            <a href="{{ route('web.inventory-records.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Nuevo ingreso</a>
        </div>
    </div>

    <div class="data-panel-body">
        @if ($inventoryRecords->count())
            <div class="table-responsive modern-table-wrapper">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Libro</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Proveedor</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($inventoryRecords as $record)
                        @php
                            $quantityTone = $record->cantidad_ingresada >= 10 ? 'success' : ($record->cantidad_ingresada <= 2 ? 'danger' : '');
                        @endphp
                        <tr>
                            <td>
                                <span class="table-cell-title"><i class="bi bi-hash"></i>{{ $record->id_inventario }}</span>
                            </td>
                            <td class="table-cell-note"><i class="bi bi-journal-text"></i> {{ $record->book?->titulo ?? 'Libro no encontrado' }}</td>
                            <td class="table-cell-note"><i class="bi bi-clock-history"></i> {{ $record->fecha_ingreso?->format('d/m/Y H:i') ?? 'Sin fecha' }}</td>
                            <td>
                                <span class="table-chip {{ $quantityTone }}"><i class="bi bi-stack"></i> {{ $record->cantidad_ingresada }}</span>
                            </td>
                            <td class="table-cell-note"><i class="bi bi-truck"></i> {{ $record->proveedor ?? 'Sin registrar' }}</td>
                            <td class="text-end">
                                <div class="panel-actions justify-content-end">
                                    <a href="{{ route('web.inventory-records.show', $record) }}" class="btn btn-outline-soft"><i class="bi bi-eye"></i> Ver</a>
                                    <a href="{{ route('web.inventory-records.edit', $record) }}" class="btn btn-primary btn-elevated"><i class="bi bi-pencil"></i> Editar</a>
                                    <form action="{{ route('web.inventory-records.destroy', $record) }}" method="POST" onsubmit="return confirm('¿Eliminar ingreso?');">
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
                <i class="bi bi-box-seam"></i>
                <h3>No hay ingresos registrados</h3>
                <p>Registra una entrada de inventario para llevar seguimiento del stock.</p>
                <a href="{{ route('web.inventory-records.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Registrar ingreso</a>
            </div>
        @endif
    </div>

    @if ($inventoryRecords->hasPages())
        <div class="d-flex justify-content-center">
            {{ $inventoryRecords->links() }}
        </div>
    @endif
</section>
@endsection
