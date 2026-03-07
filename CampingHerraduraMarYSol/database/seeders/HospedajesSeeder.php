<?php

namespace Database\Seeders;

use App\Models\Hospedaje;
use Illuminate\Database\Seeder;

class HospedajesSeeder extends Seeder
{
    public function run(): void
    {
        $hospedajes = [
            // Campings (sin aire acondicionado)
            [
                'numeros' => 'C-1',
                'tipo' => 'camping',
                'aire_acondicionado' => false,
                'familiar' => true,
                'parejas' => false,
            ],
            [
                'numeros' => 'C-2',
                'tipo' => 'camping',
                'aire_acondicionado' => false,
                'familiar' => false,
                'parejas' => true,
            ],
            [
                'numeros' => 'C-3',
                'tipo' => 'camping',
                'aire_acondicionado' => false,
                'familiar' => false,
                'parejas' => false,
            ],

            // Habitaciones CON aire acondicionado
            [
                'numeros' => 'H-101',
                'tipo' => 'habitacion',
                'aire_acondicionado' => true,
                'familiar' => true,
                'parejas' => false,
            ],
            [
                'numeros' => 'H-102',
                'tipo' => 'habitacion',
                'aire_acondicionado' => true,
                'familiar' => false,
                'parejas' => true,
            ],
            [
                'numeros' => 'H-103',
                'tipo' => 'habitacion',
                'aire_acondicionado' => true,
                'familiar' => false,
                'parejas' => false,
            ],

            // Habitaciones SIN aire acondicionado
            [
                'numeros' => 'H-201',
                'tipo' => 'habitacion',
                'aire_acondicionado' => false,
                'familiar' => true,
                'parejas' => false,
            ],
            [
                'numeros' => 'H-202',
                'tipo' => 'habitacion',
                'aire_acondicionado' => false,
                'familiar' => false,
                'parejas' => true,
            ],
            [
                'numeros' => 'H-203',
                'tipo' => 'habitacion',
                'aire_acondicionado' => false,
                'familiar' => false,
                'parejas' => false,
            ],
        ];

        foreach ($hospedajes as $hospedaje) {
            Hospedaje::firstOrCreate(['numeros' => $hospedaje['numeros']], $hospedaje);
        }
    }
}
