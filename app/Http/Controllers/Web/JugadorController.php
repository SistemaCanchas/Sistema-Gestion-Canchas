<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Cancha;
use App\Models\DetalleEquipamiento;
use App\Models\Equipamiento;
use App\Models\Reservacion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JugadorController extends Controller
{
    public function index(Request $request)
    {
        $canchas = Cancha::where('activa', true)->get();
        $equipamientos = Equipamiento::all();
        $misReservas = $request->user()->reservaciones()
            ->with(['cancha', 'equipamientos.equipamiento', 'penalizaciones'])
            ->orderByDesc('fecha')
            ->get();

        return view('jugador.panel', compact('canchas', 'equipamientos', 'misReservas'));
    }

    public function reservar(Request $request)
    {
        $request->validate([
            'cancha_id' => 'required|exists:canchas,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required',
            'hora_fin' => 'required|after:hora_inicio',
            'notas' => 'nullable|string|max:255',
            'equipo' => 'nullable|array',
        ]);

        // Validar que no exista cruce de horarios en la misma cancha
        $existeTraslape = Reservacion::where('cancha_id', $request->cancha_id)
            ->where('fecha', $request->fecha)
            ->whereIn('estado', ['pendiente', 'confirmada', 'pagada'])
            ->where(function ($q) use ($request) {
                $q->where('hora_inicio', '<', $request->hora_fin)
                  ->where('hora_fin', '>', $request->hora_inicio);
            })
            ->exists();

        if ($existeTraslape) {
            return back()->with('error', '¡Esa cancha ya está ocupada en el horario elegido! Prueba con otra hora o cancha.');
        }

        DB::transaction(function () use ($request) {
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

            // Cortesía Cancha Premium: 1 Balón de Alta Calidad (AC) gratis
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

            // Equipamiento extra solicitado
            if ($request->has('equipo')) {
                foreach ($request->equipo as $equipoId => $cantidad) {
                    $cant = (int) $cantidad;
                    if ($cant > 0) {
                        $item = Equipamiento::find($equipoId);
                        if ($item) {
                            $subtotalEquipo += $item->precio_alquiler * $cant;
                            DetalleEquipamiento::create([
                                'reservacion_id' => $reservacion->id,
                                'equipamiento_id' => $item->id,
                                'cantidad' => $cant,
                                'precio_unitario' => $item->precio_alquiler,
                                'es_cortesia' => false,
                            ]);
                        }
                    }
                }
            }

            $reservacion->update([
                'subtotal_equipo' => $subtotalEquipo,
                'total_final' => $subtotalCancha + $subtotalEquipo,
            ]);
        });

        return back()->with('exito', '¡Reservación solicitada! Pasa al lobby en efectivo el día de tu partido.');
    }
}
