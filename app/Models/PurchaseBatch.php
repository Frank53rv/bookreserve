<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseBatch extends Model
{
    protected $table = 'purchase_batches';

    protected $primaryKey = 'id_lote';

    protected $guarded = [];

    protected $casts = [
        'fecha_recepcion' => 'datetime',
    ];

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'id_proveedor', 'id_proveedor');
    }

    public function items(): HasMany
    {
        return $this->hasMany(PurchaseBatchItem::class, 'id_lote', 'id_lote');
    }
}
