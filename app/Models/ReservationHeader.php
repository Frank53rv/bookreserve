<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReservationHeader extends Model
{
    public const STATES = ['Pendiente', 'Retirado', 'Cancelado'];

    protected $table = 'reservation_headers';

    protected $primaryKey = 'id_reserva';

    protected $guarded = [];

    protected $casts = [
        'fecha_reserva' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_cliente', 'id_cliente');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ReservationDetail::class, 'id_reserva', 'id_reserva');
    }
}
