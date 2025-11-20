@extends('layouts.app')

@section('content')
<section class="modern-surface data-panel">
    <div class="data-panel-header">
        <div>
            <h1 class="panel-title"><i class="bi bi-eye"></i> Vista Previa: {{ $tipoNombre }}</h1>
            <p class="panel-subtitle">Revisa los datos antes de generar el reporte final</p>
        </div>
    </div>

    @if($tipo === 'ventas')
        <div class="mb-4">
            <h3 class="mb-3" style="color: var(--text-primary);">Resumen</h3>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">Total Ventas</div>
                        <div class="metric-value">{{ $data['totales']['total_ventas'] }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">Monto Total</div>
                        <div class="metric-value">Gs. {{ number_format($data['totales']['monto_total'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">Promedio</div>
                        <div class="metric-value">Gs. {{ number_format($data['totales']['promedio_venta'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">Libros Vendidos</div>
                        <div class="metric-value">{{ $data['totales']['libros_vendidos'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mb-3" style="color: var(--text-primary);">Top 10 Libros Más Vendidos</h3>
        <table class="table-modern mb-4">
            <thead>
                <tr>
                    <th>Libro</th>
                    <th>Cantidad Vendida</th>
                    <th>Ingresos</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['top_libros'] as $libro)
                    <tr>
                        <td class="table-cell-title">{{ $libro->titulo }}</td>
                        <td>{{ $libro->cantidad_vendida }}</td>
                        <td>Gs. {{ number_format($libro->ingresos, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($tipo === 'inventario')
        <div class="mb-4">
            <h3 class="mb-3" style="color: var(--text-primary);">Resumen</h3>
            <div class="row g-3">
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">Total Libros</div>
                        <div class="metric-value">{{ $data['totales']['total_libros'] }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">Stock Total</div>
                        <div class="metric-value">{{ $data['totales']['stock_total'] }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">Valor Inventario</div>
                        <div class="metric-value">Gs. {{ number_format($data['totales']['valor_inventario'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="metric-card">
                        <div class="metric-label">Stock Bajo</div>
                        <div class="metric-value">{{ $data['totales']['stock_bajo_count'] }}</div>
                    </div>
                </div>
            </div>
        </div>

        <h3 class="mb-3" style="color: var(--text-primary);">Primeros 10 Libros</h3>
        <table class="table-modern">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Categoría</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['libros']->take(10) as $libro)
                    <tr>
                        <td class="table-cell-title">{{ $libro->titulo }}</td>
                        <td>{{ $libro->category?->nombre ?? 'Sin categoría' }}</td>
                        <td>
                            <span class="table-chip {{ $libro->stock_actual <= 3 ? 'warning' : '' }}">
                                {{ $libro->stock_actual }}
                            </span>
                        </td>
                        <td>Gs. {{ number_format($libro->precio_venta, 0, ',', '.') }}</td>
                        <td>{{ $libro->estado }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if($tipo === 'clientes')
        <div class="mb-4">
            <h3 class="mb-3" style="color: var(--text-primary);">Resumen</h3>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-label">Total Clientes</div>
                        <div class="metric-value">{{ $data['totales']['total_clientes'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-label">Clientes Activos</div>
                        <div class="metric-value">{{ $data['totales']['clientes_activos'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-label">Total Reservas</div>
                        <div class="metric-value">{{ $data['totales']['total_reservas'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($tipo === 'reservas')
        <div class="mb-4">
            <h3 class="mb-3" style="color: var(--text-primary);">Resumen</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="metric-card">
                        <div class="metric-label">Total Reservas</div>
                        <div class="metric-value">{{ $data['totales']['total_reservas'] }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="metric-card">
                        <div class="metric-label">Libros Reservados</div>
                        <div class="metric-value">{{ $data['totales']['libros_reservados'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($tipo === 'financiero')
        <div class="mb-4">
            <h3 class="mb-3" style="color: var(--text-primary);">Resumen Financiero</h3>
            <div class="row g-3">
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-label">Total Ventas</div>
                        <div class="metric-value">Gs. {{ number_format($data['ingresos']['total_ventas'], 0, ',', '.') }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-label">Cantidad Ventas</div>
                        <div class="metric-value">{{ $data['ingresos']['ventas_count'] }}</div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="metric-card">
                        <div class="metric-label">Promedio Diario</div>
                        <div class="metric-value">Gs. {{ number_format($data['ingresos']['promedio_diario'], 0, ',', '.') }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($tipo === 'movimientos')
        <div class="mb-4">
            <h3 class="mb-3" style="color: var(--text-primary);">Resumen</h3>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="metric-card">
                        <div class="metric-label">Total Movimientos</div>
                        <div class="metric-value">{{ $data['totales']['total_movimientos'] }}</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="metric-card">
                        <div class="metric-label">Cantidad Total</div>
                        <div class="metric-value">{{ $data['totales']['total_cantidad'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="alert alert-info mt-4" style="background: rgba(56, 189, 248, 0.1); border: 1px solid rgba(56, 189, 248, 0.3); color: var(--text-primary);">
        <i class="bi bi-info-circle"></i> Esta es solo una vista previa. Para obtener el reporte completo con todos los datos, genera el archivo PDF o Excel.
    </div>
</section>
@endsection
