<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacora_movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre')->nullable();
            $table->string('email')->nullable();
            $table->string('modulo', 100)->nullable();
            $table->string('accion', 100);
            $table->string('entidad', 100)->nullable();
            $table->unsignedBigInteger('entidad_id')->nullable();
            $table->text('descripcion')->nullable();
            $table->json('metadata')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('ocurrio_en')->useCurrent();
            $table->timestamps();

            $table->index(['user_id', 'ocurrio_en']);
            $table->index(['modulo', 'accion']);
            $table->index(['entidad', 'entidad_id']);
            $table->index(['ocurrio_en']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_movimientos');
    }
};
