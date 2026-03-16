<x-layouts::app :title="__('Reporte: Uso de Habitaciones')">
    <div class="p-6">
        <style>
            @media print {
                .no-print {
                    display: none !important;
                }

                body {
                    background: white !important;
                }
            }
        </style>

        @php
            $periodoSeleccionado = request('periodo_tipo') ? ucfirst(request('periodo_tipo')) : __('Todos');
            $totalReservas = $registros->sum('total_reservas');
            $totalNoches = $registros->sum('total_noches');
        @endphp

        <div class="rounded-xl border border-zinc-200 bg-white p-5 shadow-sm dark:border-zinc-700 dark:bg-zinc-900">
            <div class="flex flex-col gap-2 md:flex-row md:items-start md:justify-between">
                <div>
                    <p class="text-sm font-semibold text-green-700 dark:text-green-400">{{ __('Camping Herradura Mar y Sol') }}</p>
                    <flux:heading size="xl">{{ __('Reporte: Uso de Habitaciones') }}</flux:heading>
                    <p class="mt-1 text-sm text-zinc-600 dark:text-zinc-300">{{ __('Reporte dinámico de ocupación de habitaciones según el período seleccionado.') }}</p>
                </div>
                <div class="text-sm text-zinc-600 dark:text-zinc-300">
                    <p><span class="font-semibold">{{ __('Filtro aplicado:') }}</span> {{ $periodoSeleccionado }}</p>
                    <p><span class="font-semibold">{{ __('Emitido:') }}</span> {{ now()->format('d/m/Y H:i') }}</p>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <flux:heading size="lg">{{ __('Datos del reporte') }}</flux:heading>
        </div>

        <div class="no-print mt-4 flex flex-wrap items-end justify-between gap-3">
            <form method="GET" class="flex flex-wrap items-end gap-2">
                <div>
                    <label class="block text-xs font-medium">{{ __('Período') }}</label>
                    <select name="periodo_tipo" class="rounded-lg border px-3 py-2 text-sm">
                        <option value="">{{ __('Todos') }}</option>
                        <option value="dia" @selected(request('periodo_tipo') === 'dia')>{{ __('Día') }}</option>
                        <option value="semana" @selected(request('periodo_tipo') === 'semana')>{{ __('Semana') }}</option>
                        <option value="mes" @selected(request('periodo_tipo') === 'mes')>{{ __('Mes') }}</option>
                    </select>
                </div>
                <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">{{ __('Filtrar') }}</button>
                <a href="{{ route('reportes.habitaciones') }}" class="rounded-lg bg-neutral-600 px-4 py-2 text-sm text-white hover:bg-neutral-700">{{ __('Limpiar') }}</a>
            </form>

            <div>
                <button type="button" onclick="window.print()" class="rounded-lg bg-blue-600 px-4 py-2 text-sm text-white hover:bg-blue-700">
                    {{ __('Imprimir') }}
                </button>
            </div>
        </div>

        <div class="mt-6 overflow-auto">
            <table class="w-full text-left divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-2 text-sm">{{ __('Período') }}</th>
                        <th class="px-4 py-2 text-sm">{{ __('Habitación') }}</th>
                        <th class="px-4 py-2 text-sm">{{ __('Reservas') }}</th>
                        <th class="px-4 py-2 text-sm">{{ __('Noches') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($registros as $row)
                        <tr>
                            <td class="px-4 py-2 text-sm">{{ strtoupper($row->periodo_tipo) }} {{ optional($row->periodo_inicio)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 text-sm">{{ $row->hospedaje?->numeros }} - {{ $row->hospedaje?->tipo }}</td>
                            <td class="px-4 py-2 text-sm">{{ $row->total_reservas }}</td>
                            <td class="px-4 py-2 text-sm">{{ $row->total_noches }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="px-4 py-4 text-sm text-zinc-500">{{ __('Sin datos') }}</td></tr>
                    @endforelse
                </tbody>
                @if($registros->count() > 0)
                    <tfoot class="bg-zinc-100 dark:bg-zinc-800/80">
                        <tr>
                            <td colspan="2" class="px-4 py-3 text-sm font-semibold">{{ __('Totalización') }}</td>
                            <td class="px-4 py-3 text-sm font-bold">{{ $totalReservas }}</td>
                            <td class="px-4 py-3 text-sm font-bold">{{ $totalNoches }}</td>
                        </tr>
                    </tfoot>
                @endif
            </table>
        </div>

        <div class="mt-4 rounded-lg bg-zinc-50 px-4 py-3 text-sm text-zinc-600 dark:bg-zinc-900 dark:text-zinc-300">
            <p>{{ __('Pie de página del reporte: resumen consolidado del uso de habitaciones.') }}</p>
            <p>{{ __('La línea de totalización corresponde a los registros visibles en la tabla actual.') }}</p>
        </div>

        <div class="no-print mt-4">{{ $registros->links() }}</div>
    </div>
</x-layouts::app>
