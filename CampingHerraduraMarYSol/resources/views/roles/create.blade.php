<x-layouts::app title="{{ __('Nuevo rol') }}">
    <div class="p-6 max-w-md">
        <flux:heading size="xl">{{ __('Nuevo rol') }}</flux:heading>

        <form method="POST" action="{{ route('roles.store') }}" class="mt-6 space-y-6">
            @csrf

            <div>
                <flux:input name="name" type="text" :label="__('Nombre')" value="{{ old('name') }}" />
                @error('name')<flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>@enderror
            </div>

            <div>
                <flux:heading size="sm">{{ __('Permissions') }}</flux:heading>
                <div class="grid gap-2 mt-2">
                    @foreach($permissions as $perm)
                        <label class="inline-flex items-center">
                            <input type="checkbox" name="permissions[]" value="{{ $perm->name }}" class="form-checkbox" />
                            <span class="ml-2">{{ $perm->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('permissions')<flux:text class="text-red-600 text-sm">{{ $message }}</flux:text>@enderror
            </div>

            <div class="flex justify-end space-x-2 rtl:space-x-reverse">
                <flux:button as="a" :href="route('roles.index')">{{ __('Cancelar') }}</flux:button>
                <flux:button type="submit">{{ __('Crear') }}</flux:button>
            </div>
        </form>
    </div>
</x-layouts::app>
