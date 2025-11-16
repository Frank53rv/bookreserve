<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SaleHeader extends Model
{
    public const STATES = ['Pendiente', 'Pagada', 'Anulada'];

    protected $table = 'sale_headers';

    protected $primaryKey = 'id_venta';

    protected $guarded = [];

    protected $casts = [
        'fecha_venta' => 'datetime',
        'total' => 'decimal:2',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_cliente', 'id_cliente');
    }

    public function details(): HasMany
    {
        return $this->hasMany(SaleDetail::class, 'id_venta', 'id_venta');
    }
}
