<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>{{ $nombre }}</title>
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10pt; }
        .header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
        .header h1 { margin: 0; font-size: 18pt; color: #8b5cf6; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #8b5cf6; color: white; padding: 8px; text-align: left; font-size: 9pt; }
        td { padding: 6px 8px; border-bottom: 1px solid #e5e7eb; font-size: 9pt; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $nombre }}</h1>
        <p>Reporte de Clientes - Generado: {{ $fecha_generacion->format('d/m/Y H:i') }}</p>
    </div>
    <table>
        <thead>
            <tr><th>Nombre</th><th>Email</th><th>Teléfono</th><th>Reservas</th><th>Compras</th></tr>
        </thead>
        <tbody>
            @foreach($data['clientes'] as $cliente)
                <tr>
                    <td>{{ $cliente->nombre }} {{ $cliente->apellido }}</td>
                    <td>{{ $cliente->correo }}</td>
                    <td>{{ $cliente->telefono }}</td>
                    <td>{{ $cliente->reservas_count }}</td>
                    <td>{{ $cliente->compras_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>