<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraMovimiento extends Model
{
    use HasFactory;

    protected $table = 'bitacora_movimientos';

    protected $fillable = [
        'user_id',
        'nombre',
        'email',
        'modulo',
        'accion',
        'entidad',
        'entidad_id',
        'descripcion',
        'metadata',
        'ip_address',
        'user_agent',
        'ocurrio_en',
    ];

    protected $casts = [
        'metadata' => 'array',
        'ocurrio_en' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
