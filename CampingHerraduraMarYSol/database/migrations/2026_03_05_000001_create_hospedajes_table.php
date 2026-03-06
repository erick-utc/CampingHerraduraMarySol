<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hospedajes', function (Blueprint $table) {
            $table->id();
            $table->string('numeros');
            $table->string('tipo');
            $table->boolean('aire_acondicionado')->default(false);
            $table->boolean('familiar')->default(false);
            $table->boolean('parejas')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hospedajes');
    }
};
