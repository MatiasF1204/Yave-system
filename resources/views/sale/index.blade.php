@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        {{-- Encabezado --}}
        <div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
            <div>
                <h1 class="h3 mb-1">Ventas</h1>
                <p class="text-muted mb-0">
                    Listado de ventas registradas en el sistema.
                </p>
            </div>

            <a href="{{ route('sales.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-1"></i>
                Nueva venta
            </a>
        </div>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- Buscador --}}
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('sales.index') }}" method="GET">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-9">
                            <label for="search" class="form-label">
                                Buscar venta
                            </label>

                            <input type="text" name="search" id="search" class="form-control"
                                placeholder="Número de venta o nombre del cliente..." value="{{ request('search') }}">
                        </div>

                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary flex-grow-1">
                                <i class="fas fa-search me-1"></i>
                                Buscar
                            </button>

                            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">
                                Limpiar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tabla de ventas --}}
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Ventas registradas</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>N.º</th>
                                <th>Fecha</th>
                                <th>Cliente</th>
                                <th>Vendedor</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($sales as $sale)
                                <tr>
                                    <td>
                                        <strong>#{{ $sale->sale_id }}</strong>
                                    </td>

                                    <td>
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}
                                    </td>

                                    <td>
                                        {{ $sale->client->full_name ?? 'Sin cliente' }}
                                    </td>

                                    <td>
                                        {{ $sale->user->name ?? 'Sin vendedor' }}
                                    </td>

                                    <td class="text-end text-nowrap">
                                        ${{ number_format((float) $sale->total, 2, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        @if ($sale->status === 'active')
                                            <span class="badge bg-success">
                                                Activa
                                            </span>
                                        @elseif ($sale->status === 'cancelled')
                                            <span class="badge bg-danger">
                                                Cancelada
                                            </span>
                                        @else
                                            <span class="badge bg-secondary">
                                                {{ ucfirst($sale->status ?? 'Sin estado') }}
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-outline-primary"
                                            title="Ver detalle">
                                            <i class="fas fa-eye me-1"></i>
                                            Ver
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-5">
                                        <i class="fas fa-receipt fa-2x text-muted mb-3"></i>

                                        <p class="mb-1 fw-semibold">
                                            No se encontraron ventas.
                                        </p>

                                        <p class="text-muted mb-0">
                                            Podés registrar una nueva venta con el botón superior.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Paginación --}}
            @if ($sales->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
                        <small class="text-muted">
                            Mostrando {{ $sales->firstItem() ?? 0 }}
                            a {{ $sales->lastItem() ?? 0 }}
                            de {{ $sales->total() }} ventas
                        </small>

                        {{ $sales->withQueryString()->links() }}
                    </div>
                </div>
            @endif
        </div>

    </div>
@endsection
