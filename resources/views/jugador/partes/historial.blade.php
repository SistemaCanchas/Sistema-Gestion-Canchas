<div class="tarjeta-estadio p-6">
    <h3 class="text-white font-black uppercase text-sm mb-4">Mis Partidos Reservados</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="text-xs uppercase text-slate-400 border-b border-slate-800">
                <tr>
                    <th class="py-3 px-3">Cancha</th>
                    <th class="py-3 px-3">Fecha y Hora</th>
                    <th class="py-3 px-3">Utilería</th>
                    <th class="py-3 px-3">Multas</th>
                    <th class="py-3 px-3">Total Lobby</th>
                    <th class="py-3 px-3">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @forelse($misReservas as $res)
                    <tr>
                        <td class="py-3 px-3 font-bold text-white">{{ $res->cancha->nombre }}</td>
                        <td class="py-3 px-3 text-slate-300">{{ $res->fecha }} ({{ $res->hora_inicio }} - {{ $res->hora_fin }})</td>
                        <td class="py-3 px-3 text-xs">
                            @foreach($res->equipamientos as $det)
                                <span class="inline-block bg-slate-800 px-2 py-0.5 rounded mr-1">
                                    {{ $det->cantidad }}x {{ $det->equipamiento->nombre }} {{ $det->es_cortesia ? '⭐' : '' }}
                                </span>
                            @endforeach
                        </td>
                        <td class="py-3 px-3 text-rose-400 font-bold">${{ number_format($res->total_penalizaciones, 2) }}</td>
                        <td class="py-3 px-3 text-lime-400 font-black">${{ number_format($res->total_final, 2) }}</td>
                        <td class="py-3 px-3 uppercase text-xs font-bold">{{ $res->estado }}</td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="py-6 text-center text-slate-500">Aún no tienes partidos agendados.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
