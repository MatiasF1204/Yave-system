@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="text-2xl font-bold">
                Clientes
            </h2>

            <a href="{{ route('clients.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i>
                Registrar cliente
            </a>

        </div>


        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif


        {{-- Barra de búsqueda --}}
        <div class="mb-4">

            <form action="{{ route('clients.index') }}" method="GET" class="w-100">

                <div class="input-group">

                    <input type="text" name="search" class="form-control" placeholder="Buscar por DNI..."
                        value="{{ request('search') }}">

                    <button class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>

                </div>

            </form>

        </div>


        {{-- Tabla --}}
        <table class="table table-bordered align-middle">

            <thead>
                <tr>
                    <th>Nombre completo</th>
                    <th>DNI</th>
                    <th>Teléfono</th>

                    @if (auth()->user()->role->name === 'Administrador')
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>


            <tbody>

                @forelse ($clients as $client)
                    <tr id="client-{{ $client->id }}">

                        <td>
                            {{ $client->full_name }}
                        </td>

                        <td>
                            {{ $client->dni }}
                        </td>

                        <td>
                            {{ $client->phone }}
                        </td>


                        @if (auth()->user()->role->name === 'Administrador')
                            <td>

                                {{-- Editar --}}
                                <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-sm btn-primary">
                                    <i class="bi bi-pencil"></i>
                                    Editar
                                </a>


                                {{-- Eliminar --}}
                                <button onclick="deleteClient({{ $client->id }})" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                    Eliminar
                                </button>

                            </td>
                        @endif

                    </tr>

                @empty

                    <tr>

                        <td colspan="{{ auth()->user()->role->name === 'Administrador' ? 4 : 3 }}"
                            class="text-center text-muted">
                            @if (request('search'))
                                No se encontraron clientes activos con ese DNI.
                            @else
                                No hay clientes registrados.
                            @endif
                        </td>

                    </tr>
                @endforelse

            </tbody>

        </table>


        {{-- Paginación --}}
        <div class="mt-4">
            {{ $clients->links() }}
        </div>

    </div>


    {{-- SweetAlert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <script>
        // ==========================================
        // ELIMINAR CLIENTE
        // ==========================================

        function deleteClient(id) {

            Swal.fire({

                title: "¿Eliminar cliente?",

                text: "Sus datos e historial se conservarán.",

                icon: "warning",

                showCancelButton: true,

                confirmButtonColor: "#d33",

                cancelButtonColor: "#6c757d",

                confirmButtonText: "Sí, eliminar",

                cancelButtonText: "Cancelar"

            }).then((result) => {

                if (result.isConfirmed) {

                    fetch(`/clients/${id}/deactivate`, {

                            method: 'PUT',

                            headers: {

                                'X-CSRF-TOKEN': '{{ csrf_token() }}',

                                'Accept': 'application/json',

                            }

                        })

                        .then(response => {

                            if (!response.ok) {
                                throw new Error('Error al eliminar el cliente.');
                            }

                            return response.json();

                        })

                        .then(data => {

                            if (data.success) {

                                Swal.fire({

                                    title: "¡Cliente eliminado!",

                                    text: "El cliente fue eliminado del listado y su historial se conservó.",

                                    icon: "success",

                                    confirmButtonText: "Aceptar"

                                }).then(() => {

                                    location.reload();

                                });

                            }

                        })

                        .catch(error => {

                            Swal.fire(
                                "Error",
                                error.message,
                                "error"
                            );

                        });

                }

            });

        }
    </script>
@endsection
