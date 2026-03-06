<?php

use App\Livewire\Users\Index as UsersIndex;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // user management page, integrated with the dashboard layout
    Route::livewire('dashboard/users', UsersIndex::class)
        ->name('dashboard.users');

    // controller routes for editing/deleting users
    Route::get('dashboard/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])
        ->name('users.edit')
        ->middleware('can:editar usuarios');

    Route::put('dashboard/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])
        ->name('users.update')
        ->middleware('can:editar usuarios');

    Route::delete('dashboard/users/{user}', [\App\Http\Controllers\UserController::class, 'destroy'])
        ->name('users.destroy')
        ->middleware('can:borrar usuarios');

    // permissions maintenance
    Route::get('dashboard/permissions', [\App\Http\Controllers\PermissionController::class, 'index'])
        ->name('permissions.index');

    Route::get('dashboard/permissions/create', [\App\Http\Controllers\PermissionController::class, 'create'])
        ->name('permissions.create')
        ->middleware('can:crear permisos');

    Route::post('dashboard/permissions', [\App\Http\Controllers\PermissionController::class, 'store'])
        ->name('permissions.store')
        ->middleware('can:crear permisos');

    Route::get('dashboard/permissions/{permission}/edit', [\App\Http\Controllers\PermissionController::class, 'edit'])
        ->name('permissions.edit')
        ->middleware('can:editar permisos');

    Route::put('dashboard/permissions/{permission}', [\App\Http\Controllers\PermissionController::class, 'update'])
        ->name('permissions.update')
        ->middleware('can:editar permisos');

    Route::delete('dashboard/permissions/{permission}', [\App\Http\Controllers\PermissionController::class, 'destroy'])
        ->name('permissions.destroy')
        ->middleware('can:borrar permisos');

    // roles maintenance
    Route::get('dashboard/roles', [\App\Http\Controllers\RoleController::class, 'index'])
        ->name('roles.index');

    Route::get('dashboard/roles/create', [\App\Http\Controllers\RoleController::class, 'create'])
        ->name('roles.create')
        ->middleware('can:crear roles');

    Route::post('dashboard/roles', [\App\Http\Controllers\RoleController::class, 'store'])
        ->name('roles.store')
        ->middleware('can:crear roles');

    Route::get('dashboard/roles/{role}/edit', [\App\Http\Controllers\RoleController::class, 'edit'])
        ->name('roles.edit')
        ->middleware('can:editar roles');

    Route::put('dashboard/roles/{role}', [\App\Http\Controllers\RoleController::class, 'update'])
        ->name('roles.update')
        ->middleware('can:editar roles');

    Route::delete('dashboard/roles/{role}', [\App\Http\Controllers\RoleController::class, 'destroy'])
        ->name('roles.destroy')
        ->middleware('can:borrar roles');
});

require __DIR__.'/settings.php';
