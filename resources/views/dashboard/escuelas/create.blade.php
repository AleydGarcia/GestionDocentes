@extends('layouts.app')

@section('title', 'Crear Escuela')

@section('content')
    <h2 class="page-title">
        <i class="bi bi-building-add"></i> Crear Escuela
    </h2>

    <form method="POST" action="{{ route('escuelas.store') }}">
        @csrf

        <div class="row g-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre de la escuela</label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                @error('nombre')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="clave" class="form-label">Clave</label>
                <input type="text" maxlength="10" pattern="[A-Za-z0-9]{10}" class="form-control @error('clave') is-invalid @enderror" id="clave" name="clave" value="{{ old('clave') }}" required>
                @error('clave')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="director_titulo" class="form-label">Título</label>
                <select class="form-select @error('director_titulo') is-invalid @enderror" id="director_titulo" name="director_titulo" required>
                    <option value="">Seleccione una opción</option>
                    <option value="Dr." {{ old('director_titulo') === 'Dr.' ? 'selected' : '' }}>Dr.</option>
                    <option value="Lic." {{ old('director_titulo') === 'Lic.' ? 'selected' : '' }}>Lic.</option>
                    <option value="Mtro." {{ old('director_titulo') === 'Mtro.' ? 'selected' : '' }}>Mtro.</option>
                    <option value="Prof." {{ old('director_titulo') === 'Prof.' ? 'selected' : '' }}>Prof.</option>
                </select>
                @error('director_titulo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="director_nombre" class="form-label">Nombre(s) del director</label>
                <input type="text" class="form-control @error('director_nombre') is-invalid @enderror" id="director_nombre" name="director_nombre" value="{{ old('director_nombre') }}" required>
                @error('director_nombre')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="director_apellido_paterno" class="form-label">Apellido paterno</label>
                <input type="text" class="form-control @error('director_apellido_paterno') is-invalid @enderror" id="director_apellido_paterno" name="director_apellido_paterno" value="{{ old('director_apellido_paterno') }}">
                @error('director_apellido_paterno')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="director_apellido_materno" class="form-label">Apellido materno</label>
                <input type="text" class="form-control @error('director_apellido_materno') is-invalid @enderror" id="director_apellido_materno" name="director_apellido_materno" value="{{ old('director_apellido_materno') }}">
                @error('director_apellido_materno')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="localidad" class="form-label">Localidad</label>
                <input type="text" class="form-control @error('localidad') is-invalid @enderror" id="localidad" name="localidad" value="{{ old('localidad') }}" required>
                @error('localidad')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Crear Escuela</button>
            <a href="{{ route('escuelas') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>
@endsection
