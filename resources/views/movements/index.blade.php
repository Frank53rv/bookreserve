@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-shuffle"></i> Movimientos</h1>
            <p class="panel-subtitle">Consulta el historial completo de préstamos, devoluciones e ingresos de inventario.</p>
        </div>
        <div class="panel-actions">
            <span class="status-pill"><i class="bi bi-activity"></i> Registros: {{ $movements->total() }}</span>
            <a href="{{ route('web.movements.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Nuevo movimiento</a>
        </div>
    </div>

    <div class="data-panel-body">
        @if ($movements->count())
            <div class="table-responsive modern-table-wrapper">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Cliente</th>
                            <th>Libro</th>
                            <th>Tipo</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($movements as $movement)
                        @php
                            $type = strtolower($movement->tipo_movimiento ?? '');
                            $typeIcon = 'bi-arrows-move';
                            $typeClass = '';
                            if ($type === 'entrada') {
                                $typeIcon = 'bi-arrow-down-circle';
                                $typeClass = 'success';
                            } elseif ($type === 'salida') {
                                $typeIcon = 'bi-arrow-up-circle';
                                $typeClass = 'danger';
                            } elseif ($type === 'devolucion') {
                                $typeIcon = 'bi-arrow-counterclockwise';
                                $typeClass = 'info';
                            }

                            $quantityClass = $movement->cantidad > 5 ? 'success' : ($movement->cantidad <= 1 ? 'danger' : '');
                        @endphp
                        <tr>
                            <td>
                                <span class="table-cell-title"><i class="bi bi-hash"></i>{{ $movement->id_movimiento }}</span>
                            </td>
                            <td class="table-cell-note"><i class="bi bi-person"></i> {{ $movement->client?->nombre }} {{ $movement->client?->apellido }}</td>
                            <td class="table-cell-note">
                                <div class="d-flex flex-column gap-1">
                                    <span><i class="bi bi-journal-text"></i> {{ $movement->book?->titulo ?? 'Libro no encontrado' }}</span>
                                    <small class="text-muted">
                                        @if ($movement->reservation)
                                            <i class="bi bi-link-45deg"></i> Reserva #{{ $movement->reservation->id_reserva }}
                                        @elseif ($movement->returnHeader)
                                            <i class="bi bi-link-45deg"></i> Devolución #{{ $movement->returnHeader->id_devolucion }}
                                        @else
                                            Registro manual
                                        @endif
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="table-chip {{ $typeClass }}"><i class="bi {{ $typeIcon }}"></i> {{ ucfirst($movement->tipo_movimiento) }}</span>
                            </td>
                            <td class="table-cell-note"><i class="bi bi-clock-history"></i> {{ $movement->fecha_movimiento?->format('d/m/Y H:i') ?? 'Sin fecha' }}</td>
                            <td>
                                <span class="table-chip {{ $quantityClass }}"><i class="bi bi-stack"></i> {{ $movement->cantidad }}</span>
                            </td>
                            <td class="text-end">
                                <div class="table-actions">
                                    <a href="{{ route('web.movements.show', $movement) }}" class="btn btn-outline-soft"><i class="bi bi-eye"></i> Ver</a>
                                    <a href="{{ route('web.movements.edit', $movement) }}" class="btn btn-primary btn-elevated"><i class="bi bi-pencil"></i> Editar</a>
                                    <form action="{{ route('web.movements.destroy', $movement) }}" method="POST" onsubmit="return confirm('¿Eliminar movimiento?');">
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
                <i class="bi bi-shuffle"></i>
                <h3>No hay movimientos registrados</h3>
                <p>Registra un movimiento para construir el historial de inventario y préstamos.</p>
                <a href="{{ route('web.movements.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Registrar movimiento</a>
            </div>
        @endif
    </div>

    @if ($movements->hasPages())
        <div class="d-flex justify-content-center">
            {{ $movements->links() }}
        </div>
    @endif
</section>
@endsection
