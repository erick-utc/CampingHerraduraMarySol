<x-layouts::app :title="__('Editar Hospedaje')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6 max-w-2xl">
        <h1 class="text-2xl font-bold">{{ __('Editar Hospedaje') }}</h1>
        <form action="{{ route('hospedajes.update', $hospedaje) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium">{{ __('Número') }}</label>
                <input type="text" name="numeros" class="w-full rounded-lg border px-3 py-2" value="{{ $hospedaje->numeros }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium">{{ __('Tipo') }}</label>
                <input type="text" name="tipo" class="w-full rounded-lg border px-3 py-2" value="{{ $hospedaje->tipo }}" required>
            </div>
            <div class="space-y-2">
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="aire_acondicionado" value="1" {{ $hospedaje->aire_acondicionado ? 'checked' : '' }}>
                    <span>{{ __('Aire Acondicionado') }}</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="familiar" value="1" {{ $hospedaje->familiar ? 'checked' : '' }}>
                    <span>{{ __('Familiar') }}</span>
                </label>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="parejas" value="1" {{ $hospedaje->parejas ? 'checked' : '' }}>
                    <span>{{ __('Parejas') }}</span>
                </label>
            </div>
            <div class="flex gap-2 pt-4">
                @can('editar hospedajes')
                <button class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700">{{ __('Actualizar') }}</button>
                @endcan
                <a href="{{ route('hospedajes.index') }}" class="rounded-lg bg-neutral-600 px-4 py-2 text-white hover:bg-neutral-700">{{ __('Cancelar') }}</a>
            </div>
        </form>
    </div>
</x-layouts::app>
