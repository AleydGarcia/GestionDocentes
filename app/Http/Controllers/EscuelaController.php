<?php

namespace App\Http\Controllers;

use App\Models\Escuela;
use Illuminate\Http\Request;

class EscuelaController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = Escuela::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('clave', 'like', "%{$q}%")
                    ->orWhere('director', 'like', "%{$q}%");
            });
        }

        return view('dashboard.escuelas', [
            'escuelas' => $query->orderBy('nombre')->get(),
            'q' => $q,
        ]);
    }

    public function create()
    {
        return view('dashboard.escuelas.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string'],
            'clave' => ['required', 'string', 'size:10', 'regex:/^[A-Z0-9]{10}$/i', 'unique:escuelas,clave'],
            'localidad' => ['required', 'string'],
            'director_titulo' => ['required', 'string', 'in:Dr.,Lic.,Mtro.,Prof.'],
            'director_nombre' => ['required', 'string', 'max:80'],
            'director_apellido_paterno' => ['nullable', 'string', 'max:80'],
            'director_apellido_materno' => ['nullable', 'string', 'max:80'],
        ], [
            'clave.size' => 'La clave debe tener exactamente 10 caracteres alfanuméricos.',
            'clave.regex' => 'La clave debe contener sólo letras y números.',
        ]);

        $data['director'] = trim(implode(' ', array_filter([
            $data['director_titulo'],
            $data['director_nombre'],
            $data['director_apellido_paterno'] ?? null,
            $data['director_apellido_materno'] ?? null,
        ])));

        unset($data['director_titulo'], $data['director_nombre'], $data['director_apellido_paterno'], $data['director_apellido_materno']);
        $data['clave'] = mb_strtoupper($data['clave'], 'UTF-8');

        Escuela::create($data);

        return redirect()->route('escuelas')->with('success', 'Escuela creada correctamente.');
    }

    public function edit(Escuela $escuela)
    {
        return view('dashboard.escuelas.edit', compact('escuela'));
    }

    public function update(Request $request, Escuela $escuela)
    {
        $data = $request->validate([
            'nombre' => ['required', 'string'],
            'clave' => ['required', 'string', 'size:10', 'regex:/^[A-Z0-9]{10}$/i', 'unique:escuelas,clave,' . $escuela->id],
            'localidad' => ['required', 'string'],
            'director_titulo' => ['required', 'string', 'in:Dr.,Lic.,Mtro.,Prof.'],
            'director_nombre' => ['required', 'string', 'max:80'],
            'director_apellido_paterno' => ['nullable', 'string', 'max:80'],
            'director_apellido_materno' => ['nullable', 'string', 'max:80'],
        ], [
            'clave.size' => 'La clave debe tener exactamente 10 caracteres alfanuméricos.',
            'clave.regex' => 'La clave debe contener sólo letras y números.',
        ]);

        $director = trim(implode(' ', array_filter([
            $data['director_titulo'],
            $data['director_nombre'],
            $data['director_apellido_paterno'] ?? null,
            $data['director_apellido_materno'] ?? null,
        ])));

        $escuela->update(array_merge($request->only(['nombre', 'localidad']), [
            'clave' => mb_strtoupper($data['clave'], 'UTF-8'),
            'director' => $director,
        ]));

        return redirect()->route('escuelas')->with('success', 'Escuela actualizada correctamente.');
    }

    public function destroy(Escuela $escuela)
    {
        if ($escuela->tramites()->exists()) {
            return redirect()->route('escuelas')->with('error', 'No se puede eliminar la escuela porque tiene trámites relacionados.');
        }

        $escuela->delete();

        return redirect()->route('escuelas')->with('success', 'Escuela eliminada correctamente.');
    }
}
