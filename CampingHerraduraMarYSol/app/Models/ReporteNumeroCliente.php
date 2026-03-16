<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteNumeroCliente extends Model
{
    use HasFactory;

    protected $table = 'reporte_numero_clientes';

    protected $fillable = [
        'periodo_tipo',
        'periodo_inicio',
        'periodo_fin',
        'total_clientes',
        'metadata',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fin' => 'date',
        'metadata' => 'array',
    ];
}
