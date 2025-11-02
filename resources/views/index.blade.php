<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sistema de Inglés</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="antialiased font-sans flex flex-col min-h-screen">
    <!-- Imagen de fondo -->
    <div class="fixed inset-0 -z-10">
        <img src="{{ asset('images/explanada.jpg') }}" alt="Background"
            class="w-full h-full object-cover filter blur-sm" loading="eager">
        <div class="absolute inset-0 bg-black/30"></div>
    </div>

    <header class="bg-blue-800/90 py-3 sm:py-4 px-4 sm:px-6 lg:px-8 flex-shrink-0">
        <div class="container mx-auto">
            <div class="flex items-center justify-between gap-4 sm:gap-6">
                <!-- Logo TECNM -->
                <div class="hidden sm:flex sm:flex-1 sm:justify-start">
                    <img src="{{ asset('images/tecnmb.png') }}" alt="TECNM" class="w-20 sm:w-24 md:w-28 lg:w-32"
                        loading="lazy">
                </div>

                <!-- Logo TESI -->
                <div class="flex flex-1 justify-center sm:justify-center">
                    <img src="{{ asset('images/tesib.png') }}" alt="TESI" class="w-20 sm:w-24 md:w-28 lg:w-32"
                        loading="lazy">
                </div>

                <!-- Navegación -->
                <div class="flex flex-1 justify-end">
                    @if (Route::has('login'))
                        <livewire:welcome.navigation />
                    @endif
                </div>
            </div>
        </div>
    </header>

    <main class="flex-grow flex items-center justify-center py-6 sm:py-8 lg:py-12">
        <div class="container mx-auto px-4">
            <div
                class="flex flex-col lg:flex-row items-center justify-center lg:justify-between gap-6 sm:gap-8 lg:gap-12">
                <!-- Imagen Rocko - Oculta en móvil pequeño, visible desde sm -->
                <div class="hidden sm:block flex-shrink-0">
                    <img src="{{ asset('images/rocko1.png') }}" alt="Rocko"
                        class="w-48 sm:w-56 md:w-64 lg:w-80 xl:w-96" loading="lazy">
                </div>

                <div class="text-center lg:text-left max-w-2xl">
                    <h1
                        class="text-2xl sm:text-3xl md:text-4xl lg:text-5xl xl:text-6xl font-bold uppercase text-white bg-blue-800/80 px-4 sm:px-6 py-3 sm:py-4 rounded-lg shadow-lg">
                        Sistema de Inglés
                    </h1>

                    <!-- Texto adicional que aparece en pantallas medianas -->
                    <div class="hidden md:block mt-4 text-white text-sm sm:text-base lg:text-lg">
                        <p>TECNOLÓGICO DE ESTUDIOS SUPERIORES</p>
                        <p>IXTAPALUCA</p>
                    </div>
                </div>

                <!-- Imagen Rocko para móvil - Solo visible en móvil pequeño -->
                <div class="sm:hidden flex-shrink-0">
                    <img src="{{ asset('images/rocko1.png') }}" alt="Rocko" class="w-32" loading="lazy">
                </div>
            </div>
        </div>
    </main>

    <footer class="bg-blue-800/90 py-4 sm:py-6 flex-shrink-0">
        <div class="container mx-auto px-4">
            <div
                class="flex flex-col sm:flex-row justify-between items-center gap-3 sm:gap-4 text-white text-xs sm:text-sm">
                <div class="uppercase whitespace-nowrap">📞 Tel: 80099050505</div>
                <div class="uppercase text-center">📧 confocuping@gmail.com</div>
                <div class="uppercase whitespace-nowrap">🕘 9:00 am - 6:00 pm</div>
            </div>
        </div>
    </footer>
</body>

</html>
