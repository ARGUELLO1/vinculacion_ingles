<?php

use App\Livewire\Actions\Logout;
use Livewire\Volt\Component;

new class extends Component {
    /**
     * Log the current user out of the application.
     */
    public function logout(Logout $logout): void
    {
        $logout();

        $this->redirect('/', navigate: true);
    }
}; ?>

<nav x-data="{ open: false }" class="bg-blue-800 border-b border-blue-800">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" wire:navigate>
                        <x-application-logo class="block h-9 w-auto fill-current text-red-500" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('admin.dashboard')" wire:navigate>
                        {{ __('Dashboard') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.permisos.index')" :active="request()->routeIs('admin.permisos.index')" wire:navigate>
                        {{ __('Permisos') }}
                    </x-nav-link>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('admin.niveles.index')" :active="request()->routeIs('admin.niveles.index')" wire:navigate>
                        {{ __('Niveles') }}
                    </x-nav-link>
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Documentos -->
                <x-dropdown class="">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-800 hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                            <span>Documentos</span>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Actas y Constancias -->
                        <x-dropdown-link :href="route('admin.documentos.alumnos')" wire:navigate>
                            {{ __('Alumnos') }}
                        </x-dropdown-link>

                        <!-- Planificación -->
                        <x-dropdown-link :href="route('admin.documentos.profesor')" wire:navigate>
                            {{ __('Profesores') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>

                <!-- Usuarios -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-800 hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                            <span>Usuarios</span>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Coordinadores -->
                        <x-dropdown-link :href="route('admin.coordinadores.index')" wire:navigate>
                            {{ __('Coordinadores') }}
                        </x-dropdown-link>

                        <!-- Capturistas -->
                        <x-dropdown-link :href="route('admin.capturistas.index')" wire:navigate>
                            {{ __('Capturistas') }}
                        </x-dropdown-link>

                        <!-- Profesores -->
                        <x-dropdown-link :href="route('admin.profesores.index')" wire:navigate>
                            {{ __('Profesores') }}
                        </x-dropdown-link>

                        <!-- Alumnos -->
                        <x-dropdown-link :href="route('admin.alumnos.index')" wire:navigate>
                            {{ __('Alumnos') }}
                        </x-dropdown-link>
                    </x-slot>
                </x-dropdown>

                <!-- Perfil -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-blue-800 hover:text-gray-200 focus:outline-none transition ease-in-out duration-150">
                            <div x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                                x-on:profile-updated.window="name = $event.detail.name"></div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20">
                                    <path fill-rule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile')" wire:navigate>
                            {{ __('Perfil') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <button wire:click="logout" class="w-full text-start">
                            <x-dropdown-link>
                                {{ __('Cerrar Sesion') }}
                            </x-dropdown-link>
                        </button>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" wire:navigate>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.permisos.index')" :active="request()->routeIs('admin.permisos.index')" wire:navigate>
                {{ __('Permisos') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('admin.niveles.index')" :active="request()->routeIs('admin.niveles.index')" wire:navigate>
                {{ __('Niveles') }}
            </x-responsive-nav-link>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <span class="font-medium text-base text-white">Documentos</span>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.documentos.alumnos')" wire:navigate>
                    {{ __('Alumnos') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.documentos.profesor')" wire:navigate>
                    {{ __('Profesores') }}
                </x-responsive-nav-link>
            </div>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <span class="font-medium text-base text-white">Usuarios</span>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('admin.coordinadores.index')" wire:navigate>
                    {{ __('Coordinadores') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.capturistas.index')" wire:navigate>
                    {{ __('Capturistas') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.profesores.index')" wire:navigate>
                    {{ __('Profesores') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link :href="route('admin.alumnos.index')" wire:navigate>
                    {{ __('Alumnos') }}
                </x-responsive-nav-link>
            </div>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-white" x-data="{{ json_encode(['name' => auth()->user()->name]) }}" x-text="name"
                    x-on:profile-updated.window="name = $event.detail.name"></div>
                <div class="font-medium text-sm text-gray-50">{{ auth()->user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile')" wire:navigate>
                    {{ __('Perfil') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <button wire:click="logout" class="w-full text-start">
                    <x-responsive-nav-link>
                        {{ __('Cerrar Sesion') }}
                    </x-responsive-nav-link>
                </button>
            </div>
        </div>
    </div>
</nav>
