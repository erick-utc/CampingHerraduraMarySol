<x-layouts::app title="{{ __('Crear usuario') }}">
    <div class="p-6 max-w-xl">
        <flux:heading size="xl">{{ __('Crear usuario') }}</flux:heading>

        <form method="POST" action="{{ route('users.store') }}" class="mt-6 space-y-6">
            @csrf
            <div>
                <flux:input
                    name="nombre"
                    type="text"
                    value="{{ old('nombre') }}"
                    :label="__('Nombre')"
                    required
                />
                @error('nombre')
                    <flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>
                @enderror
            </div>

            <div>
                <flux:input
                    name="primerApellido"
                    type="text"
                    value="{{ old('primerApellido') }}"
                    :label="__('Primer apellido')"
                    required
                />
                @error('primerApellido')
                    <flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>
                @enderror
            </div>

            <div>
                <flux:input
                    name="segundoApellido"
                    type="text"
                    value="{{ old('segundoApellido') }}"
                    :label="__('Segundo apellido')"
                    required
                />
                @error('segundoApellido')
                    <flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>
                @enderror
            </div>

            <div>
                <flux:input
                    name="cedula"
                    type="text"
                    value="{{ old('cedula') }}"
                    :label="__('Cédula')"
                    required
                />
                @error('cedula')
                    <flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>
                @enderror
            </div>

            <div>
                <flux:input
                    name="telefono"
                    type="text"
                    value="{{ old('telefono') }}"
                    :label="__('Teléfono')"
                />
                @error('telefono')
                    <flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>
                @enderror
            </div>

            <div>
                <flux:input
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    :label="__('Correo electrónico')"
                    required
                />
                @error('email')
                    <flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>
                @enderror
            </div>

            <div>
                <flux:input
                    name="password"
                    type="password"
                    :label="__('Contraseña')"
                    required
                />
                @error('password')
                    <flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>
                @enderror
            </div>

            <div>
                <flux:input
                    name="password_confirmation"
                    type="password"
                    :label="__('Confirmar contraseña')"
                    required
                />
            </div>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:button as="a" :href="route('dashboard.users')">{{ __('Cancelar') }}</flux:button>
                <flux:button variant="filled" type="submit">{{ __('Crear') }}</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
