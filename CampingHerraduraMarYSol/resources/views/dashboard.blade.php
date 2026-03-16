<x-layouts::app :title="__('Dashboard')">
    @php
        $metricas = $metricas ?? [
            'día' => ['reservas' => 0, 'personas' => 0, 'parqueos' => 0],
            'semana' => ['reservas' => 0, 'personas' => 0, 'parqueos' => 0],
            'mes' => ['reservas' => 0, 'personas' => 0, 'parqueos' => 0],
        ];
    @endphp

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <flux:heading size="xl">{{ __('Resumen de Reservaciones') }}</flux:heading>

        <div class="grid gap-4 md:grid-cols-3">
            <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                <h3 class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">{{ __('Reservaciones') }}</h3>
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>{{ __('Día') }}</span>
                        <span class="font-semibold">{{ $metricas['día']['reservas'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('Semana') }}</span>
                        <span class="font-semibold">{{ $metricas['semana']['reservas'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('Mes') }}</span>
                        <span class="font-semibold">{{ $metricas['mes']['reservas'] }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                <h3 class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">{{ __('Personas (usuarios únicos)') }}</h3>
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>{{ __('Día') }}</span>
                        <span class="font-semibold">{{ $metricas['día']['personas'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('Semana') }}</span>
                        <span class="font-semibold">{{ $metricas['semana']['personas'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('Mes') }}</span>
                        <span class="font-semibold">{{ $metricas['mes']['personas'] }}</span>
                    </div>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
                <h3 class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">{{ __('Parqueos') }}</h3>
                <div class="mt-3 space-y-2 text-sm">
                    <div class="flex justify-between">
                        <span>{{ __('Día') }}</span>
                        <span class="font-semibold">{{ $metricas['día']['parqueos'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('Semana') }}</span>
                        <span class="font-semibold">{{ $metricas['semana']['parqueos'] }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>{{ __('Mes') }}</span>
                        <span class="font-semibold">{{ $metricas['mes']['parqueos'] }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="rounded-xl border border-zinc-200 p-4 text-sm text-zinc-600 dark:border-zinc-700 dark:text-zinc-300">
            {{ __('Las métricas se calculan usando la fecha de entrada de cada reserva.') }}
        </div>
    </div>
</x-layouts::app>
