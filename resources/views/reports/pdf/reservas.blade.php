<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $nombre }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18pt; color: #f59e0b; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #f59e0b; color: white; padding: 8px; text-align: left; font-size: 9pt; }
        td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 9pt; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $nombre }}</h1>
        <p>Reporte de Reservas - Generado: {{ $fecha_generacion->format('d/m/Y H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr><th>ID</th><th>Fecha</th><th>Cliente</th><th>Estado</th><th>Libros</th></tr>
        </thead>
        <tbody>
            @foreach($data['reservas'] as $reserva)
                <tr>
                    <td>{{ $reserva->id_reserva }}</td>
                    <td>{{ $reserva->fecha_reserva->format('d/m/Y') }}</td>
                    <td>{{ $reserva->client ? $reserva->client->nombre . ' ' . $reserva->client->apellido : 'N/A' }}</td>
                    <td>{{ $reserva->estado }}</td>
                    <td>{{ $reserva->details->sum('cantidad') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>