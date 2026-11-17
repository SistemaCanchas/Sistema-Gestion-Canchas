¿<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detalle_equipamientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservacion_id')->constrained('reservacions')->onDelete('cascade');
            $table->foreignId('equipamiento_id')->constrained('equipamientos')->onDelete('cascade');
            $table->integer('cantidad')->default(1);
            $table->decimal('precio_unitario', 8, 2); // 0.00 si es el balón AC gratis de la Premium
            $table->boolean('es_cortesia')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detalle_equipamientos');
    }
};
