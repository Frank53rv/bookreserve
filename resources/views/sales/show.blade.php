@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel mb-4">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-upc"></i> Venta #{{ str_pad($sale->id_venta, 4, '0', STR_PAD_LEFT) }}</h1>
            <p class="panel-subtitle">Detalle del ticket emitido, incluyendo totales y líneas vendidas.</p>
        </div>
        <div class="panel-actions">
            <a href="{{ route('web.sales.ticket', $sale) }}" class="btn btn-outline-soft" target="_blank"><i class="bi bi-filetype-pdf"></i> Descargar ticket</a>
            <a href="{{ route('web.sales.index') }}" class="btn btn-outline-soft"><i class="bi bi-arrow-left"></i> Volver</a>
            <form action="{{ route('web.sales.destroy', $sale) }}" method="POST" onsubmit="return confirm('¿Eliminar la venta?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-soft btn-outline-danger"><i class="bi bi-trash"></i> Eliminar</button>
            </form>
        </div>
    </div>

    <div class="data-panel-body">
        <div class="row gy-4">
            <div class="col-md-6">
                <div class="data-field">
                    <span class="data-label">Cliente</span>
                    <span class="data-value">{{ $sale->client?->nombre ? $sale->client?->nombre . ' ' . $sale->client?->apellido : 'Venta anónima' }}</span>
                </div>
                <div class="data-field">
                    <span class="data-label">Fecha</span>
                    <span class="data-value">{{ optional($sale->fecha_venta)->format('d/m/Y H:i') }}</span>
                </div>
                <div class="data-field">
                    <span class="data-label">Estado</span>
                    @php
                        $stateClasses = [
                            'Pendiente' => 'warning',
                            'Pagada' => 'success',
                            'Anulada' => 'danger',
                        ];
                    @endphp
                    <span class="table-chip {{ $stateClasses[$sale->estado] ?? 'info' }}">{{ $sale->estado }}</span>
                </div>
            </div>
            <div class="col-md-6">
                <div class="data-field">
                    <span class="data-label">Método de pago</span>
                    <span class="data-value">{{ $sale->metodo_pago ?: 'No especificado' }}</span>
                </div>
                <div class="data-field">
                    <span class="data-label">Total</span>
                    <span class="data-value">S/ {{ number_format($sale->total ?? 0, 2, '.', ',') }}</span>
                </div>
                <div class="data-field">
                    <span class="data-label">Notas</span>
                    <span class="data-value">{{ $sale->notas ?: 'Sin notas' }}</span>
                </div>
            </div>
        </div>

        <div class="surface-divider"></div>

        <div class="table-responsive modern-table-wrapper">
            <table class="table-modern">
                <thead>
                    <tr>
                        <th>Libro</th>
                        <th>Cantidad</th>
                        <th>Precio unitario</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($sale->details as $detail)
                    <tr>
                        <td class="table-cell-title">
                            <i class="bi bi-book"></i>
                            <div>
                                <div>{{ $detail->book?->titulo ?? 'Libro eliminado' }}</div>
                                <small class="text-muted">ID libro: {{ $detail->id_libro }}</small>
                            </div>
                        </td>
                        <td>{{ $detail->cantidad }}</td>
                        <td>Gs. {{ number_format($detail->precio_unitario, 0, ',', '.') }}</td>
                        <td>Gs. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <div class="surface-divider"></div>

        <div class="row g-3 text-muted small">
            <div class="col-md-6">Creado: {{ optional($sale->created_at)->format('d/m/Y H:i') }}</div>
            <div class="col-md-6 text-md-end">Actualizado: {{ optional($sale->updated_at)->format('d/m/Y H:i') }}</div>
        </div>
    </div>
</section>
@endsection
