
@extends('layouts.admin')

@section('content')

    <div class="container-fluid">

        {{-- ENCABEZADO --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
            <div>
                <h2 class="fw-bold mb-1">
                    <i class="bi bi-box-seam me-2"></i>
                    Detalle del producto
                </h2>
                <p class="text-muted mb-0">
                    Información completa del producto registrado.
                </p>
            </div>

            <div class="d-flex gap-2">
                <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1"></i>
                    Volver al listado
                </a>

                @if (auth()->user()->role->name === 'Administrador')
                    <a href="{{ route('admin.products.edit', $product->id) }}"
                       class="btn btn-primary">
                        <i class="bi bi-pencil-square me-1"></i>
                        Editar producto
                    </a>
                @endif
            </div>
        </div>

        {{-- INFORMACIÓN GENERAL --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">

                <div class="row g-4 align-items-center">

                    {{-- IMAGEN --}}
                    <div class="col-12 col-md-4 col-lg-3 text-center">
                        @if ($product->image)
                            <img
                                src="{{ asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="product-image img-fluid rounded"
                            >
                        @else
                            <div class="product-image-placeholder rounded mx-auto">
                                <i class="bi bi-image"></i>
                                <span>Sin imagen disponible</span>
                            </div>
                        @endif
                    </div>

                    {{-- DATOS PRINCIPALES --}}
                    <div class="col-12 col-md-8 col-lg-9">

                        <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                            @if ($product->status === 'active')
                                <span class="badge bg-success-subtle text-success border border-success-subtle">
                                    <i class="bi bi-check-circle me-1"></i>
                                    Activo
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border">
                                    <i class="bi bi-pause-circle me-1"></i>
                                    Inactivo
                                </span>
                            @endif

                            @if ($product->stock > 0)
                                <span class="badge bg-primary-subtle text-primary border border-primary-subtle">
                                    Disponible
                                </span>
                            @else
                                <span class="badge bg-danger-subtle text-danger border border-danger-subtle">
                                    Sin stock
                                </span>
                            @endif
                        </div>

                        <h3 class="fw-bold mb-3">
                            {{ $product->name }}
                        </h3>

                        <p class="text-muted mb-3">
                            {{ $product->description ?: 'Este producto no tiene una descripción registrada.' }}
                        </p>

                        <div class="mb-3">
                            <span class="text-muted d-block small mb-1">
                                Precio de venta
                            </span>

                            <span class="display-6 fw-bold text-primary">
                                ${{ number_format($product->price, 2, ',', '.') }}
                            </span>
                        </div>

                        <div>
                            <span class="text-muted small me-2">
                                Stock actual:
                            </span>
                            <strong>{{ $product->stock }} unidades</strong>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        {{-- CATEGORÍAS --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-tags me-2 text-primary"></i>
                    Categorías
                </h5>
            </div>

            <div class="card-body p-4">
                @forelse ($product->categories as $category)
                    <span class="badge rounded-pill bg-light text-dark border me-2 mb-2 p-2">
                        <i class="bi bi-tag me-1"></i>
                        {{ $category->name }}
                    </span>
                @empty
                    <p class="text-muted mb-0">
                        El producto no tiene categorías asociadas.
                    </p>
                @endforelse
            </div>
        </div>

        {{-- COSTOS Y RENTABILIDAD --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-calculator me-2 text-primary"></i>
                    Costos y rentabilidad
                </h5>
            </div>

            <div class="card-body p-4">
                <div class="row g-3">

                    {{-- COSTO DEL PRODUCTO --}}
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="info-box h-100">
                            <span class="info-label">Costo del producto</span>
                            <h4 class="fw-bold mb-0">
                                ${{ number_format($product->cost, 2, ',', '.') }}
                            </h4>
                        </div>
                    </div>

                    {{-- COSTO DEL TRANSPORTE --}}
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="info-box h-100">
                            <span class="info-label">Costo del transporte</span>
                            <h4 class="fw-bold mb-0">
                                ${{ number_format($product->transport_cost, 2, ',', '.') }}
                            </h4>
                        </div>
                    </div>

                    {{-- COSTO TOTAL --}}
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="info-box h-100">
                            <span class="info-label">Costo total</span>
                            <h4 class="fw-bold mb-0">
                                ${{ number_format($product->total_cost, 2, ',', '.') }}
                            </h4>
                            <small class="text-muted">
                                Producto + transporte
                            </small>
                        </div>
                    </div>

                    {{-- GANANCIA --}}
                    <div class="col-12 col-md-6 col-xl-3">
                        <div class="info-box h-100">
                            <span class="info-label">Ganancia por unidad</span>
                            <h4 class="fw-bold mb-0 {{ $product->profit >= 0 ? 'text-success' : 'text-danger' }}">
                                ${{ number_format($product->profit, 2, ',', '.') }}
                            </h4>
                            <small class="text-muted">
                                Precio de venta − costo total
                            </small>
                        </div>
                    </div>

                </div>

                {{-- MARGEN DE GANANCIA --}}
                <hr class="my-4">

                <div class="row align-items-center g-2">
                    <div class="col-12 col-md-6">
                        <span class="text-muted">Margen de ganancia sobre el precio de venta</span>
                    </div>

                    <div class="col-12 col-md-6 text-md-end">
                        @if ($product->price > 0)
                            <span class="fs-5 fw-bold">
                                {{ number_format(($product->profit / $product->price) * 100, 2, ',', '.') }}%
                            </span>
                        @else
                            <span class="text-muted">No disponible</span>
                        @endif
                    </div>
                </div>

            </div>
        </div>

        {{-- INVENTARIO --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-clipboard-data me-2 text-primary"></i>
                    Información del inventario
                </h5>
            </div>

            <div class="card-body p-4">
                <div class="row g-3">

                    <div class="col-12 col-md-6">
                        <div class="info-box">
                            <span class="info-label">Unidades disponibles</span>
                            <h4 class="fw-bold mb-0">
                                {{ $product->stock }}
                            </h4>
                        </div>
                    </div>

                    <div class="col-12 col-md-6">
                        <div class="info-box">
                            <span class="info-label">Valor de venta del stock actual</span>
                            <h4 class="fw-bold mb-0">
                                ${{ number_format($product->stock * $product->price, 2, ',', '.') }}
                            </h4>
                            <small class="text-muted">
                                Stock actual × precio de venta
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
