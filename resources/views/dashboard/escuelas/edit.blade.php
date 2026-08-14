@extends('layouts.app')

@section('title', 'Editar Escuela')

@section('content')
    <h2 class="page-title">
        <i class="bi bi-pencil-square"></i> Editar Escuela
    </h2>

    @php
        $directorRaw = old('director', $escuela->director ?? '');
        $directorParts = preg_split('/\s+/', trim($directorRaw));
        $director_titulo = '';
        $director_nombre = '';
        $director_apellido_paterno = '';
        $director_apellido_materno = '';
        $possibleTitles = ['Dr.', 'Dr', 'Lic.', 'Lic', 'Mtro.', 'Mtro', 'Prof.', 'Prof'];
        if (!empty($directorParts) && in_array($directorParts[0], $possibleTitles, true)) {
            $director_titulo = $directorParts[0];
            array_shift($directorParts);
        }

        if (count($directorParts) === 1) {
            $director_nombre = $directorParts[0];
        } elseif (count($directorParts) === 2) {
            $director_nombre = $directorParts[0];
            $director_apellido_paterno = $directorParts[1];
        } elseif (count($directorParts) >= 3) {
            $director_apellido_materno = array_pop($directorParts);
            $director_apellido_paterno = array_pop($directorParts);
            $director_nombre = implode(' ', $directorParts);
        }
    @endphp

    <form method="POST" action="{{ route('escuelas.update', $escuela) }}">
        @csrf
        @method('PUT')

        <div class="row g-3">
            <div class="col-md-6">
                <label for="nombre" class="form-label">Nombre de la escuela</label>
                <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $escuela->nombre) }}" required>
                @error('nombre')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="clave" class="form-label">Clave</label>
                <input type="text" maxlength="10" pattern="[A-Za-z0-9]{10}" class="form-control @error('clave') is-invalid @enderror" id="clave" name="clave" value="{{ old('clave', $escuela->clave) }}" required>
                @error('clave')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="director_titulo" class="form-label">Título</label>
                <select class="form-select @error('director_titulo') is-invalid @enderror" id="director_titulo" name="director_titulo" required>
                    <option value="">Seleccione una opción</option>
                    <option value="Dr." {{ old('director_titulo', $director_titulo) === 'Dr.' ? 'selected' : '' }}>Dr.</option>
                    <option value="Lic." {{ old('director_titulo', $director_titulo) === 'Lic.' ? 'selected' : '' }}>Lic.</option>
                    <option value="Mtro." {{ old('director_titulo', $director_titulo) === 'Mtro.' ? 'selected' : '' }}>Mtro.</option>
                    <option value="Prof." {{ old('director_titulo', $director_titulo) === 'Prof.' ? 'selected' : '' }}>Prof.</option>
                </select>
                @error('director_titulo')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="director_nombre" class="form-label">Nombre(s) del director</label>
                <input type="text" class="form-control @error('director_nombre') is-invalid @enderror" id="director_nombre" name="director_nombre" value="{{ old('director_nombre', $director_nombre) }}" required>
                @error('director_nombre')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="director_apellido_paterno" class="form-label">Apellido paterno</label>
                <input type="text" class="form-control @error('director_apellido_paterno') is-invalid @enderror" id="director_apellido_paterno" name="director_apellido_paterno" value="{{ old('director_apellido_paterno', $director_apellido_paterno) }}">
                @error('director_apellido_paterno')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="director_apellido_materno" class="form-label">Apellido materno</label>
                <input type="text" class="form-control @error('director_apellido_materno') is-invalid @enderror" id="director_apellido_materno" name="director_apellido_materno" value="{{ old('director_apellido_materno', $director_apellido_materno) }}">
                @error('director_apellido_materno')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="localidad" class="form-label">Localidad</label>
                <input type="text" class="form-control @error('localidad') is-invalid @enderror" id="localidad" name="localidad" value="{{ old('localidad', $escuela->localidad) }}" required>
                @error('localidad')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('escuelas') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

    <div class="mt-3">
        <form action="{{ route('escuelas.destroy', $escuela) }}" method="POST" onsubmit="return confirm('¿Eliminar esta escuela? Esta acción no se puede deshacer.');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">Eliminar</button>
        </form>
    </div>
@endsection
