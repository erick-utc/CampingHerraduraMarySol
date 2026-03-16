<x-layouts::app title="{{ __('Asignar roles') }}">
    <div class="p-6 max-w-xl">
        <flux:heading size="xl">{{ __('Asignar roles a usuario') }}</flux:heading>

        <div class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
            {{ $user->nombre }} {{ $user->primerApellido }} {{ $user->segundoApellido }} ({{ $user->cedula }})
        </div>

        <form method="POST" action="{{ route('users.roles.update', $user) }}" class="mt-6 space-y-4">
            @csrf
            @method('PUT')

            <div class="space-y-2">
                @forelse($roles as $role)
                    <label class="flex items-center gap-2">
                        <input
                            type="checkbox"
                            name="roles[]"
                            value="{{ $role->name }}"
                            @checked(in_array($role->name, old('roles', $userRoles)))
                        >
                        <span>{{ $role->name }}</span>
                    </label>
                @empty
                    <p class="text-sm text-zinc-500">{{ __('No hay roles disponibles.') }}</p>
                @endforelse
            </div>

            @error('roles')
                <flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>
            @enderror
            @error('roles.*')
                <flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>
            @enderror

            <div class="flex justify-end gap-2">
                <flux:button as="a" :href="route('dashboard.users')">{{ __('Cancelar') }}</flux:button>
                <flux:button variant="filled" type="submit">{{ __('Guardar') }}</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
