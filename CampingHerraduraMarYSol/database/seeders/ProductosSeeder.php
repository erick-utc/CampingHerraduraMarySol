<?php

namespace Database\Seeders;

use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductosSeeder extends Seeder
{
    public function run(): void
    {
        $bebidas = [
            ['marca' => 'Coca-Cola', 'producto' => 'Coca-Cola', 'tamano' => '355ml', 'precio' => 1.50],
            ['marca' => 'Coca-Cola', 'producto' => 'Coca-Cola', 'tamano' => '500ml', 'precio' => 2.00],
            ['marca' => 'Sprite', 'producto' => 'Sprite', 'tamano' => '355ml', 'precio' => 1.50],
            ['marca' => 'Sprite', 'producto' => 'Sprite', 'tamano' => '500ml', 'precio' => 2.00],
            ['marca' => 'Fanta', 'producto' => 'Fanta Naranja', 'tamano' => '355ml', 'precio' => 1.50],
            ['marca' => 'Fanta', 'producto' => 'Fanta Uva', 'tamano' => '355ml', 'precio' => 1.50],
            ['marca' => 'Pepsi', 'producto' => 'Pepsi', 'tamano' => '355ml', 'precio' => 1.40],
            ['marca' => 'Pepsi', 'producto' => 'Pepsi', 'tamano' => '500ml', 'precio' => 1.90],
            ['marca' => '7UP', 'producto' => '7UP', 'tamano' => '355ml', 'precio' => 1.50],
            ['marca' => 'Mountain Dew', 'producto' => 'Mountain Dew', 'tamano' => '355ml', 'precio' => 1.60],
        ];

        foreach ($bebidas as $bebida) {
            Producto::firstOrCreate($bebida);
        }
    }
}
