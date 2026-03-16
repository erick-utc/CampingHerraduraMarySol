<?php

namespace Database\Seeders;

use App\Models\Factura;
use App\Models\Producto;
use App\Models\Reserva;
use Illuminate\Database\Seeder;

class FacturasSeeder extends Seeder
{
    public function run(): void
    {
        $productos = Producto::orderBy('id')->get();

        if ($productos->count() < 3) {
            return;
        }

        $reservas = Reserva::orderBy('id')->get();

        foreach ($reservas as $reserva) {
            $selected = $productos->shuffle()->take(3)->values();

            $ventas = [];
            $subtotal = 0;

            foreach ($selected as $producto) {
                $cantidad = random_int(1, 3);
                $precioUnitario = (float) $producto->precio;
                $subtotalLinea = round($precioUnitario * $cantidad, 2);

                $subtotal += $subtotalLinea;

                $ventas[] = [
                    'id' => $producto->id,
                    'marca' => $producto->marca,
                    'producto' => $producto->producto,
                    'tamano' => $producto->tamano,
                    'precio_unitario' => $precioUnitario,
                    'cantidad' => $cantidad,
                    'subtotal_linea' => $subtotalLinea,
                ];
            }

            $subtotal = round($subtotal, 2);
            $porcentajeImpuesto = random_int(1, 15);
            $impuesto = round($subtotal * ($porcentajeImpuesto / 100), 2);
            $total = round($subtotal + $impuesto, 2);

            Factura::updateOrCreate(
                ['reserva_id' => $reserva->id],
                [
                    'numero_factura' => $this->buildNumeroFactura($reserva->id),
                    'fecha_factura' => now()->subDays(random_int(0, 10)),
                    'subtotal' => $subtotal,
                    'impuesto' => $impuesto,
                    'total' => $total,
                    'ventas' => $ventas,
                    'reporte_productos' => array_map(static fn (array $item) => [
                        'producto' => $item['marca'] . ' ' . $item['producto'] . ' ' . $item['tamano'],
                        'cantidad' => $item['cantidad'],
                        'subtotal' => $item['subtotal_linea'],
                    ], $ventas),
                ]
            );
        }
    }

    private function buildNumeroFactura(int $reservaId): string
    {
        return 'FAC-R' . str_pad((string) $reservaId, 4, '0', STR_PAD_LEFT);
    }
}
