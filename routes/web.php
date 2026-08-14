<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Models\Docente;
use App\Models\Escuela;
use App\Models\Expediente;
use App\Models\Tramite;
use App\Models\Evidencia;
use Illuminate\Support\Facades\Storage;

// inicio principal: si está autenticado va al dashboard, si no al login
Route::get('/', function () {
    return redirect()->route(auth()->check() ? 'dashboard' : 'login');
});

// RUTAS DE AUTENTICACIÓN
// GET es Mostrar formulario
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');

// POST cuando envía el formulario
Route::post('/login', [LoginController::class, 'authenticate'])->name('login.post');

// Cerrar sesión
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// DEBUG routes: simple upload form and handler to verify file delivery
Route::get('/debug-upload', function () {
    $token = csrf_token();
    return <<<HTML
<!doctype html>
<html><body>
<form method="POST" action="/debug-upload" enctype="multipart/form-data">
<input type="file" name="evidencia" />
<input type="hidden" name="_token" value="$token" />
<button type="submit">Upload</button>
</form>
</body></html>
HTML;
});

Route::post('/debug-upload', function (\Illuminate\Http\Request $request) {
    $files = $request->allFiles();
    logger()->info('debug-upload received files', ['files' => array_keys($files)]);
    if ($request->hasFile('evidencia')) {
        $f = $request->file('evidencia');
        $name = time().'_'.$f->getClientOriginalName();
        $path = $f->storeAs('evidencias', $name, 'public');
        logger()->info('debug-upload stored', ['path' => $path]);
        return response()->json(['ok' => true, 'path' => $path]);
    }
    return response()->json(['ok' => false, 'files' => array_keys($files)]);
});

// solo usuarios autenticados (o sea el único que puede iniciar sesión puede ver los dashboards)
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard', [
            'docentesCount' => Docente::count(),
            'escuelasCount' => Escuela::count(),
            'tramitesCount' => Tramite::count(),
        ]);
    })->name('dashboard');

    // Dashboard: Crear Oficio
    Route::get('/dashboard/crear-oficio', [App\Http\Controllers\TramiteController::class, 'create'])->name('crear-oficio');
    Route::post('/dashboard/crear-oficio', [App\Http\Controllers\TramiteController::class, 'store'])->name('crear-oficio.submit');
    Route::post('/dashboard/crear-oficio/upload-evidencia', [App\Http\Controllers\TramiteController::class, 'uploadEvidencia'])->name('crear-oficio.upload_evidencia');

    // Dashboard: Docentes
    Route::get('/dashboard/docentes', [App\Http\Controllers\DocenteController::class, 'index'])->name('docentes');
    Route::get('/dashboard/docentes/create', [App\Http\Controllers\DocenteController::class, 'create'])->name('docentes.create');
    Route::post('/dashboard/docentes', [App\Http\Controllers\DocenteController::class, 'store'])->name('docentes.store');
    Route::get('/dashboard/docentes/{docente}/edit', [App\Http\Controllers\DocenteController::class, 'edit'])->name('docentes.edit');
    Route::put('/dashboard/docentes/{docente}', [App\Http\Controllers\DocenteController::class, 'update'])->name('docentes.update');
    Route::delete('/dashboard/docentes/{docente}', [App\Http\Controllers\DocenteController::class, 'destroy'])->name('docentes.destroy');

    // Dashboard: Escuelas
    Route::get('/dashboard/escuelas', [App\Http\Controllers\EscuelaController::class, 'index'])->name('escuelas');
    Route::get('/dashboard/escuelas/create', [App\Http\Controllers\EscuelaController::class, 'create'])->name('escuelas.create');
    Route::post('/dashboard/escuelas', [App\Http\Controllers\EscuelaController::class, 'store'])->name('escuelas.store');
    Route::get('/dashboard/escuelas/{escuela}/edit', [App\Http\Controllers\EscuelaController::class, 'edit'])->name('escuelas.edit');
    Route::put('/dashboard/escuelas/{escuela}', [App\Http\Controllers\EscuelaController::class, 'update'])->name('escuelas.update');
    Route::delete('/dashboard/escuelas/{escuela}', [App\Http\Controllers\EscuelaController::class, 'destroy'])->name('escuelas.destroy');

    // Dashboard: Trámites
    Route::get('/dashboard/tramites', function () {
        return view('dashboard.tramites', [
            'tramites' => Tramite::with(['expediente.docente', 'escuela', 'evidencias'])->orderBy('fecha_inicio', 'desc')->get(),
        ]);
    })->name('tramites');

    Route::get('/dashboard/tramites/{tramite}/edit', function (Tramite $tramite) {
        // Redirect to the creation wizard step 4 to view the summary/last details (read-only)
        return redirect()->route('crear-oficio', ['tramite' => $tramite->id, 'step' => 4]);
    })->name('tramites.edit');

    Route::put('/dashboard/tramites/{tramite}', function (Request $request, Tramite $tramite) {
        $request->validate([
            'escuela_id' => ['required', 'exists:escuelas,id'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
            'fecha_documento' => ['required', 'date'],
            'tipo_tramite' => ['required', 'string'],
        ]);

        $tramite->update([
            'escuela_id' => $request->escuela_id,
            'fecha_inicio' => $request->fecha_inicio,
            'fecha_fin' => $request->fecha_fin ?: null,
            'fecha_documento' => $request->fecha_documento,
            'tipo_tramite' => $request->tipo_tramite,
        ]);

        return redirect()->route('tramites')->with('success', 'Trámite actualizado correctamente.');
    })->name('tramites.update');

    Route::delete('/dashboard/tramites/{tramite}', function (Tramite $tramite) {
        $tramite->delete();
        return redirect()->back()->with('success', 'Trámite eliminado correctamente.');
    })->name('tramites.destroy');

    Route::post('/dashboard/tramites/{tramite}/prepare-print', [App\Http\Controllers\TramiteController::class, 'preparePrint'])->name('tramites.prepare_print');
    Route::get('/dashboard/tramites/{tramite}/imprimir', [App\Http\Controllers\TramiteController::class, 'print'])->name('tramites.print');

    // Download / open evidencia securely from storage
    Route::get('/dashboard/tramites/evidencia/{evidencia}', function (Evidencia $evidencia) {
        if (!Storage::disk('public')->exists($evidencia->ruta)) {
            abort(404);
        }
        return Storage::disk('public')->download($evidencia->ruta, $evidencia->nombre_archivo);
    })->name('tramites.evidencia');

    // Dashboard: Docentes expediente
    Route::get('/dashboard/docentes/{docente}/expediente', [App\Http\Controllers\DocenteController::class, 'expediente'])->name('docentes.expediente');

    // Dashboard: Reportes
    Route::get('/dashboard/reportes', function (Request $request) {
        $query = Tramite::with(['expediente.docente', 'escuela']);

        if ($request->filled('fecha_inicio')) {
            $query->where('fecha_inicio', '>=', $request->fecha_inicio);
        }

        if ($request->filled('fecha_fin')) {
            $query->where('fecha_inicio', '<=', $request->fecha_fin);
        }

        if ($request->filled('tipo_tramite') && $request->tipo_tramite !== 'Todos') {
            $query->where('tipo_tramite', $request->tipo_tramite);
        }

        if ($request->filled('docente_id') && $request->docente_id !== 'Todos') {
            $query->whereHas('expediente', function ($sub) use ($request) {
                $sub->where('docente_id', $request->docente_id);
            });
        }

        if ($request->filled('escuela_id') && $request->escuela_id !== 'Todos') {
            $query->where('escuela_id', $request->escuela_id);
        }

        $tramites = (clone $query)->orderBy('fecha_inicio', 'desc')->get();
        $tramitesPorTipo = (clone $query)
            ->selectRaw('tipo_tramite, count(*) as cantidad')
            ->groupBy('tipo_tramite')
            ->get();

        // Build summary counts for main categories and "otros"
        $mainTypes = ['Nombramientos', 'Justificantes', 'Constancias'];
        $countByType = $tramites->groupBy('tipo_tramite')->map->count();
        $mainCounts = [];
        $sumMain = 0;
        foreach ($mainTypes as $mt) {
            $c = $countByType->get($mt, 0);
            $mainCounts[$mt] = $c;
            $sumMain += $c;
        }
        $otrosCount = $tramites->count() - $sumMain;

        // Per-docente stats: counts per main type and others
        $docentesList = Docente::orderBy('nombre')->get(['id', 'nombre']);
        $docentesStats = $docentesList->map(function ($doc) use ($tramites, $mainTypes) {
            $t = $tramites->filter(function ($tr) use ($doc) {
                return $tr->expediente && $tr->expediente->docente_id == $doc->id;
            });
            $row = [
                'docente' => $doc,
                'total' => $t->count(),
            ];
            $sum = 0;
            foreach ($mainTypes as $mt) {
                $cnt = $t->where('tipo_tramite', $mt)->count();
                $row[$mt] = $cnt;
                $sum += $cnt;
            }
            $row['Otros'] = $t->count() - $sum;
            return (object) $row;
        });

        return view('dashboard.reportes', [
            'docentes' => Docente::orderBy('nombre')->get(['id', 'nombre']),
            'escuelas' => Escuela::orderBy('nombre')->get(['id', 'nombre']),
            'tiposTramite' => Tramite::select('tipo_tramite')->distinct()->orderBy('tipo_tramite')->pluck('tipo_tramite'),
            'tramites' => $tramites,
            'tramitesCount' => $tramites->count(),
            'tramitesCompletadosCount' => $tramites->whereNotNull('fecha_fin')->count(),
            'tramitesPendientesCount' => $tramites->whereNull('fecha_fin')->count(),
            'tramitesPorTipo' => $tramitesPorTipo,
            'mainCounts' => $mainCounts,
            'otrosCount' => $otrosCount,
            'docentesStats' => $docentesStats,
            'filters' => $request->only(['fecha_inicio', 'fecha_fin', 'tipo_tramite', 'docente_id', 'escuela_id']),
        ]);
    })->name('reportes');
});