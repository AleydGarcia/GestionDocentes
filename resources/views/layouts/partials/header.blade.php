<nav class="top-navbar">
    <div class="navbar-left d-flex align-items-center">
        <button id="sidebarToggle" class="btn btn-link p-0 text-muted me-2"><i class="bi bi-list" aria-hidden="true"></i></button>
        <a href="{{ route('dashboard') }}" class="d-flex align-items-center me-3">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" style="height:40px; max-width:160px; display:block;">
        </a>
        <div class="topbar-title">
            <strong>Sistema de Gestión de Permisos e Incidencias Docentes</strong>
        </div>
    </div>

    <div class="user-section">
        <span>{{ auth()->user()->usuario ?? 'Administrador' }}</span>
    </div>
</nav>
