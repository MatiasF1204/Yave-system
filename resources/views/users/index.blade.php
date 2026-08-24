@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-2xl font-bold">Usuarios</h2>
            <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Registrar usuario
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered align-middle">
            {{-- Cabezera de la tabla --}}
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr id="user-{{ $user->id }}">
                        <td>
                            {{ $user->name }}
                        </td>

                        <td>{{ $user->email }}</td>

                        <td>{{ $user->role ? $user->role->name : 'Sin rol' }}</td>

                        <td>
                            <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                {{ ucfirst($user->status) }}
                            </span>
                        </td>

                        <td>
                            {{-- Valida que no se muestre el botón de editar, si el usuario está inactivo --}}
                            @if ($user->status === 'active')
                                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">
                                    Editar
                                </a>

                                <button onclick="deactivateUser({{ $user->id }})" class="btn btn-sm btn-danger">
                                    Desactivar
                                </button>
                            @else
                                <button onclick="activateUser({{ $user->id }})" class="btn btn-sm btn-success">
                                    Activar
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Función que muestra mensaje de inactivación de usuario
        function deactivateUser(id) {
            Swal.fire({
                title: "¿Desactivar usuario?",
                text: "El usuario no podrá utilizarse.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, desactivar"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/users/${id}/deactivate`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            } else if (data.error) {
                                Swal.fire("Error", data.error, "error");
                            }
                        });
                }
            });
        }

        function activateUser(id) {
            Swal.fire({
                title: "¿Activar usuario?",
                text: "El usuario estará activo nuevamente.",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, activar"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/users/${id}/activate`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                }
            });
        }
    </script>
@endsection
