<?php

use App\Http\Controllers\Api\AdminLobbyApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\CatalogoApiController;
use App\Http\Controllers\Api\ReservacionApiController;
use Illuminate\Support\Facades\Route;

// Rutas públicas (cualquiera puede ver canchas, equipo y consultar horarios libres)
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);
Route::get('/canchas', [CatalogoApiController::class, 'canchas']);
Route::get('/equipamientos', [CatalogoApiController::class, 'equipamientos']);
Route::get('/disponibilidad', [CatalogoApiController::class, 'disponibilidad']);

// Rutas protegidas (requieren Token de Sanctum)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthApiController::class, 'logout']);

    // Jugador / Cliente
    Route::get('/mis-reservas', [ReservacionApiController::class, 'misReservas']);
    Route::post('/reservaciones', [ReservacionApiController::class, 'store']);

    // Recepcionista / Administrador del Lobby
    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/reservaciones', [AdminLobbyApiController::class, 'todasLasReservas']);
        Route::patch('/reservaciones/{id}/estado', [AdminLobbyApiController::class, 'cambiarEstado']);
        Route::post('/reservaciones/{id}/penalizacion', [AdminLobbyApiController::class, 'registrarPenalizacion']);
        Route::post('/reservaciones/{id}/cobrar', [AdminLobbyApiController::class, 'cobrarEnLobby']);
    });
});
