<x-layouts::app title="{{ __('Roles') }}">
    <div class="p-6">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Roles') }}</flux:heading>

            @can('crear roles')
                <flux:button as="a" :href="route('roles.create')">
                    {{ __('Nuevo rol') }}
                </flux:button>
            @endcan
        </div>

        <div class="mt-6 overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <img
                src="{{ asset('images/roles.png') }}"
                alt="{{ __('Ilustración de roles') }}"
                class="object-cover object-center"
            >
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
                    @foreach($roles as $role)
                        <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            <td class="px-4 py-2 text-sm">{{ $role->name }}</td>
                            <td class="px-4 py-2 text-sm whitespace-nowrap">
                                @can('editar roles')
                                    <a href="{{ route('roles.edit', $role) }}">
                                        <flux:button>{{ __('Editar') }}</flux:button>
                                    </a>
                                @endcan
                                @can('borrar roles')
                                    <form method="POST" action="{{ route('roles.destroy', $role) }}" class="inline-block">
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
