<div class="sidebar">
    <div class="px-4 mb-4">
        <h2 class="text-xl font-bold text-white mb-2">⚙️ Admin YAVÉ</h2>
        <hr class="border-gray-600">
    </div>

    {{-- Dashboard --}}
    <a href="{{ route('admin.dashboard') }}" 
       class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer me-2"></i> Dashboard
    </a>

    {{-- Usuarios --}}
    <a href="{{ route('admin.users.index') }}" 
       class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people-fill me-2"></i> Gestión de Usuarios
    </a>

    {{-- Clientes --}}
    <a href="{{ route('client.index') }}" 
       class="{{ request()->routeIs('client.*') ? 'active' : '' }}">
        <i class="bi bi-person-vcard-fill me-2"></i> Gestión de Clientes
    </a>

    {{-- Categorías --}}
    <a href="{{ route('admin.categories.index') }}" 
       class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">
        <i class="bi bi-grid-fill me-2"></i> Gestión de Categorías
    </a>

    {{-- Productos --}}
    <a href="#" class="">
        <i class="bi bi-box-seam-fill me-2"></i> Gestión de Productos
    </a>

    {{-- Medios de pago --}}
    <a href="#">
        <i class="bi bi-credit-card-fill me-2"></i> Medios de Pago
    </a>

    {{-- Ventas --}}
    <a href="#">
        <i class="bi bi-cash-stack me-2"></i> Gestión de Ventas
    </a>

    {{-- Reportes --}}
    <a href="#">
        <i class="bi bi-graph-up-arrow me-2"></i> Reportes
    </a>

    {{-- Perfil --}}
    <a href="{{ route('profile.edit') }}"
       class="{{ request()->routeIs('profile.edit') ? 'active' : '' }}">
        <i class="bi bi-person-circle me-2"></i> Mi Perfil
    </a>
</div>
