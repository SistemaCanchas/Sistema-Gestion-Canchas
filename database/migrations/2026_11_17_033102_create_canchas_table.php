<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('canchas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Cancha 1, Cancha Premium
            $table->enum('tipo', ['normal', 'pro', 'premium']);
            $table->decimal('precio_hora', 8, 2); // 15.00, 20.00, 30.00
            $table->boolean('incluye_balon_ac')->default(false); // true para Premium
            $table->text('descripcion')->nullable();
            $table->boolean('activa')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('canchas');
    }
};
