<x-layouts::app title="{{ __('Permisos') }}">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Permisos') }}</flux:heading>

            @can('crear permisos')
                <flux:button as="a" :href="route('permissions.create')">
                    {{ __('Nuevo permiso') }}
                </flux:button>
            @endcan
        </div>

        <div class="mt-6 overflow-auto">
            <table class="w-full text-left divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">Nombre</th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($permissions as $perm)
                        <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            <td class="px-4 py-2 text-sm">{{ $perm->name }}</td>
                            <td class="px-4 py-2 text-sm whitespace-nowrap">
                                @can('editar permisos')
                                    <a href="{{ route('permissions.edit', $perm) }}">
                                        <flux:button size="sm">{{ __('Edit') }}</flux:button>
                                    </a>
                                @endcan
                                @can('borrar permisos')
                                    <form method="POST" action="{{ route('permissions.destroy', $perm) }}" class="inline-block" onsubmit="return confirm('{{ __('Are you sure?') }}');">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button size="sm" variant="danger">{{ __('Delete') }}</flux:button>
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
