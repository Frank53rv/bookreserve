<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Movement extends Model
{
    protected $table = 'movements';

    protected $primaryKey = 'id_movimiento';

    protected $guarded = [];

    protected $casts = [
        'fecha_movimiento' => 'datetime',
        'cantidad' => 'integer',
        'metadata' => 'array',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_cliente', 'id_cliente');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'id_libro', 'id_libro');
    }

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(ReservationHeader::class, 'id_reserva', 'id_reserva');
    }

    public function returnHeader(): BelongsTo
    {
        return $this->belongsTo(ReturnHeader::class, 'id_devolucion', 'id_devolucion');
    }

    public function logs(): HasMany
    {
        return $this->hasMany(MovementLog::class, 'id_movimiento', 'id_movimiento');
    }
}
