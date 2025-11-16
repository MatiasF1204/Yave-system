<div class="sidebar">
    <div class="px-4 mb-4">
        <h2 class="text-xl font-bold text-white mb-2">⚙️ Admin YAVÉ</h2>
        <hr class="border-gray-600">
    </div>

    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>

    <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <i class="bi bi-people me-2"></i> Gestión de Usuarios
    </a>

    <a href=" {{ route('client.index') }} ">
        <i class="bi bi-bar-chart-line me-2"></i> Gestión de Clientes
    </a>

    <a href="  ">
        <i class="bi bi-bar-chart-line me-2"></i> Categorías
    </a>

    <a href="#">
        <i class="bi bi-bar-chart-line me-2"></i> Reportes
    </a>

    <a href="#">
        <i class="bi bi-box me-2"></i> Productos
    </a>

    <a href="{{ route('profile.edit') }}">
        <i class="bi bi-person-circle me-2"></i> Perfil
    </a>
</div>
