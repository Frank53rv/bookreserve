<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $nombre }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18pt; color: #059669; }
        .metric-box { display: inline-block; width: 30%; padding: 10px; margin: 5px; background: #f0fdf4; border-left: 3px solid #059669; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #059669; color: white; padding: 8px; text-align: left; font-size: 9pt; }
        td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 9pt; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $nombre }}</h1>
        <p>Reporte Financiero - Generado: {{ $fecha_generacion->format('d/m/Y H:i') }}</p>
    </div>
    <div>
        <div class="metric-box"><strong>Total Ventas:</strong> Gs. {{ number_format($data['ingresos']['total_ventas'], 0, ',', '.') }}</div>
        <div class="metric-box"><strong>Cantidad:</strong> {{ $data['ingresos']['ventas_count'] }}</div>
        <div class="metric-box"><strong>Promedio Diario:</strong> Gs. {{ number_format($data['ingresos']['promedio_diario'], 0, ',', '.') }}</div>
    </div>
    <h3>Ventas por Categoría</h3>
    <table>
        <thead><tr><th>Categoría</th><th style="text-align: right;">Ingresos</th></tr></thead>
        <tbody>
            @foreach($data['por_categoria'] as $cat)
                <tr>
                    <td>{{ $cat->nombre }}</td>
                    <td style="text-align: right;">Gs. {{ number_format($cat->ingresos, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>