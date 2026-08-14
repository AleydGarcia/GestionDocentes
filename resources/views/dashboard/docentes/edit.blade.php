@extends('layouts.app')

@section('title', 'Editar Docente')

@section('content')
    <h2 class="page-title">
        <i class="bi bi-pencil-square"></i> Editar Docente
    </h2>

    @php
        $nameParts = preg_split('/\s+/', trim(old('nombre', $docente->nombre ?? '')));
        $nombre_primer = '';
        $apellido_paterno = '';
        $apellido_materno = '';
        if (count($nameParts) === 1) {
            $nombre_primer = $nameParts[0];
        } elseif (count($nameParts) === 2) {
            $nombre_primer = $nameParts[0];
            $apellido_paterno = $nameParts[1];
        } elseif (count($nameParts) >= 3) {
            $apellido_materno = array_pop($nameParts);
            $apellido_paterno = array_pop($nameParts);
            $nombre_primer = implode(' ', $nameParts);
        }
    @endphp

    <form method="POST" action="{{ route('docentes.update', $docente) }}" id="docente-form">
        @csrf
        @method('PUT')

        <input type="hidden" name="nombre" id="nombre" value="{{ old('nombre', $docente->nombre) }}">

        <div class="row g-3">
            <div class="col-md-4">
                <label for="nombre_primer" class="form-label">Nombre(s) del docente</label>
                <input type="text" class="form-control @error('nombre_primer') is-invalid @enderror" id="nombre_primer" name="nombre_primer" value="{{ old('nombre_primer', $nombre_primer) }}" required>
                @error('nombre_primer')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="apellido_paterno" class="form-label">Apellido paterno</label>
                <input type="text" class="form-control @error('apellido_paterno') is-invalid @enderror" id="apellido_paterno" name="apellido_paterno" value="{{ old('apellido_paterno', $apellido_paterno) }}">
                @error('apellido_paterno')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-4">
                <label for="apellido_materno" class="form-label">Apellido materno</label>
                <input type="text" class="form-control @error('apellido_materno') is-invalid @enderror" id="apellido_materno" name="apellido_materno" value="{{ old('apellido_materno', $apellido_materno) }}">
                @error('apellido_materno')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="especialidad" class="form-label">Especialidad</label>
                <select class="form-select @error('especialidad') is-invalid @enderror" id="especialidad" name="especialidad" required>
                    <option value="">Seleccione una opción</option>
                    <option value="educación especial" {{ old('especialidad', $docente->especialidad) === 'educación especial' ? 'selected' : '' }}>educación especial</option>
                    <option value="educación física" {{ old('especialidad', $docente->especialidad) === 'educación física' ? 'selected' : '' }}>educación física</option>
                    <option value="artística" {{ old('especialidad', $docente->especialidad) === 'artística' ? 'selected' : '' }}>artística</option>
                    <option value="cadis" {{ old('especialidad', $docente->especialidad) === 'cadis' ? 'selected' : '' }}>cadis</option>
                </select>
                @error('especialidad')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="domicilio" class="form-label">Domicilio</label>
                <input type="text" class="form-control @error('domicilio') is-invalid @enderror" id="domicilio" name="domicilio" value="{{ old('domicilio', $docente->domicilio) }}" required>
                @error('domicilio')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="localidad" class="form-label">Localidad</label>
                <input type="text" class="form-control @error('localidad') is-invalid @enderror" id="localidad" name="localidad" value="{{ old('localidad', $docente->localidad) }}" required>
                @error('localidad')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="celular" class="form-label">Celular</label>
                <input type="tel" maxlength="20" pattern="\+?[0-9]{7,20}" inputmode="tel" class="form-control @error('celular') is-invalid @enderror" id="celular" name="celular" value="{{ old('celular', $docente->celular) }}" required>
                @error('celular')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="estado_civil" class="form-label">Estado Civil</label>
                <select class="form-select @error('estado_civil') is-invalid @enderror" id="estado_civil" name="estado_civil" required>
                    <option value="">Seleccione una opción</option>
                    <option value="soltero/a" {{ old('estado_civil', $docente->estado_civil) === 'soltero/a' ? 'selected' : '' }}>soltero/a</option>
                    <option value="casado/a" {{ old('estado_civil', $docente->estado_civil) === 'casado/a' ? 'selected' : '' }}>casado/a</option>
                    <option value="divorciado/a" {{ old('estado_civil', $docente->estado_civil) === 'divorciado/a' ? 'selected' : '' }}>divorciado/a</option>
                    <option value="viudo/a" {{ old('estado_civil', $docente->estado_civil) === 'viudo/a' ? 'selected' : '' }}>viudo/a</option>
                </select>
                @error('estado_civil')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="rfc" class="form-label">RFC</label>
                <input type="text" maxlength="13" pattern="[A-Za-zÑñ&]{4}[0-9]{6}[A-Za-z0-9]{3}" class="form-control @error('rfc') is-invalid @enderror" id="rfc" name="rfc" value="{{ old('rfc', $docente->rfc) }}" required>
                @error('rfc')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="curp" class="form-label">CURP</label>
                <input type="text" maxlength="18" pattern="[A-Za-zÑñ&]{4}[0-9]{6}[HMhm][A-Za-z]{2}[B-DF-HJ-NP-TV-Zb-df-hj-np-tv-z]{3}[A-Za-z0-9][0-9]" title="CURP de 18 caracteres. Ej: PEGJ900615HDFRMN03" class="form-control @error('curp') is-invalid @enderror" id="curp" name="curp" value="{{ old('curp', $docente->curp) }}" required>
                @error('curp')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="ultimo_grado_estudios" class="form-label">Último grado de estudios</label>
                <select class="form-select @error('ultimo_grado_estudios') is-invalid @enderror" id="ultimo_grado_estudios" name="ultimo_grado_estudios" required>
                    <option value="">Seleccione una opción</option>
                    <option value="licenciatura" {{ old('ultimo_grado_estudios', $docente->ultimo_grado_estudios) === 'licenciatura' ? 'selected' : '' }}>licenciatura</option>
                    <option value="maestría" {{ old('ultimo_grado_estudios', $docente->ultimo_grado_estudios) === 'maestría' ? 'selected' : '' }}>maestría</option>
                    <option value="doctorado" {{ old('ultimo_grado_estudios', $docente->ultimo_grado_estudios) === 'doctorado' ? 'selected' : '' }}>doctorado</option>
                    <option value="técnico" {{ old('ultimo_grado_estudios', $docente->ultimo_grado_estudios) === 'técnico' ? 'selected' : '' }}>técnico</option>
                    <option value="otro" {{ old('ultimo_grado_estudios', $docente->ultimo_grado_estudios) === 'otro' ? 'selected' : '' }}>otro</option>
                </select>
                @error('ultimo_grado_estudios')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="numero_pensiones" class="form-label">Número de pensiones</label>
                <input type="text" inputmode="numeric" pattern="[0-9]+" class="form-control @error('numero_pensiones') is-invalid @enderror" id="numero_pensiones" name="numero_pensiones" value="{{ old('numero_pensiones', $docente->numero_pensiones) }}" required>
                @error('numero_pensiones')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            <div class="col-md-6">
                <label for="clave_presupuestal" class="form-label">Clave Presupuestal</label>
                <input type="text" class="form-control @error('clave_presupuestal') is-invalid @enderror" id="clave_presupuestal" name="clave_presupuestal" value="{{ old('clave_presupuestal', $docente->clave_presupuestal) }}" required>
                @error('clave_presupuestal')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-primary">Guardar cambios</button>
            <a href="{{ route('docentes') }}" class="btn btn-secondary">Cancelar</a>
        </div>
    </form>

    <form action="{{ route('docentes.destroy', $docente) }}" method="POST" class="d-inline-block ms-2 mt-3" onsubmit="return confirm('¿Eliminar este docente y su expediente?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-outline-danger">Eliminar</button>
    </form>
@endsection
