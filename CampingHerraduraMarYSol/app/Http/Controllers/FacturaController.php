<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Producto;
use App\Models\Reserva;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class FacturaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver facturas')->only(['index', 'show']);
        $this->middleware('permission:crear facturas')->only(['create', 'store']);
        $this->middleware('permission:editar facturas')->only(['edit', 'update']);
        $this->middleware('permission:borrar facturas')->only('destroy');
    }

    public function index()
    {
        $user = Auth::user();

        $query = Factura::with(['reserva.usuario', 'reserva.hospedaje'])
            ->orderByDesc('id');

        if ($user instanceof User && $user->hasRole('cliente')) {
            $query->whereHas('reserva', function ($reservaQuery) use ($user): void {
                $reservaQuery->where('usuario_id', $user->id);
            });
        }

        $facturas = $query->get();

        return view('facturas.index', compact('facturas'));
    }

    public function create()
    {
        $reservas = Reserva::with(['usuario', 'hospedaje'])
            ->whereDoesntHave('factura')
            ->orderByDesc('id')
            ->get();

        $productos = Producto::orderBy('marca')->orderBy('producto')->get();

        return view('facturas.create', compact('reservas', 'productos'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'reserva_id' => ['required', 'exists:reservas,id', 'unique:facturas,reserva_id'],
            'fecha_factura' => ['required', 'date'],
            'porcentaje_impuesto' => ['required', 'integer', 'min:1', 'max:15'],
            'productos' => ['required', 'array', 'min:1'],
            'productos.*' => ['required', 'exists:productos,id'],
            'cantidades' => ['required', 'array'],
            'cantidades.*' => ['nullable', 'integer', 'min:1'],
        ]);

        $reserva = Reserva::with('hospedaje')->findOrFail($data['reserva_id']);

        $this->validateFacturaDateAgainstReserva($data['fecha_factura'], $reserva);

        [$ventas, $subtotal] = $this->buildVentasAndSubtotal($data['productos'], $data['cantidades'], $reserva);

        $impuestoMonto = round($subtotal * ((int) $data['porcentaje_impuesto'] / 100), 2);
        $total = round($subtotal + $impuestoMonto, 2);

        $factura = Factura::create([
            'reserva_id' => $data['reserva_id'],
            'numero_factura' => $this->generateNumeroFactura(),
            'fecha_factura' => $data['fecha_factura'],
            'subtotal' => $subtotal,
            'impuesto' => $impuestoMonto,
            'total' => $total,
            'ventas' => $ventas,
            'reporte_productos' => $this->buildReporteProductos($ventas),
        ]);

        return redirect()->route('facturas.show', $factura)
            ->with('success', 'Factura creada correctamente.');
    }

    public function show(Factura $factura)
    {
        $factura->load(['reserva.usuario', 'reserva.hospedaje']);

        return view('facturas.show', compact('factura'));
    }

    public function edit(Factura $factura)
    {
        $factura->load(['reserva.usuario', 'reserva.hospedaje']);

        $reservas = Reserva::with(['usuario', 'hospedaje'])
            ->where(function ($query) use ($factura) {
                $query->whereDoesntHave('factura')
                    ->orWhere('id', $factura->reserva_id);
            })
            ->orderByDesc('id')
            ->get();

        $productos = Producto::orderBy('marca')->orderBy('producto')->get();

        $ventas = $factura->ventas ?? [];
        $selectedProductos = collect($ventas)
            ->filter(fn ($item) => isset($item['id']) && $item['id'] !== null)
            ->pluck('id')
            ->all();

        $cantidades = collect($ventas)
            ->filter(fn ($item) => isset($item['id']) && $item['id'] !== null)
            ->mapWithKeys(fn ($item) => [$item['id'] => $item['cantidad']])
            ->all();

        $porcentajeImpuesto = $factura->subtotal > 0
            ? (int) round(((float) $factura->impuesto / (float) $factura->subtotal) * 100)
            : 1;

        return view('facturas.edit', compact(
            'factura',
            'reservas',
            'productos',
            'selectedProductos',
            'cantidades',
            'porcentajeImpuesto'
        ));
    }

    public function update(Request $request, Factura $factura)
    {
        $data = $request->validate([
            'reserva_id' => [
                'required',
                'exists:reservas,id',
                Rule::unique('facturas', 'reserva_id')->ignore($factura->id),
            ],
            'fecha_factura' => ['required', 'date'],
            'porcentaje_impuesto' => ['required', 'integer', 'min:1', 'max:15'],
            'productos' => ['required', 'array', 'min:1'],
            'productos.*' => ['required', 'exists:productos,id'],
            'cantidades' => ['required', 'array'],
            'cantidades.*' => ['nullable', 'integer', 'min:1'],
        ]);

        $reserva = Reserva::with('hospedaje')->findOrFail($data['reserva_id']);

        $this->validateFacturaDateAgainstReserva($data['fecha_factura'], $reserva);

        [$ventas, $subtotal] = $this->buildVentasAndSubtotal($data['productos'], $data['cantidades'], $reserva);

        $impuestoMonto = round($subtotal * ((int) $data['porcentaje_impuesto'] / 100), 2);
        $total = round($subtotal + $impuestoMonto, 2);

        $factura->update([
            'reserva_id' => $data['reserva_id'],
            'fecha_factura' => $data['fecha_factura'],
            'subtotal' => $subtotal,
            'impuesto' => $impuestoMonto,
            'total' => $total,
            'ventas' => $ventas,
            'reporte_productos' => $this->buildReporteProductos($ventas),
        ]);

        return redirect()->route('facturas.show', $factura)
            ->with('success', 'Factura actualizada correctamente.');
    }

    public function destroy(Factura $factura)
    {
        $factura->delete();

        return redirect()->route('facturas.index')
            ->with('success', 'Factura eliminada correctamente.');
    }

    private function buildVentasAndSubtotal(array $productoIds, array $cantidades, Reserva $reserva): array
    {
        $productos = Producto::whereIn('id', $productoIds)->get()->keyBy('id');

        $ventas = [];
        $subtotal = 0;

        $precioReserva = round((float) ($reserva->precio ?? 0), 2);
        $subtotal += $precioReserva;

        $ventas[] = [
            'tipo' => 'reserva',
            'id' => null,
            'marca' => __('Reserva'),
            'producto' => __('Hospedaje #') . ($reserva->hospedaje?->numeros ?? $reserva->hospedaje_id),
            'tamano' => '',
            'precio_unitario' => $precioReserva,
            'cantidad' => 1,
            'subtotal_linea' => $precioReserva,
        ];

        foreach ($productoIds as $productoId) {
            $producto = $productos->get($productoId);

            if (! $producto) {
                continue;
            }

            $cantidad = (int) ($cantidades[$productoId] ?? 1);
            $cantidad = $cantidad > 0 ? $cantidad : 1;

            $subtotalLinea = round(((float) $producto->precio) * $cantidad, 2);
            $subtotal += $subtotalLinea;

            $ventas[] = [
                'id' => $producto->id,
                'marca' => $producto->marca,
                'producto' => $producto->producto,
                'tamano' => $producto->tamano,
                'precio_unitario' => (float) $producto->precio,
                'cantidad' => $cantidad,
                'subtotal_linea' => $subtotalLinea,
            ];
        }

        return [$ventas, round($subtotal, 2)];
    }

    private function buildReporteProductos(array $ventas): array
    {
        return array_map(function ($item) {
            $descripcion = trim(($item['marca'] ?? '') . ' ' . ($item['producto'] ?? '') . ' ' . ($item['tamano'] ?? ''));

            return [
                'producto' => $descripcion !== '' ? $descripcion : __('Detalle'),
                'cantidad' => $item['cantidad'] ?? 1,
                'subtotal' => $item['subtotal_linea'] ?? 0,
            ];
        }, $ventas);
    }

    private function validateFacturaDateAgainstReserva(string $fechaFactura, Reserva $reserva): void
    {
        $fechaEntrada = $reserva->fecha_entrada;

        if (! $fechaEntrada) {
            return;
        }

        $fechaFacturaCarbon = Carbon::parse($fechaFactura);
        $inicioDiaReserva = $fechaEntrada->copy()->startOfDay();

        if ($fechaFacturaCarbon->lt($inicioDiaReserva)) {
            throw ValidationException::withMessages([
                'fecha_factura' => __('La fecha de factura debe ser el mismo día de la reserva o posterior.'),
            ]);
        }
    }

    private function generateNumeroFactura(): string
    {
        return 'FAC-' . now()->format('Ymd-His') . '-' . random_int(100, 999);
    }
}
