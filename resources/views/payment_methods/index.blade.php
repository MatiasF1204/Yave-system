@extends('layouts.admin')

@section('content')
<div class="p-6 bg-white rounded-lg shadow">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-bold">Métodos de Pago</h2>
        <a href="{{ route('admin.payment_methods.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Registrar método de pago
        </a>
    </div>

    <div class="mb-4">
        <form action="{{ route('admin.payment_methods.index') }}" method="GET">
            <div class="input-group">
                <input type="text" name="search" class="form-control"
                       placeholder="Buscar por nombre..."
                       value="{{ request('search') }}">
                <button class="btn btn-primary"><i class="bi bi-search"></i></button>
            </div>
        </form>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($paymentMethods as $pm)
                <tr>
                    <td>{{ $pm->name }}</td>
                    <td>
                        <span class="badge {{ $pm->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                            {{ ucfirst($pm->status) }}
                        </span>
                    </td>

                    <td>
                        <a href="{{ route('admin.payment_methods.edit', $pm->id) }}"
                           class="btn btn-sm btn-warning">Editar</a>

                        @if($pm->status === 'active')
                            <button onclick="deactivatePM({{ $pm->id }})"
                                    class="btn btn-sm btn-danger">Desactivar</button>
                        @else
                            <button onclick="activatePM({{ $pm->id }})"
                                    class="btn btn-sm btn-success">Activar</button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center text-muted">
                        No se encontraron métodos de pago.
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>

<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function deactivatePM(id) {
        Swal.fire({
            title: "¿Desactivar método de pago?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Sí, desactivar"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/payment-methods/${id}/deactivate`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                }).then(res => res.json())
                  .then(() => location.reload());
            }
        });
    }

    function activatePM(id) {
        Swal.fire({
            title: "¿Activar método de pago?",
            icon: "info",
            showCancelButton: true,
            confirmButtonText: "Sí, activar"
        }).then((result) => {
            if (result.isConfirmed) {
                fetch(`/admin/payment-methods/${id}/activate`, {
                    method: 'PUT',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    }
                }).then(res => res.json())
                  .then(() => location.reload());
            }
        });
    }
</script>
@endsection
