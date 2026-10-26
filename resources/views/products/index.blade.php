@extends('layouts.admin')

@section('content')

    <div class="p-6 bg-white rounded-lg shadow">
        {{-- Encabezado --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="text-2xl font-bold">
                Productos
            </h2>

            @if (auth()->user()->role->name === 'Administrador')
                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                    <i class="bi bi-box-seam"></i>
                    Registrar producto
                </a>
            @endif
        </div>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Barra de búsqueda --}}
        <div class="mb-4">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="search" class="form-control"
                        placeholder="Buscar por nombre de producto o categoría..." value="{{ request('search') }}">

                    <button class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>

                </div>
            </form>
        </div>

        {{-- Tabla --}}
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Precio de venta</th>
                        <th>Stock</th>
                        @if (auth()->user()->role->name === 'Administrador')
                            <th>Acciones</th>
                        @endif
                    </tr>
                </thead>

                <tbody>
                    @if ($products->isEmpty())
                        <tr>
                            <td colspan="{{ auth()->user()->role->name === 'Administrador' ? 5 : 4 }}"
                                class="text-center text-muted">
                                @if (request('search'))
                                    No se encontraron productos con ese nombre.
                                @else
                                    No hay productos registrados.
                                @endif
                            </td>
                        </tr>
                    @else
                        @foreach ($products as $product)
                            <tr>
                                {{-- Imagen --}}
                                <td>
                                    @if ($product->image)
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            width="60" height="60" style="object-fit: cover; border-radius: 6px;">
                                    @else
                                        <span class="text-muted">
                                            Sin imagen
                                        </span>
                                    @endif
                                </td>

                                {{-- Nombre --}}
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="product-name">
                                        {{ $product->name }}
                                    </a>
                                </td>

                                {{-- Precio --}}
                                <td>
                                    ${{ number_format($product->price, 2, ',', '.') }}
                                </td>

                                {{-- Stock --}}
                                <td>
                                    {{ $product->stock }}
                                </td>

                                {{-- Acciones --}}
                                @if (auth()->user()->role->name === 'Administrador')
                                    <td>
                                        <a href="{{ route('admin.products.edit', $product->id) }}"
                                            class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil"></i>
                                            Editar
                                        </a>
                                        <button class="btn btn-sm btn-danger"
                                            onclick="deactivateProduct({{ $product->id }})">
                                            <i class="bi bi-trash"></i>
                                            Eliminar
                                        </button>
                                    </td>
                                @endif

                            </tr>
                        @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        <div class="mt-4">
            {{ $products->links() }}
        </div>
    </div>

    {{-- Estilos del nombre del producto --}}
    <style>
        .product-name {
            font-weight: 600;
            color: inherit;
            text-decoration: none;
            transition: all 0.2s ease;
        }

        .product-name:hover {
            color: #0d6efd;
            text-decoration: underline;
        }
    </style>

    {{-- SweetAlert2 --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // ELIMINAR PRODUCTO
        function deactivateProduct(id) {

            Swal.fire({

                title: "¿Eliminar producto?",

                text: "El producto dejará de aparecer en el listado, pero no se eliminará definitivamente.",

                icon: "warning",

                showCancelButton: true,

                confirmButtonColor: "#d33",

                cancelButtonColor: "#6c757d",

                cancelButtonText: "Cancelar",

                confirmButtonText: "Sí, eliminar"

            }).then((result) => {

                if (result.isConfirmed) {

                    fetch(`/admin/products/${id}/deactivate`, {

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

                                    title: "¡Eliminado!",

                                    text: "El producto fue eliminado correctamente.",

                                    icon: "success",

                                    confirmButtonText: "Aceptar"

                                }).then(() => {

                                    location.reload();

                                });

                            }

                        });

                }

            });

        }
    </script>

@endsection
