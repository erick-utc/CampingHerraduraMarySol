<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    use HasFactory;

    protected $fillable = [
        'reserva_id',
        'numero_factura',
        'fecha_factura',
        'subtotal',
        'impuesto',
        'total',
        'ventas',
        'reporte_productos',
    ];

    protected $casts = [
        'fecha_factura' => 'datetime',
        'subtotal' => 'decimal:2',
        'impuesto' => 'decimal:2',
        'total' => 'decimal:2',
        'ventas' => 'array',
        'reporte_productos' => 'array',
    ];

    public function reserva()
    {
        return $this->belongsTo(Reserva::class);
    }
}
