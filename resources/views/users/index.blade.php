@extends('layouts.admin')

@section('content') <div class="p-6 bg-white rounded-lg shadow">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-bold">Usuarios</h2>

        <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i>
            Registrar usuario
        </a>
    </div>


    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif


    <table class="table table-bordered align-middle">

        {{-- Cabecera de la tabla --}}
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Acciones</th>
            </tr>
        </thead>


        <tbody>

            @foreach ($users as $user)

                <tr id="user-{{ $user->id }}">

                    {{-- Usuario --}}
                    <td>
                        {{ $user->name }}
                    </td>


                    {{-- Email --}}
                    <td>
                        {{ $user->email }}
                    </td>


                    {{-- Rol --}}
                    <td>
                        {{ $user->role ? $user->role->name : 'Sin rol' }}
                    </td>


                    {{-- Acciones --}}
                    <td>

                        {{-- Si está activo --}}
                        @if ($user->status === 'active')

                            <a
                                href="{{ route('admin.users.edit', $user) }}"
                                class="btn btn-sm btn-primary"
                            >
                                <i class="bi bi-pencil"></i>
                                Editar
                            </a>

                            <button
                                onclick="deleteUser({{ $user->id }})"
                                class="btn btn-sm btn-danger"
                            >
                                <i class="bi bi-trash"></i>
                                Eliminar
                            </button>

                        @else

                            {{-- Si está inactivo --}}
                            <button
                                onclick="activateUser({{ $user->id }})"
                                class="btn btn-sm btn-success"
                            >
                                <i class="bi bi-arrow-clockwise"></i>
                                Restaurar
                            </button>

                        @endif

                    </td>

                </tr>

            @endforeach

        </tbody>

    </table>

</div>


{{-- SweetAlert2 --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

    // ==========================================
    // ELIMINAR / DESACTIVAR USUARIO
    // ==========================================

    function deleteUser(id) {

        Swal.fire({

            title: "¿Eliminar usuario?",

            text: "El usuario dejará de estar disponible. Su historial se conservará.",

            icon: "warning",

            showCancelButton: true,

            confirmButtonColor: "#d33",

            cancelButtonColor: "#6c757d",

            confirmButtonText: "Sí, eliminar",

            cancelButtonText: "Cancelar"

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

                        Swal.fire(
                            "Error",
                            data.error,
                            "error"
                        );

                    }

                });

            }

        });

    }


    // ==========================================
    // RESTAURAR USUARIO
    // ==========================================

    function activateUser(id) {

        Swal.fire({

            title: "¿Restaurar usuario?",

            text: "El usuario volverá a estar activo y podrá utilizarse nuevamente.",

            icon: "info",

            showCancelButton: true,

            confirmButtonColor: "#198754",

            cancelButtonColor: "#6c757d",

            confirmButtonText: "Sí, restaurar",

            cancelButtonText: "Cancelar"

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

                    } else if (data.error) {

                        Swal.fire(
                            "Error",
                            data.error,
                            "error"
                        );

                    }

                });

            }

        });

    }

</script>


@endsection
