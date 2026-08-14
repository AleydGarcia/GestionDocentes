<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use Illuminate\Http\Request;

class DocenteController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $query = Docente::query();

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nombre', 'like', "%{$q}%")
                    ->orWhere('rfc', 'like', "%{$q}%")
                    ->orWhere('curp', 'like', "%{$q}%");
            });
        }

        return view('dashboard.docentes', [
            'docentes' => $query->orderBy('nombre')->get(),
            'q' => $q,
        ]);
    }

    public function create()
    {
        return view('dashboard.docentes.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre_primer' => ['required', 'string', 'max:155'],
            'apellido_paterno' => ['nullable', 'string', 'max:80'],
            'apellido_materno' => ['nullable', 'string', 'max:80'],
            'especialidad' => ['required', 'string', 'in:educación especial,educación física,artística,cadis'],
            'domicilio' => ['required', 'string', 'max:255'],
            'localidad' => ['required', 'string', 'max:255'],
            'celular' => ['required', 'string', 'regex:/^\+?[0-9]{10}$/'],
            'estado_civil' => ['required', 'string', 'in:soltero/a,casado/a,divorciado/a,viudo/a'],
            'rfc' => ['required', 'string', 'size:13', 'regex:/^[A-ZÑ&]{4}[0-9]{6}[A-Z0-9]{3}$/i', 'unique:docentes,rfc'],
            'curp' => ['required', 'string', 'size:18', 'regex:/^[A-ZÑ&]{4}[0-9]{6}[HM][A-Z]{2}[B-DF-HJ-NP-TV-Z]{3}[A-Z0-9][0-9]$/i', 'unique:docentes,curp'],
            'ultimo_grado_estudios' => ['required', 'string', 'in:licenciatura,maestría,doctorado,técnico,otro'],
            'numero_pensiones' => ['required', 'string', 'regex:/^[0-9]+$/'],
            'clave_presupuestal' => ['required', 'string', 'max:50', 'unique:docentes,clave_presupuestal'],
        ], [
            'nombre_primer.required' => 'El nombre es obligatorio.',
            'celular.regex' => 'El celular debe contener sólo dígitos y puede incluir prefijo internacional.',
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.size' => 'El RFC debe tener 13 caracteres.',
            'rfc.regex' => 'El RFC debe seguir el formato válido de 13 caracteres.',
            'curp.required' => 'La CURP es obligatoria.',
            'curp.size' => 'La CURP debe tener 18 caracteres.',
            'curp.regex' => 'La CURP debe ser válida y tener el formato apropiado.',
        ]);

        $nombre = trim(implode(' ', array_filter([
            $data['nombre_primer'],
            $data['apellido_paterno'] ?? null,
            $data['apellido_materno'] ?? null,
        ])));

        $data['nombre'] = $nombre;
        unset($data['nombre_primer'], $data['apellido_paterno'], $data['apellido_materno']);

        $data['rfc'] = mb_strtoupper($data['rfc'], 'UTF-8');
        $data['curp'] = mb_strtoupper($data['curp'], 'UTF-8');

        Docente::create($data);

        return redirect()->route('docentes')->with('success', 'Docente creado correctamente.');
    }

    public function edit(Docente $docente)
    {
        return view('dashboard.docentes.edit', compact('docente'));
    }

    public function update(Request $request, Docente $docente)
    {
        $data = $request->validate([
            'nombre_primer' => ['required', 'string', 'max:155'],
            'apellido_paterno' => ['nullable', 'string', 'max:80'],
            'apellido_materno' => ['nullable', 'string', 'max:80'],
            'especialidad' => ['required', 'string', 'in:educación especial,educación física,artística,cadis'],
            'domicilio' => ['required', 'string', 'max:255'],
            'localidad' => ['required', 'string', 'max:255'],
            'celular' => ['required', 'string', 'regex:/^\+?[0-9]{10}$/'],
            'estado_civil' => ['required', 'string', 'in:soltero/a,casado/a,divorciado/a,viudo/a'],
            'rfc' => ['required', 'string', 'size:13', 'regex:/^[A-ZÑ&]{4}[0-9]{6}[A-Z0-9]{3}$/i', 'unique:docentes,rfc,' . $docente->id],
            'curp' => ['required', 'string', 'size:18', 'regex:/^[A-ZÑ&]{4}[0-9]{6}[HM][A-Z]{2}[B-DF-HJ-NP-TV-Z]{3}[A-Z0-9][0-9]$/i', 'unique:docentes,curp,' . $docente->id],
            'ultimo_grado_estudios' => ['required', 'string', 'in:licenciatura,maestría,doctorado,técnico,otro'],
            'numero_pensiones' => ['required', 'string', 'regex:/^[0-9]+$/'],
            'clave_presupuestal' => ['required', 'string', 'max:50', 'unique:docentes,clave_presupuestal,' . $docente->id],
        ], [
            'nombre_primer.required' => 'El nombre es obligatorio.',
            'celular.regex' => 'El celular debe contener sólo dígitos y puede incluir prefijo internacional.',
            'rfc.required' => 'El RFC es obligatorio.',
            'rfc.size' => 'El RFC debe tener 13 caracteres.',
            'rfc.regex' => 'El RFC debe seguir el formato válido de 13 caracteres.',
            'curp.size' => 'La CURP debe tener 18 caracteres.',
            'curp.regex' => 'La CURP debe ser válida y tener el formato apropiado.',
        ]);

        $nombre = trim(implode(' ', array_filter([
            $request->input('nombre_primer'),
            $request->input('apellido_paterno'),
            $request->input('apellido_materno'),
        ])));

        $docente->update(array_merge($request->only([
            'especialidad',
            'domicilio',
            'localidad',
            'celular',
            'estado_civil',
            'rfc',
            'curp',
            'ultimo_grado_estudios',
            'numero_pensiones',
            'clave_presupuestal',
        ]), [
            'nombre' => $nombre,
            'rfc' => mb_strtoupper($request->input('rfc'), 'UTF-8'),
            'curp' => mb_strtoupper($request->input('curp'), 'UTF-8'),
        ]));

        return redirect()->route('docentes')->with('success', 'Docente actualizado correctamente.');
    }

    public function destroy(Docente $docente)
    {
        $docente->delete();
        return redirect()->route('docentes')->with('success', 'Docente eliminado correctamente.');
    }

    public function expediente(Docente $docente)
    {
        $docente->load(['expedientes.tramites.escuela']);

        return view('dashboard.docentes.expediente', compact('docente'));
    }
}