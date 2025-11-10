<div class="sidebar">
    <div class="px-4 mb-4">
        <h2 class="text-xl font-bold text-white mb-2">🧴 Productos YAVÉ</h2>
        <hr class="border-gray-600">
    </div>

    <a href="{{ route('seller.dashboard') }}" class="{{ request()->routeIs('seller.dashboard') ? 'active' : '' }}">
        <i class="bi bi-speedometer2 me-2"></i> Dashboard
    </a>

    <a href="#">
        <i class="bi bi-cart4 me-2"></i> Ventas
    </a>

    <a href="#">
        <i class="bi bi-people me-2"></i> Clientes
    </a>

    <a href="{{ route('profile.edit') }}">
        <i class="bi bi-person-circle me-2"></i> Perfil
    </a>
</div>
