<?php

namespace App\Actions\Fortify;

use App\Concerns\PasswordValidationRules;
use App\Concerns\ProfileValidationRules;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Spatie\Permission\Models\Role;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules, ProfileValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    public function create(array $input): User
    {
        Validator::make($input, [
            ...$this->profileRules(),
            'nombre' => ['required', 'string', 'max:255'],
            'primerApellido' => ['required', 'string', 'max:255'],
            'segundoApellido' => ['required', 'string', 'max:255'],
            'cedula' => ['required', 'string', 'max:50', 'unique:users,cedula'],
            'telefono' => ['nullable', 'string', 'max:50'],
            'password' => $this->passwordRules(),
        ])->validate();

        $user = User::create([
            'name' => $input['name'],
            'nombre' => $input['nombre'],
            'primerApellido' => $input['primerApellido'],
            'segundoApellido' => $input['segundoApellido'],
            'cedula' => $input['cedula'],
            'telefono' => $input['telefono'] ?? null,
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        Role::firstOrCreate(['name' => 'cliente']);
        $user->syncRoles(['cliente']);

        return $user;
    }
}
