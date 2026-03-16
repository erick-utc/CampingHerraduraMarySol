<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersReservasSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'cedula' => '117650001',
                'name' => 'Carlos Perez',
                'nombre' => 'Carlos',
                'primerApellido' => 'Pérez',
                'segundoApellido' => 'Ramírez',
                'telefono' => '60010001',
                'email' => 'carlos.perez@camping.local',
            ],
            [
                'cedula' => '117650002',
                'name' => 'Ana Mora',
                'nombre' => 'Ana',
                'primerApellido' => 'Mora',
                'segundoApellido' => 'Solano',
                'telefono' => '60010002',
                'email' => 'ana.mora@camping.local',
            ],
            [
                'cedula' => '117650003',
                'name' => 'Luis Gomez',
                'nombre' => 'Luis',
                'primerApellido' => 'Gómez',
                'segundoApellido' => 'Castro',
                'telefono' => '60010003',
                'email' => 'luis.gomez@camping.local',
            ],
        ];

        foreach ($users as $user) {
            User::firstOrCreate(
                ['cedula' => $user['cedula']],
                [
                    ...$user,
                    'password' => bcrypt('password'),
                ]
            );
        }
    }
}
