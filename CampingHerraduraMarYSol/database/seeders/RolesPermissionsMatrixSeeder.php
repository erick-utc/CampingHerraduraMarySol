<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesPermissionsMatrixSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $allPermissions = [
            'ver permisos', 'crear permisos', 'editar permisos', 'borrar permisos',
            'ver roles', 'crear roles', 'editar roles', 'borrar roles',
            'ver usuarios', 'crear usuarios', 'editar usuarios', 'borrar usuarios',
            'ver reservas', 'crear reservas', 'editar reservas', 'borrar reservas',
            'ver hospedajes', 'crear hospedajes', 'editar hospedajes', 'borrar hospedajes',
            'ver productos', 'crear productos', 'editar productos', 'borrar productos',
            'ver facturas', 'crear facturas', 'editar facturas', 'borrar facturas',
            'bitacora movimiento de usuario',
            'ver bitacora',
            'ver reportes',
        ];

        foreach ($allPermissions as $permissionName) {
            Permission::firstOrCreate(['name' => $permissionName]);
        }

        $administrador = Role::firstOrCreate(['name' => 'administrador']);
        $coordinador = Role::firstOrCreate(['name' => 'coordinador']);
        $cliente = Role::firstOrCreate(['name' => 'cliente']);

        $administrador->syncPermissions(Permission::all());

        $coordinadorPermissions = [
            'ver usuarios',
            'ver reservas',
            'crear reservas',
            'editar reservas',
            'ver facturas',
            'crear facturas',
            'editar facturas',
            'borrar facturas',
        ];

        $clientePermissions = [
            'ver usuarios',
            'ver reservas',
            'crear reservas',
        ];

        $coordinador->syncPermissions($coordinadorPermissions);
        $cliente->syncPermissions($clientePermissions);

        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
    }
}
