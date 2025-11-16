<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    protected $table = 'suppliers';

    protected $primaryKey = 'id_proveedor';

    protected $guarded = [];

    public function batches(): HasMany
    {
        return $this->hasMany(PurchaseBatch::class, 'id_proveedor', 'id_proveedor');
    }
}
