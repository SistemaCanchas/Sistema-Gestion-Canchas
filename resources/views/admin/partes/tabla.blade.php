<div class="tarjeta-estadio p-6">
    <h3 class="text-base font-black uppercase text-white mb-4">Agenda de Canchas y Cobros</h3>
    <div class="overflow-x-auto">
        <table class="min-w-full text-sm text-left">
            <thead class="text-xs uppercase text-slate-400 border-b border-slate-800">
                <tr>
                    <th class="px-3 py-3">Jugador</th>
                    <th class="px-3 py-3">Cancha y Horario</th>
                    <th class="px-3 py-3">Utilería y Multas</th>
                    <th class="px-3 py-3">Total Final</th>
                    <th class="px-3 py-3">Estado</th>
                    <th class="px-3 py-3">Acciones Lobby</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-800">
                @foreach($reservaciones as $res)
                    <tr class="hover:bg-slate-900/60">
                        <td class="px-3 py-4">
                            <p class="font-bold text-white">{{ $res->cliente->name }}</p>
                            <p class="text-xs text-slate-400">{{ $res->cliente->telefono ?? $res->cliente->email }}</p>
                        </td>
                        <td class="px-3 py-4">
                            <p class="font-bold text-lime-400">{{ $res->cancha->nombre }}</p>
                            <p class="text-xs text-slate-300">{{ $res->fecha }} | {{ $res->hora_inicio }} - {{ $res->hora_fin }}</p>
                        </td>
                        <td class="px-3 py-4 space-y-1">
                            @foreach($res->equipamientos as $det)
                                <span class="block text-xs text-slate-300">• {{ $det->cantidad }}x {{ $det->equipamiento->nombre }} {{ $det->es_cortesia ? '⭐(Gratis)' : '' }}</span>
                            @endforeach
                            @if($res->total_penalizaciones > 0)
                                <span class="block text-xs text-rose-400 font-bold">+ Multa balón: ${{ number_format($res->total_penalizaciones, 2) }}</span>
                            @endif
                        </td>
                        <td class="px-3 py-4 font-black text-base text-white">${{ number_format($res->total_final, 2) }}</td>
                        <td class="px-3 py-4">
                            <span class="px-2 py-1 rounded text-[11px] font-black uppercase bg-slate-800 text-amber-300">
                                {{ $res->estado }}
                            </span>
                        </td>
                        <td class="px-3 py-4 space-y-2">
                            @if($res->estado === 'pendiente')
                                <div class="flex gap-1">
                                    <form action="{{ route('admin.confirmar', $res->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="bg-sky-500 text-slate-950 px-2.5 py-1 rounded text-xs font-black cursor-pointer">Confirmar</button>
                                    </form>
                                    <form action="{{ route('admin.cancelar', $res->id) }}" method="POST">
                                        @csrf @method('PATCH')
                                        <button class="bg-slate-700 text-white px-2.5 py-1 rounded text-xs font-bold cursor-pointer">Cancelar</button>
                                    </form>
                                </div>
                            @endif

                            @if(in_array($res->estado, ['pendiente', 'confirmada']))
                                <form action="{{ route('admin.penalizar', $res->id) }}" method="POST" class="flex items-center gap-1">
                                    @csrf
                                    <select name="tipo_balon" class="text-xs py-1 px-2">
                                        <option value="balon_normal">Pinchado Normal ($20)</option>
                                        <option value="balon_ac">Pinchado AC ($30)</option>
                                    </select>
                                    <button class="bg-rose-600 text-white px-2.5 py-1 rounded text-xs font-bold cursor-pointer">+ Multa</button>
                                </form>

                                <form action="{{ route('admin.cobrar', $res->id) }}" method="POST">
                                    @csrf
                                    <button class="w-full btn-neon px-3 py-1.5 text-xs uppercase cursor-pointer">
                                        💵 Cobrar (${{ number_format($res->total_final, 2) }})
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
