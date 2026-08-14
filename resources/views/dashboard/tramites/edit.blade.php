@extends('layouts.app')

@section('title', 'Editar Trámite')

@section('content')
    <h2 class="page-title">
        <i class="bi bi-pencil-square"></i> Editar Trámite
    </h2>

    <form method="POST" action="{{ route('tramites.update', $tramite) }}">
        @csrf
        @method('PUT')

        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label for="escuela_id" class="form-label">Escuela</label>
                <select class="form-select @error('escuela_id') is-invalid @enderror" id="escuela_id" name="escuela_id" required>
                    @foreach($escuelas as $escuela)
                        <option value="{{ $escuela->id }}" {{ old('escuela_id', $tramite->escuela_id) == $escuela->id ? 'selected' : '' }}>{{ $escuela->nombre }} - {{ $escuela->localidad }}</option>
                    @endforeach
                </select>
                @error('escuela_id')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="tipo_tramite" class="form-label">Tipo de trámite</label>
                <select class="form-select @error('tipo_tramite') is-invalid @enderror" id="tipo_tramite" name="tipo_tramite" required>
                    @foreach($tiposTramite as $tipo)
                        <option value="{{ $tipo }}" {{ old('tipo_tramite', $tramite->tipo_tramite) == $tipo ? 'selected' : '' }}>{{ $tipo }}</option>
                    @endforeach
                </select>
                @error('tipo_tramite')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                <input type="date" class="form-control @error('fecha_inicio') is-invalid @enderror" id="fecha_inicio" name="fecha_inicio" value="{{ old('fecha_inicio', $tramite->fecha_inicio?->format('Y-m-d')) }}" required>
                @error('fecha_inicio')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="fecha_fin" class="form-label">Fecha de fin</label>
                <input type="date" class="form-control @error('fecha_fin') is-invalid @enderror" id="fecha_fin" name="fecha_fin" value="{{ old('fecha_fin', optional($tramite->fecha_fin)->format('Y-m-d')) }}">
                @error('fecha_fin')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="fecha_documento" class="form-label">Fecha del documento</label>
                <input type="date" class="form-control @error('fecha_documento') is-invalid @enderror" id="fecha_documento" name="fecha_documento" value="{{ old('fecha_documento', $tramite->fecha_documento?->format('Y-m-d')) }}" required>
                @error('fecha_documento')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>


        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('tramites') }}" class="btn btn-secondary">Cancelar</a>
            <form action="{{ route('tramites.destroy', $tramite) }}" method="POST" class="d-inline-block ms-2" onsubmit="return confirm('¿Eliminar este trámite? Esta acción no se puede deshacer.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-outline-danger">Eliminar</button>
            </form>
        </div>
    </form>
@endsection
