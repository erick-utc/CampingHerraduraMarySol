<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteUsoCamping extends Model
{
    use HasFactory;

    protected $table = 'reporte_uso_camping';

    protected $fillable = [
        'periodo_tipo',
        'periodo_inicio',
        'periodo_fin',
        'total_reservas_camping',
        'total_noches_camping',
        'metadata',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fin' => 'date',
        'metadata' => 'array',
    ];
}
