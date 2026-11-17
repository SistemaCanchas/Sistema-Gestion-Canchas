<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('equipamientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Balón Normal, Balón Alta Calidad, Tacos, Guantes
            $table->enum('categoria', ['balon_normal', 'balon_ac', 'tacos', 'guantes']);
            $table->string('talla', 10)->nullable(); // '7', '8', '9' para tacos; null para el resto
            $table->decimal('precio_alquiler', 8, 2); // 3.00 o 5.00
            $table->integer('stock_total'); // 10, 10, 10 por talla, 15
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('equipamientos');
    }
};
