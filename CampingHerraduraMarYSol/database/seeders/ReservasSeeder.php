<?php

namespace Database\Seeders;

use App\Models\Hospedaje;
use App\Models\Reserva;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReservasSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = User::whereIn('cedula', ['117650001', '117650002'])->get()->keyBy('cedula');
        $hospedajes = Hospedaje::whereIn('numeros', ['H-101', 'C-1'])->get()->keyBy('numeros');

        if ($usuarios->count() < 2 || $hospedajes->count() < 2) {
            return;
        }

        $reservas = [
            [
                'usuario_id' => $usuarios['117650001']->id,
                'hospedaje_id' => $hospedajes['H-101']->id,
                'precio' => 45000,
                'fecha_entrada' => now()->addDays(2)->setTime(14, 0),
                'fecha_salida' => now()->addDays(4)->setTime(11, 0),
                'espacios_de_parqueo' => 1,
                'estado' => 'aprobado',
                'desayuno' => true,
            ],
            [
                'usuario_id' => $usuarios['117650002']->id,
                'hospedaje_id' => $hospedajes['C-1']->id,
                'precio' => 30000,
                'fecha_entrada' => now()->addDays(5)->setTime(15, 0),
                'fecha_salida' => now()->addDays(7)->setTime(10, 0),
                'espacios_de_parqueo' => 0,
                'estado' => 'en espera',
                'desayuno' => false,
            ],
        ];

        foreach ($reservas as $reserva) {
            Reserva::firstOrCreate(
                [
                    'usuario_id' => $reserva['usuario_id'],
                    'hospedaje_id' => $reserva['hospedaje_id'],
                    'fecha_entrada' => $reserva['fecha_entrada'],
                ],
                $reserva
            );
        }
    }
}
