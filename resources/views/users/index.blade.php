@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    <h2 class="text-2xl font-bold mb-4">Usuarios</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
            <tr id="user-{{ $user->id }}">
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->role ? $user->role->name : 'Sin rol' }}</td>
                <td>
                    <span class="badge {{ $user->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($user->status) }}
                    </span>
                </td>

                <td>
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-primary">Editar</a>

                    @if($user->status === 'active')
                        <button onclick="deactivateUser({{ $user->id }})"
                                class="btn btn-sm btn-danger">
                            Desactivar
                        </button>
                    @else
                        <button onclick="activateUser({{ $user->id }})"
                                class="btn btn-sm btn-success">
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
function deactivateUser(id) {
    Swal.fire({
        title: "¿Desactivar usuario?",
        text: "El usuario quedará inactivo.",
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
