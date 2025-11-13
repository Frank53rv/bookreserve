<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryRecord extends Model
{
    protected $table = 'inventory_records';

    protected $primaryKey = 'id_inventario';

    protected $guarded = [];

    protected $casts = [
        'fecha_ingreso' => 'datetime',
        'cantidad_ingresada' => 'integer',
    ];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'id_libro', 'id_libro');
    }
}
