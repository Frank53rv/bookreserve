<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $nombre }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; color: #1a1a1a; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18pt; color: #2563eb; }
        .header p { margin: 5px 0; color: #666; }
        .metric-box { display: inline-block; width: 23%; padding: 10px; margin: 5px 0; background: #f3f4f6; border-left: 3px solid #10b981; }
        .metric-label { font-size: 8pt; color: #666; text-transform: uppercase; }
        .metric-value { font-size: 16pt; font-weight: bold; color: #1a1a1a; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #10b981; color: white; padding: 8px; text-align: left; font-size: 9pt; }
        td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 9pt; }
        tr:nth-child(even) { background: #f9fafb; }
        .section-title { font-size: 14pt; font-weight: bold; margin-top: 20px; margin-bottom: 10px; color: #059669; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .footer { margin-top: 30px; text-align: center; font-size: 8pt; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
        .badge-low { background: #fef3c7; color: #92400e; padding: 2px 6px; border-radius: 3px; font-size: 8pt; }
        .badge-out { background: #fee2e2; color: #991b1b; padding: 2px 6px; border-radius: 3px; font-size: 8pt; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $nombre }}</h1>
        <p>Reporte de Inventario</p>
        <p>Generado: {{ $fecha_generacion->format('d/m/Y H:i') }}</p>
    </div>

    <div class="section-title">Resumen General</div>
    <div>
        <div class="metric-box">
            <div class="metric-label">Total Libros</div>
            <div class="metric-value">{{ $data['totales']['total_libros'] }}</div>
        </div>
        <div class="metric-box">
            <div class="metric-label">Stock Total</div>
            <div class="metric-value">{{ $data['totales']['stock_total'] }}</div>
        </div>
        <div class="metric-box">
            <div class="metric-label">Valor Inventario</div>
            <div class="metric-value">Gs. {{ number_format($data['totales']['valor_inventario'], 0, ',', '.') }}</div>
        </div>
        <div class="metric-box">
            <div class="metric-label">Stock Bajo</div>
            <div class="metric-value">{{ $data['totales']['stock_bajo_count'] }}</div>
        </div>
    </div>

    <div class="section-title">Detalle de Inventario</div>
    <table>
        <thead>
            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Categoría</th>
                <th style="text-align: center;">Stock</th>
                <th style="text-align: right;">Precio</th>
                <th style="text-align: right;">Valor Total</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['libros'] as $libro)
                <tr>
                    <td>{{ $libro->titulo }}</td>
                    <td>{{ $libro->autor }}</td>
                    <td>{{ $libro->category?->nombre ?? 'Sin categoría' }}</td>
                    <td style="text-align: center;">
                        {{ $libro->stock_actual }}
                        @if($libro->stock_actual === 0)
                            <span class="badge-out">AGOTADO</span>
                        @elseif($libro->stock_actual <= 3)
                            <span class="badge-low">BAJO</span>
                        @endif
                    </td>
                    <td style="text-align: right;">Gs. {{ number_format($libro->precio_venta ?? 0, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Gs. {{ number_format($libro->stock_actual * ($libro->precio_venta ?? 0), 0, ',', '.') }}</td>
                    <td>{{ $libro->estado }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if($data['por_categoria']->count() > 0)
        <div class="section-title">Inventario por Categoría</div>
        <table>
            <thead>
                <tr>
                    <th>Categoría</th>
                    <th style="text-align: center;">Cantidad Libros</th>
                    <th style="text-align: center;">Stock Total</th>
                    <th style="text-align: right;">Valor</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data['por_categoria'] as $categoria => $info)
                    <tr>
                        <td>{{ $categoria ?? 'Sin categoría' }}</td>
                        <td style="text-align: center;">{{ $info['cantidad'] }}</td>
                        <td style="text-align: center;">{{ $info['stock'] }}</td>
                        <td style="text-align: right;">Gs. {{ number_format($info['valor'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>{{ config('app.name', 'BookReserve') }} - Sistema de Gestión de Biblioteca</p>
        <p>Este reporte fue generado automáticamente</p>
    </div>
</body>
</html>
