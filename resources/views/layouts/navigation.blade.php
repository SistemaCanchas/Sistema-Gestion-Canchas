<nav class="bg-slate-900 border-b border-slate-800">
    <div class="max-w-6xl mx-auto px-4 h-16 flex justify-between items-center">
        <!-- Logo Balonazo -->
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2 font-black text-lg tracking-wider text-white uppercase">
            <span class="bg-lime-400 text-slate-950 w-8 h-8 rounded-lg flex items-center justify-center text-base">⚽</span>
            BALON<span class="text-lime-400">AZO</span>
        </a>

        <!-- Usuario y Cerrar Sesión -->
        <div class="flex items-center gap-4">
            <span class="hidden sm:inline text-xs text-slate-400 font-semibold">
                {{ Auth::user()->name }} ({{ Auth::user()->esAdmin() ? 'Lobby' : 'Jugador' }})
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-slate-800 hover:bg-rose-500/20 hover:text-rose-400 text-slate-300 border border-slate-700 px-3 py-1.5 rounded-lg text-xs font-bold transition cursor-pointer">
                    Salir
                </button>
            </form>
        </div>
    </div>
</nav>
