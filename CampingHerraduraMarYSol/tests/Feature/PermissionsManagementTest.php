<?php

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Spatie\Permission\Models\Permission;

beforeEach(function () {
    app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
    $this->seed(RolesAndPermissionsSeeder::class);
});

test('permissions index is visible to authorized user', function () {
    $user = User::factory()->create();
    $user->givePermissionTo('ver permisos');

    $this->actingAs($user)
        ->get(route('permissions.index'))
        ->assertOk();
});

test('cannot view permissions without permission', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get(route('permissions.index'))
        ->assertStatus(403);
});

test('can create a permission', function () {
    $user = User::factory()->create();
    $user->givePermissionTo(['ver permisos', 'crear permisos']);

    $this->actingAs($user)
        ->post(route('permissions.store'), ['name' => 'test permission'])
        ->assertRedirect(route('permissions.index'));

    $this->assertDatabaseHas('permissions', ['name' => 'test permission']);
});

test('can edit a permission', function () {
    $perm = Permission::create(['name' => 'foo']);
    $user = User::factory()->create();
    $user->givePermissionTo(['ver permisos', 'editar permisos']);

    $this->actingAs($user)
        ->get(route('permissions.edit', $perm))
        ->assertOk()
        ->assertSee('foo');

    $this->put(route('permissions.update', $perm), ['name' => 'bar'])
        ->assertRedirect(route('permissions.index'));

    expect($perm->refresh()->name)->toEqual('bar');
});

test('can delete a permission', function () {
    $perm = Permission::create(['name' => 'delete-me']);
    $user = User::factory()->create();
    $user->givePermissionTo(['ver permisos', 'borrar permisos']);

    $this->actingAs($user)
        ->delete(route('permissions.destroy', $perm))
        ->assertRedirect(route('permissions.index'));

    expect(Permission::find($perm->id))->toBeNull();
});
