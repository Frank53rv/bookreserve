<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    protected $primaryKey = 'id_reporte';

    protected $fillable = [
        'nombre',
        'tipo',
        'formato',
        'parametros',
        'archivo_path',
        'generado_por',
        'fecha_generacion',
    ];

    protected $casts = [
        'parametros' => 'array',
        'fecha_generacion' => 'datetime',
    ];

    const TIPOS = [
        'ventas' => 'Reporte de Ventas',
        'inventario' => 'Reporte de Inventario',
        'clientes' => 'Reporte de Clientes',
        'reservas' => 'Reporte de Reservas',
        'financiero' => 'Reporte Financiero',
        'movimientos' => 'Reporte de Movimientos',
    ];

    const FORMATOS = [
        'pdf' => 'PDF',
        'excel' => 'Excel',
    ];
}
