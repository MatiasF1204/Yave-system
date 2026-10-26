@extends('layouts.admin')

@section('content') <div class="p-6 bg-white rounded-lg shadow">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-2xl font-bold">Categorías</h2>

            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-folder-plus"></i> Registrar categoría
            </a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Barra de búsqueda --}}
        <div class="mb-4">
            <form action="{{ route('admin.categories.index') }}" method="GET" class="w-100">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Buscar por nombre..."
                        value="{{ request('search') }}">

                    <button class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </form>
        </div>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Nombre</th>

                    @if (auth()->user()->role->name === 'Administrador')
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>

            <tbody>

                @if ($categories->isEmpty())
                    <tr>
                        <td colspan="{{ auth()->user()->role->name === 'Administrador' ? 2 : 1 }}"
                            class="text-center text-muted">
                            @if (request('search'))
                                No se encontraron categorías con ese nombre.
                            @else
                                No hay categorías registradas.
                            @endif
                        </td>
                    </tr>
                @else
                    @foreach ($categories as $category)
                        <tr>
                            <td>
                                {{ $category->name }}
                            </td>

                            @if (auth()->user()->role->name === 'Administrador')
                                <td>

                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="btn btn-sm btn-warning">
                                        Editar
                                    </a>

                                    <button class="btn btn-sm btn-danger" onclick="deactivateCategory({{ $category->id }})">
                                        Eliminar
                                    </button>

                                </td>
                            @endif

                        </tr>
                    @endforeach
                @endif

            </tbody>
        </table>

        <div class="mt-4">
            {{ $categories->links() }}
        </div>

    </div>

    {{-- SweetAlert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function deactivateCategory(id) {
            Swal.fire({
                title: "¿Eliminar categoría?",
                text: "La categoría dejará de aparecer. Su historial se conservará.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#d33",
                cancelButtonColor: "#6c757d",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Sí, eliminar"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/categories/${id}/deactivate`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(async response => {
                            const data = await response.json();

                            if (!response.ok || !data.success) {
                                throw new Error(
                                    data.message || 'No se pudo desactivar la categoría.'
                                );
                            }

                            return data;
                        })
                        .then(data => {
                            Swal.fire({
                                title: "¡Eliminada!",
                                text: data.message,
                                icon: "success",
                                confirmButtonText: "Aceptar"
                            }).then(() => {
                                location.reload();
                            });
                        })
                        .catch(error => {
                            Swal.fire({
                                title: "No se puede eliminar",
                                text: error.message,
                                icon: "error",
                                confirmButtonText: "Entendido"
                            });
                        });
                }
            });
        }
    </script>

@endsection
