<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        abort_unless(Auth::user()?->can('crear usuarios') ?? false, 403);

        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()?->can('crear usuarios') ?? false, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nombre' => ['required', 'string', 'max:255'],
            'primerApellido' => ['required', 'string', 'max:255'],
            'segundoApellido' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'string', 'max:255', 'unique:users,cedula'],
            'telefono' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'confirmed', 'min:8'],
        ]);

        $user = User::create($data);

        // Every newly created user starts as cliente by default.
        Role::findOrCreate('cliente');
        $user->syncRoles(['cliente']);

        return Redirect::route('dashboard.users')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function editRoles(User $user)
    {
        abort_unless(Auth::user()?->can('editar usuarios') ?? false, 403);

        $roles = Role::orderBy('name')->get();
        $userRoles = $user->roles->pluck('name')->toArray();

        return view('users.roles', compact('user', 'roles', 'userRoles'));
    }

    public function updateRoles(Request $request, User $user)
    {
        abort_unless(Auth::user()?->can('editar usuarios') ?? false, 403);

        $data = $request->validate([
            'roles' => ['nullable', 'array'],
            'roles.*' => ['string', 'exists:roles,name'],
        ]);

        $user->syncRoles($data['roles'] ?? []);

        return Redirect::route('dashboard.users')
            ->with('status', 'Roles del usuario actualizados correctamente.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // permission is already enforced by route middleware, but double-
        // check to be defensive.
        abort_unless(Auth::user()?->can('editar usuarios') ?? false, 403);

        // we simply reuse the same column list the component uses so the
        // controller stays in sync when the schema changes.
        $columns = collect(\Illuminate\Support\Facades\Schema::getColumnListing('users'))
            ->reject(fn ($column) => in_array($column, [
                'id',
                'password',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'remember_token',
                'email_verified_at',
                'two_factor_confirmed_at',
                'created_at',
                'updated_at',
            ]))
            ->toArray();

        return view('users.edit', compact('user', 'columns'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        abort_unless(Auth::user()?->can('editar usuarios') ?? false, 403);

        $columns = collect(\Illuminate\Support\Facades\Schema::getColumnListing('users'))
            ->reject(fn ($column) => in_array($column, [
                'id',
                'password',
                'two_factor_secret',
                'two_factor_recovery_codes',
                'remember_token',
                'email_verified_at',
                'two_factor_confirmed_at',
                'created_at',
                'updated_at',
            ]))
            ->toArray();

        $rules = [];
        foreach ($columns as $column) {
            if ($column === 'email') {
                $rules[$column] = 'required|email:rfc,dns|unique:users,email,' . $user->id;
            } else {
                $rules[$column] = 'nullable';
            }
        }

        $data = $request->validate($rules);

        $user->fill($data);
        $user->save();

        return Redirect::route('dashboard.users')
            ->with('status', 'Usuario actualizado correctamente.');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        abort_unless(Auth::user()?->can('borrar usuarios') ?? false, 403);

        $user->delete();

        return Redirect::route('dashboard.users')
            ->with('status', 'Usuario eliminado correctamente.');
    }
}
