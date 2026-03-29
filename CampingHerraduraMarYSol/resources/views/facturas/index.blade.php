<x-layouts::app :title="__('Facturas')">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Facturas') }}</flux:heading>

            @can('crear facturas')
                <flux:button as="a" :href="route('facturas.create')">
                    {{ __('Nueva Factura') }}
                </flux:button>
            @endcan
        </div>

        <div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <img
                src="{{ asset('images/camping3.jpg') }}"
                alt="{{ __('Imagen de facturación de camping') }}"
                class="aspect-video w-full object-cover object-center"
            >
        </div>

        @if(session('success'))
            <div class="mt-4 rounded-lg bg-green-100 p-4 text-green-800">{{ session('success') }}</div>
        @endif

        <div class="mt-6 overflow-auto">
            <table class="w-full text-left divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Número') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Reserva') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Cliente') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Subtotal') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Impuesto') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Total') }}</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($facturas as $factura)
                        <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            <td class="px-4 py-2 text-sm">{{ $factura->numero_factura }}</td>
                            <td class="px-4 py-2 text-sm">#{{ $factura->reserva_id }}</td>
                            <td class="px-4 py-2 text-sm">
                                {{ $factura->reserva?->usuario?->cedula }} -
                                {{ $factura->reserva?->usuario?->nombre }}
                                {{ $factura->reserva?->usuario?->primerApellido }}
                            </td>
                            <td class="px-4 py-2 text-sm">${{ number_format((float) $factura->subtotal, 2) }}</td>
                            <td class="px-4 py-2 text-sm">${{ number_format((float) $factura->impuesto, 2) }}</td>
                            <td class="px-4 py-2 text-sm font-semibold">${{ number_format((float) $factura->total, 2) }}</td>
                            <td class="px-4 py-2 text-sm whitespace-nowrap">
                                <a href="{{ route('facturas.show', $factura) }}" class="inline-block">
                                    <flux:button>{{ __('Ver detalle') }}</flux:button>
                                </a>
                                @can('editar facturas')
                                    <a href="{{ route('facturas.edit', $factura) }}" class="inline-block">
                                        <flux:button>{{ __('Editar') }}</flux:button>
                                    </a>
                                @endcan
                                @can('borrar facturas')
                                    <form method="POST" action="{{ route('facturas.destroy', $factura) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="danger">{{ __('Eliminar') }}</flux:button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-4 text-sm text-zinc-500">{{ __('No hay facturas registradas.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
