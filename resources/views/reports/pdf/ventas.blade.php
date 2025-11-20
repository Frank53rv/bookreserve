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
        .metric-box { display: inline-block; width: 24%; padding: 10px; margin: 5px 0; background: #f3f4f6; border-left: 3px solid #2563eb; }
        .metric-label { font-size: 8pt; color: #666; text-transform: uppercase; }
        .metric-value { font-size: 16pt; font-weight: bold; color: #1a1a1a; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #2563eb; color: white; padding: 8px; text-align: left; font-size: 9pt; }
        td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 9pt; }
        tr:nth-child(even) { background: #f9fafb; }
        .section-title { font-size: 14pt; font-weight: bold; margin-top: 20px; margin-bottom: 10px; color: #1e40af; border-bottom: 1px solid #ddd; padding-bottom: 5px; }
        .footer { margin-top: 30px; text-align: center; font-size: 8pt; color: #999; border-top: 1px solid #ddd; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $nombre }}</h1>
        <p>Reporte de Ventas</p>
        <p>Generado: {{ $fecha_generacion->format('d/m/Y H:i') }}</p>
        <p>Período: {{ $data['periodo']['desde']->format('d/m/Y') }} - {{ $data['periodo']['hasta']->format('d/m/Y') }}</p>
    </div>

    <div class="section-title">Resumen General</div>
    <div>
        <div class="metric-box">
            <div class="metric-label">Total Ventas</div>
            <div class="metric-value">{{ $data['totales']['total_ventas'] }}</div>
        </div>
        <div class="metric-box">
            <div class="metric-label">Monto Total</div>
            <div class="metric-value">Gs. {{ number_format($data['totales']['monto_total'], 0, ',', '.') }}</div>
        </div>
        <div class="metric-box">
            <div class="metric-label">Promedio Venta</div>
            <div class="metric-value">Gs. {{ number_format($data['totales']['promedio_venta'], 0, ',', '.') }}</div>
        </div>
        <div class="metric-box">
            <div class="metric-label">Libros Vendidos</div>
            <div class="metric-value">{{ $data['totales']['libros_vendidos'] }}</div>
        </div>
    </div>

    <div class="section-title">Top 10 Libros Más Vendidos</div>
    <table>
        <thead>
            <tr>
                <th>Libro</th>
                <th style="text-align: right;">Cantidad Vendida</th>
                <th style="text-align: right;">Ingresos</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['top_libros'] as $libro)
                <tr>
                    <td>{{ $libro->titulo }}</td>
                    <td style="text-align: right;">{{ $libro->cantidad_vendida }}</td>
                    <td style="text-align: right;">Gs. {{ number_format($libro->ingresos, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Detalle de Ventas</div>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Cliente</th>
                <th>Estado</th>
                <th style="text-align: right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data['ventas'] as $venta)
                <tr>
                    <td>{{ $venta->id_venta }}</td>
                    <td>{{ $venta->fecha_venta->format('d/m/Y H:i') }}</td>
                    <td>{{ $venta->client ? $venta->client->nombre . ' ' . $venta->client->apellido : 'Sin cliente' }}</td>
                    <td>{{ $venta->estado }}</td>
                    <td style="text-align: right;">Gs. {{ number_format($venta->total, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>{{ config('app.name', 'BookReserve') }} - Sistema de Gestión de Biblioteca</p>
        <p>Este reporte fue generado automáticamente</p>
    </div>
</body>
</html>
