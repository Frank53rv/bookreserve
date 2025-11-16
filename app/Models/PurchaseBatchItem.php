<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PurchaseBatchItem extends Model
{
    protected $table = 'purchase_batch_items';

    protected $primaryKey = 'id_detalle_lote';

    protected $guarded = [];

    protected $casts = [
        'cantidad' => 'integer',
        'costo_unitario' => 'decimal:2',
        'fecha_vencimiento' => 'date',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(PurchaseBatch::class, 'id_lote', 'id_lote');
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class, 'id_libro', 'id_libro');
    }
}
