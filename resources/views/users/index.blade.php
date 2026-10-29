
@extends('layouts.admin')

@section('content')
<div class="container-fluid users-page p-0">
    {{-- Encabezado --}}
    <div class="users-heading mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <div>
                <div class="small mb-2">
                    <i class="bi bi-people-fill me-1"></i>
                    GESTIÓN DE USUARIOS
                </div>

                <h1 class="h3 fw-bold mb-2">Usuarios</h1>

                <p class="mb-0">
                    Consultá y administrá las cuentas, los roles y el acceso al sistema.
                </p>
            </div>

            <a href="{{ route('admin.users.create') }}" class="btn btn-register">
                <i class="bi bi-person-plus-fill me-1"></i>
                Registrar usuario
            </a>
        </div>
    </div>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
        </div>
    @endif

    {{-- Tabla de usuarios --}}
    <div class="users-card">

        {{-- Cabecera de la tabla --}}
        <div class="section-heading d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div class="d-flex align-items-center">
                <span class="section-icon">
                    <i class="bi bi-person-lines-fill"></i>
                </span>

                <div>
                    <h5 class="mb-1 fw-bold">Listado de usuarios</h5>
                    <small class="text-muted">
                        Usuarios registrados y estado de sus cuentas.
                    </small>
                </div>
            </div>

            <span class="users-count">
                <i class="bi bi-people-fill me-1"></i>
                {{ method_exists($users, 'total') ? $users->total() : $users->count() }}
                usuario(s)
            </span>
        </div>

        {{-- Tabla --}}
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th>Usuario</th>
                        <th>Correo electrónico</th>
                        <th>Rol</th>
                        <th>Estado</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($users as $user)
                        <tr id="user-{{ $user->id }}">

                            {{-- Usuario --}}
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <span class="user-avatar">
                                        {{ mb_strtoupper(mb_substr($user->name, 0, 1)) }}
                                    </span>

                                    <div class="user-details">
                                        <span class="user-name">{{ $user->name }}</span>
                                        <small class="user-id">ID: {{ $user->id }}</small>
                                    </div>
                                </div>
                            </td>

                            {{-- Correo electrónico --}}
                            <td>
                                <div class="user-email">
                                    <i class="bi bi-envelope-fill me-1"></i>
                                    {{ $user->email }}
                                </div>
                            </td>

                            {{-- Rol --}}
                            <td>
                                @if ($user->role)
                                    <span class="user-role">
                                        <i class="bi bi-shield-check me-1"></i>
                                        {{ $user->role->name }}
                                    </span>
                                @else
                                    <span class="user-role user-role-none">
                                        <i class="bi bi-shield-exclamation me-1"></i>
                                        Sin rol
                                    </span>
                                @endif
                            </td>

                            {{-- Estado --}}
                            <td>
                                @if ($user->status === 'active')
                                    <span class="user-status status-active">
                                        <span class="status-dot"></span>
                                        Activo
                                    </span>
                                @else
                                    <span class="user-status status-inactive">
                                        <span class="status-dot"></span>
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            {{-- Acciones --}}
                            <td class="text-center">
                                <div class="d-flex flex-wrap justify-content-center gap-2">
                                    @if ($user->status === 'active')
                                        <a href="{{ route('admin.users.edit', $user) }}"
                                           class="btn btn-sm btn-edit">
                                            <i class="bi bi-pencil-square me-1"></i>
                                            Editar
                                        </a>

                                        <button type="button"
                                                onclick="deleteUser({{ $user->id }})"
                                                class="btn btn-sm btn-delete">
                                            <i class="bi bi-trash3 me-1"></i>
                                            Eliminar
                                        </button>
                                    @else
                                        <button type="button"
                                                onclick="activateUser({{ $user->id }})"
                                                class="btn btn-sm btn-restore">
                                            <i class="bi bi-arrow-counterclockwise me-1"></i>
                                            Restaurar
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <div class="empty-icon">
                                        <i class="bi bi-person-x"></i>
                                    </div>

                                    <h5 class="fw-bold mb-2">No hay usuarios registrados</h5>

                                    <p class="text-muted mb-3">
                                        Todavía no hay usuarios para mostrar en este listado.
                                    </p>

                                    <a href="{{ route('admin.users.create') }}"
                                       class="btn btn-register-empty">
                                        <i class="bi bi-person-plus-fill me-1"></i>
                                        Registrar usuario
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if (method_exists($users, 'hasPages') && ($users->hasPages() || $users->total() > 0))
            <div class="results-footer d-flex flex-wrap justify-content-between align-items-center gap-3">
                {{ $users->withQueryString()->links() }}
            </div>
        @endif

    </div>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Desactivar usuario
    function deleteUser(id) {
        Swal.fire({
            title: '¿Eliminar usuario?',
            text: 'El usuario dejará de estar disponible. Su historial se conservará.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#a83240',
            cancelButtonColor: '#344b65',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            fetch(`/admin/users/${id}/deactivate`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.error || 'No se pudo desactivar el usuario.'
                    );
                }

                return data;
            })
            .then(data => {
                if (!data.success) {
                    throw new Error(
                        data.error || 'El sistema no confirmó la desactivación.'
                    );
                }

                Swal.fire({
                    title: '¡Usuario desactivado!',
                    text: 'El usuario fue quitado del listado de activos y su historial se conservó.',
                    icon: 'success',
                    confirmButtonColor: '#142b4a',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: error.message || 'Ocurrió un problema de conexión.',
                    icon: 'error',
                    confirmButtonColor: '#142b4a'
                });
            });
        });
    }

    // Restaurar usuario
    function activateUser(id) {
        Swal.fire({
            title: '¿Restaurar usuario?',
            text: 'El usuario volverá a estar activo y podrá utilizarse nuevamente.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28784d',
            cancelButtonColor: '#344b65',
            confirmButtonText: 'Sí, restaurar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (!result.isConfirmed) {
                return;
            }

            fetch(`/admin/users/${id}/activate`, {
                method: 'PUT',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const data = await response.json();

                if (!response.ok) {
                    throw new Error(
                        data.error || 'No se pudo restaurar el usuario.'
                    );
                }

                return data;
            })
            .then(data => {
                if (!data.success) {
                    throw new Error(
                        data.error || 'El sistema no confirmó la restauración.'
                    );
                }

                Swal.fire({
                    title: '¡Usuario restaurado!',
                    text: 'El usuario está activo nuevamente.',
                    icon: 'success',
                    confirmButtonColor: '#142b4a',
                    confirmButtonText: 'Aceptar'
                }).then(() => {
                    location.reload();
                });
            })
            .catch(error => {
                Swal.fire({
                    title: 'Error',
                    text: error.message || 'Ocurrió un problema de conexión.',
                    icon: 'error',
                    confirmButtonColor: '#142b4a'
                });
            });
        });
    }
</script>
@endsection
