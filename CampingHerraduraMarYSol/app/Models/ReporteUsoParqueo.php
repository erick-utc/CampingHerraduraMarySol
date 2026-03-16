<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteUsoParqueo extends Model
{
    use HasFactory;

    protected $table = 'reporte_uso_parqueo';

    protected $fillable = [
        'periodo_tipo',
        'periodo_inicio',
        'periodo_fin',
        'total_reservas_con_parqueo',
        'total_espacios_parqueo',
        'metadata',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fin' => 'date',
        'metadata' => 'array',
    ];
}
