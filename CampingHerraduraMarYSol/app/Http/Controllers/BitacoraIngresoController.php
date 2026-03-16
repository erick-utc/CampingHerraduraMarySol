<?php

namespace App\Http\Controllers;

use App\Models\BitacoraIngreso;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BitacoraIngresoController extends Controller
{
    public function index(Request $request)
    {
        abort_unless(Auth::user()?->can('bitacora movimiento de usuario') ?? false, 403);

        $query = BitacoraIngreso::query()
            ->with('user')
            ->orderByDesc('ocurrio_en');

        if ($request->filled('fecha_desde')) {
            $query->whereDate('ocurrio_en', '>=', $request->string('fecha_desde')->toString());
        }

        if ($request->filled('fecha_hasta')) {
            $query->whereDate('ocurrio_en', '<=', $request->string('fecha_hasta')->toString());
        }

        if ($request->filled('evento')) {
            $query->where('evento', $request->string('evento')->toString());
        }

        if ($request->filled('usuario')) {
            $usuario = $request->string('usuario')->lower()->toString();
            $query->where(function ($subQuery) use ($usuario) {
                $subQuery->whereRaw('LOWER(nombre) like ?', ["%{$usuario}%"])
                    ->orWhereRaw('LOWER(email) like ?', ["%{$usuario}%"]);
            });
        }

        $ingresos = $query->paginate(50)->withQueryString();

        return view('bitacoras.ingresos.index', compact('ingresos'));
    }
}
