<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Gestión')</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Dashboard CSS General -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @yield('extra-css')

    <style>
        :root {
            --color-naranja-principal: #f97316;
            --color-naranja-claro: #ffedd5;
            --color-borde: #fdba74;
            --color-texto: #333333;
        }
        body { background-color: #ffffff; color: var(--color-texto); font-family: system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif; overflow-x: hidden; }
        .topbar { height: 70px; border-bottom: 1px solid var(--color-borde); background-color: white; position: sticky; top: 0; z-index: 1040; }
        .btn-menu { font-size: 1.75rem; color: var(--color-texto); background: none; border: none; cursor: pointer; }
        .logo-placeholder { width: 120px; height: 40px; background-color: var(--color-naranja-claro); border: 1px dashed var(--color-naranja-principal); }
        .sidebar-wrapper { width: 250px; min-height: calc(100vh - 70px); border-right: 1px solid var(--color-borde); background-color: white; transition: all 0.3s ease; }
        .sidebar-wrapper.toggled { margin-left: -250px; }
        .sidebar-link { display: flex; align-items: center; padding: 1rem 1.5rem; color: var(--color-texto); text-decoration: none; font-weight: 500; transition: 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background-color: var(--color-naranja-claro); color: var(--color-naranja-principal); }
        .sidebar-icon { font-size: 1.4rem; margin-right: 15px; color: var(--color-naranja-principal); }
        .main-content { flex-grow: 1; padding: 2rem; transition: all 0.3s ease; }
        .banner-card { background-color: var(--color-naranja-claro); border: 1px solid var(--color-borde); border-radius: 8px; cursor: pointer; transition: transform 0.2s; }
        .banner-card:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(249, 115, 22, 0.1); }
        .banner-icon { font-size: 3.5rem; color: var(--color-naranja-principal); }
        .grid-card { border: 1px solid var(--color-borde); border-radius: 8px; height: 100%; transition: box-shadow 0.2s; }
        .grid-card:hover { box-shadow: 0 4px 12px rgba(249, 115, 22, 0.15); border-color: var(--color-naranja-principal); }
        .grid-icon { font-size: 2rem; color: var(--color-naranja-principal); }
        .custom-footer { border-top: 1px solid var(--color-borde); color: var(--color-naranja-principal); font-size: 0.85rem; padding: 1.5rem 0; margin-top: 3rem; text-align: center; }

        /* small helpers to integrate with existing templates */
        nav.top-navbar { display:flex; align-items:center; justify-content:space-between; padding:0 1rem; }
        .topbar-title { margin-left: .5rem; }
        .user-section { margin-right: 1rem; }
        aside.sidebar { width:250px; }
        body.sidebar-collapsed aside.sidebar { margin-left:-250px; }
    </style>
</head>
<body>
    @include('layouts.partials.header')
    @include('layouts.partials.sidebar')

    <main class="main-content">
        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Robust Sidebar Toggle: supports both original and modified sidebar markup
        (function(){
            const toggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('sidebar');
            toggle && toggle.addEventListener('click', function() {
                // If original sidebar with fixed behavior (CSS expects body.sidebar-toggled)
                if (document.body.classList) {
                    document.body.classList.toggle('sidebar-toggled');
                }

                // Also toggle sidebar.toggled if present
                if (sidebar && sidebar.classList) {
                    sidebar.classList.toggle('toggled');
                }
            });

            // Close sidebar on link click (mobile) - support both .nav-link and .sidebar-link
            document.querySelectorAll('#sidebar .nav-link, #sidebar .sidebar-link').forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        document.body.classList.add('sidebar-toggled');
                        if (sidebar) sidebar.classList.add('toggled');
                    }
                });
            });

            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    document.body.classList.remove('sidebar-toggled');
                    if (sidebar) sidebar.classList.remove('toggled');
                }
            });
        })();
    </script>
    @yield('extra-js')
</body>
</html>
