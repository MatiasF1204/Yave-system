@extends('layouts.admin')

@section('content')
    <div class="container-fluid products-page p-0">

        {{-- Encabezado --}}
        <div class="products-heading mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="small mb-2 heading-label">
                        <i class="bi bi-box-seam-fill me-1"></i>
                        GESTIÓN DE INVENTARIO
                    </div>

                    <h1 class="h3 fw-bold mb-2">Productos</h1>

                    <p class="mb-0">
                        Consultá los productos, sus precios y las existencias disponibles.
                    </p>
                </div>

                @if (auth()->user()->role->name === 'Administrador')
                    <a href="{{ route('admin.products.create') }}" class="btn-register">
                        <i class="bi bi-plus-circle-fill"></i>
                        Registrar producto
                    </a>
                @endif
            </div>
        </div>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show products-alert" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- Listado --}}
        <div class="products-card">

            {{-- Cabecera y buscador --}}
            <div class="section-heading">
                <div class="section-title">
                    <span class="section-icon">
                        <i class="bi bi-boxes"></i>
                    </span>

                    <div>
                        <h5 class="mb-1">Listado de productos</h5>
                        <small>Información de precios, imágenes y stock.</small>
                    </div>
                </div>

                <div class="products-count">
                    <i class="bi bi-box-seam me-1"></i>
                    {{ method_exists($products, 'total') ? $products->total() : $products->count() }}
                    producto(s)
                </div>
            </div>

            {{-- Búsqueda --}}
            <div class="search-section">
                <form action="{{ route('products.index') }}" method="GET" class="products-search">
                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>

                        <input type="text" name="search" class="search-input"
                            placeholder="Buscar por nombre de producto o categoría..." value="{{ request('search') }}"
                            aria-label="Buscar productos">

                        @if (request('search'))
                            <a href="{{ route('products.index') }}" class="clear-search" aria-label="Limpiar búsqueda"
                                title="Limpiar búsqueda">
                                <i class="bi bi-x-circle"></i>
                            </a>
                        @endif
                    </div>

                    <button type="submit" class="btn-search">
                        <i class="bi bi-search me-1"></i>
                        Buscar
                    </button>
                </form>

                @if (request('search'))
                    <div class="search-feedback">
                        <i class="bi bi-funnel-fill"></i>
                        Resultados para:
                        <strong>{{ request('search') }}</strong>
                    </div>
                @endif
            </div>

            {{-- Tabla --}}
            <div class="table-responsive">
                <table class="table products-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="image-column">Imagen</th>
                            <th>Producto</th>
                            <th>Precio de venta</th>
                            <th>Stock disponible</th>

                            @if (auth()->user()->role->name === 'Administrador')
                                <th class="text-center actions-column">Acciones</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            <tr id="product-{{ $product->id }}">

                                {{-- Imagen --}}
                                <td>
                                    <a href="{{ route('products.show', $product->id) }}" class="product-image-link"
                                        aria-label="Ver {{ $product->name }}">
                                        @if ($product->image)
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}" class="product-image" loading="lazy">
                                        @else
                                            <span class="product-image-placeholder">
                                                <i class="bi bi-image"></i>
                                            </span>
                                        @endif
                                    </a>
                                </td>

                                {{-- Nombre --}}
                                <td>
                                    <div class="product-info">
                                        <a href="{{ route('products.show', $product->id) }}" class="product-name">
                                            {{ $product->name }}
                                        </a>

                                        <small class="product-reference">
                                            Código: #{{ $product->id }}
                                        </small>
                                    </div>
                                </td>

                                {{-- Precio --}}
                                <td>
                                    <span class="product-price">
                                        ${{ number_format($product->price, 2, ',', '.') }}
                                    </span>
                                </td>

                                {{-- Stock --}}
                                <td>
                                    @if ($product->stock <= 0)
                                        <span class="stock-badge stock-empty">
                                            <span class="stock-dot"></span>
                                            Sin stock
                                        </span>
                                    @elseif ($product->stock <= 5)
                                        <span class="stock-badge stock-low">
                                            <span class="stock-dot"></span>
                                            {{ $product->stock }} unidades
                                        </span>
                                    @else
                                        <span class="stock-badge stock-available">
                                            <span class="stock-dot"></span>
                                            {{ $product->stock }} unidades
                                        </span>
                                    @endif
                                </td>

                                {{-- Acciones --}}
                                @if (auth()->user()->role->name === 'Administrador')
                                    <td>
                                        <div class="product-actions">
                                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                                class="btn-action btn-edit" title="Editar producto">
                                                <i class="bi bi-pencil-square"></i>
                                                <span>Editar</span>
                                            </a>

                                            <button type="button" class="btn-action btn-deactivate"
                                                onclick="deactivateProduct({{ $product->id }})"
                                                title="Desactivar producto">
                                                <i class="bi bi-trash3"></i>
                                                <span>Eliminar</span>
                                            </button>
                                        </div>
                                    </td>
                                @endif

                            </tr>
                        @empty
                            <tr>
                                <td colspan="{{ auth()->user()->role->name === 'Administrador' ? 5 : 4 }}">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-box-seam"></i>
                                        </div>

                                        @if (request('search'))
                                            <h5>No se encontraron productos</h5>
                                            <p>
                                                No hay productos que coincidan con
                                                <strong>{{ request('search') }}</strong>.
                                            </p>

                                            <a href="{{ route('products.index') }}" class="btn-empty">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                Ver todos los productos
                                            </a>
                                        @else
                                            <h5>No hay productos registrados</h5>
                                            <p>Todavía no hay productos disponibles para mostrar.</p>

                                            @if (auth()->user()->role->name === 'Administrador')
                                                <a href="{{ route('admin.products.create') }}" class="btn-empty">
                                                    <i class="bi bi-plus-circle me-1"></i>
                                                    Registrar producto
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

            {{-- Pie y paginación --}}
            @if ($products->count() > 0)
                <div class="products-footer">
                    <div class="results-info">
                        @if (method_exists($products, 'firstItem') && $products->firstItem())
                            Mostrando
                            <strong>{{ $products->firstItem() }}</strong>
                            a
                            <strong>{{ $products->lastItem() }}</strong>
                            de
                            <strong>{{ $products->total() }}</strong>
                            productos
                        @else
                            <strong>{{ $products->count() }}</strong> producto(s)
                        @endif
                    </div>

                    @if (method_exists($products, 'hasPages') && $products->hasPages())
                        <div class="products-pagination">
                            {{ $products->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Desactivar producto
        function deactivateProduct(id) {
            Swal.fire({
                title: '¿Desactivar producto?',
                text: 'El producto dejará de aparecer en el listado de activos. Su historial se conservará.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#a83240',
                cancelButtonColor: '#344b65',
                confirmButtonText: 'Sí, desactivar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                focusCancel: true
            }).then(async (result) => {
                if (!result.isConfirmed) return;

                try {
                    const response = await fetch(
                        `/admin/products/${id}/deactivate`, {
                            method: 'PUT',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }
                    );

                    const data = await response.json();

                    if (!response.ok || !data.success) {
                        throw new Error(
                            data.message ||
                            data.error ||
                            'No se pudo desactivar el producto.'
                        );
                    }

                    await Swal.fire({
                        title: '¡Producto desactivado!',
                        text: data.message || 'El producto fue desactivado correctamente.',
                        icon: 'success',
                        confirmButtonColor: '#142b4a',
                        confirmButtonText: 'Aceptar'
                    });

                    window.location.reload();

                } catch (error) {
                    Swal.fire({
                        title: 'No se pudo completar la operación',
                        text: error.message || 'Ocurrió un problema de conexión. Intentá nuevamente.',
                        icon: 'error',
                        confirmButtonColor: '#142b4a',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    </script>
@endsection
