<?php

namespace App\Http\Controllers;

use App\Models\Hospedaje;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $esAdministrador = $user instanceof User && $user->hasRole('administrador');
        $esCliente = $user instanceof User && $user->hasRole('cliente');

        $metricas = [];
        $reservasCliente = collect();
        $habitaciones = collect();

        if ($esAdministrador) {
            $now = now();

            $periodos = [
                'día' => [
                    'inicio' => $now->copy()->startOfDay(),
                    'fin' => $now->copy()->endOfDay(),
                ],
                'semana' => [
                    'inicio' => $now->copy()->startOfWeek(Carbon::MONDAY),
                    'fin' => $now->copy()->endOfWeek(Carbon::SUNDAY),
                ],
                'mes' => [
                    'inicio' => $now->copy()->startOfMonth(),
                    'fin' => $now->copy()->endOfMonth(),
                ],
            ];

            foreach ($periodos as $nombre => $rango) {
                $consultaBase = Reserva::query()
                    ->whereBetween('fecha_entrada', [$rango['inicio'], $rango['fin']]);

                $metricas[$nombre] = [
                    'reservas' => (clone $consultaBase)->count(),
                    'personas' => (clone $consultaBase)->distinct('usuario_id')->count('usuario_id'),
                    'parqueos' => (clone $consultaBase)->sum('espacios_de_parqueo'),
                ];
            }
        }

        if ($esCliente && $user instanceof User) {
            $reservasCliente = Reserva::query()
                ->with(['hospedaje', 'factura'])
                ->where('usuario_id', $user->id)
                ->orderByDesc('fecha_entrada')
                ->get();

            $habitaciones = Hospedaje::query()
                ->whereIn('tipo', ['habitacion', 'camping'])
                ->orderBy('tipo')
                ->orderBy('numeros')
                ->get();
        }

        return view('dashboard', compact('metricas', 'reservasCliente', 'habitaciones', 'esAdministrador', 'esCliente'));
    }
}
