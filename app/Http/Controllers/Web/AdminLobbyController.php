<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Pago;
use App\Models\Penalizacion;
use App\Models\Reservacion;
use Illuminate\Http\Request;

class AdminLobbyController extends Controller
{
    public function index()
    {
        $reservaciones = Reservacion::with(['cliente', 'cancha', 'equipamientos.equipamiento', 'pago', 'penalizaciones'])
            ->orderByDesc('fecha')
            ->orderBy('hora_inicio')
            ->get();

        $ingresosTotales = Pago::sum('monto');
        $reservasHoy = Reservacion::whereDate('fecha', today())->count();
        $pendientes = Reservacion::where('estado', 'pendiente')->count();

        return view('admin.lobby', compact('reservaciones', 'ingresosTotales', 'reservasHoy', 'pendientes'));
    }

    public function confirmar($id)
    {
        $reserva = Reservacion::findOrFail($id);
        $reserva->update(['estado' => 'confirmada']);
        return back()->with('exito', "Reservación #{$reserva->id} confirmada.");
    }

    public function cancelar($id)
    {
        $reserva = Reservacion::findOrFail($id);
        $reserva->update(['estado' => 'cancelada']);
        return back()->with('exito', "Reservación #{$reserva->id} cancelada.");
    }

    public function penalizar(Request $request, $id)
    {
        $request->validate(['tipo_balon' => 'required|in:balon_normal,balon_ac']);
        $reserva = Reservacion::findOrFail($id);
        $multa = $request->tipo_balon === 'balon_ac' ? 30.00 : 20.00;

        Penalizacion::create([
            'reservacion_id' => $reserva->id,
            'tipo_balon' => $request->tipo_balon,
            'monto_multa' => $multa,
            'descripcion' => 'Balón pinchado durante el partido',
            'pagada' => false,
        ]);

        $nuevoTotalMultas = $reserva->total_penalizaciones + $multa;
        $reserva->update([
            'total_penalizaciones' => $nuevoTotalMultas,
            'total_final' => $reserva->subtotal_cancha + $reserva->subtotal_equipo + $nuevoTotalMultas,
        ]);

        return back()->with('exito', "Multa de $$multa agregada por balón pinchado.");
    }

    public function cobrar($id)
    {
        $reserva = Reservacion::findOrFail($id);

        if ($reserva->estado === 'pagada') {
            return back()->with('error', 'Esta reservación ya fue pagada.');
        }

        Pago::create([
            'reservacion_id' => $reserva->id,
            'monto' => $reserva->total_final,
            'metodo_pago' => 'efectivo_lobby',
            'notas_cobro' => 'Pagado en efectivo en el lobby',
        ]);

        $reserva->penalizaciones()->update(['pagada' => true]);
        $reserva->update(['estado' => 'pagada']);

        return back()->with('exito', "¡Pago en efectivo de \${$reserva->total_final} registrado en el lobby!");
    }
}
