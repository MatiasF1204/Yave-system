@extends('layouts.admin')

@section('content')
    <div class="container-fluid payment-methods-page p-0">

        {{-- Encabezado --}}
        <div class="payment-heading mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="small mb-2 heading-label">
                        <i class="bi bi-credit-card-2-front-fill me-1"></i>
                        GESTIÓN DE COBROS
                    </div>

                    <h1 class="h3 fw-bold mb-2">Métodos de pago</h1>

                    <p class="mb-0">
                        Administrá las formas de pago disponibles para registrar las ventas.
                    </p>
                </div>

                <a href="{{ route('admin.payment_methods.create') }}" class="btn-register">
                    <i class="bi bi-plus-circle-fill"></i>
                    Registrar método de pago
                </a>
            </div>
        </div>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show payment-alert" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i>
                {{ session('success') }}

                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- Tarjeta principal --}}
        <div class="payment-card">

            {{-- Cabecera del listado --}}
            <div class="section-heading">
                <div class="section-title">
                    <span class="section-icon">
                        <i class="bi bi-wallet2"></i>
                    </span>

                    <div>
                        <h5 class="mb-1">Listado de métodos de pago</h5>
                        <small>Consultá, editá o desactivá las formas de pago.</small>
                    </div>
                </div>

                <span class="payment-count">
                    <i class="bi bi-credit-card me-1"></i>
                    {{ method_exists($paymentMethods, 'total') ? $paymentMethods->total() : $paymentMethods->count() }}
                    método(s)
                </span>
            </div>

            {{-- Buscador --}}
            <div class="search-section">
                <form action="{{ route('admin.payment_methods.index') }}" method="GET" class="payment-search">

                    <div class="search-input-wrapper">
                        <i class="bi bi-search search-icon"></i>

                        <input type="text" name="search" class="search-input"
                            placeholder="Buscar método de pago por nombre..." value="{{ request('search') }}"
                            aria-label="Buscar métodos de pago">

                        @if (request('search'))
                            <a href="{{ route('admin.payment_methods.index') }}" class="clear-search"
                                title="Limpiar búsqueda" aria-label="Limpiar búsqueda">
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
                <table class="table payment-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>Método de pago</th>
                            <th class="text-center actions-column">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($paymentMethods as $pm)
                            <tr id="payment-method-{{ $pm->id }}">

                                {{-- Nombre --}}
                                <td>
                                    <div class="payment-method-info">
                                        <span class="payment-method-icon">
                                            <i class="bi bi-credit-card-2-front"></i>
                                        </span>

                                        <div class="payment-method-details">
                                            <span class="payment-method-name">
                                                {{ $pm->name }}
                                            </span>

                                            <small class="payment-reference">
                                                Código: #{{ $pm->id }}
                                            </small>
                                        </div>
                                    </div>
                                </td>

                                {{-- Acciones --}}
                                <td>
                                    <div class="payment-actions">
                                        <a href="{{ route('admin.payment_methods.edit', $pm->id) }}"
                                            class="btn-action btn-edit" title="Editar método de pago">
                                            <i class="bi bi-pencil-square"></i>
                                            <span>Editar</span>
                                        </a>

                                        <button type="button" onclick="deletePM({{ $pm->id }})"
                                            class="btn-action btn-deactivate" title="Desactivar método de pago">
                                            <i class="bi bi-trash3"></i>
                                            <span>Eliminar</span>
                                        </button>
                                    </div>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="2">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-credit-card-2-front"></i>
                                        </div>

                                        @if (request('search'))
                                            <h5>No se encontraron métodos de pago</h5>
                                            <p>
                                                No hay métodos de pago activos que coincidan con
                                                <strong>{{ request('search') }}</strong>.
                                            </p>

                                            <a href="{{ route('admin.payment_methods.index') }}" class="btn-empty">
                                                <i class="bi bi-arrow-counterclockwise me-1"></i>
                                                Ver todos los métodos
                                            </a>
                                        @else
                                            <h5>No hay métodos de pago registrados</h5>
                                            <p>
                                                Todavía no hay métodos de pago disponibles para mostrar.
                                            </p>

                                            <a href="{{ route('admin.payment_methods.create') }}" class="btn-empty">
                                                <i class="bi bi-plus-circle me-1"></i>
                                                Registrar método de pago
                                            </a>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pie y paginación --}}
            @if ($paymentMethods->count() > 0)
                <div class="payment-footer">
                    <div class="results-info">
                        @if (method_exists($paymentMethods, 'firstItem') && $paymentMethods->firstItem())
                            Mostrando
                            <strong>{{ $paymentMethods->firstItem() }}</strong>
                            a
                            <strong>{{ $paymentMethods->lastItem() }}</strong>
                            de
                            <strong>{{ $paymentMethods->total() }}</strong>
                            métodos de pago
                        @else
                            <strong>{{ $paymentMethods->count() }}</strong>
                            método(s) de pago
                        @endif
                    </div>

                    @if (method_exists($paymentMethods, 'hasPages') && $paymentMethods->hasPages())
                        <div class="payment-pagination">
                            {{ $paymentMethods->withQueryString()->links() }}
                        </div>
                    @endif
                </div>
            @endif

        </div>
    </div>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // Desactivar método de pago
        function deletePM(id) {
            Swal.fire({
                title: '¿Desactivar método de pago?',
                text: 'Dejará de aparecer entre los métodos disponibles, pero sus datos e historial se conservarán.',
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
                        `/admin/payment-methods/${id}/deactivate`, {
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
                            'No se pudo desactivar el método de pago.'
                        );
                    }

                    await Swal.fire({
                        title: '¡Método desactivado!',
                        text: data.message ||
                            'El método de pago fue desactivado correctamente y su historial se conservó.',
                        icon: 'success',
                        confirmButtonColor: '#142b4a',
                        confirmButtonText: 'Aceptar'
                    });

                    window.location.reload();

                } catch (error) {
                    Swal.fire({
                        title: 'No se pudo completar la operación',
                        text: error.message ||
                            'Ocurrió un problema de conexión. Intentá nuevamente.',
                        icon: 'error',
                        confirmButtonColor: '#142b4a',
                        confirmButtonText: 'Aceptar'
                    });
                }
            });
        }
    </script>
@endsection
