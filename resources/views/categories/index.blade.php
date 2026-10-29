@extends('layouts.admin')

@section('content')
    <div class="container-fluid categories-page">

        {{-- Encabezado --}}
        <div class="categories-heading mb-4">
            <div class="heading-content">
                <span class="heading-label">GESTIÓN DE CATEGORÍAS</span>
                <h2>Categorías</h2>
                <p>Organizá y administrá las categorías de tus productos.</p>
            </div>

            <div class="heading-action">
                <a href="{{ route('admin.categories.create') }}" class="btn-register">
                    <i class="bi bi-folder-plus"></i>
                    <span>Registrar categoría</span>
                </a>
            </div>
        </div>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="alert alert-success categories-alert">
                <i class="bi bi-check-circle-fill"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        {{-- Tarjeta de búsqueda --}}
        <div class="categories-card search-card mb-4">
            <div class="section-heading">
                <span class="section-icon">
                    <i class="bi bi-search"></i>
                </span>

                <div class="section-heading-text">
                    <h5>Buscar categorías</h5>
                    <small>Encontrá una categoría por su nombre.</small>
                </div>
            </div>

            <form action="{{ route('admin.categories.index') }}" method="GET">
                <div class="search-form">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-input-icon"></i>

                        <input type="text" name="search" class="search-input" placeholder="Buscar por nombre..."
                            value="{{ request('search') }}" aria-label="Buscar categorías por nombre">
                    </div>

                    <button type="submit" class="btn-search">
                        <i class="bi bi-search"></i>
                        <span>Buscar</span>
                    </button>

                    @if (request('search'))
                        <a href="{{ route('admin.categories.index') }}" class="btn-clear-search">
                            <i class="bi bi-x-circle"></i>
                            <span>Limpiar</span>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Listado --}}
        <div class="categories-card">

            <div class="section-heading list-heading">
                <span class="section-icon">
                    <i class="bi bi-folder2-open"></i>
                </span>

                <div class="section-heading-text">
                    <h5>Listado de categorías</h5>
                    <small>Categorías registradas en el sistema.</small>
                </div>

                <div class="categories-count">
                    {{ $categories->total() }}
                    {{ $categories->total() === 1 ? 'categoría' : 'categorías' }}
                </div>
            </div>

            <div class="table-responsive">
                <table class="table categories-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="category-column">Categoría</th>

                            @if (auth()->user()->role->name === 'Administrador')
                                <th class="actions-column text-end">Acciones</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($categories as $category)
                            <tr id="category-{{ $category->id }}">
                                <td>
                                    <div class="category-info">
                                        <div class="category-icon">
                                            <i class="bi bi-folder-fill"></i>
                                        </div>

                                        <div class="category-details">
                                            <span class="category-name">
                                                {{ $category->name }}
                                            </span>

                                            <span class="category-id">
                                                ID: {{ $category->id }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                @if (auth()->user()->role->name === 'Administrador')
                                    <td>
                                        <div class="category-actions">
                                            <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn-edit"
                                                title="Editar categoría"
                                                aria-label="Editar categoría {{ $category->name }}">
                                                <i class="bi bi-pencil-square"></i>
                                                <span>Editar</span>
                                            </a>

                                            <button type="button" class="btn-delete"
                                                onclick="deactivateCategory({{ $category->id }})"
                                                title="Eliminar categoría"
                                                aria-label="Eliminar categoría {{ $category->name }}">
                                                <i class="bi bi-trash3"></i>
                                                <span>Eliminar</span>
                                            </button>
                                        </div>
                                    </td>
                                @endif
                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role->name === 'Administrador' ? 2 : 1 }}">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-folder2-open"></i>
                                        </div>

                                        @if (request('search'))
                                            <h6>No se encontraron categorías</h6>
                                            <p>No hay categorías que coincidan con "{{ request('search') }}".</p>

                                            <a href="{{ route('admin.categories.index') }}" class="btn-empty-action">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                                Ver todas las categorías
                                            </a>
                                        @else
                                            <h6>No hay categorías registradas</h6>
                                            <p>Cuando registres una categoría, aparecerá en este listado.</p>

                                            @if (auth()->user()->role->name === 'Administrador')
                                                <a href="{{ route('admin.categories.create') }}" class="btn-empty-action">
                                                    <i class="bi bi-folder-plus"></i>
                                                    Registrar categoría
                                                </a>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if ($categories->hasPages())
                <div class="categories-footer">
                    <div class="categories-pagination">
                        {{ $categories->withQueryString()->links() }}
                    </div>
                </div>
            @endif

        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function deactivateCategory(id) {
            Swal.fire({
                title: "¿Eliminar categoría?",
                text: "La categoría dejará de aparecer. Su historial se conservará.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#a83240",
                cancelButtonColor: "#6c757d",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Sí, eliminar"
            }).then((result) => {
                if (!result.isConfirmed) {
                    return;
                }

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
                            text: data.message || 'La categoría se eliminó correctamente.',
                            icon: "success",
                            confirmButtonText: "Aceptar"
                        }).then(() => {
                            location.reload();
                        });
                    })
                    .catch(error => {
                        Swal.fire({
                            title: "No se puede eliminar",
                            text: error.message || 'Ocurrió un error inesperado.',
                            icon: "error",
                            confirmButtonText: "Entendido"
                        });
                    });
            });
        }
    </script>
@endsection
