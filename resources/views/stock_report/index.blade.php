@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/stock-report.css') }}">

    <div class="stock-report">

        {{-- Encabezado --}}
        <div class="stock-report__header">
            <div>
                <span class="stock-report__eyebrow">
                    <i class="bi bi-clipboard-data"></i>
                    ADMINISTRACIÓN DE INVENTARIO
                </span>

                <h1>Reporte de stock</h1>

                <p>
                    Consultá las existencias, detectá productos con stock bajo
                    y organizá la reposición de mercadería.
                </p>
            </div>

            <div class="stock-report__header-icon">
                <i class="bi bi-box-seam"></i>
            </div>
        </div>

        {{-- Indicadores --}}
        <div class="stock-report__stats">

            <div class="stock-stat stock-stat--blue">
                <div class="stock-stat__top">
                    <span>Productos activos</span>
                    <span class="stock-stat__icon">
                        <i class="bi bi-boxes"></i>
                    </span>
                </div>

                <div class="stock-stat__number" data-count="{{ $totalProducts }}">
                    0
                </div>

                <div class="stock-stat__footer">
                    <i class="bi bi-check-circle"></i>
                    Productos habilitados
                </div>
            </div>

            <div class="stock-stat stock-stat--purple">
                <div class="stock-stat__top">
                    <span>Unidades disponibles</span>
                    <span class="stock-stat__icon">
                        <i class="bi bi-stack"></i>
                    </span>
                </div>

                <div class="stock-stat__number" data-count="{{ $totalStock }}">
                    0
                </div>

                <div class="stock-stat__footer">
                    <i class="bi bi-box"></i>
                    Total de unidades registradas
                </div>
            </div>

            <div class="stock-stat stock-stat--orange">
                <div class="stock-stat__top">
                    <span>Stock bajo</span>
                    <span class="stock-stat__icon">
                        <i class="bi bi-exclamation-triangle"></i>
                    </span>
                </div>

                <div class="stock-stat__number" data-count="{{ $lowStock }}">
                    0
                </div>

                <div class="stock-stat__footer">
                    <i class="bi bi-arrow-down-circle"></i>
                    Entre 1 y 5 unidades
                </div>
            </div>

            <div class="stock-stat stock-stat--red">
                <div class="stock-stat__top">
                    <span>Sin stock</span>
                    <span class="stock-stat__icon">
                        <i class="bi bi-x-circle"></i>
                    </span>
                </div>

                <div class="stock-stat__number" data-count="{{ $outOfStock }}">
                    0
                </div>

                <div class="stock-stat__footer">
                    <i class="bi bi-arrow-repeat"></i>
                    Requieren reposición
                </div>
            </div>

        </div>

        {{-- Filtros --}}
        <section class="stock-panel">
            <div class="stock-panel__heading">
                <div class="stock-panel__title-icon">
                    <i class="bi bi-funnel"></i>
                </div>

                <div>
                    <h2>Buscar productos</h2>
                    <p>Combiná los filtros para encontrar lo que necesitás.</p>
                </div>
            </div>

            <form action="{{ route('admin.reports.stock') }}" method="GET" class="stock-filters">

                <div class="stock-filter stock-filter--search">
                    <label for="stock-search">Nombre del producto</label>

                    <div class="stock-input-icon">
                        <i class="bi bi-search"></i>

                        <input type="text" id="stock-search" name="search" value="{{ $search }}"
                            placeholder="Ej.: detergente, lavandina..." maxlength="150">
                    </div>
                </div>

                <div class="stock-filter">
                    <label for="stock-category">Categoría</label>

                    <select name="category" id="stock-category">
                        <option value="">Todas las categorías</option>

                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected((string) $categoryId === (string) $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="stock-filter">
                    <label for="stock-status">Disponibilidad</label>

                    <select name="stock_status" id="stock-status">
                        <option value="all" @selected($stockStatus === 'all')>
                            Todo el stock
                        </option>

                        <option value="available" @selected($stockStatus === 'available')>
                            Stock suficiente
                        </option>

                        <option value="low" @selected($stockStatus === 'low')>
                            Stock bajo
                        </option>

                        <option value="out" @selected($stockStatus === 'out')>
                            Sin stock
                        </option>
                    </select>
                </div>

                <div class="stock-filter">
                    <label for="product-status">Estado del producto</label>

                    <select name="status" id="product-status">
                        <option value="active" @selected($productStatus === 'active')>
                            Activos
                        </option>

                        <option value="inactive" @selected($productStatus === 'inactive')>
                            Inactivos
                        </option>

                        <option value="all" @selected($productStatus === 'all')>
                            Todos los estados
                        </option>
                    </select>
                </div>

                <div class="stock-filter__actions">
                    <button type="submit" class="stock-btn stock-btn--primary">
                        <i class="bi bi-search"></i>
                        Buscar
                    </button>

                    <a href="{{ route('admin.reports.stock') }}" class="stock-btn stock-btn--secondary">
                        <i class="bi bi-arrow-counterclockwise"></i>
                        Limpiar
                    </a>
                </div>
            </form>
        </section>

        {{-- Listado de productos --}}
        <section class="stock-panel stock-products-panel">

            <div class="stock-panel__heading stock-panel__heading--between">
                <div class="stock-panel__heading-main">
                    <div class="stock-panel__title-icon">
                        <i class="bi bi-list-ul"></i>
                    </div>

                    <div>
                        <h2>Detalle del inventario</h2>
                        <p>
                            @if ($products->total() > 0)
                                Mostrando {{ $products->firstItem() }}
                                a {{ $products->lastItem() }}
                                de {{ $products->total() }} productos.
                            @else
                                No hay productos que coincidan con los filtros.
                            @endif
                        </p>
                    </div>
                </div>

                <span class="stock-results-badge">
                    <i class="bi bi-box-seam"></i>
                    {{ $products->total() }} resultados
                </span>
            </div>

            <div class="stock-table-wrapper">
                <table class="stock-table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Categorías</th>
                            <th>Estado</th>
                            <th>Existencias</th>
                            <th>Nivel de stock</th>
                            <th>Disponibilidad</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($products as $product)
                            @php
                                $stock = (int) $product->stock;

                                $stockLabel = $stock <= 0 ? 'Sin stock' : ($stock <= 5 ? 'Stock bajo' : 'Disponible');

                                $stockClass = $stock <= 0 ? 'out' : ($stock <= 5 ? 'low' : 'available');

                                $barWidth = min(($stock / $maxStock) * 100, 100);
                            @endphp

                            <tr>
                                <td>
                                    <div class="stock-product-cell">
                                        <div class="stock-product-image">
                                            @if ($product->image)
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    alt="{{ $product->name }}" loading="lazy">
                                            @else
                                                <i class="bi bi-box-seam"></i>
                                            @endif
                                        </div>

                                        <div class="stock-product-info">
                                            <a href="{{ route('products.show', $product->id) }}"
                                                class="stock-product-name">
                                                {{ $product->name }}
                                            </a>

                                            <span class="stock-product-id">
                                                Código #{{ $product->id }}
                                            </span>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <div class="stock-categories">
                                        @forelse ($product->categories as $category)
                                            <span class="stock-category-chip">
                                                {{ $category->name }}
                                            </span>
                                        @empty
                                            <span class="stock-muted">Sin categoría</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td>
                                    @if ($product->status === 'active')
                                        <span class="stock-status-badge stock-status-badge--active">
                                            <span></span>
                                            Activo
                                        </span>
                                    @else
                                        <span class="stock-status-badge stock-status-badge--inactive">
                                            <span></span>
                                            Inactivo
                                        </span>
                                    @endif
                                </td>

                                <td>
                                    <span class="stock-quantity stock-quantity--{{ $stockClass }}">
                                        {{ number_format($stock, 0, ',', '.') }}
                                        <small>unid.</small>
                                    </span>
                                </td>

                                <td>
                                    <div class="stock-level">
                                        <div class="stock-level__track">
                                            <div class="stock-level__bar stock-level__bar--{{ $stockClass }}"
                                                style="--bar-width: {{ $barWidth }}%">
                                            </div>
                                        </div>

                                        <span>
                                            @if ($stock <= 0)
                                                0%
                                            @elseif ($stock <= 5)
                                                Bajo
                                            @else
                                                Suficiente
                                            @endif
                                        </span>
                                    </div>
                                </td>

                                <td>
                                    <span class="stock-availability stock-availability--{{ $stockClass }}">
                                        <i
                                            class="bi
                                        @if ($stock <= 0) bi-x-circle-fill
                                        @elseif ($stock <= 5)
                                            bi-exclamation-circle-fill
                                        @else
                                            bi-check-circle-fill @endif">
                                        </i>

                                        {{ $stockLabel }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="stock-empty">
                                        <div class="stock-empty__icon">
                                            <i class="bi bi-search"></i>
                                        </div>

                                        <h3>No se encontraron productos</h3>

                                        <p>
                                            Probá con otro nombre o modificá los filtros.
                                        </p>

                                        <a href="{{ route('admin.reports.stock') }}"
                                            class="stock-btn stock-btn--secondary">
                                            Limpiar filtros
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if ($products->hasPages() || $products->total() > 0)
                <div class="results-footer justify-content-between align-items-center gap-3 mt-3 ">
                    {{ $products->withQueryString()->links() }}
                </div>
            @endif

        </section>

        <div class="stock-report__footnote">
            <i class="bi bi-info-circle"></i>
            El stock bajo se considera entre 1 y 5 unidades.
            Los indicadores superiores contabilizan solamente productos activos.
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Animación de conteo de los indicadores.
            const counters = document.querySelectorAll(
                '.stock-report .stock-stat__number[data-count]'
            );

            counters.forEach(function(counter) {
                const target = Number(counter.dataset.count) || 0;
                const duration = 850;
                const startTime = performance.now();

                function animateCounter(now) {
                    const progress = Math.min((now - startTime) / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    const value = Math.floor(target * eased);

                    counter.textContent = value.toLocaleString('es-AR');

                    if (progress < 1) {
                        requestAnimationFrame(animateCounter);
                    } else {
                        counter.textContent = target.toLocaleString('es-AR');
                    }
                }

                requestAnimationFrame(animateCounter);
            });
        });
    </script>
@endsection
