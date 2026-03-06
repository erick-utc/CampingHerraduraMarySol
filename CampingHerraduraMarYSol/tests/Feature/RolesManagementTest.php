<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    $this->seed(RolesAndPermissionsSeeder::class);
});

it('role index is visible to authorized user', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('ver roles');

    $this->actingAs($user)
        ->get(route('roles.index'))
        ->assertOk();
});

it('cannot view roles without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('roles.index'))
        ->assertStatus(403);
});

it('can create a role with permissions', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['ver roles', 'crear roles']);
    $perm = Permission::create(['name' => 'foo']);

    $this->actingAs($user)
        ->post(route('roles.store'), [
            'name' => 'new-role',
            'permissions' => ['foo'],
        ])
        ->assertRedirect(route('roles.index'));

    $this->assertDatabaseHas('roles', ['name' => 'new-role']);
    expect(Role::where('name', 'new-role')->first()->hasPermissionTo('foo'))->toBeTrue();
});

it('can edit a role and sync permissions', function () {
    $perm1 = Permission::create(['name' => 'a']);
    $perm2 = Permission::create(['name' => 'b']);
    $role = Role::create(['name' => 'role1']);
    $role->syncPermissions(['a']);

    $user = User::factory()->create();
    $user->givePermissionTo(['ver roles', 'editar roles']);

    $this->actingAs($user)
        ->get(route('roles.edit', $role))
        ->assertOk()
        ->assertSee('role1');

    $this->put(route('roles.update', $role), [
        'name' => 'role1-updated',
        'permissions' => ['b'],
    ])
        ->assertRedirect(route('roles.index'));

    expect($role->refresh()->name)->toEqual('role1-updated');
    expect($role->hasPermissionTo('b'))->toBeTrue();
});

it('can delete a role', function () {
    $role = Role::create(['name' => 'deleteme']);
    $user = User::factory()->create();
    $user->givePermissionTo(['ver roles', 'borrar roles']);

    $this->actingAs($user)
        ->delete(route('roles.destroy', $role))
        ->assertRedirect(route('roles.index'));

    expect(Role::find($role->id))->toBeNull();
});
