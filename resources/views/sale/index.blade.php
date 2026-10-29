@extends('layouts.admin')

@section('content')
    <div class="container-fluid sales-page p-0">

        {{-- Encabezado --}}
        <div class="page-heading mb-4">
            <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                <div>
                    <div class="small mb-2">
                        <i class="fas fa-chart-line me-1"></i>
                        GESTIÓN COMERCIAL
                    </div>

                    <h1 class="h3 fw-bold mb-2">Ventas</h1>

                    <p class="mb-0">
                        Consultá, filtrá y revisá las ventas registradas en el sistema.
                    </p>
                </div>

                <a href="{{ route('sales.create') }}" class="btn btn-new-sale">
                    <i class="fas fa-plus me-2"></i>
                    Nueva venta
                </a>
            </div>
        </div>

        {{-- Mensaje de éxito --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- Errores de filtros --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Revisá los datos ingresados.</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Panel de filtros --}}
        <div class="sales-card mb-4">

            <div class="card-body p-4">
                <form action="{{ route('sales.index') }}" method="GET">
                    <div class="row g-3">

                        {{-- Número de venta --}}
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label for="sale_id" class="form-label">
                                <i class="fas fa-hashtag me-1 text-primary"></i>
                                Número de venta
                            </label>

                            <input type="number" min="1" name="sale_id" id="sale_id" class="form-control"
                                placeholder="Ej.: 125" value="{{ request('sale_id') }}">
                        </div>

                        {{-- DNI del cliente --}}
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label for="dni" class="form-label">
                                <i class="fas fa-id-card me-1 text-primary"></i>
                                DNI del cliente
                            </label>

                            <input type="text" name="dni" id="dni" class="form-control"
                                placeholder="Ingresá el DNI" value="{{ request('dni') }}">
                        </div>

                        {{-- Vendedor --}}
                        <div class="col-12 col-sm-6 col-lg-4">
                            <label for="user_id" class="form-label">
                                <i class="fas fa-user-tie me-1 text-primary"></i>
                                Vendedor
                            </label>

                            <select name="user_id" id="user_id" class="form-select">
                                <option value="">Todos los vendedores</option>

                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" @selected((string) request('user_id') === (string) $user->id)>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Fecha desde --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="date_from" class="form-label">
                                <i class="fas fa-calendar-alt me-1 text-primary"></i>
                                Fecha desde
                            </label>

                            <input type="date" name="date_from" id="date_from" class="form-control"
                                value="{{ request('date_from') }}">
                        </div>

                        {{-- Fecha hasta --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="date_to" class="form-label">
                                <i class="fas fa-calendar-check me-1 text-primary"></i>
                                Fecha hasta
                            </label>

                            <input type="date" name="date_to" id="date_to" class="form-control"
                                value="{{ request('date_to') }}">
                        </div>

                        {{-- Estado --}}
                        <div class="col-12 col-sm-6 col-lg-3">
                            <label for="status" class="form-label">
                                <i class="fas fa-toggle-on me-1 text-primary"></i>
                                Estado de la venta
                            </label>

                            <select name="status" id="status" class="form-select">
                                <option value="">Todos los estados</option>
                                <option value="active" @selected(request('status') === 'active')>
                                    Activa
                                </option>
                                <option value="cancelled" @selected(request('status') === 'cancelled')>
                                    Anulada
                                </option>
                            </select>
                        </div>

                        {{-- Botones --}}
                        <div class="col-12 col-sm-6 col-lg-3 d-flex align-items-end gap-2">
                            <button type="submit" class="btn btn-search flex-grow-1">
                                <i class="fas fa-search me-1"></i>
                                Buscar
                            </button>

                            <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary btn-clear"
                                title="Limpiar filtros y mostrar las ventas de hoy">
                                <i class="fas fa-undo me-1"></i>
                                Limpiar
                            </a>
                        </div>
                    </div>

                    <div class="mt-3 small text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Si no seleccionás fechas, se mostrarán únicamente las ventas de hoy.
                        Para consultar ventas anteriores, indicá una fecha o un rango.
                    </div>
                </form>
            </div>
        </div>

        {{-- Resultados --}}
        <div class="sales-card">
            <div class="section-heading d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center">
                    <span class="section-icon">
                        <i class="fas fa-receipt"></i>
                    </span>

                    <div>
                        <h5 class="mb-1 fw-bold">Listado de ventas</h5>
                        <small class="text-muted">
                            @if (!request()->filled('date_from') && !request()->filled('date_to'))
                                Ventas del día de hoy
                            @else
                                Ventas según el período seleccionado
                            @endif
                        </small>
                    </div>
                </div>

                <span class="badge rounded-pill text-bg-primary px-3 py-2">
                    {{ $sales->total() }} resultado(s)
                </span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>N.º de venta</th>
                            <th>Fecha y hora</th>
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
                                    <span class="sale-number">
                                        #{{ $sale->sale_id }}
                                    </span>
                                </td>

                                <td class="text-nowrap">
                                    <div class="fw-semibold">
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('H:i') }} hs
                                    </small>
                                </td>

                                <td>
                                    <div class="fw-semibold">
                                        {{ $sale->client->full_name ?? 'Sin cliente' }}
                                    </div>

                                    @if ($sale->client && $sale->client->dni)
                                        <small class="text-muted">
                                            DNI: {{ $sale->client->dni }}
                                        </small>
                                    @endif
                                </td>

                                <td>
                                    <i class="fas fa-user-circle text-muted me-1"></i>
                                    {{ $sale->user->name ?? 'Sin vendedor' }}
                                </td>

                                <td class="text-end">
                                    <span class="sale-total">
                                        ${{ number_format((float) $sale->total, 2, ',', '.') }}
                                    </span>
                                </td>

                                <td class="text-center">
                                    @if ($sale->status === 'active')
                                        <span class="status-badge status-active">
                                            <i class="fas fa-check-circle me-1"></i>
                                            Activa
                                        </span>
                                    @elseif ($sale->status === 'cancelled')
                                        <span class="status-badge status-cancelled">
                                            <i class="fas fa-times-circle me-1"></i>
                                            Anulada
                                        </span>
                                    @else
                                        <span class="status-badge status-other">
                                            {{ ucfirst($sale->status ?? 'Sin estado') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-detail"
                                        title="Ver detalle de la venta">
                                        <i class="fas fa-eye me-1"></i>
                                        Ver detalle
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="fas fa-search"></i>
                                        </div>

                                        <h5 class="fw-bold mb-2">
                                            No se encontraron ventas
                                        </h5>

                                        <p class="text-muted mb-3">
                                            No hay ventas que coincidan con los filtros seleccionados.
                                            Probá modificando los criterios de búsqueda.
                                        </p>

                                        <a href="{{ route('sales.index') }}" class="btn btn-outline-primary">
                                            <i class="fas fa-undo me-1"></i>
                                            Ver ventas de hoy
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if ($sales->hasPages())
                <div class="results-footer">
                    <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                        <small class="text-muted">
                            Mostrando
                            <strong>{{ $sales->firstItem() }}</strong>
                            a
                            <strong>{{ $sales->lastItem() }}</strong>
                            de
                            <strong>{{ $sales->total() }}</strong>
                            ventas
                        </small>

                        <div>
                            {{ $sales->links() }}
                        </div>
                    </div>
                </div>
            @elseif ($sales->total() > 0)
                <div class="results-footer">
                    <small class="text-muted">
                        Se encontraron {{ $sales->total() }} venta(s).
                    </small>
                </div>
            @endif
        </div>

    </div>
@endsection
