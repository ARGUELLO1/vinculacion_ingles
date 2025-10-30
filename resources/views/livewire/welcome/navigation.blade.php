<nav class="flex flex-1 justify-end">
    @auth
        <a href="{{ url('/dashboard') }}"
            class="rounded-md px-3 py-2 text-white ring-1 ring-transparent transition hover:text-gray-500/70 focus:outline-none focus-visible:ring-[#FF2D20]">
            Dashboard
        </a>
    @else
        <div class="flex items-center gap-2 sm:gap-3">
            <x-primary-button :href="route('login')" wire:navigate class="text-sm sm:text-base">
                Login
            </x-primary-button>

            @if (Route::has('register'))
                <x-secondary-button :href="route('register')" wire:navigate class="text-sm sm:text-base">
                    Registro
                </x-secondary-button>
            @endif
        </div>
    @endauth
</nav>
