@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-bold">Clientes</h2>
        <a href="{{ route('client.create') }}" class="btn btn-primary">
            <i class="bi bi-person-plus"></i> Registrar cliente
        </a>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre completo</th>
                <th>DNI</th>
                <th>Teléfono</th>
                <th>Estado</th>
                @if(auth()->user()->role->name === 'Administrador')
                    <th>Acciones</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($clients as $client)
            <tr>
                <td>{{ $client->full_name }}</td>
                <td>{{ $client->dni }}</td>
                <td>{{ $client->phone }}</td>
                <td>
                    <span class="badge {{ $client->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                        {{ ucfirst($client->status) }}
                    </span>
                </td>

                @if(auth()->user()->role->name === 'Administrador')
                <td>
                    <a href="{{ route('client.edit', $client->client_id) }}" class="btn btn-sm btn-warning">Editar</a>
                    @if($client->status === 'active')
                        <button onclick="deactivateClient({{ $client->client_id }})" class="btn btn-sm btn-danger">
                            Desactivar
                        </button>
                    @else
                        <button onclick="activateClient({{ $client->client_id }})" class="btn btn-sm btn-success">
                            Activar
                        </button>
                    @endif
                </td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>
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
                    location.reload();
                    Swal.fire("¡Actualizado!", "El cliente ha sido desactivado.", "success");
                }
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
        confirmButtonText: "Sí, activar"
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/clients/${id}/activate`, {
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
                    Swal.fire("¡Actualizado!", "El cliente ha sido activado.", "success");
                }
            });
        }
    });
}
</script>
@endsection
