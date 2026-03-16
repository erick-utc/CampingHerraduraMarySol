<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Hospedaje;
use App\Models\ReporteNumeroCliente;
use App\Models\ReporteUsoCamping;
use App\Models\ReporteUsoHabitacion;
use App\Models\ReporteUsoParqueo;
use App\Models\ReporteVentasProducto;
use App\Models\Reserva;
use Carbon\CarbonImmutable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReporteController extends Controller
{
    public function clientes(Request $request)
    {
        $this->authorizeReportes();
        $this->refreshClientes();

        $registros = ReporteNumeroCliente::query()
            ->when($request->filled('periodo_tipo'), fn ($q) => $q->where('periodo_tipo', $request->string('periodo_tipo')->toString()))
            ->orderByDesc('periodo_inicio')
            ->paginate(50)
            ->withQueryString();

        return view('reportes.clientes', compact('registros'));
    }

    public function habitaciones(Request $request)
    {
        $this->authorizeReportes();
        $this->refreshHabitaciones();

        $registros = ReporteUsoHabitacion::query()
            ->with('hospedaje')
            ->when($request->filled('periodo_tipo'), fn ($q) => $q->where('periodo_tipo', $request->string('periodo_tipo')->toString()))
            ->orderByDesc('periodo_inicio')
            ->paginate(50)
            ->withQueryString();

        return view('reportes.habitaciones', compact('registros'));
    }

    public function camping(Request $request)
    {
        $this->authorizeReportes();
        $this->refreshCamping();

        $registros = ReporteUsoCamping::query()
            ->when($request->filled('periodo_tipo'), fn ($q) => $q->where('periodo_tipo', $request->string('periodo_tipo')->toString()))
            ->orderByDesc('periodo_inicio')
            ->paginate(50)
            ->withQueryString();

        return view('reportes.camping', compact('registros'));
    }

    public function ventasProductos(Request $request)
    {
        $this->authorizeReportes();
        $this->refreshVentasProductos();

        $registros = ReporteVentasProducto::query()
            ->with('producto')
            ->when($request->filled('periodo_tipo'), fn ($q) => $q->where('periodo_tipo', $request->string('periodo_tipo')->toString()))
            ->orderByDesc('periodo_inicio')
            ->paginate(50)
            ->withQueryString();

        return view('reportes.ventas_productos', compact('registros'));
    }

    public function parqueo(Request $request)
    {
        $this->authorizeReportes();
        $this->refreshParqueo();

        $registros = ReporteUsoParqueo::query()
            ->when($request->filled('periodo_tipo'), fn ($q) => $q->where('periodo_tipo', $request->string('periodo_tipo')->toString()))
            ->orderByDesc('periodo_inicio')
            ->paginate(50)
            ->withQueryString();

        return view('reportes.parqueo', compact('registros'));
    }

    private function authorizeReportes(): void
    {
        abort_unless(Auth::user()?->can('ver reportes') ?? false, 403);
    }

    private function refreshClientes(): void
    {
        foreach ($this->periodos() as $tipo => $range) {
            $totalClientes = Reserva::query()
                ->whereBetween('fecha_entrada', [$range['inicio'], $range['fin']])
                ->distinct('usuario_id')
                ->count('usuario_id');

            ReporteNumeroCliente::updateOrCreate(
                ['periodo_tipo' => $tipo, 'periodo_inicio' => $range['inicio']->toDateString(), 'periodo_fin' => $range['fin']->toDateString()],
                ['total_clientes' => $totalClientes, 'metadata' => null]
            );
        }
    }

    private function refreshHabitaciones(): void
    {
        $habitacionesIds = Hospedaje::query()
            ->where('tipo', 'habitacion')
            ->pluck('id');

        foreach ($this->periodos() as $tipo => $range) {
            foreach ($habitacionesIds as $hospedajeId) {
                $reservas = Reserva::query()
                    ->where('hospedaje_id', $hospedajeId)
                    ->whereBetween('fecha_entrada', [$range['inicio'], $range['fin']])
                    ->get();

                $totalReservas = $reservas->count();
                $totalNoches = $reservas->sum(function ($reserva) {
                    $entrada = CarbonImmutable::parse($reserva->fecha_entrada);
                    $salida = CarbonImmutable::parse($reserva->fecha_salida);
                    return max(1, $entrada->diffInDays($salida));
                });

                ReporteUsoHabitacion::updateOrCreate(
                    [
                        'periodo_tipo' => $tipo,
                        'periodo_inicio' => $range['inicio']->toDateString(),
                        'periodo_fin' => $range['fin']->toDateString(),
                        'hospedaje_id' => $hospedajeId,
                    ],
                    [
                        'total_reservas' => $totalReservas,
                        'total_noches' => $totalNoches,
                        'metadata' => null,
                    ]
                );
            }
        }
    }

    private function refreshCamping(): void
    {
        $campingIds = Hospedaje::query()
            ->where('tipo', 'camping')
            ->pluck('id');

        foreach ($this->periodos() as $tipo => $range) {
            $reservas = Reserva::query()
                ->whereIn('hospedaje_id', $campingIds)
                ->whereBetween('fecha_entrada', [$range['inicio'], $range['fin']])
                ->get();

            $totalReservas = $reservas->count();
            $totalNoches = $reservas->sum(function ($reserva) {
                $entrada = CarbonImmutable::parse($reserva->fecha_entrada);
                $salida = CarbonImmutable::parse($reserva->fecha_salida);
                return max(1, $entrada->diffInDays($salida));
            });

            ReporteUsoCamping::updateOrCreate(
                ['periodo_tipo' => $tipo, 'periodo_inicio' => $range['inicio']->toDateString(), 'periodo_fin' => $range['fin']->toDateString()],
                ['total_reservas_camping' => $totalReservas, 'total_noches_camping' => $totalNoches, 'metadata' => null]
            );
        }
    }

    private function refreshVentasProductos(): void
    {
        foreach ($this->periodos() as $tipo => $range) {
            $facturas = Factura::query()
                ->whereBetween('fecha_factura', [$range['inicio'], $range['fin']])
                ->get();

            $agregado = [];

            foreach ($facturas as $factura) {
                $ventas = $factura->ventas ?? [];
                foreach ($ventas as $item) {
                    $productoId = (int) ($item['id'] ?? 0);
                    if ($productoId <= 0) {
                        continue;
                    }

                    if (! isset($agregado[$productoId])) {
                        $agregado[$productoId] = ['cantidad' => 0, 'monto' => 0.0];
                    }

                    $agregado[$productoId]['cantidad'] += (int) ($item['cantidad'] ?? 0);
                    $agregado[$productoId]['monto'] += (float) ($item['subtotal_linea'] ?? 0);
                }
            }

            foreach ($agregado as $productoId => $values) {
                ReporteVentasProducto::updateOrCreate(
                    [
                        'periodo_tipo' => $tipo,
                        'periodo_inicio' => $range['inicio']->toDateString(),
                        'periodo_fin' => $range['fin']->toDateString(),
                        'producto_id' => $productoId,
                    ],
                    [
                        'cantidad_vendida' => $values['cantidad'],
                        'monto_total' => round($values['monto'], 2),
                        'metadata' => null,
                    ]
                );
            }
        }
    }

    private function refreshParqueo(): void
    {
        foreach ($this->periodos() as $tipo => $range) {
            $reservas = Reserva::query()
                ->whereBetween('fecha_entrada', [$range['inicio'], $range['fin']])
                ->where('espacios_de_parqueo', '>', 0)
                ->get();

            ReporteUsoParqueo::updateOrCreate(
                ['periodo_tipo' => $tipo, 'periodo_inicio' => $range['inicio']->toDateString(), 'periodo_fin' => $range['fin']->toDateString()],
                [
                    'total_reservas_con_parqueo' => $reservas->count(),
                    'total_espacios_parqueo' => $reservas->sum('espacios_de_parqueo'),
                    'metadata' => null,
                ]
            );
        }
    }

    private function periodos(): array
    {
        $now = CarbonImmutable::now();

        return [
            'dia' => [
                'inicio' => $now->startOfDay(),
                'fin' => $now->endOfDay(),
            ],
            'semana' => [
                'inicio' => $now->startOfWeek(),
                'fin' => $now->endOfWeek(),
            ],
            'mes' => [
                'inicio' => $now->startOfMonth(),
                'fin' => $now->endOfMonth(),
            ],
        ];
    }
}
