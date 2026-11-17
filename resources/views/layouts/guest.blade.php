<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>Balonazo — Acceso</title>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="https://tailwindcss.com"></script>
        <link rel="stylesheet" href="{{ asset('css/balonazo.css') }}">
    </head>
    <body class="font-sans antialiased bg-slate-950 text-slate-100">
        <div class="min-h-screen flex flex-col justify-center items-center px-4">
            <div class="mb-6 text-center">
                <a href="/" class="inline-flex items-center gap-2 font-black text-3xl tracking-wider text-white uppercase">
                    <span class="bg-lime-400 text-slate-950 w-10 h-10 rounded-xl flex items-center justify-center text-xl">⚽</span>
                    BALON<span class="text-lime-400">AZO</span>
                </a>
                <p class="text-xs text-slate-400 mt-1">Sistema de Canchas y Utilería</p>
            </div>

            <div class="w-full max-w-md px-6 py-8 bg-slate-900 border border-slate-800 shadow-2xl rounded-2xl">
                {{ $slot }}
            </div>
        </div>
    </body>
</html>
