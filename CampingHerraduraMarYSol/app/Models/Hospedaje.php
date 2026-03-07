<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hospedaje extends Model
{
    use HasFactory;

    protected $fillable = [
        'numeros',
        'tipo',
        'aire_acondicionado',
        'familiar',
        'parejas',
    ];

    protected $casts = [
        'aire_acondicionado' => 'boolean',
        'familiar' => 'boolean',
        'parejas' => 'boolean',
    ];
}
