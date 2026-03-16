<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reporte_ventas_productos', function (Blueprint $table) {
            $table->id();
            $table->enum('periodo_tipo', ['dia', 'semana', 'mes']);
            $table->date('periodo_inicio');
            $table->date('periodo_fin');
            $table->foreignId('producto_id')->constrained('productos')->cascadeOnDelete();
            $table->unsignedInteger('cantidad_vendida')->default(0);
            $table->decimal('monto_total', 12, 2)->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['periodo_tipo', 'periodo_inicio', 'periodo_fin'], 'rep_ven_periodo_idx');
            $table->index(['producto_id'], 'rep_ven_producto_idx');
            $table->unique(['periodo_tipo', 'periodo_inicio', 'periodo_fin', 'producto_id'], 'rep_ven_periodo_prod_uq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reporte_ventas_productos');
    }
};
