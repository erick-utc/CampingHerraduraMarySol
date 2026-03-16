<x-layouts::app :title="__('Editar Reserva')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl p-6 max-w-3xl">
        <h1 class="text-2xl font-bold">{{ __('Editar Reserva') }}</h1>

        <form action="{{ route('reservas.update', $reserva) }}" method="POST" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-sm font-medium">{{ __('Buscar usuario por cédula') }}</label>
                <input type="text" id="buscar_usuario" class="w-full rounded-lg border px-3 py-2" placeholder="{{ __('Escriba cédula o nombre') }}">
            </div>

            <div>
                <label class="block text-sm font-medium">{{ __('Usuario') }}</label>
                <select name="usuario_id" id="usuario_id" class="w-full rounded-lg border px-3 py-2" required>
                    <option value="">{{ __('Seleccione un usuario') }}</option>
                    @foreach($usuarios as $usuario)
                        <option
                            value="{{ $usuario->id }}"
                            data-search="{{ mb_strtolower($usuario->cedula.' '.$usuario->nombre.' '.$usuario->primerApellido.' '.$usuario->segundoApellido) }}"
                            @selected(old('usuario_id', $reserva->usuario_id) == $usuario->id)
                        >
                            {{ $usuario->cedula }} - {{ $usuario->nombre }} {{ $usuario->primerApellido }}
                        </option>
                    @endforeach
                </select>
                @error('usuario_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium">{{ __('Buscar habitación') }}</label>
                <input type="text" id="buscar_hospedaje" class="w-full rounded-lg border px-3 py-2" placeholder="{{ __('Escriba número o tipo') }}">
            </div>

            <div>
                <label class="block text-sm font-medium">{{ __('Habitación') }}</label>
                <select name="hospedaje_id" id="hospedaje_id" class="w-full rounded-lg border px-3 py-2" required>
                    <option value="">{{ __('Seleccione una habitación') }}</option>
                    @foreach($hospedajes as $hospedaje)
                        <option
                            value="{{ $hospedaje->id }}"
                            data-search="{{ mb_strtolower($hospedaje->numeros.' '.$hospedaje->tipo) }}"
                            @selected(old('hospedaje_id', $reserva->hospedaje_id) == $hospedaje->id)
                        >
                            {{ $hospedaje->numeros }} - {{ $hospedaje->tipo }}
                        </option>
                    @endforeach
                </select>
                @error('hospedaje_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div>
                    <label class="block text-sm font-medium">{{ __('Fecha Entrada') }}</label>
                    <input type="datetime-local" name="fecha_entrada" class="w-full rounded-lg border px-3 py-2" value="{{ old('fecha_entrada', optional($reserva->fecha_entrada)->format('Y-m-d\\TH:i')) }}" required>
                    @error('fecha_entrada')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">{{ __('Fecha Salida') }}</label>
                    <input type="datetime-local" name="fecha_salida" class="w-full rounded-lg border px-3 py-2" value="{{ old('fecha_salida', optional($reserva->fecha_salida)->format('Y-m-d\\TH:i')) }}" required>
                    @error('fecha_salida')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <div>
                    <label class="block text-sm font-medium">{{ __('Precio') }}</label>
                    <input type="number" step="0.01" min="0" name="precio" class="w-full rounded-lg border px-3 py-2" value="{{ old('precio', $reserva->precio) }}" required>
                    @error('precio')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">{{ __('Espacios de Parqueo') }}</label>
                    <input type="number" min="0" name="espacios_de_parqueo" class="w-full rounded-lg border px-3 py-2" value="{{ old('espacios_de_parqueo', $reserva->espacios_de_parqueo) }}">
                    @error('espacios_de_parqueo')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium">{{ __('Estado') }}</label>
                    <select name="estado" class="w-full rounded-lg border px-3 py-2" required>
                        <option value="creado" @selected(old('estado', $reserva->estado) === 'creado')>{{ __('Creado') }}</option>
                        <option value="en espera" @selected(old('estado', $reserva->estado) === 'en espera')>{{ __('En espera') }}</option>
                        <option value="aprobado" @selected(old('estado', $reserva->estado) === 'aprobado')>{{ __('Aprobado') }}</option>
                        <option value="cancelado" @selected(old('estado', $reserva->estado) === 'cancelado')>{{ __('Cancelado') }}</option>
                    </select>
                    @error('estado')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="flex items-center gap-2">
                    <input type="checkbox" name="desayuno" value="1" @checked(old('desayuno', $reserva->desayuno))>
                    <span>{{ __('Incluye desayuno') }}</span>
                </label>
            </div>

            <div class="flex gap-2 pt-4">
                <button type="submit" class="rounded-lg bg-green-600 px-4 py-2 text-white hover:bg-green-700">{{ __('Actualizar') }}</button>
                <a href="{{ route('reservas.index') }}" class="rounded-lg bg-neutral-600 px-4 py-2 text-white hover:bg-neutral-700">{{ __('Cancelar') }}</a>
            </div>
        </form>
    </div>

    <script>
        function setupSearch(inputId, selectId) {
            const input = document.getElementById(inputId);
            const select = document.getElementById(selectId);
            if (!input || !select) return;

            input.addEventListener('input', function () {
                const term = this.value.toLowerCase().trim();
                const options = select.querySelectorAll('option');

                options.forEach((option, index) => {
                    if (index === 0) {
                        option.hidden = false;
                        return;
                    }

                    const searchable = option.dataset.search || '';
                    option.hidden = term !== '' && !searchable.includes(term);
                });
            });
        }

        setupSearch('buscar_usuario', 'usuario_id');
        setupSearch('buscar_hospedaje', 'hospedaje_id');
    </script>
</x-layouts::app>
