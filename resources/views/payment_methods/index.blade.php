@extends('layouts.admin')

@section('content')

<div class="p-6 bg-white rounded-lg shadow">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-2xl font-bold">
            Métodos de Pago
        </h2>

        <a
            href="{{ route('admin.payment_methods.create') }}"
            class="btn btn-primary"
        >
            <i class="bi bi-plus-circle"></i>
            Registrar método de pago
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

        <form
            action="{{ route('admin.payment_methods.index') }}"
            method="GET"
        >

            <div class="input-group">

                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Buscar por nombre..."
                    value="{{ request('search') }}"
                >

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

                <th>
                    Nombre
                </th>

                <th>
                    Acciones
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse ($paymentMethods as $pm)

                <tr id="payment-method-{{ $pm->id }}">

                    <td>
                        {{ $pm->name }}
                    </td>


                    <td>

                        {{-- Editar --}}
                        <a
                            href="{{ route('admin.payment_methods.edit', $pm->id) }}"
                            class="btn btn-sm btn-primary"
                        >
                            <i class="bi bi-pencil"></i>
                            Editar
                        </a>


                        {{-- Eliminar --}}
                        <button
                            onclick="deletePM({{ $pm->id }})"
                            class="btn btn-sm btn-danger"
                        >
                            <i class="bi bi-trash"></i>
                            Eliminar
                        </button>

                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="2"
                        class="text-center text-muted"
                    >
                        @if (request('search'))
                            No se encontraron métodos de pago activos con ese nombre.
                        @else
                            No hay métodos de pago registrados.
                        @endif
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>

</div>


{{-- SweetAlert2 --}}
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>


<script>

    // ==========================================
    // ELIMINAR MÉTODO DE PAGO
    // ==========================================

    function deletePM(id) {

        Swal.fire({

            title: "¿Eliminar método de pago?",

            text: "El método dejará de aparecer en el listado, pero sus datos e historial se conservarán.",

            icon: "warning",

            showCancelButton: true,

            confirmButtonColor: "#d33",

            cancelButtonColor: "#6c757d",

            confirmButtonText: "Sí, eliminar",

            cancelButtonText: "Cancelar"

        }).then((result) => {

            if (result.isConfirmed) {

                fetch(`/admin/payment-methods/${id}/deactivate`, {

                    method: 'PUT',

                    headers: {

                        'X-CSRF-TOKEN': '{{ csrf_token() }}',

                        'Accept': 'application/json',

                    }

                })

                .then(response => {

                    if (!response.ok) {
                        throw new Error('Error al eliminar el método de pago.');
                    }

                    return response.json();

                })

                .then(data => {

                    if (data.success) {

                        Swal.fire({

                            title: "¡Método eliminado!",

                            text: "El método de pago fue eliminado del listado y su historial se conservó.",

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
