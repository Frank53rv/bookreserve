<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReservationDetail extends Model
{
    protected $table = 'reservation_details';

    protected $primaryKey = 'id_detalle_reserva';

    protected $guarded = [];

    protected $casts = [
        'cantidad' => 'integer',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(ReservationHeader::class, 'id_reserva', 'id_reserva');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'id_libro', 'id_libro');
    }
}
