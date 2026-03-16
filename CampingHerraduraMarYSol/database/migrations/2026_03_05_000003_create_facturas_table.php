<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reserva_id')->unique()->constrained('reservas')->onDelete('cascade');
            $table->string('numero_factura')->unique();
            $table->dateTime('fecha_factura')->nullable();
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('impuesto', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->json('ventas')->nullable(); // can store checked products, etc
            $table->json('reporte_productos')->nullable();
            $table->timestamps();

            $table->index(['fecha_factura']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
