<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <div class="max-w-xl">
                <header>
                    <h2 class="text-lg font-medium text-gray-900">
                        {{ __('Coordinador Information') }}
                    </h2>
                </header>

                <section>
                    <form wire:submit='save' class="mt-6 space-y-6">
                        <div>
                            <x-input-label for="name" :value="__('Name')" />
                            <x-text-input wire:model="form.name" id="name" name="name" type="text"
                                class="mt-1 block w-full" required autofocus autocomplete="name" />
                            <x-input-error class="mt-2" :messages="$errors->get('form.name')" />
                        </div>

                        <div>
                            <x-input-label for="ap_paterno" :value="__('Apellido Paterno')" />
                            <x-text-input wire:model="form.ap_paterno" id="ap_paterno" name="ap_paterno" type="text"
                                class="mt-1 block w-full" required autofocus autocomplete="ap_paterno" />
                            <x-input-error class="mt-2" :messages="$errors->get('form.ap_paterno')" />
                        </div>

                        <div>
                            <x-input-label for="ap_materno" :value="__('Apellido Materno')" />
                            <x-text-input wire:model="form.ap_materno" id="ap_materno" name="ap_materno" type="text"
                                class="mt-1 block w-full" required autofocus autocomplete="ap_materno" />
                            <x-input-error class="mt-2" :messages="$errors->get('form.ap_materno')" />
                        </div>

                        <div>
                            <x-input-label for="email" :value="__('Email')" />
                            <x-text-input wire:model="form.email" id="email" name="email" type="email"
                                class="mt-1 block w-full" required autofocus autocomplete="email" />
                            <x-input-error class="mt-2" :messages="$errors->get('form.email')" />
                        </div>

                        <!-- Solo mostrar password en creación -->
                        @if (!isset($this->form->coordinadorEdit))
                            <div>
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input wire:model="form.password" id="password" class="block mt-1 w-full"
                                    type="password" name="password" required autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('form.password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input wire:model="form.password_confirmation" id="password_confirmation"
                                    class="block mt-1 w-full" type="password" name="password_confirmation" required
                                    autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('form.password_confirmation')" class="mt-2" />
                            </div>
                        @else
                            <!-- Mensaje informativo para edición -->
                            <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                <p class="text-sm text-blue-800">
                                    <strong>Nota:</strong> El campo de email no se puede modificar por seguridad.
                                </p>
                            </div>
                        @endif

                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ request()->routeIs('admin.coordinadores.create') ? 'Crear' : 'Actualizar' }}</x-primary-button>
                            <x-danger-button :href="route('admin.coordinadores.index')" wire:navigate>{{ __('Cancelar') }}</x-danger-button>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>
</div>
