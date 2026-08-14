@extends('layouts.app')

@section('title', 'Docentes')

@section('content')
    <h2 class="page-title">
        <i class="bi bi-building"></i> Gestión de Docentes
    </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" action="{{ route('docentes') }}">
                <div class="input-group">
                    <input id="buscar_docentes" type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Buscar docente por nombre, RFC o CURP..." autocomplete="off">
                </div>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('docentes.create') }}" class="btn btn-primary">
                Agregar Nuevo Docente
            </a>
        </div>
    </div>

    <div class="dashboard-table table-responsive">
        <table class="table table-custom mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>RFC</th>
                    <th>CURP</th>
                    <th>Especialidad</th>
                    <th>Domicilio</th>
                    <th>Localidad</th>
                    <th>Celular</th>
                    <th>Estado Civil</th>
                    <th>Último Grado</th>
                    <th>Pensiones</th>
                    <th>Clave Presupuestal</th>
                    <th>Registrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($docentes as $docente)
                    <tr>
                        <td>#{{ $docente->id }}</td>
                        <td>{{ $docente->nombre }}</td>
                        <td>{{ $docente->rfc }}</td>
                        <td>{{ $docente->curp }}</td>
                        <td>{{ $docente->especialidad }}</td>
                        <td>{{ $docente->domicilio }}</td>
                        <td>{{ $docente->localidad }}</td>
                        <td>{{ $docente->celular }}</td>
                        <td>{{ $docente->estado_civil }}</td>
                        <td>{{ $docente->ultimo_grado_estudios }}</td>
                        <td>{{ $docente->numero_pensiones }}</td>
                        <td>{{ $docente->clave_presupuestal }}</td>
                        <td>{{ optional($docente->created_at)->format('d/m/Y') }}</td>
                        <td class="d-flex gap-1 align-items-center">
                            <a href="{{ route('docentes.edit', $docente) }}" class="btn btn-sm btn-outline-warning" title="Editar">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                            <a href="{{ route('docentes.expediente', $docente) }}" class="btn btn-sm btn-outline-info" title="Ver expediente">
                                <i class="bi bi-file-earmark-text"></i>
                            </a>
                            <form action="{{ route('docentes.destroy', $docente) }}" method="POST" onsubmit="return confirm('¿Eliminar este docente y su expediente?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Eliminar">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="14" class="text-center">No hay docentes registrados aún.</td>
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
            <li class="page-item"><a class="page-link" href="#">3</a></li>
            <li class="page-item"><a class="page-link" href="#">Siguiente</a></li>
        </ul>
    </nav>

    <div class="dashboard-footer" style="margin-left: -50px; margin-right: -50px;">
        <p>&copy; 2026 Sistema de Gestión de Permisos e Incidencias Docentes.</p>
    </div>
@endsection

@section('extra-js')
    <script>
        (function(){
            function debounce(fn, wait){
                let t;
                return function(...args){ clearTimeout(t); t = setTimeout(()=>fn.apply(this,args), wait); };
            }

            document.addEventListener('DOMContentLoaded', function(){
                const input = document.getElementById('buscar_docentes');
                if (!input) return;
                const tbody = document.querySelector('.dashboard-table table tbody');
                if (!tbody) return;

                const filter = () => {
                    const q = input.value.trim().toLowerCase();
                    Array.from(tbody.querySelectorAll('tr')).forEach(row => {
                        if (!q) { row.style.display = ''; return; }
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(q) ? '' : 'none';
                    });
                };

                input.addEventListener('input', debounce(filter, 200));
            });
        })();
    </script>
@endsection
