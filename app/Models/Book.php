<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table = 'books';

    protected $primaryKey = 'id_libro';

    protected $guarded = [];

    protected $casts = [
        'anio_publicacion' => 'integer',
        'stock_actual' => 'integer',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'id_categoria', 'id_categoria');
    }

    public function reservationDetails(): HasMany
    {
        return $this->hasMany(ReservationDetail::class, 'id_libro', 'id_libro');
    }

    public function returnDetails(): HasMany
    {
        return $this->hasMany(ReturnDetail::class, 'id_libro', 'id_libro');
    }

    public function inventoryRecords(): HasMany
    {
        return $this->hasMany(InventoryRecord::class, 'id_libro', 'id_libro');
    }

    public function movements(): HasMany
    {
        return $this->hasMany(Movement::class, 'id_libro', 'id_libro');
    }
}
