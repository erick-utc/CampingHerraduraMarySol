<x-layouts::app title="{{ __('Editar rol') }}">
    <div class="p-6 max-w-md">
        <flux:heading size="xl">{{ __('Editar rol') }}</flux:heading>

        <form method="POST" action="{{ route('roles.update', $role) }}" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <flux:input name="name" type="text" :label="__('Nombre')" value="{{ old('name', $role->name) }}" />
                @error('name')<flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>@enderror
            </div>

            <div>
                <flux:heading size="sm">{{ __('Permisos') }}</flux:heading>
                <div class="grid gap-2 mt-2">
                    @foreach($permissions as $perm)
                        <label class="inline-flex items-center">
                            <input
                                type="checkbox"
                                name="permissions[]"
                                value="{{ $perm->name }}"
                                {{ $role->hasPermissionTo($perm) ? 'checked' : '' }}
                                class="form-checkbox"
                            />
                            <span class="ml-2">{{ $perm->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('permissions')<flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>@enderror
            </div>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:button as="a" :href="route('roles.index')">{{ __('Cancelar') }}</flux:button>
                <flux:button type="submit">{{ __('Guardar') }}</flux:button>
            </div>
        </form>

        @can('borrar roles')
            <form method="POST" action="{{ route('roles.destroy', $role) }}" class="mt-4" onsubmit="return confirm('{{ __('¿Está seguro?') }}');">
                @csrf
                @method('DELETE')
                <flux:button variant="danger">{{ __('Eliminar rol') }}</flux:button>
            </form>
        @endcan
    </div>
</x-layouts::app>
