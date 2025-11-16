<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDetail extends Model
{
    protected $table = 'sale_details';

    protected $primaryKey = 'id_detalle_venta';

    protected $guarded = [];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function sale(): BelongsTo
    {
        return $this->belongsTo(SaleHeader::class, 'id_venta', 'id_venta');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'id_libro', 'id_libro');
    }
}
