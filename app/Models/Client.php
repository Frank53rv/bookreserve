<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Client extends Model
{
    protected $table = 'clients';

    protected $primaryKey = 'id_cliente';

    protected $guarded = [];

    protected $casts = [
        'fecha_registro' => 'date',
    ];

    public function reservationHeaders(): HasMany
    {
        return $this->hasMany(ReservationHeader::class, 'id_cliente', 'id_cliente');
    }

    public function returnHeaders(): HasMany
    {
        return $this->hasMany(ReturnHeader::class, 'id_cliente', 'id_cliente');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class, 'id_cliente', 'id_cliente');
    }
}
