<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4 space-y-6">
        <div class="border-b border-slate-800 pb-4">
            <h1 class="text-2xl font-black uppercase text-white">🏟️ Control de <span class="text-lime-400">Lobby y Caja</span></h1>
            <p class="text-xs text-slate-400">Recepción en efectivo, confirmación de canchas y control de balones</p>
        </div>

        @if(session('exito'))
            <div class="bg-lime-400/10 border border-lime-400 text-lime-300 p-4 rounded-xl text-sm font-bold">✅ {{ session('exito') }}</div>
        @endif

        @include('admin.partes.metricas')
        @include('admin.partes.tabla')
    </div>
</x-app-layout>
