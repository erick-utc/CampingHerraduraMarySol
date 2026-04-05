<x-layouts::app :title="__('Hospedajes')">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Hospedajes') }}</flux:heading>

            @can('crear hospedajes')
                <flux:button as="a" :href="route('hospedajes.create')">
                    {{ __('Nuevo Hospedaje') }}
                </flux:button>
            @endcan
        </div>

        <div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <img
                src="{{ asset('images/camping1.jpg') }}"
                alt="{{ __('Imagen de hospedajes') }}"
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
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Número') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Tipo') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Aire Acondicionado') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Familiar') }}</th>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">{{ __('Parejas') }}</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($hospedajes as $hospedaje)
                        <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            <td class="px-4 py-2 text-sm">{{ $hospedaje->numeros }}</td>
                            <td class="px-4 py-2 text-sm">{{ $hospedaje->tipo }}</td>
                            <td class="px-4 py-2 text-sm">{{ $hospedaje->aire_acondicionado ? '✓' : '✗' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $hospedaje->familiar ? '✓' : '✗' }}</td>
                            <td class="px-4 py-2 text-sm">{{ $hospedaje->parejas ? '✓' : '✗' }}</td>
                            <td class="px-4 py-2 text-sm whitespace-nowrap">
                                @can('editar hospedajes')
                                    <a href="{{ route('hospedajes.edit', $hospedaje) }}">
                                        <flux:button>{{ __('Editar') }}</flux:button>
                                    </a>
                                @endcan
                                @can('borrar hospedajes')
                                    <form method="POST" action="{{ route('hospedajes.destroy', $hospedaje) }}" class="inline-block">
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
