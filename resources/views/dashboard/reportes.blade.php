@extends('layouts.app')

@section('title', 'Reportes')

@section('content')
    <h2 class="page-title">
        <i class="bi bi-speedometer2"></i> Reportes
    </h2>

    <form action="{{ route('reportes') }}" method="GET" class="report-filters card p-4 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-2">
                <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" value="{{ old('fecha_inicio', $filters['fecha_inicio'] ?? '') }}">
            </div>
            <div class="col-md-2">
                <label for="fecha_fin" class="form-label">Fecha de fin</label>
                <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="{{ old('fecha_fin', $filters['fecha_fin'] ?? '') }}">
            </div>
            <div class="col-md-2">
                <label for="tipo_tramite" class="form-label">Tipo de trámite</label>
                <select name="tipo_tramite" id="tipo_tramite" class="form-select">
                    <option value="Todos">Todos</option>
                    @foreach($tiposTramite as $tipo)
                        <option value="{{ $tipo }}" @if(($filters['tipo_tramite'] ?? 'Todos') === $tipo) selected @endif>{{ $tipo }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="docente_id" class="form-label">Docente</label>
                <select name="docente_id" id="docente_id" class="form-select">
                    <option value="Todos">Todos</option>
                    @foreach($docentes as $docente)
                        <option value="{{ $docente->id }}" @if(($filters['docente_id'] ?? 'Todos') == $docente->id) selected @endif>{{ $docente->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label for="escuela_id" class="form-label">Escuela</label>
                <select name="escuela_id" id="escuela_id" class="form-select">
                    <option value="Todos">Todas</option>
                    @foreach($escuelas as $escuela)
                        <option value="{{ $escuela->id }}" @if(($filters['escuela_id'] ?? 'Todos') == $escuela->id) selected @endif>{{ $escuela->nombre }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2 d-grid gap-2">
                <button type="submit" class="btn btn-primary">Generar reporte</button>
                <a href="{{ route('reportes') }}" class="btn btn-outline-secondary">Limpiar filtros</a>
            </div>
        </div>
    </form>

    <div class="report-summary row g-3 mb-4">
        <div class="col-md-3">
            <div class="report-card report-card-primary">
                <div class="report-card-title">Total de trámites</div>
                <div class="report-card-value">{{ $tramitesCount }}</div>
            </div>
        </div>
        @php
            $colors = ['#fff4e6','#eefdf3','#f5f5ff','#fff8f0','#fff0f6','#f0fff7'];
            $textColors = ['#7a4b00','#166534','#4f2177','#7a4b00','#7a1f4f','#065f46'];
        @endphp
        @foreach($tramitesPorTipo as $i => $tipo)
            <div class="col-md-2">
                <div class="report-card" style="background: {{ $colors[$i % count($colors)] }}; color: {{ $textColors[$i % count($textColors)] }};">
                    <div class="report-card-title">{{ $tipo->tipo_tramite }}</div>
                    <div class="report-card-value">{{ $tipo->cantidad }}</div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="card p-4 mb-4">
        <h5 class="section-title mb-4">Cantidad de trámites por docente</h5>
        <div class="table-responsive">
            <table class="table table-custom align-middle mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Docente</th>
                        <th>Nombramientos</th>
                        <th>Justificantes</th>
                        <th>Constancias</th>
                        <th>Otros</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($docentesStats as $index => $row)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $row->docente->nombre }}</td>
                            <td>{{ $row->{'Nombramientos'} ?? 0 }}</td>
                            <td>{{ $row->{'Justificantes'} ?? 0 }}</td>
                            <td>{{ $row->{'Constancias'} ?? 0 }}</td>
                            <td>{{ $row->Otros }}</td>
                            <td>{{ $row->total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="dashboard-footer">
        <p>&copy; 2026 Sistema de Gestión de Permisos e Incidencias Docentes.</p>
    </div>
@endsection
