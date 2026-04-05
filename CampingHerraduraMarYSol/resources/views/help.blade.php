<x-layouts::app :title="__('Ayuda')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6">
        <flux:heading size="xl">{{ __('Centro de ayuda') }}</flux:heading>

        <div class="overflow-hidden rounded-xl border border-zinc-200 dark:border-zinc-700">
            <img
                src="{{ asset('images/acerca2.jpg') }}"
                alt="{{ __('Imagen de ayuda') }}"
                class="aspect-[16/4] w-full object-cover object-center"
            >
        </div>

        <div class="rounded-xl border border-zinc-200 p-5 dark:border-zinc-700">
            <h3 class="text-sm font-semibold text-zinc-700 dark:text-zinc-200">{{ __('Manual de usuario (PDF)') }}</h3>
            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-300">
                {{ __('En esta seccion estara disponible el manual de usuario en formato PDF.') }}
            </p>
            <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                {{ __('Por el momento el archivo aun no ha sido creado. Cuando este listo, podra descargarlo desde aqui.') }}
            </p>

            <div class="mt-4">
                <flux:button variant="subtle" disabled>
                    {{ __('Manual proximamente') }}
                </flux:button>
            </div>
        </div>

        <div class="rounded-xl border border-dashed border-zinc-300 p-4 text-sm text-zinc-600 dark:border-zinc-700 dark:text-zinc-300">
            {{ __('Si necesita asistencia mientras se publica el manual, contacte al administrador del sistema.') }}
        </div>
    </div>
</x-layouts::app>
