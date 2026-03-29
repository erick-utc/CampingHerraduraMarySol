<x-layouts::app :title="__('Detalle de Factura')">
    <div class="p-6 max-w-4xl">
        <div class="flex items-center justify-between">
            <flux:heading size="xl">{{ __('Factura') }} {{ $factura->numero_factura }}</flux:heading>
            <a href="{{ route('facturas.index') }}">
                <flux:button>{{ __('Volver') }}</flux:button>
            </a>
        </div>

        @if(session('success'))
            <div class="mt-4 rounded-lg bg-green-100 p-4 text-green-800">{{ session('success') }}</div>
        @endif

        <div class="mt-6 grid gap-4 md:grid-cols-2">
            <div class="rounded-lg border p-4">
                <h3 class="font-semibold">{{ __('Datos de factura') }}</h3>
                <p class="text-sm">{{ __('Número:') }} {{ $factura->numero_factura }}</p>
                <p class="text-sm">{{ __('Fecha:') }} {{ optional($factura->fecha_factura)->format('d/m/Y H:i') }}</p>
                <p class="text-sm">{{ __('Subtotal:') }} ${{ number_format((float) $factura->subtotal, 2) }}</p>
                <p class="text-sm">{{ __('Impuesto:') }} ${{ number_format((float) $factura->impuesto, 2) }}</p>
                <p class="text-sm font-bold">{{ __('Total:') }} ${{ number_format((float) $factura->total, 2) }}</p>
            </div>

            <div class="rounded-lg border p-4">
                <h3 class="font-semibold">{{ __('Reserva asociada') }}</h3>
                <p class="text-sm">#{{ $factura->reserva_id }}</p>
                <p class="text-sm">
                    {{ $factura->reserva?->usuario?->cedula }} -
                    {{ $factura->reserva?->usuario?->nombre }}
                    {{ $factura->reserva?->usuario?->primerApellido }}
                    {{ $factura->reserva?->usuario?->segundoApellido }}
                </p>
                <p class="text-sm">{{ __('Hospedaje:') }} {{ $factura->reserva?->hospedaje?->numeros }} - {{ $factura->reserva?->hospedaje?->tipo }}</p>
            </div>
        </div>

        <div class="mt-6 rounded-lg border p-4">
            <h3 class="font-semibold mb-3">{{ __('Productos facturados (detalle)') }}</h3>
            <ul class="list-disc pl-6 space-y-2">
                @forelse(($factura->ventas ?? []) as $item)
                    <li>
                        @if(($item['tipo'] ?? null) === 'reserva')
                            <strong>{{ __('Reserva') }}: {{ $item['producto'] ?? __('Hospedaje') }}</strong>
                        @else
                            <strong>{{ $item['marca'] ?? '' }} {{ $item['producto'] ?? '' }} {{ $item['tamano'] ?? '' }}</strong>
                        @endif
                        - {{ __('Cantidad:') }} {{ $item['cantidad'] ?? 0 }}
                        - {{ __('Precio unitario:') }} ${{ number_format((float) ($item['precio_unitario'] ?? 0), 2) }}
                        - {{ __('Subtotal:') }} ${{ number_format((float) ($item['subtotal_linea'] ?? 0), 2) }}
                    </li>
                @empty
                    <li>{{ __('No hay productos asociados a esta factura.') }}</li>
                @endforelse
            </ul>
        </div>

        <div class="mt-6 flex gap-2">
            @can('editar facturas')
                <a href="{{ route('facturas.edit', $factura) }}">
                    <flux:button>{{ __('Editar') }}</flux:button>
                </a>
            @endcan
            @can('borrar facturas')
                <form method="POST" action="{{ route('facturas.destroy', $factura) }}" onsubmit="return confirm('{{ __('¿Está seguro?') }}');">
                    @csrf
                    @method('DELETE')
                    <flux:button variant="danger">{{ __('Eliminar') }}</flux:button>
                </form>
            @endcan
        </div>
    </div>
</x-layouts::app>
