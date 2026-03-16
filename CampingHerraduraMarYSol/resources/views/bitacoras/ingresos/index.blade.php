<x-layouts::app :title="__('Bitácora de Ingresos')">
    <div class="p-6">
        <flux:heading size="xl">{{ __('Bitácora de Ingresos') }}</flux:heading>

        <form method="GET" action="{{ route('bitacoras.ingresos.index') }}" class="mt-4 grid gap-3 md:grid-cols-5">
            <div>
                <label class="block text-xs font-medium">{{ __('Desde') }}</label>
                <input type="date" name="fecha_desde" value="{{ request('fecha_desde') }}" class="w-full rounded-lg border px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium">{{ __('Hasta') }}</label>
                <input type="date" name="fecha_hasta" value="{{ request('fecha_hasta') }}" class="w-full rounded-lg border px-3 py-2 text-sm">
            </div>
            <div>
                <label class="block text-xs font-medium">{{ __('Evento') }}</label>
                <select name="evento" class="w-full rounded-lg border px-3 py-2 text-sm">
                    <option value="">{{ __('Todos') }}</option>
                    <option value="login" @selected(request('evento') === 'login')>LOGIN</option>
                    <option value="logout" @selected(request('evento') === 'logout')>LOGOUT</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-medium">{{ __('Usuario/Correo') }}</label>
                <input type="text" name="usuario" value="{{ request('usuario') }}" class="w-full rounded-lg border px-3 py-2 text-sm" placeholder="{{ __('Nombre o email') }}">
            </div>
            <div class="flex items-end gap-2">
                <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-sm text-white hover:bg-green-700">{{ __('Filtrar') }}</button>
                <a href="{{ route('bitacoras.ingresos.index') }}" class="rounded-lg bg-neutral-600 px-4 py-2 text-sm text-white hover:bg-neutral-700">{{ __('Limpiar') }}</a>
            </div>
        </form>

        <div class="mt-6 overflow-auto">
            <table class="w-full text-left divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Fecha') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Evento') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Nombre') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Correo') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('IP') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @forelse($ingresos as $item)
                        <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            <td class="px-4 py-2 text-sm">{{ optional($item->ocurrio_en)->format('d/m/Y H:i:s') }}</td>
                            <td class="px-4 py-2 text-sm">{{ strtoupper($item->evento) }}</td>
                            <td class="px-4 py-2 text-sm">{{ $item->nombre }}</td>
                            <td class="px-4 py-2 text-sm">{{ $item->email }}</td>
                            <td class="px-4 py-2 text-sm">{{ $item->ip_address }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-sm text-zinc-500">{{ __('No hay registros de ingresos.') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $ingresos->links() }}
        </div>
    </div>
</x-layouts::app>
