<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Redirect;

class PermissionController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()->can('ver permisos'), 403);

        $permissions = Permission::all();

        return view('permissions.index', compact('permissions'));
    }

    public function create()
    {
        abort_unless(auth()->user()->can('crear permisos'), 403);

        return view('permissions.create');
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()->can('crear permisos'), 403);

        $data = $request->validate([
            'name' => 'required|string|unique:permissions,name',
        ]);

        Permission::create($data);

        return Redirect::route('permissions.index')
            ->with('status', 'Permiso creado correctamente.');
    }

    public function edit(Permission $permission)
    {
        abort_unless(auth()->user()->can('editar permisos'), 403);

        return view('permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        abort_unless(auth()->user()->can('editar permisos'), 403);

        $data = $request->validate([
            'name' => 'required|string|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update($data);

        return Redirect::route('permissions.index')
            ->with('status', 'Permiso actualizado correctamente.');
    }

    public function destroy(Permission $permission)
    {
        abort_unless(auth()->user()->can('borrar permisos'), 403);

        $permission->delete();

        return Redirect::route('permissions.index')
            ->with('status', 'Permiso eliminado correctamente.');
    }
}
