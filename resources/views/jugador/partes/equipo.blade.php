<div class="tarjeta-estadio p-6">
    <h3 class="text-lime-400 font-black uppercase text-sm mb-4">2. Utilería Extra (Opcional)</h3>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
        @foreach($equipamientos as $item)
            <div class="flex items-center justify-between bg-slate-950 border border-slate-800 p-3 rounded-xl">
                <div>
                    <p class="font-bold text-sm text-white">{{ $item->nombre }}</p>
                    <p class="text-xs text-lime-400">${{ number_format($item->precio_alquiler, 2) }} <span class="text-slate-500">(Stock: {{ $item->stock_total }})</span></p>
                </div>
                <input type="number" name="equipo[{{ $item->id }}]" min="0" max="{{ $item->stock_total }}" value="0"
                       class="w-16 rounded-lg bg-slate-900 border-slate-700 text-center text-white font-bold">
            </div>
        @endforeach
    </div>
    <div class="mt-6 flex justify-end">
        <button type="submit" class="btn-neon px-8 py-3 uppercase text-sm tracking-wider cursor-pointer">
            Confirmar Reservación ⚡
        </button>
    </div>
</div>
