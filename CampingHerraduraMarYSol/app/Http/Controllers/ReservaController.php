<?php

namespace App\Http\Controllers;

use App\Mail\ReservaEventoMail;
use App\Models\Hospedaje;
use App\Models\Reserva;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ReservaController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver reservas')->only('index');
        $this->middleware('permission:crear reservas')->only(['create', 'store']);
        $this->middleware('permission:editar reservas')->only(['edit', 'update']);
        $this->middleware('permission:borrar reservas')->only('destroy');
    }

    public function index()
    {
        $reservas = Reserva::with(['usuario', 'hospedaje'])->orderByDesc('id')->get();

        return view('reservas.index', compact('reservas'));
    }

    public function create(Request $request)
    {
        $usuarios = User::orderBy('name')->get();
        $hospedajes = Hospedaje::orderBy('numeros')->get();
        $hospedajeSeleccionado = $request->integer('hospedaje_id');

        return view('reservas.create', compact('usuarios', 'hospedajes', 'hospedajeSeleccionado'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'usuario_id' => ['required', 'exists:users,id'],
            'hospedaje_id' => ['required', 'exists:hospedajes,id'],
            'precio' => ['required', 'numeric', 'min:0'],
            'fecha_entrada' => ['required', 'date'],
            'fecha_salida' => ['required', 'date', 'after:fecha_entrada'],
            'espacios_de_parqueo' => ['nullable', 'integer', 'min:0'],
            'estado' => ['required', Rule::in(['creado', 'en espera', 'aprobado', 'cancelado'])],
            'desayuno' => ['nullable', 'boolean'],
        ]);

        $data['espacios_de_parqueo'] = $data['espacios_de_parqueo'] ?? 0;
        $data['desayuno'] = $request->boolean('desayuno');

        $reserva = Reserva::create($data);
        $this->sendReservaEmail('creada', $reserva->fresh()->load(['usuario', 'hospedaje']));

        return redirect()->route('reservas.index')->with('success', 'Reserva creada correctamente.');
    }

    public function edit(Reserva $reserva)
    {
        $usuarios = User::orderBy('name')->get();
        $hospedajes = Hospedaje::orderBy('numeros')->get();

        return view('reservas.edit', compact('reserva', 'usuarios', 'hospedajes'));
    }

    public function update(Request $request, Reserva $reserva)
    {
        $data = $request->validate([
            'usuario_id' => ['required', 'exists:users,id'],
            'hospedaje_id' => ['required', 'exists:hospedajes,id'],
            'precio' => ['required', 'numeric', 'min:0'],
            'fecha_entrada' => ['required', 'date'],
            'fecha_salida' => ['required', 'date', 'after:fecha_entrada'],
            'espacios_de_parqueo' => ['nullable', 'integer', 'min:0'],
            'estado' => ['required', Rule::in(['creado', 'en espera', 'aprobado', 'cancelado'])],
            'desayuno' => ['nullable', 'boolean'],
        ]);

        $data['espacios_de_parqueo'] = $data['espacios_de_parqueo'] ?? 0;
        $data['desayuno'] = $request->boolean('desayuno');

        $reserva->update($data);
        $this->sendReservaEmail('actualizada', $reserva->fresh()->load(['usuario', 'hospedaje']));

        return redirect()->route('reservas.index')->with('success', 'Reserva actualizada correctamente.');
    }

    public function destroy(Reserva $reserva)
    {
        $reserva->load(['usuario', 'hospedaje']);
        $snapshot = clone $reserva;

        $reserva->delete();
        $this->sendReservaEmail('eliminada', $snapshot);

        return redirect()->route('reservas.index')->with('success', 'Reserva eliminada correctamente.');
    }

    private function sendReservaEmail(string $accion, Reserva $reserva): void
    {
        $email = $reserva->usuario?->email;

        if (! $email) {
            return;
        }

        try {
            Mail::to($email)->send(new ReservaEventoMail($accion, $reserva));
        } catch (\Throwable $e) {
            Log::channel('mail')->error('No se pudo enviar correo de reserva.', [
                'accion' => $accion,
                'reserva_id' => $reserva->id,
                'email' => $email,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
