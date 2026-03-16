<?php

use App\Livewire\Users\Index as UsersIndex;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\BitacoraIngresoController;
use App\Http\Controllers\BitacoraMovimientoController;
use App\Http\Controllers\ReporteController;
use App\Models\Hospedaje;

Route::get('/', function () {
    $habitaciones = Hospedaje::query()
        ->whereIn('tipo', ['habitacion', 'camping'])
        ->orderBy('tipo')
        ->orderBy('numeros')
        ->get();

    return view('welcome', compact('habitaciones'));
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // user management page, integrated with the dashboard layout
    Route::livewire('dashboard/users', UsersIndex::class)
        ->name('dashboard.users');

    Route::get('dashboard/users/create', [\App\Http\Controllers\UserController::class, 'create'])
        ->name('users.create')
        ->middleware('can:crear usuarios');

    Route::post('dashboard/users', [\App\Http\Controllers\UserController::class, 'store'])
        ->name('users.store')
        ->middleware('can:crear usuarios');

    // controller routes for editing/deleting users
    Route::get('dashboard/users/{user}/edit', [\App\Http\Controllers\UserController::class, 'edit'])
        ->name('users.edit')
        ->middleware('can:editar usuarios');

    Route::put('dashboard/users/{user}', [\App\Http\Controllers\UserController::class, 'update'])
        ->name('users.update')
        ->middleware('can:editar usuarios');

    Route::get('dashboard/users/{user}/roles', [\App\Http\Controllers\UserController::class, 'editRoles'])
        ->name('users.roles.edit')
        ->middleware('can:editar usuarios');

    Route::put('dashboard/users/{user}/roles', [\App\Http\Controllers\UserController::class, 'updateRoles'])
        ->name('users.roles.update')
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

    // hospedajes, productos & reservas maintenance
    Route::resource('hospedajes', \App\Http\Controllers\HospedajeController::class);
    Route::resource('productos', \App\Http\Controllers\ProductoController::class);
    Route::resource('reservas', \App\Http\Controllers\ReservaController::class)->except('show');
    Route::resource('facturas', FacturaController::class);

    Route::get('bitacoras/ingresos', [BitacoraIngresoController::class, 'index'])
        ->name('bitacoras.ingresos.index')
        ->middleware('can:bitacora movimiento de usuario');

    Route::get('bitacoras/movimientos', [BitacoraMovimientoController::class, 'index'])
        ->name('bitacoras.movimientos.index')
        ->middleware('can:ver bitacora');

    Route::prefix('reportes')->name('reportes.')->middleware('can:ver reportes')->group(function () {
        Route::get('clientes', [ReporteController::class, 'clientes'])->name('clientes');
        Route::get('habitaciones', [ReporteController::class, 'habitaciones'])->name('habitaciones');
        Route::get('camping', [ReporteController::class, 'camping'])->name('camping');
        Route::get('ventas-productos', [ReporteController::class, 'ventasProductos'])->name('ventas-productos');
        Route::get('parqueo', [ReporteController::class, 'parqueo'])->name('parqueo');
    });
    
    // DEBUG: Temporary route to check permissions
    Route::get('debug/permissions', function () {
        $user = \Illuminate\Support\Facades\Auth::user();

        if (! $user) {
            return response()->json(['error' => 'No autenticado'], 401);
        }

        return response()->json([
            'user' => $user->email,
            'roles' => $user->getRoleNames(),
            'permissions' => $user->getPermissionNames(),
            'can_editar_hospedajes' => $user->can('editar hospedajes'),
            'can_borrar_hospedajes' => $user->can('borrar hospedajes'),
            'can_editar_productos' => $user->can('editar productos'),
            'can_borrar_productos' => $user->can('borrar productos'),
        ]);
    });
});

require __DIR__.'/settings.php';
