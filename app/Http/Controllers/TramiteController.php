<?php

namespace App\Http\Controllers;
use App\Http\Requests\StoreTramiteRequest;
use App\Models\Docente;
use App\Models\Escuela;
use App\Models\Expediente;
use App\Models\Evidencia;
use App\Models\Tramite;
use App\Models\Firmante;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TramiteController extends Controller
{
    public function create(Request $request)
    {
        $successModal = session('successModal', false);
        $tramite = $request->filled('tramite')
            ? Tramite::with(['expediente.docente', 'escuela', 'evidencias'])->findOrFail($request->integer('tramite'))
            : null;

        // allow overriding the initial wizard step via query param `step`
        $requestedStep = $request->input('step');
        // also accept a server-flashed initial step to be more robust
        $flashedStep = session('initialStep', null);
        $signMode = $request->boolean('sign');

        // If validation failed and the session contains errors, infer the appropriate wizard step
        $errorStep = null;
        $errors = session()->get('errors');
        if ($errors && $errors->any()) {
            if ($errors->has('docente_id')) {
                $errorStep = 1;
            } elseif ($errors->has('escuela_id')) {
                $errorStep = 2;
            } elseif ($errors->has('tipo_tramite') || $errors->has('fecha_inicio') || $errors->has('fecha_fin') || $errors->has('fecha_documento') || $errors->has('evidencia')) {
                // fields shown on step 3
                $errorStep = 3;
            } elseif ($errors->has('firmantes')) {
                $errorStep = 4;
            }
        }

        // pass any flashed selected firmantes back to the view so we can preselect them after redirect
        $selectedFirmantes = session('selectedFirmantes', []);

        return view('dashboard.crear-oficio', [
            'docentes' => Docente::orderBy('nombre')->get(),
            'escuelas' => Escuela::orderBy('nombre')->get(),
            'tiposTramite' => ['Pensionado', 'Pre pensionado', 'Jubilación', 'Prejubilatorio', 'Permisos económicos', 'Permiso sin goce de sueldo', 'Constancias', 'Justificantes', 'Comisión', 'Dictamen', 'Resumen clínico'],
            'tramite' => $tramite,
            'firmantes' => Firmante::orderBy('nombre')->get(),
            'initialStep' => $requestedStep ? (int) $requestedStep : ($errorStep ? $errorStep : ($flashedStep ? (int)$flashedStep : ($tramite ? ($successModal ? 4 : 3) : 1))),
            'successModal' => $successModal,
            'signMode' => $signMode,
            'selectedFirmantes' => $selectedFirmantes,
        ]);
    }

    public function store(StoreTramiteRequest $request)
    {
        $data = $request->validated();
        $tramite = null;
        $evidenceSaved = false;
        $evidencePath = null;

        Log::info('store() called for tramite', [
            'hasFile' => $request->hasFile('evidencia'),
            'file_present' => $request->hasFile('evidencia') ? true : false,
        ]);

        DB::transaction(function () use ($data, $request, &$tramite, &$evidenceSaved, &$evidencePath) {
            if (!empty($data['tramite_id'])) {
                $tramite = Tramite::findOrFail($data['tramite_id']);
            } else {
                $expediente = Expediente::create([
                    'docente_id' => $data['docente_id'],
                    'fecha_creacion' => now()->toDateString(),
                ]);

                $tramite = new Tramite(['expediente_id' => $expediente->id]);
            }

            $tramite->fill([
                'escuela_id' => $data['escuela_id'],
                'tipo_tramite' => $data['tipo_tramite'],
                'fecha_inicio' => $data['fecha_inicio'],
                'fecha_fin' => $data['fecha_fin'] ?? null,
                'fecha_documento' => $data['fecha_documento'],
            ]);
            $tramite->save();

            if ($request->hasFile('evidencia')) {
                $uploaded = $request->file('evidencia');
                Log::info('evidencia uploaded info', [
                    'originalName' => $uploaded->getClientOriginalName(),
                    'size' => $uploaded->getSize(),
                    'mime' => $uploaded->getClientMimeType(),
                    'isValid' => $uploaded->isValid(),
                    'error' => method_exists($uploaded, 'getError') ? $uploaded->getError() : null,
                ]);
                try {
                    $archivo = $request->file('evidencia');
                    $safeName = Str::random(16) . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $archivo->getClientOriginalName());
                    $path = $archivo->storeAs('evidencias', $safeName, 'public');

                    Evidencia::create([
                        'tramite_id' => $tramite->id,
                        'nombre_archivo' => $archivo->getClientOriginalName(),
                        'ruta' => $path,
                        'fecha_carga' => now()->toDateString(),
                    ]);

                    $evidenceSaved = true;
                    $evidencePath = $path;
                    Log::info('evidence saved', ['path' => $path]);
                } catch (\Throwable $e) {
                    Log::error('Error guardando evidencia', ['message' => $e->getMessage()]);
                    // Re-throw to rollback transaction
                    throw $e;
                }
            }
            // If no uploaded file in this request but an AJAX-uploaded path was provided, persist that as an Evidencia
            if (!$request->hasFile('evidencia') && $request->filled('evidence_path')) {
                try {
                    $pathProvided = $request->input('evidence_path');
                    $originalName = $request->input('evidence_name', basename($pathProvided));
                    Evidencia::create([
                        'tramite_id' => $tramite->id,
                        'nombre_archivo' => $originalName,
                        'ruta' => $pathProvided,
                        'fecha_carga' => now()->toDateString(),
                    ]);
                    $evidenceSaved = true;
                    $evidencePath = $pathProvided;
                    Log::info('evidence saved from ajax path', ['path' => $pathProvided]);
                } catch (\Throwable $e) {
                    Log::error('Error guardando evidencia desde path', ['message' => $e->getMessage()]);
                    throw $e;
                }
            }
            // persist selected firmantes as JSON on the tramite for reliable preselection
            $selectedFirmantes = $request->input('firmantes', []);
            $selectedFirmantes = is_array($selectedFirmantes) ? array_values(array_filter($selectedFirmantes)) : [];
            $tramite->firmantes = $selectedFirmantes;
            $tramite->save();
        });

        // flash selection for immediate preselection; also persisted on the model above
        $selected = $tramite->firmantes ?? [];

        $redirect = redirect()->route('crear-oficio', ['tramite' => $tramite->id, 'step' => 4])
            ->with('successModal', true)
            ->with('selectedFirmantes', $selected)
            ->with('initialStep', 4);

        if ($evidenceSaved) {
            $redirect->with('evidenceSaved', true)->with('evidencePath', $evidencePath);
        }

        return $redirect;
    }

    /**
     * AJAX endpoint to upload evidence immediately when the user selects a file.
     */
    public function uploadEvidencia(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:pdf,jpg,jpeg,png', 'max:10240'],
        ]);

        $f = $request->file('file');
        $safeName = Str::random(16) . '_' . preg_replace('/[^A-Za-z0-9._-]/', '_', $f->getClientOriginalName());
        $path = $f->storeAs('evidencias', $safeName, 'public');

        Log::info('ajax evidence uploaded', ['original' => $f->getClientOriginalName(), 'path' => $path]);

        return response()->json(['ok' => true, 'path' => $path, 'originalName' => $f->getClientOriginalName()]);
    }

    public function print(Request $request, Tramite $tramite)
    {
        $tramite->load(['expediente.docente', 'escuela', 'evidencias']);

        // determine template by tipo_tramite
        $tipo = $tramite->tipo_tramite;

        // Certain types do not produce a printable document and should redirect
        $noDocumentTypes = ['Dictamen', 'Resumen clínico', 'Resumen clinico'];
        if (in_array($tipo, $noDocumentTypes, true)) {
            return redirect()->route('tramites')->with('info', 'Este tipo de trámite no genera un documento imprimible.');
        }

        $nombramientoTypes = ['Pensionado', 'Pre pensionado', 'Jubilación', 'Prejubilatorio'];
        $oficioTypes = ['Permisos económicos', 'Permiso sin goce de sueldo', 'Constancias', 'Justificantes'];

        // Determine the requested firmantes in the following priority:
        // 1) query params (firmantes[]=1&firmantes[]=2), 2) prepared session key, 3) persisted on the tramite model
        $ids = [];
        $requested = $request->query('firmantes');
        if ($requested) {
            $ids = is_array($requested) ? $requested : [$requested];
        } else {
            $sessionKey = 'print_firmantes_' . $tramite->id;
            $sessionIds = session($sessionKey, null);
            if ($sessionIds && is_array($sessionIds) && count($sessionIds) > 0) {
                $ids = $sessionIds;
            } elseif ($tramite->firmantes && is_array($tramite->firmantes) && count($tramite->firmantes) > 0) {
                $ids = $tramite->firmantes;
            }
        }

        // If we have ids, fetch the firmantes and preserve the selection order
        if (!empty($ids)) {
            $firmantesById = \App\Models\Firmante::whereIn('id', $ids)->get()->keyBy('id');
            $ordered = collect($ids)->map(function ($id) use ($firmantesById) {
                return $firmantesById->get($id);
            })->filter();
            $firmantes = $ordered->values();
        } else {
            $firmantes = collect();
        }

        if (in_array($tipo, $nombramientoTypes, true)) {
            $view = 'dashboard.templates.nombramiento';
        } elseif (in_array($tipo, $oficioTypes, true)) {
            $view = 'dashboard.templates.oficio';
        } elseif ($tipo === 'Comisión') {
            $view = 'dashboard.templates.comision';
        } else {
            $view = 'dashboard.tramites.pdf';
        }

        $pdf = Pdf::loadView($view, compact('tramite', 'firmantes'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("oficio-{$tramite->id}.pdf");
    }

    public function preparePrint(Request $request, Tramite $tramite)
    {
        $ids = $request->input('firmantes', []);
        $ids = is_array($ids) ? array_values(array_filter($ids)) : [];
        // store temporarily in session keyed by tramite id
        $sessionKey = 'print_firmantes_' . $tramite->id;
        session([$sessionKey => $ids]);

        return response()->json(['ok' => true]);
    }
}
