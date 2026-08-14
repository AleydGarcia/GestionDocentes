@extends('layouts.app')

@section('title', 'Expediente del Docente')

@section('content')
    <h2 class="page-title">
        <i class="bi bi-file-earmark-text"></i> Expediente de {{ $docente->nombre }}
    </h2>

    <div class="mb-4">
        <a href="{{ route('docentes') }}" class="btn btn-secondary">Regresar a Docentes</a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <h5 class="section-title">Datos del docente</h5>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <strong>Nombre:</strong> {{ $docente->nombre }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>RFC:</strong> {{ $docente->rfc }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>CURP:</strong> {{ $docente->curp }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Celular:</strong> {{ $docente->celular }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Estado civil:</strong> {{ $docente->estado_civil }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Localidad:</strong> {{ $docente->localidad }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Especialidad:</strong> {{ $docente->especialidad }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Clave presupuestal:</strong> {{ $docente->clave_presupuestal }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Último grado:</strong> {{ $docente->ultimo_grado_estudios }}
                </div>
                <div class="col-md-6 mb-3">
                    <strong>Pensiones:</strong> {{ $docente->numero_pensiones }}</n                </div>
                <div class="col-md-12 mb-3">
                    <strong>Domicilio:</strong> {{ $docente->domicilio }}
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="section-title">Expedientes</h5>

            @if($docente->expedientes->isEmpty())
                <p>No hay expedientes registrados para este docente.</p>
            @else
                @foreach($docente->expedientes as $expediente)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <strong>Expediente #EXP-{{ $expediente->id }}</strong>
                                <div class="text-muted">Creado: {{ optional($expediente->fecha_creacion)->format('d/m/Y') }}</div>
                            </div>
                        </div>

                        @if($expediente->tramites->isEmpty())
                            <p>No hay trámites vinculados a este expediente.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-custom table-sm">
                                    <thead>
                                        <tr>
                                            <th>Trámite</th>
                                            <th>Escuela</th>
                                            <th>Fecha inicio</th>
                                            <th>Fecha fin</th>
                                            <th>Fecha documento</th>
                                            <th>Registrado</th>
                                            <th>Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expediente->tramites as $tramite)
                                            <tr>
                                                <td>{{ $tramite->tipo_tramite }}</td>
                                                <td>{{ optional($tramite->escuela)->nombre ?? 'N/D' }}</td>
                                                <td>{{ optional($tramite->fecha_inicio)->format('d/m/Y') }}</td>
                                                <td>{{ optional($tramite->fecha_fin)->format('d/m/Y') ?? 'No aplica' }}</td>
                                                <td>{{ optional($tramite->fecha_documento)->format('d/m/Y') }}</td>
                                                <td>{{ optional($tramite->created_at)->format('d/m/Y') }}</td>
                                                <td>
                                                    @php
                                                        // Dictamen/Resumen clinico do not generate a document (only evidence upload)
                                                        $noDocTypes = ['Dictamen', 'Resumen clínico', 'Resumen clinico'];
                                                        $e = $tramite->evidencias->first();
                                                    @endphp
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

                                                    <form method="POST" action="{{ route('tramites.destroy', $tramite) }}" class="d-inline" onsubmit="return confirm('¿Eliminar este trámite?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                @endforeach
            @endif
        </div>
    </div>
@endsection
