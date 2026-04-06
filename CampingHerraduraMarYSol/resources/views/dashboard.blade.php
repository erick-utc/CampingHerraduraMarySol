<x-layouts::app :title="__('Dashboard')">
    @php
        $metricas = $metricas ?? [
            'día' => ['reservas' => 0, 'personas' => 0, 'parqueos' => 0],
            'semana' => ['reservas' => 0, 'personas' => 0, 'parqueos' => 0],
            'mes' => ['reservas' => 0, 'personas' => 0, 'parqueos' => 0],
        ];
        $reservasCliente = $reservasCliente ?? collect();
        $habitaciones = $habitaciones ?? collect();
    @endphp

    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <img
                src="{{ asset('images/dashboard2.webp') }}"
                alt="{{ __('Imagen principal del dashboard') }}"
                class="aspect-[16/4] w-full object-cover object-center"
            >
        </div>

        @if($esAdministrador ?? false)
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
        @elseif($esCliente ?? false)
            <flux:heading size="xl">{{ __('Mis Reservaciones') }}</flux:heading>

            <div class="overflow-auto rounded-xl border border-zinc-200 dark:border-zinc-700">
                <table class="w-full text-left divide-y divide-zinc-200 dark:divide-zinc-700">
                    <thead class="bg-zinc-50 dark:bg-zinc-800">
                        <tr>
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Hospedaje') }}</th>
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Entrada') }}</th>
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Salida') }}</th>
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Estado') }}</th>
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Parqueo') }}</th>
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Precio') }}</th>
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Nº Factura') }}</th>
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Fecha Factura') }}</th>
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Subtotal') }}</th>
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Impuesto') }}</th>
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Total') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($reservasCliente as $reserva)
                            <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800">
                                <td class="px-4 py-2 text-sm">
                                    @if($reserva->hospedaje)
                                        {{ $reserva->hospedaje->numeros }} - {{ ucfirst($reserva->hospedaje->tipo) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-2 text-sm">{{ optional($reserva->fecha_entrada)->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2 text-sm">{{ optional($reserva->fecha_salida)->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-2 text-sm">{{ ucfirst($reserva->estado) }}</td>
                                <td class="px-4 py-2 text-sm">{{ $reserva->espacios_de_parqueo }}</td>
                                <td class="px-4 py-2 text-sm">${{ number_format($reserva->precio, 2) }}</td>
                                <td class="px-4 py-2 text-sm">{{ $reserva->factura?->numero_factura ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm">{{ optional($reserva->factura?->fecha_factura)->format('d/m/Y') ?? '-' }}</td>
                                <td class="px-4 py-2 text-sm">{{ $reserva->factura ? '$' . number_format($reserva->factura->subtotal, 2) : '-' }}</td>
                                <td class="px-4 py-2 text-sm">{{ $reserva->factura ? '$' . number_format($reserva->factura->impuesto, 2) : '-' }}</td>
                                <td class="px-4 py-2 text-sm font-semibold">{{ $reserva->factura ? '$' . number_format($reserva->factura->total, 2) : '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="px-4 py-4 text-sm text-zinc-500">{{ __('No tienes reservaciones registradas.') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-2">
                <h2 class="mb-3 text-sm font-semibold text-zinc-700 dark:text-zinc-200">{{ __('Hospedajes disponibles (Habitaciones y Camping)') }}</h2>

                <div class="grid gap-4 md:grid-cols-3">
                    @forelse($habitaciones as $habitacion)
                        @php
                            $reservaUrl = route('reservas.create', ['hospedaje_id' => $habitacion->id]);
                        @endphp

                        <a href="{{ $reservaUrl }}" class="block rounded-lg border border-[#e3e3e0] bg-[#FDFDFC] p-4 transition hover:border-[#19140035] dark:border-[#3E3E3A] dark:bg-[#161615] dark:hover:border-[#62605b]" style="padding: 1rem;">
                            <div class="mb-2 flex items-center justify-between">
                                <p class="text-sm font-semibold">{{ __('Hospedaje') }} #{{ $habitacion->numeros }}</p>
                                <span class="rounded-sm border border-[#19140035] px-2 py-0.5 text-xs dark:border-[#3E3E3A]">
                                    {{ ucfirst($habitacion->tipo) }}
                                </span>
                            </div>

                            <ul class="mb-3 space-y-1 text-xs text-[#706f6c] dark:text-[#A1A09A]">
                                <li>{{ $habitacion->aire_acondicionado ? __('✓ Aire acondicionado') : __('✗ Sin aire acondicionado') }}</li>
                                <li>{{ $habitacion->familiar ? __('✓ Apta para familia') : __('✗ No familiar') }}</li>
                                <li>{{ $habitacion->parejas ? __('✓ Ideal para parejas') : __('✗ No ideal para parejas') }}</li>
                            </ul>

                            <div class="mt-4">
                                <span class="inline-block rounded-sm border border-black bg-[#1b1b18] px-3 py-1.5 text-xs text-white dark:border-[#eeeeec] dark:bg-[#eeeeec] dark:text-[#1C1C1A]">
                                    {{ __('Reservar') }}
                                </span>
                            </div>
                        </a>
                    @empty
                        <p class="text-xs text-zinc-500 dark:text-zinc-400">{{ __('No hay hospedajes registrados por el momento.') }}</p>
                    @endforelse
                </div>
            </div>
        @endif
    </div>
</x-layouts::app>
