<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Penalizacion;
use App\Models\Reservacion;
use Illuminate\Http\Request;

class AdminLobbyApiController extends Controller
{
    public function todasLasReservas()
    {
        return response()->json(
            Reservacion::with(['cliente', 'cancha', 'equipamientos.equipamiento', 'pago', 'penalizaciones'])
                ->orderByDesc('fecha')
                ->get()
        );
    }

    public function cambiarEstado(Request $request, $id)
    {
        $request->validate(['estado' => 'required|in:confirmada,cancelada']);
        $reservacion = Reservacion::findOrFail($id);
        $reservacion->update(['estado' => $request->estado]);

        return response()->json(['mensaje' => 'Estado actualizado', 'reservacion' => $reservacion]);
    }

    public function registrarPenalizacion(Request $request, $id)
    {
        $request->validate([
            'tipo_balon' => 'required|in:balon_normal,balon_ac',
            'descripcion' => 'nullable|string',
        ]);

        $reservacion = Reservacion::findOrFail($id);
        $multa = $request->tipo_balon === 'balon_ac' ? 30.00 : 20.00;

        $penalizacion = Penalizacion::create([
            'reservacion_id' => $reservacion->id,
            'tipo_balon' => $request->tipo_balon,
            'monto_multa' => $multa,
            'descripcion' => $request->descripcion ?? 'Balón pinchado durante el partido',
            'pagada' => false,
        ]);

        $nuevoTotalMultas = $reservacion->total_penalizaciones + $multa;
        $reservacion->update([
            'total_penalizaciones' => $nuevoTotalMultas,
            'total_final' => $reservacion->subtotal_cancha + $reservacion->subtotal_equipo + $nuevoTotalMultas,
        ]);

        return response()->json([
            'mensaje' => "Penalización de $$multa aplicada a la reservación.",
            'penalizacion' => $penalizacion,
            'reservacion' => $reservacion,
        ], 201);
    }

    public function cobrarEnLobby(Request $request, $id)
    {
        $reservacion = Reservacion::findOrFail($id);

        if ($reservacion->estado === 'pagada') {
            return response()->json(['mensaje' => 'Esta reservación ya fue cobrada.'], 422);
        }

        $pago = Pago::create([
            'reservacion_id' => $reservacion->id,
            'monto' => $reservacion->total_final,
            'metodo_pago' => 'efectivo_lobby',
            'notas_cobro' => $request->notas_cobro ?? 'Cobrado en efectivo en el lobby',
        ]);

        $reservacion->penalizaciones()->update(['pagada' => true]);
        $reservacion->update(['estado' => 'pagada']);

        return response()->json([
            'mensaje' => 'Pago en efectivo registrado en el lobby.',
            'pago' => $pago,
            'reservacion' => $reservacion,
        ]);
    }
}
