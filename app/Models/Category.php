<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $table = 'categories';

    protected $primaryKey = 'id_categoria';

    protected $guarded = [];

    /**
     * Books belonging to this category.
     */
    public function books(): HasMany
    {
        return $this->hasMany(Book::class, 'id_categoria', 'id_categoria');
    }
}
