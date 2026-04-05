<x-layouts::app :title="__('Reservas')">
    @php
        $habitaciones = $habitaciones ?? collect();
    @endphp

    <div class="p-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Reservas') }}</flux:heading>

            @can('crear reservas')
                <flux:button as="a" :href="route('reservas.create')">
                    {{ __('Nueva Reserva') }}
                </flux:button>
            @endcan
        </div>

        <div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <img
                src="{{ asset('images/camping2.avif') }}"
                alt="{{ __('Imagen de reservas en camping') }}"
                class="aspect-[16/4] w-full object-cover object-center"
            >
        </div>

        @if(session('success'))
            <div class="rounded-lg bg-green-100 p-4 text-green-800">{{ session('success') }}</div>
        @endif

        <div class="mt-6 overflow-auto">
            <table class="w-full text-left divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Usuario') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Habitación') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Entrada') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Salida') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Estado') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Desayuno') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Parqueo') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Precio') }}</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($reservas as $reserva)
                        <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            <td class="px-4 py-2 text-sm">
                                @if($reserva->usuario)
                                    {{ $reserva->usuario->cedula }} - {{ $reserva->usuario->nombre }} {{ $reserva->usuario->primerApellido }} {{ $reserva->usuario->segundoApellido }}
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm">
                                @if($reserva->hospedaje)
                                    <div>{{ $reserva->hospedaje->numeros }} - {{ $reserva->hospedaje->tipo }}</div>
                                    @can('ver hospedajes')
                                        <a href="{{ route('hospedajes.show', $reserva->hospedaje) }}" class="text-blue-600 hover:underline">{{ __('Ver características') }}</a>
                                    @endcan
                                @else
                                    -
                                @endif
                            </td>
                            <td class="px-4 py-2 text-sm">{{ optional($reserva->fecha_entrada)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 text-sm">{{ optional($reserva->fecha_salida)->format('d/m/Y H:i') }}</td>
                            <td class="px-4 py-2 text-sm">{{ ucfirst($reserva->estado) }}</td>
                            <td class="px-4 py-2 text-sm">{{ $reserva->desayuno ? 'Sí' : 'No' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $reserva->espacios_de_parqueo }}</td>
                            <td class="px-4 py-2 text-sm">${{ number_format($reserva->precio, 2) }}</td>
                            <td class="px-4 py-2 text-sm whitespace-nowrap">
                                @can('editar reservas')
                                    <a href="{{ route('reservas.edit', $reserva) }}">
                                        <flux:button>{{ __('Editar') }}</flux:button>
                                    </a>
                                @endcan
                                @can('borrar reservas')
                                    <form method="POST" action="{{ route('reservas.destroy', $reserva) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="danger">{{ __('Eliminar') }}</flux:button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-4 text-sm text-zinc-500">{{ __('No hay reservas registradas.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-8">
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
    </div>
</x-layouts::app>
