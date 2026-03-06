<x-layouts::app title="{{ __('Nuevo permiso') }}">
    <div class="p-6 max-w-md">
        <flux:heading size="xl">{{ __('Nuevo permiso') }}</flux:heading>

        <form method="POST" action="{{ route('permissions.store') }}" class="mt-6 space-y-6">
            @csrf

            <div>
                <flux:input name="name" type="text" :label="__('Nombre')" value="{{ old('name') }}" />
                @error('name')<flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>@enderror
            </div>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:button as="a" :href="route('permissions.index')">{{ __('Cancelar') }}</flux:button>
                <flux:button type="submit">{{ __('Crear') }}</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
