@extends('layouts.admin')

@section('content')
    <div class="p-6 bg-white rounded-lg shadow">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="text-2xl font-bold">Categorías</h2>
            <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                <i class="bi bi-folder-plus"></i> Registrar categoría
            </a>
        </div>

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

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Estado</th>
                    @if (auth()->user()->role->name === 'Administrador')
                        <th>Acciones</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @if (request('search') && $categories->isEmpty())
                    <tr>
                        <td colspan="{{ auth()->user()->role->name === 'Administrador' ? 3 : 2 }}"
                            class="text-center text-muted">
                            No se encontraron categorías con ese nombre.
                        </td>
                    </tr>
                @else
                    @foreach ($categories as $category)
                        <tr>
                            <td>{{ $category->name }}</td>
                            <td>
                                <span class="badge {{ $category->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                    {{ ucfirst($category->status) }}
                                </span>
                            </td>

                            @if (auth()->user()->role->name === 'Administrador')
                                <td>
                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                        class="btn btn-sm btn-warning">
                                        Editar
                                    </a>

                                    @if ($category->status === 'active')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="deactivateCategory({{ $category->id }})">
                                            Desactivar
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-success"
                                            onclick="activateCategory({{ $category->id }})">
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
    </div>

    {{-- SweetAlert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function deactivateCategory(id) {
            Swal.fire({
                title: "¿Desactivar categoría?",
                text: "La categoría pasará a estado inactivo.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, desactivar"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/categories/${id}/deactivate`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        }).then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                location.reload();
                            }
                        });
                }
            });
        }

        function activateCategory(id) {
            Swal.fire({
                title: "¿Activar categoría?",
                text: "La categoría pasará a estado activo.",
                icon: "info",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, activar"
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/admin/categories/${id}/activate`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        }).then(res => res.json())
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
