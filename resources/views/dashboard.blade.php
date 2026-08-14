@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h4 class="mb-4">Bienvenido, {{ auth()->user()->usuario ?? 'Administrador' }}</h4>

    <a href="{{ route('crear-oficio') }}" class="banner-card p-4 mb-4 d-flex align-items-center justify-content-between text-decoration-none text-reset">
        <div class="d-flex align-items-center gap-4">
            <i class="bi bi-file-earmark-text banner-icon"></i>
            <div>
                <h3 class="mb-1 fw-bold">Generar Nuevo Oficio</h3>
                <p class="m-0 text-muted">Crear un nuevo trámite y generar oficio</p>
            </div>
        </div>
        <i class="bi bi-chevron-right fs-2 text-secondary"></i>
    </a>

    <div class="row g-4">
        <div class="col-12 col-md-6">
            <a href="{{ route('docentes') }}" class="grid-card p-4 d-flex align-items-start gap-4 text-decoration-none text-reset">
                <i class="bi bi-mortarboard-fill grid-icon"></i>
                <div>
                    <h5 class="fw-bold mb-1">Docentes</h5>
                    <p class="text-muted m-0 small">Consulta, registra y administra la información de los docentes.</p>
                    
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6">
            <a href="{{ route('escuelas') }}" class="grid-card p-4 d-flex align-items-start gap-4 text-decoration-none text-reset">
                <i class="bi bi-house-door grid-icon"></i>
                <div>
                    <h5 class="fw-bold mb-1">Escuelas</h5>
                    <p class="text-muted m-0 small">Consulta, registra y administra la información de las escuelas.</p>
                    
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6">
            <a href="{{ route('tramites') }}" class="grid-card p-4 d-flex align-items-start gap-4 text-decoration-none text-reset">
                <i class="bi bi-file-text grid-icon"></i>
                <div>
                    <h5 class="fw-bold mb-1">Trámites</h5>
                    <p class="text-muted m-0 small">Consulta los diferentes trámites y expedientes.</p>
                </div>
            </a>
        </div>

        <div class="col-12 col-md-6">
            <a href="{{ route('reportes') }}" class="grid-card p-4 d-flex align-items-start gap-4 text-decoration-none text-reset">
                <i class="bi bi-bar-chart-line-fill grid-icon"></i>
                <div>
                    <h5 class="fw-bold mb-1">Reportes</h5>
                    <p class="text-muted m-0 small">Genera reportes y consulta información.</p>
                </div>
            </a>
        </div>
    </div>

    <footer class="custom-footer">
        SNTE Sección xx | Secretaría de Trabajos y Conflictos de Niveles Especiales
    </footer>
@endsection
