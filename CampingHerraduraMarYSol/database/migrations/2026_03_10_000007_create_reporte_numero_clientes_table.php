<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reporte_numero_clientes', function (Blueprint $table) {
            $table->id();
            $table->enum('periodo_tipo', ['dia', 'semana', 'mes']);
            $table->date('periodo_inicio');
            $table->date('periodo_fin');
            $table->unsignedInteger('total_clientes')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['periodo_tipo', 'periodo_inicio', 'periodo_fin'], 'rep_cli_periodo_idx');
            $table->unique(['periodo_tipo', 'periodo_inicio', 'periodo_fin'], 'rep_cli_periodo_uq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reporte_numero_clientes');
    }
};
