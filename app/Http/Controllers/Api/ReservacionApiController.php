<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cancha;
use App\Models\DetalleEquipamiento;
use App\Models\Equipamiento;
use App\Models\Reservacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservacionApiController extends Controller
{
    public function misReservas(Request $request)
    {
        $reservas = $request->user()->reservaciones()
            ->with(['cancha', 'equipamientos.equipamiento', 'pago', 'penalizaciones'])
            ->orderByDesc('fecha')
            ->get();

        return response()->json($reservas);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'notas' => 'nullable|string',
            'equipamientos' => 'nullable|array',
            'equipamientos.*.id' => 'required|exists:equipamientos,id',
            'equipamientos.*.cantidad' => 'required|integer|min:1',
        ]);

        // 1. Verificar que la cancha no esté ocupada en ese bloque
        $existeTraslape = Reservacion::where('cancha_id', $request->cancha_id)
            ->where('fecha', $request->fecha)
            ->whereIn('estado', ['pendiente', 'confirmada', 'pagada'])
            ->where(function ($q) use ($request) {
                $q->where('hora_inicio', '<', $request->hora_fin)
                  ->where('hora_fin', '>', $request->hora_inicio);
            })
            ->exists();

        if ($existeTraslape) {
            return response()->json(['mensaje' => 'La cancha ya está reservada en ese horario.'], 422);
        }

        return DB::transaction(function () use ($request) {
            $cancha = Cancha::findOrFail($request->cancha_id);
            $horas = Carbon::parse($request->hora_inicio)->diffInMinutes(Carbon::parse($request->hora_fin)) / 60;
            $subtotalCancha = $cancha->precio_hora * $horas;
            $subtotalEquipo = 0;

            $reservacion = Reservacion::create([
                'user_id' => $request->user()->id,
                'cancha_id' => $cancha->id,
                'fecha' => $request->fecha,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'subtotal_cancha' => $subtotalCancha,
                'subtotal_equipo' => 0,
                'total_penalizaciones' => 0,
                'total_final' => $subtotalCancha,
                'estado' => 'pendiente',
                'notas' => $request->notas,
            ]);

            // 2. Si es Cancha Premium, incluir 1 Balón de Alta Calidad (AC) GRATIS
            if ($cancha->incluye_balon_ac) {
                $balonAC = Equipamiento::where('categoria', 'balon_ac')->first();
                if ($balonAC) {
                    DetalleEquipamiento::create([
                        'reservacion_id' => $reservacion->id,
                        'equipamiento_id' => $balonAC->id,
                        'cantidad' => 1,
                        'precio_unitario' => 0.00,
                        'es_cortesia' => true,
                    ]);
                }
            }

            // 3. Agregar equipamiento extra seleccionado por el jugador
            if ($request->filled('equipamientos')) {
                foreach ($request->equipamientos as $itemReq) {
                    $equipo = Equipamiento::findOrFail($itemReq['id']);
                    $cantidad = $itemReq['cantidad'];
                    $costo = $equipo->precio_alquiler * $cantidad;
                    $subtotalEquipo += $costo;

                    DetalleEquipamiento::create([
                        'reservacion_id' => $reservacion->id,
                        'equipamiento_id' => $equipo->id,
                        'cantidad' => $cantidad,
                        'precio_unitario' => $equipo->precio_alquiler,
                        'es_cortesia' => false,
                    ]);
                }
            }

            $reservacion->update([
                'subtotal_equipo' => $subtotalEquipo,
                'total_final' => $subtotalCancha + $subtotalEquipo,
            ]);

            return response()->json([
                'mensaje' => 'Reservación solicitada. Recuerda pagar en efectivo en el lobby.',
                'reservacion' => $reservacion->load(['cancha', 'equipamientos.equipamiento']),
            ], 201);
        });
    }
}
