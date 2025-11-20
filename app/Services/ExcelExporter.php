<?php

namespace App\Services;

use Illuminate\Support\Collection;

class ExcelExporter
{
    public function export(array $data, string $tipo): string
    {
        // Implementación básica con CSV hasta instalar maatwebsite/excel
        $filename = storage_path('app/public/reports/' . uniqid('report_') . '.csv');
        
        if (!file_exists(dirname($filename))) {
            mkdir(dirname($filename), 0755, true);
        }

        $file = fopen($filename, 'w');
        
        // Agregar BOM para UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

        switch ($tipo) {
            case 'ventas':
                $this->exportVentas($file, $data);
                break;
            case 'inventario':
                $this->exportInventario($file, $data);
                break;
            case 'clientes':
                $this->exportClientes($file, $data);
                break;
            case 'reservas':
                $this->exportReservas($file, $data);
                break;
            case 'financiero':
                $this->exportFinanciero($file, $data);
                break;
            case 'movimientos':
                $this->exportMovimientos($file, $data);
                break;
        }

        fclose($file);
        return $filename;
    }

    protected function exportVentas($file, array $data)
    {
        fputcsv($file, ['ID Venta', 'Fecha', 'Cliente', 'Estado', 'Total', 'Método Pago']);
        
        foreach ($data['ventas'] as $venta) {
            fputcsv($file, [
                $venta->id_venta,
                $venta->fecha_venta->format('Y-m-d H:i'),
                $venta->client ? $venta->client->nombre . ' ' . $venta->client->apellido : 'Sin cliente',
                $venta->estado,
                $venta->total,
                $venta->metodo_pago,
            ]);
        }

        fputcsv($file, []);
        fputcsv($file, ['TOTALES']);
        fputcsv($file, ['Total Ventas:', $data['totales']['total_ventas']]);
        fputcsv($file, ['Monto Total:', $data['totales']['monto_total']]);
        fputcsv($file, ['Promedio:', $data['totales']['promedio_venta']]);
    }

    protected function exportInventario($file, array $data)
    {
        fputcsv($file, ['ID', 'Título', 'Autor', 'Categoría', 'Stock', 'Precio', 'Valor Total', 'Estado']);
        
        foreach ($data['libros'] as $libro) {
            fputcsv($file, [
                $libro->id_libro,
                $libro->titulo,
                $libro->autor,
                $libro->category?->nombre ?? 'Sin categoría',
                $libro->stock_actual,
                $libro->precio_venta,
                $libro->stock_actual * ($libro->precio_venta ?? 0),
                $libro->estado,
            ]);
        }

        fputcsv($file, []);
        fputcsv($file, ['TOTALES']);
        fputcsv($file, ['Total Libros:', $data['totales']['total_libros']]);
        fputcsv($file, ['Stock Total:', $data['totales']['stock_total']]);
        fputcsv($file, ['Valor Inventario:', $data['totales']['valor_inventario']]);
    }

    protected function exportClientes($file, array $data)
    {
        fputcsv($file, ['ID', 'Nombre', 'Apellido', 'Email', 'Teléfono', 'Reservas', 'Compras']);
        
        foreach ($data['clientes'] as $cliente) {
            fputcsv($file, [
                $cliente->id_cliente,
                $cliente->nombre,
                $cliente->apellido,
                $cliente->correo,
                $cliente->telefono,
                $cliente->reservas_count,
                $cliente->compras_count,
            ]);
        }

        fputcsv($file, []);
        fputcsv($file, ['TOTALES']);
        fputcsv($file, ['Total Clientes:', $data['totales']['total_clientes']]);
        fputcsv($file, ['Clientes Activos:', $data['totales']['clientes_activos']]);
    }

    protected function exportReservas($file, array $data)
    {
        fputcsv($file, ['ID', 'Fecha Reserva', 'Cliente', 'Estado', 'Libros Reservados']);
        
        foreach ($data['reservas'] as $reserva) {
            fputcsv($file, [
                $reserva->id_reserva,
                $reserva->fecha_reserva->format('Y-m-d H:i'),
                $reserva->client ? $reserva->client->nombre . ' ' . $reserva->client->apellido : 'Sin cliente',
                $reserva->estado,
                $reserva->details->sum('cantidad'),
            ]);
        }

        fputcsv($file, []);
        fputcsv($file, ['TOTALES']);
        fputcsv($file, ['Total Reservas:', $data['totales']['total_reservas']]);
        fputcsv($file, ['Libros Reservados:', $data['totales']['libros_reservados']]);
    }

    protected function exportFinanciero($file, array $data)
    {
        fputcsv($file, ['RESUMEN FINANCIERO']);
        fputcsv($file, []);
        fputcsv($file, ['Total Ventas:', $data['ingresos']['total_ventas']]);
        fputcsv($file, ['Cantidad Ventas:', $data['ingresos']['ventas_count']]);
        fputcsv($file, ['Promedio Diario:', $data['ingresos']['promedio_diario']]);
        fputcsv($file, []);
        
        fputcsv($file, ['VENTAS POR MÉTODO DE PAGO']);
        fputcsv($file, ['Método', 'Cantidad', 'Monto']);
        foreach ($data['por_metodo_pago'] as $metodo => $info) {
            fputcsv($file, [$metodo, $info['cantidad'], $info['monto']]);
        }
    }

    protected function exportMovimientos($file, array $data)
    {
        fputcsv($file, ['ID', 'Fecha', 'Libro', 'Tipo', 'Cantidad', 'Referencia']);
        
        foreach ($data['movimientos'] as $mov) {
            fputcsv($file, [
                $mov->id_movimiento,
                $mov->fecha_movimiento->format('Y-m-d H:i'),
                $mov->book?->titulo ?? 'Desconocido',
                $mov->tipo_movimiento,
                $mov->cantidad,
                $mov->referencia ?? '',
            ]);
        }

        fputcsv($file, []);
        fputcsv($file, ['TOTALES']);
        fputcsv($file, ['Total Movimientos:', $data['totales']['total_movimientos']]);
    }
}
