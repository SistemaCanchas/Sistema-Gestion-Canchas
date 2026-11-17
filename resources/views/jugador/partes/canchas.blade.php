<div class="tarjeta-estadio p-6">
    <h3 class="text-lime-400 font-black uppercase text-sm mb-4">1. Elige Cancha y Horario</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-5">
        @foreach($canchas as $cancha)
            <label class="tarjeta-cancha cursor-pointer bg-slate-950 border border-slate-800 p-4 rounded-xl block">
                <div class="flex justify-between items-center mb-1">
                    <input type="radio" name="cancha_id" value="{{ $cancha->id }}" {{ $loop->first ? 'checked' : '' }} class="text-lime-400">
                    <span class="font-black text-lime-400">${{ number_format($cancha->precio_hora, 0) }}/hr</span>
                </div>
                <p class="font-bold text-white">{{ $cancha->nombre }}</p>
                <p class="text-xs text-slate-400">{{ $cancha->descripcion }}</p>
                @if($cancha->incluye_balon_ac)
                    <span class="inline-block mt-2 text-[11px] bg-amber-400/20 text-amber-300 px-2 py-0.5 rounded font-bold">
                        ⭐ Balón Alta Calidad GRATIS
                    </span>
                @endif
            </label>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div>
            <label class="block text-xs text-slate-400 uppercase font-bold mb-1">Fecha</label>
            <input type="date" name="fecha" min="{{ date('Y-m-d') }}" value="{{ date('Y-m-d') }}" required class="w-full rounded-lg bg-slate-950 border-slate-700 text-white">
        </div>
        <div>
            <label class="block text-xs text-slate-400 uppercase font-bold mb-1">Hora Inicio</label>
            <input type="time" name="hora_inicio" value="18:00" required class="w-full rounded-lg bg-slate-950 border-slate-700 text-white">
        </div>
        <div>
            <label class="block text-xs text-slate-400 uppercase font-bold mb-1">Hora Fin</label>
            <input type="time" name="hora_fin" value="19:00" required class="w-full rounded-lg bg-slate-950 border-slate-700 text-white">
        </div>
    </div>
</div>
