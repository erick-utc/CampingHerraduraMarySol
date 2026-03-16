<?php

namespace App\Http\Controllers;

use App\Models\Hospedaje;
use Illuminate\Http\Request;

class HospedajeController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:ver hospedajes')->only(['index', 'show']);
        $this->middleware('permission:crear hospedajes')->only(['create', 'store']);
        $this->middleware('permission:editar hospedajes')->only(['edit', 'update']);
        $this->middleware('permission:borrar hospedajes')->only('destroy');
    }

    public function index()
    {
        $hospedajes = Hospedaje::all();
        return view('hospedajes.index', compact('hospedajes'));
    }

    public function create()
    {
        return view('hospedajes.create');
    }

    public function show(Hospedaje $hospedaje)
    {
        return view('hospedajes.show', compact('hospedaje'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numeros' => 'required',
            'tipo' => 'required',
            'aire_acondicionado' => 'boolean',
            'familiar' => 'boolean',
            'parejas' => 'boolean',
        ]);

        Hospedaje::create($request->all());
        return redirect()->route('hospedajes.index')->with('success', 'Hospedaje creado correctamente.');
    }

    public function edit(Hospedaje $hospedaje)
    {
        return view('hospedajes.edit', compact('hospedaje'));
    }

    public function update(Request $request, Hospedaje $hospedaje)
    {
        $request->validate([
            'numeros' => 'required',
            'tipo' => 'required',
            'aire_acondicionado' => 'boolean',
            'familiar' => 'boolean',
            'parejas' => 'boolean',
        ]);

        $hospedaje->update($request->all());
        return redirect()->route('hospedajes.index')->with('success', 'Hospedaje actualizado correctamente.');
    }

    public function destroy(Hospedaje $hospedaje)
    {
        $hospedaje->delete();
        return redirect()->route('hospedajes.index')->with('success', 'Hospedaje eliminado correctamente.');
    }
}
