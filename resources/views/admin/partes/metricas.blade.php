<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="tarjeta-estadio p-5 border-l-4 border-lime-400">
        <p class="text-xs uppercase text-slate-400 font-bold">Caja Efectivo (Lobby)</p>
        <p class="text-3xl font-black text-lime-400 mt-1">${{ number_format($ingresosTotales, 2) }}</p>
    </div>
    <div class="tarjeta-estadio p-5 border-l-4 border-sky-400">
        <p class="text-xs uppercase text-slate-400 font-bold">Partidos Hoy</p>
        <p class="text-3xl font-black text-white mt-1">{{ $reservasHoy }}</p>
    </div>
    <div class="tarjeta-estadio p-5 border-l-4 border-amber-400">
        <p class="text-xs uppercase text-slate-400 font-bold">Solicitudes Pendientes</p>
        <p class="text-3xl font-black text-amber-400 mt-1">{{ $pendientes }}</p>
    </div>
</div>
