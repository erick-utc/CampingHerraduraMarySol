<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\Producto;
use App\Models\Reserva;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
        $facturas = Factura::with(['reserva.usuario', 'reserva.hospedaje'])
            ->orderByDesc('id')
            ->get();

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

        [$ventas, $subtotal] = $this->buildVentasAndSubtotal($data['productos'], $data['cantidades']);

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
            'reporte_productos' => array_map(fn ($item) => [
                'producto' => $item['marca'] . ' ' . $item['producto'] . ' ' . $item['tamano'],
                'cantidad' => $item['cantidad'],
                'subtotal' => $item['subtotal_linea'],
            ], $ventas),
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
        $selectedProductos = collect($ventas)->pluck('id')->all();
        $cantidades = collect($ventas)->mapWithKeys(fn ($item) => [$item['id'] => $item['cantidad']])->all();

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

        [$ventas, $subtotal] = $this->buildVentasAndSubtotal($data['productos'], $data['cantidades']);

        $impuestoMonto = round($subtotal * ((int) $data['porcentaje_impuesto'] / 100), 2);
        $total = round($subtotal + $impuestoMonto, 2);

        $factura->update([
            'reserva_id' => $data['reserva_id'],
            'fecha_factura' => $data['fecha_factura'],
            'subtotal' => $subtotal,
            'impuesto' => $impuestoMonto,
            'total' => $total,
            'ventas' => $ventas,
            'reporte_productos' => array_map(fn ($item) => [
                'producto' => $item['marca'] . ' ' . $item['producto'] . ' ' . $item['tamano'],
                'cantidad' => $item['cantidad'],
                'subtotal' => $item['subtotal_linea'],
            ], $ventas),
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

    private function buildVentasAndSubtotal(array $productoIds, array $cantidades): array
    {
        $productos = Producto::whereIn('id', $productoIds)->get()->keyBy('id');

        $ventas = [];
        $subtotal = 0;

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

    private function generateNumeroFactura(): string
    {
        return 'FAC-' . now()->format('Ymd-His') . '-' . random_int(100, 999);
    }
}
