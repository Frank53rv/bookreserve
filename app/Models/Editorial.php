<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Editorial extends Model
{
    protected $table = 'editorials';

    protected $primaryKey = 'id_editorial';

    protected $guarded = [];

    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'id_editorial', 'id_editorial');
    }
}
