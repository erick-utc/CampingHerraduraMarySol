<x-layouts::app :title="__('Reservas')">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Reservas') }}</flux:heading>

            @can('crear reservas')
                <flux:button as="a" :href="route('reservas.create')">
                    {{ __('Nueva Reserva') }}
                </flux:button>
            @endcan
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
    </div>
</x-layouts::app>
