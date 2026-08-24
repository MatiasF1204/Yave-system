@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-2xl font-bold">Clientes</h2>
            <a href="{{ route('clients.create') }}" class="btn btn-primary">
                <i class="bi bi-person-plus"></i> Registrar cliente
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

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

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre completo</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    @if (auth()->user()->role->name === 'Administrador')
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @if (request('search') && $clients->isEmpty())
                    <tr>
                        <td colspan="{{ auth()->user()->role->name === 'Administrador' ? 5 : 4 }}"
                            class="text-center text-muted">
                            No se encontraron clientes con ese DNI.
                        </td>
                    </tr>
                @else
                    @foreach ($clients as $client)
                        <tr>
                            <td>{{ $client->full_name }}</td>
                            <td>{{ $client->dni }}</td>
                            <td>{{ $client->phone }}</td>
                            <td>
                                <span class="badge {{ $client->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($client->status) }}
                                </span>
                            </td>

                            @if (auth()->user()->role->name === 'Administrador')
                                <td>
                                    @if ($client->status === 'active')
                                    <a href="{{ route('clients.edit', $client->id) }}"
                                        class="btn btn-sm btn-warning">Editar
                                    </a>

                                    <button onclick="deactivateClient({{ $client->id }})"
                                        class="btn btn-sm btn-danger">
                                        Desactivar
                                    </button>
                                    @else
                                        <button onclick="activateClient({{ $client->id }})"
                                            class="btn btn-sm btn-success">
                                            Activar
                                        </button>
                                    @endif
                                </td>
                            @endif
                        </tr>
                    @endforeach
                @endif
            </tbody>

        </table>

        {{-- Paginación --}}
        <div class="d-flex justify-content-center mt-4">
            {{ $clients->links() }}
        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function deactivateClient(id) {
            Swal.fire({
                title: "¿Desactivar cliente?",
                text: "El cliente pasará a estado inactivo.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, desactivar"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/clients/${id}/deactivate`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: "¡Actualizado!",
                                    text: "El cliente ha sido desactivado.",
                                    icon: "success",
                                    confirmButtonText: "Aceptar"
                                }).then(() => {
                                    location.reload();
                                });                            }
                        });
                }
            });
        }

        function activateClient(id) {
            Swal.fire({
                title: "¿Activar cliente?",
                text: "El cliente pasará a estado activo.",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, activar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/clients/${id}/activate`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Error al activar el cliente.');
                            }

                            return response.json();
                        })
                        .then(data => {

                            if (data.success) {
                                Swal.fire({
                                    title: "¡Actualizado!",
                                    text: "El cliente ha sido activado.",
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
