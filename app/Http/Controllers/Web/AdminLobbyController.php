<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cancha;
use App\Models\Equipamiento;
use App\Models\Pago;
use App\Models\Penalizacion;
use App\Models\Reservacion;
use Illuminate\Http\Request;

class AdminLobbyController extends Controller
{
    public function index(Request $request)
    {
        $desde = $request->input('desde');
        $hasta = $request->input('hasta');

        // Consulta de reservaciones (filtrable por fechas)
        $queryReservas = Reservacion::with(['cliente', 'cancha', 'equipamientos.equipamiento', 'pago', 'penalizaciones'])
            ->orderByDesc('fecha')
            ->orderBy('hora_inicio');

        $queryPagos = Pago::query();

        if ($desde && $hasta) {
            $queryReservas->whereBetween('fecha', [$desde, $hasta]);
            $queryPagos->whereBetween('created_at', [$desde . ' 00:00:00', $hasta . ' 23:59:59']);
        }

        $reservaciones = $queryReservas->get();
        $ingresosTotales = $queryPagos->sum('monto');
        $reservasHoy = Reservacion::whereDate('fecha', today())->count();
        $pendientes = Reservacion::where('estado', 'pendiente')->count();

        $canchas = Cancha::all();
        $equipamientos = Equipamiento::all();

        return view('admin.lobby', compact(
            'reservaciones', 'ingresosTotales', 'reservasHoy', 'pendientes',
            'canchas', 'equipamientos', 'desde', 'hasta'
        ));
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

    // Activar o desactivar cancha (ej. por mantenimiento)
    public function toggleCancha($id)
    {
        $cancha = Cancha::findOrFail($id);
        $cancha->update(['activa' => ! $cancha->activa]);
        return back()->with('exito', "Estado de {$cancha->nombre} actualizado.");
    }

    // Actualizar stock o precio de equipamiento
    public function actualizarEquipo(Request $request, $id)
    {
        $request->validate([
            'stock_total' => 'required|integer|min:0',
            'precio_alquiler' => 'required|numeric|min:0',
        ]);
        $equipo = Equipamiento::findOrFail($id);
        $equipo->update([
            'stock_total' => $request->stock_total,
            'precio_alquiler' => $request->precio_alquiler,
        ]);
        return back()->with('exito', "Inventario de {$equipo->nombre} actualizado.");
    }
}
