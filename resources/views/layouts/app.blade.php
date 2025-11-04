<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
    <!-- Imagen de fondo con blur -->
    <div class="fixed inset-0 -z-10">
        <img src="{{ asset('images/explanada.jpg') }}" alt="Background"
            class="w-full h-full object-cover filter blur-sm">
        <!-- Overlay para mejor legibilidad -->
        <div class="absolute inset-0 bg-black/30"></div>
    </div>

    <div class="min-h-screen">
        <!-- Navegación dinámica según rol -->
        @auth
        @switch(auth()->user()->getRoleNames()->first())
        @case('admin')
        <livewire:layout.admin.navigation />
        @break

        @case('coordinador')
        <livewire:layout.coordinador.navigation />
        @break

        @case('capturista')
        <livewire:layout.capturista.navigation />
        @break

        @case('profesor')
        <livewire:layout.profesor.navigation />
        @break

        @case('alumno')
        <livewire:layout.alumno.navigation />
        @break

        @default
        <livewire:layout.navigation />
        @endswitch
        @else
        <livewire:layout.navigation />
        @endauth

        <!-- Page Heading -->
        @if (isset($header))
        <header class="bg-[#1b396b] shadow-none w-full">
            <div class="py-6 px-4 sm:px-6 lg:px-8 text-white font-bold text-xl">
                {{ $header }}
            </div>
        </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    @livewireScripts
</body>

</html>