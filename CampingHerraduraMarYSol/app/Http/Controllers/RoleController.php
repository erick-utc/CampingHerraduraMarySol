<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Redirect;

class RoleController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->can('ver roles'), 403);

        $roles = Role::all();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('crear roles'), 403);

        $permissions = Permission::all();

        return view('roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('crear roles'), 403);

        $data = $request->validate([
            'name' => 'required|string|unique:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role = Role::create(['name' => $data['name']]);
        if (! empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        return Redirect::route('roles.index')
            ->with('status', 'Rol creado correctamente.');
    }

    public function edit(Role $role)
    {
        abort_unless(auth()->user()->can('editar roles'), 403);

        $permissions = Permission::all();

        return view('roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        abort_unless(auth()->user()->can('editar roles'), 403);

        $data = $request->validate([
            'name' => 'required|string|unique:roles,name,' . $role->id,
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,name',
        ]);

        $role->update(['name' => $data['name']]);
        $role->syncPermissions($data['permissions'] ?? []);

        return Redirect::route('roles.index')
            ->with('status', 'Rol actualizado correctamente.');
    }

    public function destroy(Role $role)
    {
        abort_unless(auth()->user()->can('borrar roles'), 403);

        $role->delete();

        return Redirect::route('roles.index')
            ->with('status', 'Rol eliminado correctamente.');
    }
}
