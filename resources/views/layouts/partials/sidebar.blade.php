<aside class="sidebar" id="sidebar">
    <nav class="nav flex-column">
        <a href="{{ route('crear-oficio') }}" class="nav-link @if(request()->routeIs('crear-oficio')) active @endif">
            <i class="bi bi-plus-lg"></i> Generar Oficio
        </a>
        <a href="{{ route('docentes') }}" class="nav-link @if(request()->routeIs('docentes')) active @endif">
            <i class="bi bi-mortarboard-fill"></i> Docentes
        </a>
        <a href="{{ route('escuelas') }}" class="nav-link @if(request()->routeIs('escuelas')) active @endif">
            <i class="bi bi-building"></i> Escuelas
        </a>
        <a href="{{ route('tramites') }}" class="nav-link @if(request()->routeIs('tramites')) active @endif">
            <i class="bi bi-clock-history"></i> Trámites
        </a>
        <a href="{{ route('reportes') }}" class="nav-link @if(request()->routeIs('reportes')) active @endif">
            <i class="bi bi-bar-chart-line-fill"></i> Reportes
        </a>
    </nav>

    <div class="logout-section">
        <form action="{{ route('logout') }}" method="POST" style="display: block;">
            @csrf
            <button type="submit" class="nav-link logout-button">
                Cerrar Sesión
            </button>
        </form>
    </div>
</aside>
