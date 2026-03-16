<x-layouts::app :title="__('Detalle de Habitación')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6 max-w-2xl">
        <h1 class="text-2xl font-bold">{{ __('Detalle de Habitación') }}</h1>

        <div class="rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 space-y-3">
            <div>
                <span class="font-semibold">{{ __('Número:') }}</span>
                <span>{{ $hospedaje->numeros }}</span>
            </div>
            <div>
                <span class="font-semibold">{{ __('Tipo:') }}</span>
                <span>{{ $hospedaje->tipo }}</span>
            </div>
            <div>
                <span class="font-semibold">{{ __('Aire Acondicionado:') }}</span>
                <span>{{ $hospedaje->aire_acondicionado ? __('Sí') : __('No') }}</span>
            </div>
            <div>
                <span class="font-semibold">{{ __('Familiar:') }}</span>
                <span>{{ $hospedaje->familiar ? __('Sí') : __('No') }}</span>
            </div>
            <div>
                <span class="font-semibold">{{ __('Parejas:') }}</span>
                <span>{{ $hospedaje->parejas ? __('Sí') : __('No') }}</span>
            </div>
        </div>

        <div class="flex gap-2 pt-2">
            <a href="{{ route('reservas.index') }}" class="rounded-lg bg-neutral-600 px-4 py-2 text-white hover:bg-neutral-700">{{ __('Volver a Reservas') }}</a>
            <a href="{{ route('hospedajes.index') }}" class="rounded-lg bg-zinc-500 px-4 py-2 text-white hover:bg-zinc-600">{{ __('Ver Hospedajes') }}</a>
        </div>
    </div>
</x-layouts::app>
