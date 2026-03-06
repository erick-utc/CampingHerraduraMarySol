<x-layouts::app title="{{ __('Editar usuario') }}">
    <div class="p-6 max-w-xl">
        <flux:heading size="xl">{{ __('Editar usuario') }}</flux:heading>

        <form method="POST" action="{{ route('users.update', $user) }}" class="mt-6 space-y-6">
            @csrf
            @method('PUT')

            @foreach($columns as $column)
                <div>
                    <flux:input
                        name="{{ $column }}"
                        type="text"
                        value="{{ old($column, $user->{$column}) }}"
                        :label="ucwords(str_replace(['_', '-'], ' ', $column))"
                    />
                    @error($column)
                        <flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>
                    @enderror
                </div>
            @endforeach

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:button as="a" :href="route('dashboard.users')">{{ __('Cancelar') }}</flux:button>
                <flux:button variant="filled" type="submit">{{ __('Guardar') }}</flux:button>
            </div>
        </form>

        <form method="POST" action="{{ route('users.destroy', $user) }}" class="mt-4" onsubmit="return confirm('{{ __('¿Está seguro?') }}');">
            @csrf
            @method('DELETE')
            <flux:button variant="danger">{{ __('Eliminar usuario') }}</flux:button>
        </form>
    </div>
</x-layouts::app>
