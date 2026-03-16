<?php

namespace App\Http\Controllers;

use App\Models\Reserva;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
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

        $metricas = [];

        foreach ($periodos as $nombre => $rango) {
            $consultaBase = Reserva::query()
                ->whereBetween('fecha_entrada', [$rango['inicio'], $rango['fin']]);

            $metricas[$nombre] = [
                'reservas' => (clone $consultaBase)->count(),
                'personas' => (clone $consultaBase)->distinct('usuario_id')->count('usuario_id'),
                'parqueos' => (clone $consultaBase)->sum('espacios_de_parqueo'),
            ];
        }

        return view('dashboard', compact('metricas'));
    }
}
