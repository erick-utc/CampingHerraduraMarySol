<x-layouts::app :title="__('Productos')">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Productos') }}</flux:heading>

            @can('crear productos')
                <flux:button as="a" :href="route('productos.create')">
                    {{ __('Nuevo Producto') }}
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
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Marca') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Producto') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Tamaño') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Precio') }}</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($productos as $producto)
                        <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            <td class="px-4 py-2 text-sm">{{ $producto->marca }}</td>
                            <td class="px-4 py-2 text-sm">{{ $producto->producto }}</td>
                            <td class="px-4 py-2 text-sm">{{ $producto->tamano }}</td>
                            <td class="px-4 py-2 text-sm">${{ number_format($producto->precio, 2) }}</td>
                            <td class="px-4 py-2 text-sm whitespace-nowrap">
                                @can('editar productos')
                                    <a href="{{ route('productos.edit', $producto) }}">
                                        <flux:button>{{ __('Editar') }}</flux:button>
                                    </a>
                                @endcan
                                @can('borrar productos')
                                    <form method="POST" action="{{ route('productos.destroy', $producto) }}" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" variant="danger">{{ __('Eliminar') }}</flux:button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-layouts::app>
