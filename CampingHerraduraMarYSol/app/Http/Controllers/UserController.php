<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class UserController extends Controller
{
    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        // permission is already enforced by route middleware, but double-
        // check to be defensive.
        abort_unless(auth()->user()->can('editar usuarios'), 403);

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
        abort_unless(auth()->user()->can('editar usuarios'), 403);

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
                $rules[$column] = 'required|email|unique:users,email,' . $user->id;
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
        abort_unless(auth()->user()->can('borrar usuarios'), 403);

        $user->delete();

        return Redirect::route('dashboard.users')
            ->with('status', 'Usuario eliminado correctamente.');
    }
}
