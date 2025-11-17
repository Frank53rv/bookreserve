<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <style>
        * { font-family: 'DejaVu Sans', sans-serif; }
        body { margin: 0; padding: 18px; background: #f8fafc; color: #0f172a; }
        .ticket-wrapper { background: #ffffff; padding: 20px; border-radius: 12px; border: 1px solid #e2e8f0; }
        .ticket-header { text-align: center; margin-bottom: 16px; }
        .ticket-header h1 { margin: 0; font-size: 20px; letter-spacing: 0.05em; }
        .ticket-meta { font-size: 12px; margin-bottom: 12px; }
        .ticket-meta span { display: block; }
        table { width: 100%; border-collapse: collapse; font-size: 12px; margin-top: 10px; }
        th, td { padding: 6px; border-bottom: 1px solid #e2e8f0; text-align: left; }
        th { background: #f1f5f9; }
        .totals { text-align: right; margin-top: 12px; font-size: 14px; }
        .footer { margin-top: 18px; font-size: 11px; text-align: center; color: #64748b; }
    </style>
</head>
<body>
<div class="ticket-wrapper">
    <div class="ticket-header">
        <h1>{{ $company['name'] ?? config('app.name', 'BookReserve') }}</h1>
        <small>Ticket de venta #{{ str_pad($sale->id_venta, 4, '0', STR_PAD_LEFT) }}</small>
    </div>
    <div class="ticket-meta">
        <span><strong>Fecha:</strong> {{ optional($sale->fecha_venta)->format('d/m/Y H:i') }}</span>
        <span><strong>Cliente:</strong> {{ $sale->client?->nombre ? $sale->client?->nombre . ' ' . $sale->client?->apellido : 'Sin registrar' }}</span>
        <span><strong>Estado:</strong> {{ $sale->estado }}</span>
        <span><strong>Método pago:</strong> {{ $sale->metodo_pago ?: 'No especificado' }}</span>
        @if (!empty($company['address']))
            <span><strong>Dirección:</strong> {{ $company['address'] }}</span>
        @endif
        @if (!empty($company['phone']))
            <span><strong>Teléfono:</strong> {{ $company['phone'] }}</span>
        @endif
    </div>

    <table>
        <thead>
        <tr>
            <th>Libro</th>
            <th>Cant.</th>
            <th>Precio</th>
            <th>Subtot.</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($sale->details as $detail)
            <tr>
                <td>{{ $detail->book?->titulo ?? ('Libro #' . $detail->id_libro) }}</td>
                <td>{{ $detail->cantidad }}</td>
                <td>Gs. {{ number_format($detail->precio_unitario, 0, ',', '.') }}</td>
                <td>Gs. {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <div class="total">
        <strong>Total: Gs. {{ number_format($sale->total ?? 0, 0, ',', '.') }}</strong>
    </div>

    @if ($sale->notas)
        <p style="font-size: 11px; margin-top: 10px;"><strong>Notas:</strong> {{ $sale->notas }}</p>
    @endif

    <div class="footer">
        <p>Gracias por su compra.</p>
        <p>Generado el {{ ($company['issued_at'] ?? now())->format('d/m/Y H:i') }}</p>
    </div>
</div>
</body>
</html>
