<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnDetail extends Model
{
    protected $table = 'return_details';

    protected $primaryKey = 'id_detalle_devolucion';

    protected $guarded = [];

    protected $casts = [
        'cantidad_devuelta' => 'integer',
    ];

    public function returnHeader(): BelongsTo
    {
        return $this->belongsTo(ReturnHeader::class, 'id_devolucion', 'id_devolucion');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'id_libro', 'id_libro');
    }

    public function reservationDetail(): BelongsTo
    {
        return $this->belongsTo(ReservationDetail::class, 'id_detalle_reserva', 'id_detalle_reserva');
    }
}
