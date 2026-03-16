<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bitacora_ingresos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('nombre');
            $table->string('email');
            $table->enum('evento', ['login', 'logout']);
            $table->string('session_id')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('ocurrio_en')->useCurrent();
            $table->timestamps();

            $table->index(['evento', 'ocurrio_en']);
            $table->index(['user_id', 'ocurrio_en']);
            $table->index(['email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bitacora_ingresos');
    }
};
