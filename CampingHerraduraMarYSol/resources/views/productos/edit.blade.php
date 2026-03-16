<x-layouts::app :title="__('Editar Producto')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6 max-w-2xl">
        <h1 class="text-2xl font-bold">{{ __('Editar Producto') }}</h1>
        <form action="{{ route('productos.update', $producto) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <div>
                <label class="block text-sm font-medium">{{ __('Marca') }}</label>
                <input type="text" name="marca" class="w-full rounded-lg border px-3 py-2" value="{{ $producto->marca }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium">{{ __('Producto') }}</label>
                <input type="text" name="producto" class="w-full rounded-lg border px-3 py-2" value="{{ $producto->producto }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium">{{ __('Tamaño') }}</label>
                <input type="text" name="tamano" class="w-full rounded-lg border px-3 py-2" value="{{ $producto->tamano }}" required>
            </div>
            <div>
                <label class="block text-sm font-medium">{{ __('Precio') }}</label>
                <input type="number" step="0.01" name="precio" class="w-full rounded-lg border px-3 py-2" value="{{ $producto->precio }}" required>
            </div>
            <div class="flex gap-2 pt-4">
                <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700">{{ __('Actualizar') }}</button>
                <a href="{{ route('productos.index') }}" class="rounded-lg bg-neutral-600 px-4 py-2 text-white hover:bg-neutral-700">{{ __('Cancelar') }}</a>
            </div>
        </form>
    </div>
</x-layouts::app>
