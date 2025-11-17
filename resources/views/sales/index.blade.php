@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-receipt-cutoff"></i> Ventas</h1>
            <p class="panel-subtitle">Consulta el historial de tickets generados y crea nuevas ventas cuando lo necesites.</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('web.sales.create') }}" class="btn btn-primary btn-elevated"><i class="bi bi-plus-circle"></i> Registrar venta</a>
        </div>
    </div>

    <div class="data-panel-body">
        @if ($sales->count())
            @php
                $stateClasses = [
                    'Pendiente' => 'warning',
                    'Pagada' => 'success',
                    'Anulada' => 'danger',
                ];
            @endphp
            <div class="table-responsive modern-table-wrapper">
                <table class="table-modern">
                    <thead>
                        <tr>
                            <th>Ticket</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th>Total</th>
                            <th>Ítems</th>
                            <th class="text-end">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td class="table-cell-title">
                                <i class="bi bi-upc-scan"></i>
                                <div>
                                    <div>Venta #{{ str_pad($sale->id_venta, 4, '0', STR_PAD_LEFT) }}</div>
                                    <small class="text-muted">{{ optional($sale->fecha_venta)->format('d/m/Y H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="table-cell-note">
                                    <i class="bi bi-person"></i>
                                    <div>{{ $sale->client?->nombre ? $sale->client?->nombre . ' ' . $sale->client?->apellido : 'Venta anónima' }}</div>
                                </div>
                            </td>
                            <td>
                                @php($chipClass = $stateClasses[$sale->estado] ?? 'info')
                                <span class="table-chip {{ $chipClass }}">{{ $sale->estado }}</span>
                            </td>
                            <td>
                                <div class="table-cell-note">
                                    <i class="bi bi-cash"></i>
                                    <div>Gs. {{ number_format($sale->total ?? 0, 0, ',', '.') }}</div>
                                </div>
                            </td>
                            <td>{{ $sale->details_count }}</td>
                            <td class="text-end">
                                <div class="table-actions">
                                    <a href="{{ route('web.sales.show', $sale) }}" class="btn btn-outline-soft"><i class="bi bi-eye"></i> Ver</a>
                                    <a href="{{ route('web.sales.ticket', $sale) }}" class="btn btn-outline-soft" target="_blank"><i class="bi bi-filetype-pdf"></i> Ticket</a>
                                    <form action="{{ route('web.sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('¿Eliminar la venta? Esta acción revertirá el stock.');">
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
                <i class="bi bi-receipt"></i>
                <h3>No hay ventas registradas</h3>
                <p>Cuando registres la primera venta, podrás descargar el ticket PDF desde aquí.</p>
            </div>
        @endif
    </div>

    @if ($sales->hasPages())
        <div class="d-flex justify-content-center">
            {{ $sales->links() }}
        </div>
    @endif
</section>
@endsection
