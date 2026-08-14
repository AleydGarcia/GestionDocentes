@extends('layouts.app')

@section('title', 'Escuelas')

@section('content')
    <h2 class="page-title">
        <i class="bi bi-building"></i> Gestión de Escuelas
    </h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row mb-4">
        <div class="col-md-8">
            <form method="GET" action="{{ route('escuelas') }}">
                <div class="input-group">
                    <input id="buscar_escuelas" type="text" name="q" value="{{ $q ?? '' }}" class="form-control" placeholder="Buscar escuela por nombre, clave o director..." autocomplete="off">
                </div>
            </form>
        </div>
        <div class="col-md-4 text-end">
            <a href="{{ route('escuelas.create') }}" class="btn btn-primary">
                Agregar Nueva Escuela
            </a>
        </div>
    </div>

    <div class="dashboard-table table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Clave</th>
                    <th>Director</th>
                    <th>Localidad</th>
                    <th>Registrado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse($escuelas as $escuela)
                    <tr>
                        <td>#{{ $escuela->id }}</td>
                        <td>{{ $escuela->nombre }}</td>
                        <td>{{ $escuela->clave }}</td>
                        <td>{{ $escuela->director }}</td>
                        <td>{{ $escuela->localidad }}</td>
                        <td>{{ optional($escuela->created_at)->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('escuelas.edit', $escuela) }}" class="btn btn-sm btn-outline-primary" title="Editar">
                                <i class="bi bi-pencil-square"></i> Editar
                            </a>
                            <form method="POST" action="{{ route('escuelas.destroy', $escuela) }}" class="d-inline" onsubmit="return confirm('¿Eliminar esta escuela? Esta acción no se puede deshacer.');">
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
                        <td colspan="7" class="text-center">No hay escuelas registradas aún.</td>
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

@section('extra-js')
    <script>
        (function(){
            function debounce(fn, wait){
                let t;
                return function(...args){ clearTimeout(t); t = setTimeout(()=>fn.apply(this,args), wait); };
            }

            document.addEventListener('DOMContentLoaded', function(){
                const input = document.getElementById('buscar_escuelas');
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
