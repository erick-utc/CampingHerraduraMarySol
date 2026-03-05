<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\RolesAndPermissionsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
        ]);

        // 2. Create the User
    $user = User::factory()->create([
        'name' => 'Test User',
        'nombre'=> 'Administrador',
        'primerApellido' => 'Herradura',
        'segundoApellido' => 'Mary Sol',
        'cedula' => '123456789',
        'telefono' => '1234567890',
        'email' => 'admin@admin.com',
        'password' => bcrypt('password'), // Always hash passwords in seeders
    ]);

        $user->assignRole('administrador');
    }
}
