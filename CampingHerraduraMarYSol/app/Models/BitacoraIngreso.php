<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraIngreso extends Model
{
    use HasFactory;

    protected $table = 'bitacora_ingresos';

    protected $fillable = [
        'user_id',
        'nombre',
        'email',
        'evento',
        'session_id',
        'ip_address',
        'user_agent',
        'ocurrio_en',
    ];

    protected $casts = [
        'ocurrio_en' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
