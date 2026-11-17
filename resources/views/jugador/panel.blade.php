<x-app-layout>
    <div class="max-w-6xl mx-auto py-8 px-4 space-y-6">
        <div class="flex justify-between items-center border-b border-slate-800 pb-4">
            <div>
                <h1 class="text-2xl font-black uppercase text-white">⚽ Arena <span class="text-lime-400">Balonazo</span></h1>
                <p class="text-xs text-slate-400">Reserva tu cancha y paga en efectivo al llegar al lobby</p>
            </div>
            <span class="text-xs bg-slate-800 px-3 py-1.5 rounded-full text-lime-400 font-bold">{{ Auth::user()->name }}</span>
        </div>

        @if(session('exito'))
            <div class="bg-lime-400/10 border border-lime-400 text-lime-300 p-4 rounded-xl text-sm font-bold">🔥 {{ session('exito') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-rose-500/10 border border-rose-500 text-rose-300 p-4 rounded-xl text-sm font-bold">⚠️ {{ session('error') }}</div>
        @endif

        <form action="{{ route('jugador.reservar') }}" method="POST" class="space-y-6">
            @csrf
            @include('jugador.partes.canchas')
            @include('jugador.partes.equipo')
        </form>

        @include('jugador.partes.historial')
    </div>
</x-app-layout>
