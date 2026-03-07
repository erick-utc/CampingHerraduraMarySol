<x-layouts::app :title="__('Nuevo Producto')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6 max-w-2xl">
        <h1 class="text-2xl font-bold">{{ __('Nuevo Producto') }}</h1>
        <form action="{{ route('productos.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium">{{ __('Marca') }}</label>
                <input type="text" name="marca" class="w-full rounded-lg border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">{{ __('Producto') }}</label>
                <input type="text" name="producto" class="w-full rounded-lg border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">{{ __('Tamaño') }}</label>
                <input type="text" name="tamano" class="w-full rounded-lg border px-3 py-2" required>
            </div>
            <div>
                <label class="block text-sm font-medium">{{ __('Precio') }}</label>
                <input type="number" step="0.01" name="precio" class="w-full rounded-lg border px-3 py-2" required>
            </div>
            <div class="flex gap-2 pt-4">
                @can('crear productos')
                <button class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700">{{ __('Guardar') }}</button>
                @endcan
                <a href="{{ route('productos.index') }}" class="rounded-lg bg-neutral-600 px-4 py-2 text-white hover:bg-neutral-700">{{ __('Cancelar') }}</a>
            </div>
        </form>
    </div>
</x-layouts::app>
