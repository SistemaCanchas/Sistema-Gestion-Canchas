<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\AdminLobbyController;
use App\Http\Controllers\Web\JugadorController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

// Redirección inteligente según el rol después de iniciar sesión
Route::get('/dashboard', function () {
    if (auth()->user()->esAdmin()) {
        return redirect()->route('admin.lobby');
    }
    return redirect()->route('jugador.panel');
})->middleware(['auth'])->name('dashboard');

// Rutas del Jugador (Cliente)
Route::middleware(['auth'])->group(function () {
    Route::get('/reservar', [JugadorController::class, 'index'])->name('jugador.panel');
    Route::post('/reservar', [JugadorController::class, 'reservar'])->name('jugador.reservar');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Rutas del Recepcionista / Administrador del Lobby
Route::middleware(['auth', 'admin'])->prefix('lobby')->group(function () {
    Route::get('/', [AdminLobbyController::class, 'index'])->name('admin.lobby');
    Route::patch('/reservas/{id}/confirmar', [AdminLobbyController::class, 'confirmar'])->name('admin.confirmar');
    Route::patch('/reservas/{id}/cancelar', [AdminLobbyController::class, 'cancelar'])->name('admin.cancelar');
    Route::post('/reservas/{id}/penalizar', [AdminLobbyController::class, 'penalizar'])->name('admin.penalizar');
    Route::post('/reservas/{id}/cobrar', [AdminLobbyController::class, 'cobrar'])->name('admin.cobrar');
    Route::patch('/canchas/{id}/toggle', [AdminLobbyController::class, 'toggleCancha'])->name('admin.cancha.toggle');
    Route::patch('/equipamiento/{id}', [AdminLobbyController::class, 'actualizarEquipo'])->name('admin.equipo.actualizar');

});

require __DIR__.'/auth.php';
