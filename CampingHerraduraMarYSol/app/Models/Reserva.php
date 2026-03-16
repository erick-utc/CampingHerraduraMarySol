<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    use HasFactory;

    protected $fillable = [
        'usuario_id',
        'hospedaje_id',
        'precio',
        'fecha_entrada',
        'fecha_salida',
        'espacios_de_parqueo',
        'estado',
        'desayuno',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
        'fecha_entrada' => 'datetime',
        'fecha_salida' => 'datetime',
        'desayuno' => 'boolean',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function hospedaje()
    {
        return $this->belongsTo(Hospedaje::class);
    }

    public function factura()
    {
        return $this->hasOne(Factura::class);
    }
}
