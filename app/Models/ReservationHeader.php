<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReservationHeader extends Model
{
    public const STATES = ['Reservado', 'Parcial', 'Completado', 'Cancelado'];

    protected $table = 'reservation_headers';

    protected $primaryKey = 'id_reserva';

    protected $guarded = [];

    protected $casts = [
        'fecha_reserva' => 'datetime',
        'fecha_estimada_devolucion' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_cliente', 'id_cliente');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ReservationDetail::class, 'id_reserva', 'id_reserva');
    }

    public function refreshLoanState(): void
    {
        if ($this->estado === 'Cancelado') {
            return;
        }

        [$reserved, $returned] = $this->loanTotals();

        $newState = match (true) {
            $reserved === 0 => 'Reservado',
            $returned === 0 => 'Reservado',
            $returned >= $reserved => 'Completado',
            default => 'Parcial',
        };

        if ($newState !== $this->estado) {
            $this->forceFill(['estado' => $newState])->save();
        }
    }

    public function loanTotals(): array
    {
        $details = $this->details()
            ->withSum('returnDetails as return_details_sum_cantidad_devuelta', 'cantidad_devuelta')
            ->get();

        $reserved = 0;
        $returned = 0;

        foreach ($details as $detail) {
            $reserved += $detail->cantidad;
            $returned += min(
                $detail->cantidad,
                (int) ($detail->return_details_sum_cantidad_devuelta ?? 0)
            );
        }

        return [$reserved, $returned];
    }
}
