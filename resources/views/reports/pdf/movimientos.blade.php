<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $nombre }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18pt; color: #6366f1; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #6366f1; color: white; padding: 8px; text-align: left; font-size: 9pt; }
        td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 9pt; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $nombre }}</h1>
        <p>Reporte de Movimientos - Generado: {{ $fecha_generacion->format('d/m/Y H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr><th>Fecha</th><th>Libro</th><th>Tipo</th><th>Cantidad</th><th>Referencia</th></tr>
        </thead>
        <tbody>
            @foreach($data['movimientos'] as $mov)
                <tr>
                    <td>{{ $mov->fecha_movimiento->format('d/m/Y H:i') }}</td>
                    <td>{{ $mov->book?->titulo ?? 'Desconocido' }}</td>
                    <td>{{ $mov->tipo_movimiento }}</td>
                    <td>{{ $mov->cantidad }}</td>
                    <td>{{ $mov->referencia ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>