@extends('layouts.app')

@push('styles')
<style>
    .movement-table {
        width: 100%;
        table-layout: fixed;
    }

    .movement-table th,
    .movement-table td {
        text-align: left;
        vertical-align: top;
        white-space: normal;
    }

    .movement-cell {
        display: flex;
        gap: 0.75rem;
        align-items: flex-start;
    }

    .movement-cell-icon {
        width: 1.75rem;
        height: 1.75rem;
        border-radius: 999px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.08);
        color: var(--accent-2);
        flex-shrink: 0;
        font-size: 0.9rem;
    }

    .movement-cell-body {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .movement-value {
        font-weight: 600;
        color: var(--text-primary);
    }

    .movement-meta {
        font-size: 0.85rem;
        color: var(--text-muted);
    }

    .movement-table .table-actions {
        justify-content: flex-end;
    }

    @media (max-width: 768px) {
        .movement-cell {
            flex-direction: column;
        }

        .movement-cell-icon {
            width: auto;
            height: auto;
            border-radius: 12px;
            padding: 0.25rem 0.5rem;
        }
    }

    @media (max-width: 1100px) {
        .movement-table thead {
            display: none;
        }

        .movement-table tbody,
        .movement-table tr,
        .movement-table td {
            display: block;
            width: 100%;
        }

        .movement-table tbody {
            display: grid;
            gap: 1rem;
        }

        .movement-table tr {
            background: rgba(8, 12, 38, 0.95);
            border-radius: 24px;
            padding: 1.25rem;
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 18px 40px rgba(2, 6, 23, 0.6);
        }

        .movement-table td {
            padding: 0.85rem 0;
            border: none;
        }

        .movement-table td::before {
            content: attr(data-label);
            display: block;
            font-size: 0.75rem;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: var(--text-muted);
            margin-bottom: 0.25rem;
        }

        .movement-table td.text-end {
            text-align: left !important;
        }

        .movement-table .table-actions {
            justify-content: flex-start;
            gap: 0.4rem;
        }
    }
</style>
@endpush

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
                <table class="table-modern movement-table">
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Libro</th>
                            <th scope="col">Tipo</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Cantidad</th>
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
                            <td data-label="ID">
                                <div class="movement-cell">
                                    <span class="movement-cell-icon"><i class="bi bi-hash"></i></span>
                                    <div class="movement-cell-body">
                                        <span class="movement-value">#{{ str_pad($movement->id_movimiento, 4, '0', STR_PAD_LEFT) }}</span>
                                        <span class="movement-meta">Origen: {{ $movement->referencia ?? 'Manual' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Cliente">
                                <div class="movement-cell">
                                    <span class="movement-cell-icon"><i class="bi bi-person"></i></span>
                                    <div class="movement-cell-body">
                                        <span class="movement-value">
                                            {{ trim(($movement->client?->nombre ?? '') . ' ' . ($movement->client?->apellido ?? '')) ?: 'Sin cliente' }}
                                        </span>
                                        <span class="movement-meta">{{ $movement->client?->documento ?? 'Sin documento' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Libro">
                                <div class="movement-cell">
                                    <span class="movement-cell-icon"><i class="bi bi-journal-text"></i></span>
                                    <div class="movement-cell-body">
                                        <span class="movement-value">{{ $movement->book?->titulo ?? 'Libro no encontrado' }}</span>
                                        <span class="movement-meta">
                                            @if ($movement->reservation)
                                                Reserva #{{ $movement->reservation->id_reserva }}
                                            @elseif ($movement->returnHeader)
                                                Devolución #{{ $movement->returnHeader->id_devolucion }}
                                            @else
                                                Registro manual
                                            @endif
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Tipo">
                                <span class="table-chip {{ $typeClass }}"><i class="bi {{ $typeIcon }}"></i> {{ ucfirst($movement->tipo_movimiento) }}</span>
                            </td>
                            <td data-label="Fecha">
                                <div class="movement-cell">
                                    <span class="movement-cell-icon"><i class="bi bi-clock-history"></i></span>
                                    <div class="movement-cell-body">
                                        <span class="movement-value">{{ $movement->fecha_movimiento?->format('d/m/Y H:i') ?? 'Sin fecha' }}</span>
                                        <span class="movement-meta">{{ $movement->fecha_movimiento?->diffForHumans() ?? 'Sin registro' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Cantidad">
                                <span class="table-chip {{ $quantityClass }}"><i class="bi bi-stack"></i> {{ $movement->cantidad }}</span>
                            </td>
                            <td class="text-end" data-label="Acciones">
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
