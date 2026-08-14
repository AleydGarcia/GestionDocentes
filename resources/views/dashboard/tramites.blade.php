@extends('layouts.app')

@section('title', 'Trámites')

@section('content')
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3 gap-2">
        <h2 class="page-title mb-2 mb-md-0">
            <i class="bi bi-clock-history"></i> Gestión de Trámites
        </h2>

        <div>
            <a href="{{ route('crear-oficio') }}" class="btn btn-primary btn-sm"><i class="bi bi-plus-lg"></i> Agregar Trámite</a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @php
        // Dictamen and Resumen clínico do not generate a document but do require evidence upload
        $noDocTypes = ['Dictamen', 'Resumen clínico', 'Resumen clinico'];
    @endphp

    <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>Folio</th>
                    <th>Expediente</th>
                    <th>Docente</th>
                    <th>Escuela</th>
                    <th>Tipo de Trámite</th>
                    <th>Fecha Inicio</th>
                    <th>Fecha Fin</th>
                    <th>Fecha documento</th>
                    <th>Evidencia</th>
                    <th>Registrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tramites as $tramite)
                    @php
                        $docente = optional($tramite->expediente->docente)->nombre;
                        $escuela = optional($tramite->escuela)->nombre;
                    @endphp
                    <tr>
                        <td>#TRM-{{ $tramite->id }}</td>
                        <td>#EXP-{{ $tramite->expediente_id }}</td>
                        <td>{{ $docente ?? 'N/D' }}</td>
                        <td>{{ $escuela ?? 'N/D' }}</td>
                        <td>{{ $tramite->tipo_tramite }}</td>
                        <td>{{ optional($tramite->fecha_inicio)->format('d/m/Y') }}</td>
                        <td>{{ optional($tramite->fecha_fin)->format('d/m/Y') ?? 'No aplica' }}</td>
                        <td>{{ optional($tramite->fecha_documento)->format('d/m/Y') }}</td>
                        <td>
                            @php $e = $tramite->evidencias->first(); @endphp
                            @if($e)
                                <a href="{{ route('tramites.evidencia', $e) }}" target="_blank" class="btn btn-sm btn-outline-secondary">Abrir</a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @php
                                $created = $tramite->created_at;
                                $createdText = $created ? \Carbon\Carbon::parse($created)->locale('es')->translatedFormat('j \de F \de Y') : '-';
                            @endphp
                            {{ $createdText }}
                        </td>
                        <td>
                            @if(in_array($tramite->tipo_tramite, $noDocTypes))
                                @if($e)
                                    <a href="{{ route('tramites.evidencia', $e) }}" target="_blank" class="btn btn-sm btn-outline-secondary" title="Ver evidencia">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                @else
                                    <a href="{{ route('tramites.edit', $tramite) }}" class="btn btn-sm btn-outline-secondary" title="Editar trámite">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                @endif
                            @else
                                    <a href="{{ route('crear-oficio', ['tramite' => $tramite->id, 'step' => 4]) }}" class="btn btn-sm btn-outline-primary" title="Preparar impresión / editar datos">
                                        <i class="bi bi-printer"></i>
                                    </a>
                            @endif

                            @if($e)
                                <a href="{{ route('tramites.evidencia', $e) }}" target="_blank" class="btn btn-sm btn-outline-secondary ms-1" title="Abrir evidencia">
                                    <i class="bi bi-file-earmark-arrow-up"></i>
                                </a>
                            @endif
                            {{-- Firmar button removed (printing only) --}}
                            <form method="POST" action="{{ route('tramites.destroy', $tramite) }}" class="d-inline" onsubmit="return confirm('¿Eliminar este trámite? Esta acción no se puede deshacer.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i> Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" class="text-center">No hay trámites registrados aún.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <nav aria-label="Paginación" class="mt-4">
        <ul class="pagination justify-content-center">
            <li class="page-item disabled"><a class="page-link" href="#">Anterior</a></li>
            <li class="page-item active"><a class="page-link" href="#">1</a></li>
            <li class="page-item"><a class="page-link" href="#">2</a></li>
            <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
        </ul>
    </nav>

    <div class="dashboard-footer" style="margin-left: -50px; margin-right: -50px;">
        <p>&copy; 2026 Sistema de Gestión de Permisos e Incidencias Docentes.</p>
    </div>
@endsection
