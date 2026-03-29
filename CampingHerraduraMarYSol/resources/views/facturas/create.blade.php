<x-layouts::app :title="__('Nueva Factura')">
    <div class="p-6 max-w-4xl">
        <flux:heading size="xl">{{ __('Nueva Factura') }}</flux:heading>

        <form method="POST" action="{{ route('facturas.store') }}" class="mt-6 space-y-5" id="factura-form">
            @csrf

            <div>
                <label class="block text-sm font-medium">{{ __('Reserva') }}</label>
                <select name="reserva_id" id="reserva_id" class="w-full rounded-lg border px-3 py-2" required>
                    <option value="">{{ __('Seleccione una reserva') }}</option>
                    @foreach($reservas as $reserva)
                        <option value="{{ $reserva->id }}" data-precio-reserva="{{ (float) $reserva->precio }}" @selected(old('reserva_id') == $reserva->id)>
                            #{{ $reserva->id }} - {{ $reserva->usuario?->cedula }} - {{ $reserva->usuario?->nombre }} {{ $reserva->usuario?->primerApellido }} - {{ $reserva->hospedaje?->numeros }}
                        </option>
                    @endforeach
                </select>
                @error('reserva_id')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">{{ __('Fecha de factura') }}</label>
                <input type="datetime-local" name="fecha_factura" value="{{ old('fecha_factura', now()->format('Y-m-d\\TH:i')) }}" class="w-full rounded-lg border px-3 py-2" required>
                @error('fecha_factura')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">{{ __('Impuesto') }}</label>
                <select name="porcentaje_impuesto" id="porcentaje_impuesto" class="w-full rounded-lg border px-3 py-2" required>
                    @for($i = 1; $i <= 15; $i++)
                        <option value="{{ $i }}" @selected(old('porcentaje_impuesto', 13) == $i)>{{ $i }}%</option>
                    @endfor
                </select>
                @error('porcentaje_impuesto')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium">{{ __('Productos de la factura') }}</label>
                <input
                    type="text"
                    id="filtro_productos"
                    class="mt-2 w-full rounded-lg border px-3 py-2"
                    placeholder="{{ __('Filtrar productos por marca, nombre o tamaño') }}"
                >
                <ul class="mt-2 space-y-2 rounded-lg border p-3">
                    @foreach($productos as $producto)
                        <li class="producto-item flex items-center justify-between gap-3 border-b pb-2 last:border-b-0" data-search="{{ mb_strtolower($producto->marca.' '.$producto->producto.' '.$producto->tamano) }}">
                            <label class="flex items-center gap-2">
                                <input type="checkbox" class="producto-check" name="productos[]" value="{{ $producto->id }}" data-precio="{{ (float) $producto->precio }}" @checked(in_array($producto->id, old('productos', [])))>
                                <span>{{ $producto->marca }} - {{ $producto->producto }} ({{ $producto->tamano }}) - ${{ number_format((float) $producto->precio, 2) }}</span>
                            </label>
                            <input type="number" min="1" name="cantidades[{{ $producto->id }}]" value="{{ old('cantidades.' . $producto->id, 1) }}" class="w-24 rounded-lg border px-2 py-1 producto-cantidad" data-producto-id="{{ $producto->id }}">
                        </li>
                    @endforeach
                </ul>
                @error('productos')<p class="text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            <div class="rounded-lg border p-4 text-sm">
                <div class="flex justify-between"><span>{{ __('Reserva') }}</span><strong id="reserva-preview">$0.00</strong></div>
                <div class="flex justify-between"><span>{{ __('Productos') }}</span><strong id="productos-preview">$0.00</strong></div>
                <div class="flex justify-between"><span>{{ __('Subtotal') }}</span><strong id="subtotal-preview">$0.00</strong></div>
                <div class="flex justify-between"><span>{{ __('Impuesto') }}</span><strong id="impuesto-preview">$0.00</strong></div>
                <div class="flex justify-between text-base"><span>{{ __('Total') }}</span><strong id="total-preview">$0.00</strong></div>
            </div>

            <div class="flex gap-2">
                <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700">{{ __('Guardar') }}</button>
                <a href="{{ route('facturas.index') }}" class="rounded-lg bg-neutral-600 px-4 py-2 text-white hover:bg-neutral-700">{{ __('Cancelar') }}</a>
            </div>
        </form>
    </div>

    <script>
        function recalculateFacturaTotals() {
            const checks = document.querySelectorAll('.producto-check');
            const impuestoSelect = document.getElementById('porcentaje_impuesto');
            const reservaSelect = document.getElementById('reserva_id');

            let subtotalProductos = 0;

            checks.forEach((check) => {
                if (!check.checked) return;

                const precio = Number(check.dataset.precio || 0);
                const cantidadInput = document.querySelector('.producto-cantidad[data-producto-id="' + check.value + '"]');
                const cantidad = Number(cantidadInput?.value || 1);

                subtotalProductos += (precio * (cantidad > 0 ? cantidad : 1));
            });

            const selectedOption = reservaSelect?.options[reservaSelect.selectedIndex];
            const subtotalReserva = Number(selectedOption?.dataset.precioReserva || 0);
            const subtotal = subtotalReserva + subtotalProductos;

            const porcentaje = Number(impuestoSelect?.value || 0);
            const impuesto = subtotal * (porcentaje / 100);
            const total = subtotal + impuesto;

            document.getElementById('reserva-preview').textContent = '$' + subtotalReserva.toFixed(2);
            document.getElementById('productos-preview').textContent = '$' + subtotalProductos.toFixed(2);
            document.getElementById('subtotal-preview').textContent = '$' + subtotal.toFixed(2);
            document.getElementById('impuesto-preview').textContent = '$' + impuesto.toFixed(2);
            document.getElementById('total-preview').textContent = '$' + total.toFixed(2);
        }

        document.querySelectorAll('.producto-check, .producto-cantidad, #porcentaje_impuesto, #reserva_id').forEach((element) => {
            element.addEventListener('input', recalculateFacturaTotals);
            element.addEventListener('change', recalculateFacturaTotals);
        });

        const filtroProductos = document.getElementById('filtro_productos');
        if (filtroProductos) {
            filtroProductos.addEventListener('input', function () {
                const term = this.value.toLowerCase().trim();
                document.querySelectorAll('.producto-item').forEach((item) => {
                    const searchable = item.dataset.search || '';
                    item.style.display = term === '' || searchable.includes(term) ? '' : 'none';
                });
            });
        }

        recalculateFacturaTotals();
    </script>
</x-layouts::app>
