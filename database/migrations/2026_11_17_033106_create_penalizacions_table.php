<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('penalizaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('reservacion_id')->constrained('reservacions')->onDelete('cascade');
            $table->enum('tipo_balon', ['balon_normal', 'balon_ac']);
            $table->decimal('monto_multa', 8, 2); // 20.00 o 30.00
            $table->string('descripcion')->default('Balón pinchado o dañado durante el partido');
            $table->boolean('pagada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('penalizaciones');
    }
};
