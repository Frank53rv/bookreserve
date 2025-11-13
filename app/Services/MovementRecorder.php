<?php

namespace App\Services;

use App\Models\Movement;
use App\Models\ReservationDetail;
use App\Models\ReservationHeader;
use App\Models\ReturnDetail;
use App\Models\ReturnHeader;
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
}
