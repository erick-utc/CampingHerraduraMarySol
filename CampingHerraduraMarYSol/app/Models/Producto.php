<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca',
        'producto',
        'tamano',
        'precio',
    ];

    protected $casts = [
        'precio' => 'decimal:2',
    ];
}
