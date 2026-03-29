<x-layouts::app :title="__('Acerca de')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <flux:heading size="xl">{{ __('Acerca de la aplicacion') }}</flux:heading>

        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <img
                src="{{ asset('images/acerca1.webp') }}"
                alt="{{ __('Imagen de acerca de') }}"
                class="aspect-video w-full object-cover object-center"
            >
        </div>

        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <h3 class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">{{ __('Informacion general') }}</h3>
            <div class="mt-3 grid gap-2 text-sm">
                <div class="flex justify-between gap-4">
                    <span>{{ __('Nombre de la app') }}</span>
                    <span class="font-semibold">{{ config('app.name') }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span>{{ __('Entorno') }}</span>
                    <span class="font-semibold">{{ app()->environment() }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span>{{ __('URL base') }}</span>
                    <span class="font-semibold">{{ config('app.url') }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span>{{ __('Zona horaria') }}</span>
                    <span class="font-semibold">{{ config('app.timezone') }}</span>
                </div>
            </div>
        </div>

        @if(Auth::user()->hasRole('administrador'))
        <div class="rounded-xl border border-zinc-200 p-4 dark:border-zinc-700">
            <h3 class="text-sm font-semibold text-zinc-600 dark:text-zinc-300">{{ __('Stack tecnico') }}</h3>
            <div class="mt-3 grid gap-2 text-sm">
                <div class="flex justify-between gap-4">
                    <span>{{ __('Laravel') }}</span>
                    <span class="font-semibold">{{ app()->version() }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span>{{ __('PHP') }}</span>
                    <span class="font-semibold">{{ PHP_VERSION }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span>{{ __('Driver de base de datos') }}</span>
                    <span class="font-semibold">{{ config('database.default') }}</span>
                </div>
                <div class="flex justify-between gap-4">
                    <span>{{ __('Canal de logs') }}</span>
                    <span class="font-semibold">{{ config('logging.default') }}</span>
                </div>
            </div>
        </div>
        @endif
        <div class="rounded-xl border border-zinc-200 p-4 text-sm text-zinc-600 dark:border-zinc-700 dark:text-zinc-300">
            <p class="font-semibold text-zinc-700 dark:text-zinc-200">{{ __('Proposito de la aplicacion') }}</p>
            <p class="mt-2">
                {{ __('Camping Herradura MarySol centraliza el control de reservaciones, hospedajes, usuarios, facturacion, bitacoras y reportes en un solo panel administrativo.') }}
            </p>
        </div>
    </div>
</x-layouts::app>
