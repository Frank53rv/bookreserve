<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MovementLog extends Model
{
    protected $table = 'movement_logs';

    protected $primaryKey = 'id_log_movimiento';

    protected $guarded = [];

    protected $casts = [
        'contexto' => 'array',
    ];

    public function movement(): BelongsTo
    {
        return $this->belongsTo(Movement::class, 'id_movimiento', 'id_movimiento');
    }
}
