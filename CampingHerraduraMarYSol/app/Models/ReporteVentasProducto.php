<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReporteVentasProducto extends Model
{
    use HasFactory;

    protected $table = 'reporte_ventas_productos';

    protected $fillable = [
        'periodo_tipo',
        'periodo_inicio',
        'periodo_fin',
        'producto_id',
        'cantidad_vendida',
        'monto_total',
        'metadata',
    ];

    protected $casts = [
        'periodo_inicio' => 'date',
        'periodo_fin' => 'date',
        'monto_total' => 'decimal:2',
        'metadata' => 'array',
    ];

    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
}
