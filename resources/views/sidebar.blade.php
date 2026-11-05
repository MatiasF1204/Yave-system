{{-- Botón y controles para el menú móvil --}}
<input type="checkbox" id="sidebar-toggle" class="sidebar-toggle-input">

<label for="sidebar-toggle" class="sidebar-toggle-button" aria-label="Abrir o cerrar menú">
    <i class="bi bi-list"></i>
</label>

<label for="sidebar-toggle" class="sidebar-overlay" aria-label="Cerrar menú lateral"></label>

<div class="sidebar">

    <button type="button" id="sidebar-collapse" class="sidebar-collapse-button" aria-label="Contraer menú lateral"
        aria-expanded="true" title="Contraer menú">
        <i class="bi bi-chevron-left"></i>
    </button>

    {{-- Logo y nombre del sistema --}}
    <div class="sidebar-brand">
        <img src="{{ asset('images/logo-blanco.png') }}" alt="Logo Productos YAVÉ">

        <div class="sidebar-brand-text">
            <h2>Productos YAVÉ</h2>
            <span>Panel de gestión</span>
        </div>
    </div>

    <nav class="sidebar-nav">

        @if (auth()->user()->role?->name === 'Administrador')
            <span class="sidebar-section-title">Principal</span>

            {{-- Dashboard --}}
            <a href="{{ route('admin.dashboard') }}"
                class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2"></i>
                <span>Dashboard</span>
            </a>
        @endif

        <span class="sidebar-section-title">Administración</span>

        @if (auth()->user()->role?->name === 'Administrador')
            {{-- Usuarios --}}
            <a href="{{ route('admin.users.index') }}"
                class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                <i class="bi bi-people-fill"></i>
                <span>Gestión de Usuarios</span>
            </a>
        @endif

        {{-- Clientes --}}
        <a href="{{ route('clients.index') }}" class="{{ request()->routeIs('clients.*') ? 'active' : '' }}">
            <i class="bi bi-person-vcard-fill"></i>
            <span>Gestión de Clientes</span>
        </a>

        {{-- Categorías --}}
        <a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
            <i class="bi bi-grid-fill"></i>
            <span>Gestión de Categorías</span>
        </a>

        {{-- Productos --}}
        <a href="{{ route('products.index') }}"
            class="{{ request()->routeIs('products.*', 'admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam-fill"></i>
            <span>Gestión de Productos</span>
        </a>

        {{-- Medios de pago --}}
        <a href="{{ route('payment_methods.index') }}"
            class="{{ request()->routeIs('payment_methods.*') ? 'active' : '' }}">
            <i class="bi bi-credit-card-fill"></i>
            <span>Medios de Pago</span>
        </a>

        <div class="sidebar-divider"></div>

        <span class="sidebar-section-title">Operaciones</span>

        {{-- Introducción de ticket --}}
        <a href="{{ route('sales.create') }}"
            class="{{ request()->routeIs('sales.create', 'sales.store') ? 'active' : '' }}">
            <i class="bi bi-receipt"></i>
            <span>Introducción de Ticket</span>
        </a>

        {{-- Gestión de ventas --}}
        <a href="{{ route('sales.index') }}"
            class="{{ request()->routeIs('sales.index', 'sales.show') ? 'active' : '' }}">
            <i class="bi bi-cash-stack"></i>
            <span>Gestión de Ventas</span>
        </a>

        {{-- Control diario --}}
        <a href="{{ route('daily-control.index') }}"
            class="{{ request()->routeIs('daily-control.index') ? 'active' : '' }}">
            <i class="bi bi-clipboard-data"></i>
            <span>Control diario de caja</span>
        </a>

        {{-- Reportes: solamente administrador --}}
        @if (auth()->user()->role?->name === 'Administrador')
            <div class="sidebar-divider"></div>

            <span class="sidebar-section-title">Análisis</span>

            <a href="{{ route('admin.reports.sales') }}"
                class="{{ request()->routeIs('admin.reports.sales') ? 'active' : '' }}">
                <i class="bi bi-graph-up-arrow"></i>
                <span>Reporte de Ventas</span>
            </a>

            <a href="{{ route('admin.reports.stock') }}"
                class="{{ request()->routeIs('admin.reports.stock') ? 'active' : '' }}">
                <i class="bi bi-box-seam"></i>
                <span>Reporte de Stock</span>
            </a>
        @endif

        @if (auth()->user()->role?->name === 'Administrador')
        <div class="sidebar-divider"></div>

            <span class="sidebar-section-title">Cuenta</span>

            {{-- Perfil --}}
            <a href="{{ route('admin.profile.edit') }}"
                class="{{ request()->routeIs('admin.profile.edit') ? 'active' : '' }}">
                <i class="bi bi-person-circle"></i>
                <span>Mi Perfil</span>
            </a>
        @endif

    </nav>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebar-toggle');
        const sidebar = document.querySelector('.sidebar');

        if (!sidebarToggle || !sidebar) return;

        // Cerrar el menú después de elegir una opción en pantallas pequeñas.
        sidebar.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function() {
                if (window.innerWidth <= 900) {
                    sidebarToggle.checked = false;
                }
            });
        });

        // Si se amplía la ventana, evitar que el menú quede en estado móvil.
        window.addEventListener('resize', function() {
            if (window.innerWidth > 900) {
                sidebarToggle.checked = false;
            }
        });
    });

    // Contraer o expandir el sidebar en escritorio.
    const collapseButton = document.getElementById('sidebar-collapse');

    if (collapseButton) {
        const collapseIcon = collapseButton.querySelector('i');

        function updateSidebarButton() {
            const collapsed = document.body.classList.contains('sidebar-collapsed');

            collapseButton.setAttribute('aria-expanded', String(!collapsed));
            collapseButton.setAttribute(
                'aria-label',
                collapsed ? 'Expandir menú lateral' : 'Contraer menú lateral'
            );
            collapseButton.setAttribute(
                'title',
                collapsed ? 'Expandir menú' : 'Contraer menú'
            );

            if (collapseIcon) {
                collapseIcon.classList.toggle('bi-chevron-left', !collapsed);
                collapseIcon.classList.toggle('bi-chevron-right', collapsed);
            }

            // Mostrar el nombre de cada opción al pasar el cursor.
            document.querySelectorAll('.sidebar-nav a').forEach(function(link) {
                const label = link.querySelector('span');

                if (label) {
                    link.setAttribute('title', label.textContent.trim());
                }
            });
        }

        collapseButton.addEventListener('click', function() {
            document.body.classList.toggle('sidebar-collapsed');
            updateSidebarButton();
        });

        updateSidebarButton();
    }
</script>
