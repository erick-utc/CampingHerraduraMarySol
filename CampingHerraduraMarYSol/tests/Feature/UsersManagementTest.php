<?php

use App\Livewire\Users\Index as UsersIndex;
use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Livewire\Livewire;

// ensure the permissions from the seeder exist in the test database
beforeEach(function () {
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('users page is displayed', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('ver usuarios');

    $this->actingAs($user);

    $this->get('/dashboard/users')->assertOk();
});

it('forbids access when permission is missing', function () {
    $user = User::factory()->create();

    $this->actingAs($user);

    $this->get('/dashboard/users')->assertStatus(403);
});

it('table shows user attributes', function () {
    $user = User::factory()->create([
        'name' => 'Jane Doe',
        'email' => 'jane@example.com',
    ]);
    $user->givePermissionTo(['ver usuarios']);

    $this->actingAs($user);

    Livewire::test(UsersIndex::class)
        ->assertSee('Jane Doe')
        ->assertSee('jane@example.com');
});

it('allows editing a user via controller', function () {
    $user = User::factory()->create(['name' => 'Old Name']);
    $editor = User::factory()->create();
    $editor->givePermissionTo(['ver usuarios', 'editar usuarios']);

    $this->actingAs($editor);

    $this->get(route('users.edit', $user))
        ->assertOk()
        ->assertSee('Old Name');

    $this->put(route('users.update', $user), [
        'name' => 'New Name',
        'email' => $user->email,
    ])
        ->assertRedirect(route('dashboard.users'));

    expect($user->refresh()->name)->toEqual('New Name');
});

it('allows deleting a user via controller', function () {
    $user = User::factory()->create();
    $deleter = User::factory()->create();
    $deleter->givePermissionTo(['ver usuarios', 'borrar usuarios']);

    $this->actingAs($deleter);

    $this->delete(route('users.destroy', $user))
        ->assertRedirect(route('dashboard.users'));

    expect(User::find($user->id))->toBeNull();
});
