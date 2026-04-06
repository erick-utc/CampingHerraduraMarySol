<section class="w-full">
    <div class="p-6">
        <div class="flex items-center justify-between gap-3">
            <flux:heading size="xl">{{ __('Usuarios') }}</flux:heading>

            @can('crear usuarios')
                <flux:button as="a" :href="route('users.create')">
                    {{ __('Nuevo usuario') }}
                </flux:button>
            @endcan
        </div>

        <div class="mt-6 inline-block overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700 w-full">
            <img
                src="{{ asset('images/usuarios.jpg') }}"
                alt="{{ __('Ilustración de usuarios') }}"
                class="aspect-[16/4] w-full object-cover object-center"
            >
        </div>

        <div class="mt-6 overflow-auto">
            <table class="w-full text-left divide-y divide-zinc-200 dark:divide-zinc-700">
                <thead class="bg-zinc-50 dark:bg-zinc-800">
                    <tr>
                        @foreach($columns as $column)
                            <th class="px-4 py-2 text-sm font-medium text-zinc-600 dark:text-zinc-400">
                                {{ ucwords(str_replace(['_', '-'], ' ', $column)) }}
                            </th>
                        @endforeach
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                    @foreach($users as $user)
                        <tr class="hover:bg-zinc-100 dark:hover:bg-zinc-800">
                            @foreach($columns as $column)
                                <td class="px-4 py-2 text-sm">
                                    {{ $user->{$column} }}
                                </td>
                            @endforeach
                            <td class="px-4 py-2 text-sm whitespace-nowrap">
                                @can('editar usuarios')
                                    <a href="{{ route('users.edit', $user) }}" class="inline-block">
                                        <flux:button size="sm">{{ __('Editar') }}</flux:button>
                                    </a>

                                    <a href="{{ route('users.roles.edit', $user) }}" class="inline-block">
                                        <flux:button size="sm">{{ __('Roles') }}</flux:button>
                                    </a>
                                @endcan

                                @can('borrar usuarios')
                                    <form
                                        method="POST"
                                        action="{{ route('users.destroy', $user) }}"
                                        class="inline-block"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" size="sm" variant="danger">{{ __('Eliminar') }}</flux:button>
                                    </form>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
