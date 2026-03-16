<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteUsoHabitacion extends Model
{
    use HasFactory;

    protected $table = 'reporte_uso_habitaciones';

    protected $fillable = [
        'periodo_tipo',
        'periodo_inicio',
        'periodo_fin',
        'hospedaje_id',
        'total_reservas',
        'total_noches',
        'metadata',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fin' => 'date',
        'metadata' => 'array',
    ];

    public function hospedaje()
    {
        return $this->belongsTo(Hospedaje::class);
    }
}
