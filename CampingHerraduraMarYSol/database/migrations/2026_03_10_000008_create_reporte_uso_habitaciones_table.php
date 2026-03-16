<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reporte_uso_habitaciones', function (Blueprint $table) {
            $table->id();
            $table->enum('periodo_tipo', ['dia', 'semana', 'mes']);
            $table->date('periodo_inicio');
            $table->date('periodo_fin');
            $table->foreignId('hospedaje_id')->constrained('hospedajes')->cascadeOnDelete();
            $table->unsignedInteger('total_reservas')->default(0);
            $table->unsignedInteger('total_noches')->default(0);
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['periodo_tipo', 'periodo_inicio', 'periodo_fin'], 'rep_hab_periodo_idx');
            $table->index(['hospedaje_id'], 'rep_hab_hospedaje_idx');
            $table->unique(['periodo_tipo', 'periodo_inicio', 'periodo_fin', 'hospedaje_id'], 'rep_hab_periodo_hosp_uq');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reporte_uso_habitaciones');
    }
};
