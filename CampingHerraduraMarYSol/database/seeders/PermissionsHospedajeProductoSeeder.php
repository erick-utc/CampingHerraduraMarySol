<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionsHospedajeProductoSeeder extends Seeder
{
    public function run(): void
    {
        // ensure cache is clear so roles see new permissions immediately
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $entities = ['hospedajes', 'productos'];
        $actions = ['ver', 'crear', 'editar', 'borrar'];

        $created = [];
        foreach ($entities as $entity) {
            foreach ($actions as $action) {
                $permission = Permission::firstOrCreate([
                    'name' => "$action $entity"
                ]);
                $created[] = $permission;
            }
        }

        // sync all permissions (including ones just created) with the administrador role
        $role = \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'administrador']);
        $role->syncPermissions(Permission::all());
    }
}
