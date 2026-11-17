<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservacions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('cancha_id')->constrained('canchas')->onDelete('cascade');
            $table->date('fecha');
            $table->time('hora_inicio');
            $table->time('hora_fin');
            $table->decimal('subtotal_cancha', 8, 2);
            $table->decimal('subtotal_equipo', 8, 2)->default(0);
            $table->decimal('total_penalizaciones', 8, 2)->default(0);
            $table->decimal('total_final', 8, 2);
            $table->enum('estado', ['pendiente', 'confirmada', 'pagada', 'cancelada'])->default('pendiente');
            $table->text('notas')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservacions');
    }
};
