<?php

namespace App\Http\Controllers;

use App\Models\BitacoraMovimiento;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BitacoraMovimientoController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::user()?->can('ver bitacora') ?? false, 403);

        $query = BitacoraMovimiento::query()
            ->with('user')
            ->orderByDesc('ocurrio_en');

        if ($request->filled('fecha_desde')) {
            $query->whereDate('ocurrio_en', '>=', $request->string('fecha_desde')->toString());
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('ocurrio_en', '<=', $request->string('fecha_hasta')->toString());
        }

        if ($request->filled('modulo')) {
            $modulo = $request->string('modulo')->lower()->toString();
            $query->whereRaw('LOWER(modulo) like ?', ["%{$modulo}%"]);
        }

        if ($request->filled('accion')) {
            $query->where('accion', strtoupper($request->string('accion')->toString()));
        }

        if ($request->filled('usuario')) {
            $usuario = $request->string('usuario')->lower()->toString();
            $query->where(function ($subQuery) use ($usuario) {
                $subQuery->whereRaw('LOWER(nombre) like ?', ["%{$usuario}%"])
                    ->orWhereRaw('LOWER(email) like ?', ["%{$usuario}%"]);
            });
        }

        $movimientos = $query->paginate(50)->withQueryString();

        return view('bitacoras.movimientos.index', compact('movimientos'));
    }
}
