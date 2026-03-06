<x-layouts::app title="{{ __('Editar permiso') }}">
    <div class="p-6 max-w-md">
        <flux:heading size="xl">{{ __('Editar permiso') }}</flux:heading>

        <form method="POST" action="{{ route('permissions.update', $permission) }}" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <flux:input name="name" type="text" :label="__('Nombre')" value="{{ old('name', $permission->name) }}" />
                @error('name')<flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>@enderror
            </div>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:button as="a" :href="route('permissions.index')">{{ __('Cancelar') }}</flux:button>
                <flux:button type="submit">{{ __('Guardar') }}</flux:button>
            </div>
        </form>

        @can('borrar permisos')
            <form method="POST" action="{{ route('permissions.destroy', $permission) }}" class="mt-4" onsubmit="return confirm('{{ __('¿Está seguro?') }}');">
                @csrf
                @method('DELETE')
                <flux:button variant="danger">{{ __('Eliminar permiso') }}</flux:button>
            </form>
        @endcan
    </div>
</x-layouts::app>
