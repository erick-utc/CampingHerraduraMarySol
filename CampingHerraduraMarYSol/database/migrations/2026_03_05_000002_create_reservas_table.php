<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('hospedaje_id')->constrained('hospedajes')->onDelete('cascade');
            $table->decimal('precio', 10, 2);
            $table->dateTime('fecha_entrada');
            $table->dateTime('fecha_salida');
            $table->integer('espacios_de_parqueo')->default(0);
            $table->string('estado'); // creado/en espera/aprobado/cancelado
            $table->boolean('desayuno')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservas');
    }
};
