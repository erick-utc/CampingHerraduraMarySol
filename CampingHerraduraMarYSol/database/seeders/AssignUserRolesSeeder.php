<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AssignUserRolesSeeder extends Seeder
{
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'administrador']);
        $coordinadorRole = Role::firstOrCreate(['name' => 'coordinador']);
        $clienteRole = Role::firstOrCreate(['name' => 'cliente']);

        $coordinador = User::firstOrCreate(
            ['cedula' => '117650010'],
            [
                'name' => 'Usuario Coordinador',
                'nombre' => 'Coordinador',
                'primerApellido' => 'General',
                'segundoApellido' => 'Camping',
                'telefono' => '60010010',
                'email' => 'coordinador@camping.local',
                'password' => bcrypt('password'),
            ]
        );

        $coordinador->syncRoles([$coordinadorRole->name]);

        User::query()
            ->where('id', '!=', $coordinador->id)
            ->get()
            ->each(function (User $user) use ($adminRole, $clienteRole): void {
                if ($user->hasRole($adminRole->name)) {
                    return;
                }

                $user->syncRoles([$clienteRole->name]);
            });
    }
}
