<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnHeader extends Model
{
    protected $table = 'return_headers';

    protected $primaryKey = 'id_devolucion';

    protected $guarded = [];

    protected $casts = [
        'fecha_devolucion' => 'datetime',
    ];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'id_cliente', 'id_cliente');
    }

    public function details(): HasMany
    {
        return $this->hasMany(ReturnDetail::class, 'id_devolucion', 'id_devolucion');
    }
}
