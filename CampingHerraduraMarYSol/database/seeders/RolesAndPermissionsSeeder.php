<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        // Clear cache at the start
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Reset cached roles and permissions
        $permissions = [
        'ver usuarios', 'crear usuarios', 'editar usuarios', 'borrar usuarios',
        'ver roles', 'crear roles', 'editar roles', 'borrar roles',
        'ver permisos', 'crear permisos', 'editar permisos', 'borrar permisos',
    ];

    foreach ($permissions as $permission) {
        Permission::firstOrCreate(['name' => $permission]);
    }

    $role = Role::firstOrCreate(['name' => 'administrador']);
    $role->syncPermissions(Permission::all()); // syncPermissions is safer than givePermissionTo during seeding
    }
}
