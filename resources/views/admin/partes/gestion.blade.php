<!-- Filtro de Reporte de Ingresos por Fechas -->
<div class="tarjeta-estadio p-5 no-print">
    <form method="GET" action="{{ route('admin.lobby') }}" class="flex flex-wrap items-end justify-between gap-4">
        <div class="flex flex-wrap items-end gap-3">
            <div>
                <label class="block text-xs text-slate-400 uppercase font-bold mb-1">Desde</label>
                <input type="date" name="desde" value="{{ $desde }}" class="text-xs py-1.5 px-3">
            </div>
            <div>
                <label class="block text-xs text-slate-400 uppercase font-bold mb-1">Hasta</label>
                <input type="date" name="hasta" value="{{ $hasta }}" class="text-xs py-1.5 px-3">
            </div>
            <button type="submit" class="btn-neon px-4 py-2 text-xs uppercase cursor-pointer">Filtrar Reporte</button>
            @if($desde || $hasta)
                <a href="{{ route('admin.lobby') }}" class="bg-slate-800 text-slate-300 px-3 py-2 rounded-lg text-xs font-bold">Limpiar</a>
            @endif
        </div>
        <button type="button" onclick="window.print()" class="bg-slate-800 hover:bg-slate-700 text-white border border-slate-700 px-4 py-2 rounded-lg text-xs font-bold cursor-pointer">
            🖨️ Imprimir Reporte
        </button>
    </form>
</div>

<!-- Gestión Rápida de Canchas e Inventario -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 no-print">
    <!-- Control de Canchas -->
    <div class="tarjeta-estadio p-5">
        <h3 class="text-sm font-black uppercase text-lime-400 mb-3">Estado de las 6 Canchas</h3>
        <div class="space-y-2">
            @foreach($canchas as $cancha)
                <div class="flex items-center justify-between bg-slate-950 border border-slate-800 px-3 py-2 rounded-lg text-xs">
                    <div>
                        <span class="font-bold text-white">{{ $cancha->nombre }}</span>
                        <span class="text-slate-400 ml-1">(${{ number_format($cancha->precio_hora, 0) }}/hr)</span>
                    </div>
                    <form action="{{ route('admin.cancha.toggle', $cancha->id) }}" method="POST">
                        @csrf @method('PATCH')
                        <button class="px-2.5 py-1 rounded font-black uppercase cursor-pointer {{ $cancha->activa ? 'bg-lime-400/20 text-lime-300' : 'bg-rose-500/20 text-rose-300' }}">
                            {{ $cancha->activa ? 'Activa' : 'Mantenimiento' }}
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Control de Inventario (Balones, Tacos, Guantes) -->
    <div class="tarjeta-estadio p-5">
        <h3 class="text-sm font-black uppercase text-lime-400 mb-3">Stock de Utilería</h3>
        <div class="space-y-2">
            @foreach($equipamientos as $item)
                <form action="{{ route('admin.equipo.actualizar', $item->id) }}" method="POST"
                      class="flex items-center justify-between bg-slate-950 border border-slate-800 px-3 py-2 rounded-lg text-xs gap-2">
                    @csrf @method('PATCH')
                    <span class="font-bold text-white flex-1">{{ $item->nombre }}</span>
                    <div class="flex items-center gap-1">
                        <span class="text-slate-400">$</span>
                        <input type="number" step="0.50" name="precio_alquiler" value="{{ $item->precio_alquiler }}" class="w-16 py-1 px-1 text-center">
                        <span class="text-slate-400 ml-1">Stock:</span>
                        <input type="number" name="stock_total" value="{{ $item->stock_total }}" class="w-14 py-1 px-1 text-center">
                        <button class="bg-sky-500 text-slate-950 font-black px-2 py-1 rounded cursor-pointer">OK</button>
                    </div>
                </form>
            @endforeach
        </div>
    </div>
</div>
