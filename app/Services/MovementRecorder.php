<?php

namespace App\Services;

use App\Models\Movement;
use App\Models\PurchaseBatch;
use App\Models\PurchaseBatchItem;
use App\Models\ReservationDetail;
use App\Models\ReservationHeader;
use App\Models\ReturnDetail;
use App\Models\ReturnHeader;
use App\Models\SaleDetail;
use App\Models\SaleHeader;
use Illuminate\Support\Carbon;

class MovementRecorder
{
    public function record(array $attributes, string $description, array $context = []): Movement
    {
        $payload = array_merge([
            'fecha_movimiento' => Carbon::now(),
            'observacion' => null,
            'metadata' => null,
            'id_reserva' => null,
            'id_devolucion' => null,
        ], $attributes);

        $movement = Movement::create($payload);

        $movement->logs()->create([
            'descripcion' => $description,
            'contexto' => $context ?: null,
        ]);

        return $movement;
    }

    public function recordReservationMovement(ReservationHeader $reservation, ReservationDetail $detail, array $extra = []): Movement
    {
        $detail->loadMissing('book');

        return $this->record(array_merge([
            'id_cliente' => $reservation->id_cliente,
            'id_libro' => $detail->id_libro,
            'id_reserva' => $reservation->id_reserva,
            'tipo_movimiento' => 'Salida',
            'fecha_movimiento' => $reservation->fecha_reserva,
            'cantidad' => $detail->cantidad,
            'observacion' => sprintf('Reserva #%d para %s %s', $reservation->id_reserva, $reservation->client?->nombre, $reservation->client?->apellido),
            'metadata' => [
                'detalle_reserva' => $detail->id_detalle_reserva,
                'estado_reserva' => $reservation->estado,
            ],
        ], $extra), sprintf('Salida de %d ejemplares de "%s" por reserva #%d', $detail->cantidad, $detail->book?->titulo ?? 'Libro no disponible', $reservation->id_reserva));
    }

    public function recordReturnMovement(ReturnHeader $return, ReturnDetail $detail, array $extra = []): Movement
    {
        $return->loadMissing('client');
        $detail->loadMissing('book');

        return $this->record(array_merge([
            'id_cliente' => $return->id_cliente,
            'id_libro' => $detail->id_libro,
            'id_devolucion' => $return->id_devolucion,
            'id_reserva' => $return->id_reserva,
            'tipo_movimiento' => 'Devolucion',
            'fecha_movimiento' => $return->fecha_devolucion,
            'cantidad' => $detail->cantidad_devuelta,
            'observacion' => sprintf('Devolución #%d vinculada a reserva #%d', $return->id_devolucion, $return->id_reserva),
            'metadata' => array_filter([
                'detalle_devolucion' => $detail->id_detalle_devolucion,
                'detalle_reserva' => $detail->id_detalle_reserva,
                'estado_devolucion' => $return->estado,
            ]),
        ], $extra), sprintf('Ingreso por devolución de %d ejemplares de "%s" (devolución #%d)', $detail->cantidad_devuelta, $detail->book?->titulo ?? 'Libro no disponible', $return->id_devolucion));
    }

    public function recordInventoryMovement(array $attributes, string $description, array $context = []): Movement
    {
        return $this->record($attributes, $description, $context);
    }

    public function recordPurchaseMovement(PurchaseBatch $batch, PurchaseBatchItem $item, array $extra = []): Movement
    {
        $batch->loadMissing('supplier');
        $item->loadMissing('book');

        return $this->record(array_merge([
            'id_cliente' => null,
            'id_proveedor' => $batch->id_proveedor,
            'id_libro' => $item->id_libro,
            'tipo_movimiento' => 'Entrada',
            'fecha_movimiento' => $batch->fecha_recepcion,
            'cantidad' => $item->cantidad,
            'observacion' => sprintf('Ingreso por lote %s', $batch->codigo_lote),
            'metadata' => array_filter([
                'lote' => $batch->id_lote,
                'costo_unitario' => $item->costo_unitario,
                'documento' => $batch->documento_referencia,
            ]),
        ], $extra), sprintf('Entrada de %d ejemplares de "%s" (lote %s)', $item->cantidad, $item->book?->titulo ?? 'Libro no disponible', $batch->codigo_lote));
    }

    public function recordSaleMovement(SaleHeader $sale, SaleDetail $detail, array $extra = []): Movement
    {
        $sale->loadMissing('client');
        $detail->loadMissing('book');

        return $this->record(array_merge([
            'id_cliente' => $sale->id_cliente,
            'id_proveedor' => null,
            'id_libro' => $detail->id_libro,
            'tipo_movimiento' => 'Salida',
            'fecha_movimiento' => $sale->fecha_venta,
            'cantidad' => $detail->cantidad,
            'observacion' => sprintf('Venta #%d', $sale->id_venta),
            'metadata' => array_filter([
                'venta' => $sale->id_venta,
                'precio_unitario' => $detail->precio_unitario,
                'metodo_pago' => $sale->metodo_pago,
            ]),
        ], $extra), sprintf('Salida por venta de %d ejemplares de "%s"', $detail->cantidad, $detail->book?->titulo ?? 'Libro no disponible'));
    }
}
