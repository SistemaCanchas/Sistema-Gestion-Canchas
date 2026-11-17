<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cancha;
use App\Models\DetalleEquipamiento;
use App\Models\Equipamiento;
use App\Models\Reservacion;
use Illuminate\Http\Request;

class CatalogoApiController extends Controller
{
    public function canchas()
    {
        return response()->json(Cancha::where('activa', true)->get());
    }

    public function equipamientos()
    {
        return response()->json(Equipamiento::all());
    }

    public function disponibilidad(Request $request)
    {
        $request->validate([
            'fecha' => 'required|date',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
        ]);

        $fecha = $request->fecha;
        $inicio = $request->hora_inicio;
        $fin = $request->hora_fin;

        // Buscar IDs de reservas activas que se traslapen en esa fecha y hora
        $reservasTraslapadas = Reservacion::where('fecha', $fecha)
            ->whereIn('estado', ['pendiente', 'confirmada', 'pagada'])
            ->where(function ($q) use ($inicio, $fin) {
                $q->where('hora_inicio', '<', $fin)
                  ->where('hora_fin', '>', $inicio);
            })
            ->pluck('id');

        $canchasOcupadasIds = Reservacion::whereIn('id', $reservasTraslapadas)->pluck('cancha_id');

        $canchas = Cancha::where('activa', true)->get()->map(function ($cancha) use ($canchasOcupadasIds) {
            $cancha->disponible = ! $canchasOcupadasIds->contains($cancha->id);
            return $cancha;
        });

        // Calcular stock disponible de cada artículo en ese horario
        $equipamientos = Equipamiento::all()->map(function ($item) use ($reservasTraslapadas) {
            $enUso = DetalleEquipamiento::whereIn('reservacion_id', $reservasTraslapadas)
                ->where('equipamiento_id', $item->id)
                ->sum('cantidad');

            $item->stock_disponible = max(0, $item->stock_total - $enUso);
            return $item;
        });

        return response()->json([
            'fecha' => $fecha,
            'horario' => "$inicio - $fin",
            'canchas' => $canchas,
            'equipamientos' => $equipamientos,
        ]);
    }
}
