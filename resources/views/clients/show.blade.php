@extends('layouts.admin')

@section('content')
    <div class="container-fluid clients-page p-0">
        {{-- Información personal --}}
        <div class="clients-card mb-4">
            <div class="section-heading">
                <div class="d-flex align-items-center">
                    <span class="section-icon">
                        <i class="bi bi-person-vcard"></i>
                    </span>

                    <div>
                        <h5 class="mb-1 fw-bold">Información del cliente</h5>
                        <small class="text-muted">
                            Datos personales registrados en el sistema.
                        </small>
                    </div>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <span class="client-avatar" style="width: 65px; height: 65px; font-size: 1.5rem;">
                        {{ mb_strtoupper(mb_substr($client->full_name, 0, 1)) }}
                    </span>

                    <div>
                        <h4 class="fw-bold mb-1">{{ $client->full_name }}</h4>
                        <span class="text-muted small">
                            Cliente registrado
                        </span>
                    </div>
                </div>

                <div class="row g-4">
                    <div class="col-12 col-md-4">
                        <div class="text-muted small fw-semibold mb-1">
                            <i class="bi bi-person-badge me-1"></i>
                            DNI
                        </div>
                        <div class="fw-semibold">
                            {{ $client->dni }}
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="text-muted small fw-semibold mb-1">
                            <i class="bi bi-telephone me-1"></i>
                            Teléfono
                        </div>
                        <div class="fw-semibold">
                            {{ $client->phone ?: 'No registrado' }}
                        </div>
                    </div>

                    <div class="col-12 col-md-4">
                        <div class="text-muted small fw-semibold mb-1">
                            <i class="bi bi-calendar-check me-1"></i>
                            Fecha de registro
                        </div>
                        <div class="fw-semibold">
                            {{ $client->created_at ? $client->created_at->format('d/m/Y') : 'No disponible' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Resumen de compras --}}
        <div class="row g-3 mb-4">

            {{-- Cantidad de ventas --}}
            <div class="col-12 col-md-4">
                <div class="clients-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small fw-semibold mb-2">
                                    VENTAS REALIZADAS
                                </div>

                                <h3 class="fw-bold mb-0">
                                    {{ $totalSales }}
                                </h3>
                                <small class="text-muted">Ventas activas</small>
                            </div>

                            <span class="section-icon">
                                <i class="bi bi-receipt"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Total comprado --}}
            <div class="col-12 col-md-4">
                <div class="clients-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small fw-semibold mb-2">
                                    TOTAL COMPRADO
                                </div>

                                <h3 class="fw-bold mb-0">
                                    ${{ number_format((float) $totalSpent, 2, ',', '.') }}
                                </h3>
                                <small class="text-muted">En ventas activas</small>
                            </div>

                            <span class="section-icon">
                                <i class="bi bi-cash-stack"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Última compra --}}
            <div class="col-12 col-md-4">
                <div class="clients-card h-100">
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="text-muted small fw-semibold mb-2">
                                    ÚLTIMA COMPRA
                                </div>

                                <h3 class="fw-bold mb-0">
                                    {{ $lastSale ? \Carbon\Carbon::parse($lastSale)->format('d/m/Y') : 'Sin compras' }}
                                </h3>
                                <small class="text-muted">Última venta activa</small>
                            </div>

                            <span class="section-icon">
                                <i class="bi bi-calendar-event"></i>
                            </span>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- Historial de ventas --}}
        <div class="clients-card">

            <div class="section-heading d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center">
                    <span class="section-icon">
                        <i class="bi bi-clock-history"></i>
                    </span>

                    <div>
                        <h5 class="mb-1 fw-bold">Historial de compras</h5>
                        <small class="text-muted">
                            Todas las ventas asociadas a este cliente.
                        </small>
                    </div>
                </div>

                <span class="badge rounded-pill px-3 py-2" style="background:#142b4a;color:#fff">
                    {{ $sales->total() }} venta(s)
                </span>
            </div>

            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>N.º de venta</th>
                            <th>Fecha y hora</th>
                            <th>Vendedor</th>
                            <th>Total</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($sales as $sale)
                            <tr>
                                <td>
                                    <span class="fw-bold">
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
                                    {{ $sale->user->name ?? 'Sin vendedor' }}
                                </td>

                                <td class="text-nowrap fw-semibold">
                                    ${{ number_format((float) $sale->total, 2, ',', '.') }}
                                </td>

                                <td class="text-center">
                                    @if ($sale->status === 'active')
                                        <span class="status-badge status-active">
                                            <i class="bi bi-check-circle me-1"></i>
                                            Activa
                                        </span>
                                    @elseif ($sale->status === 'cancelled')
                                        <span class="status-badge status-cancelled">
                                            <i class="bi bi-x-circle me-1"></i>
                                            Anulada
                                        </span>
                                    @else
                                        <span class="status-badge status-other">
                                            {{ ucfirst($sale->status ?? 'Sin estado') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('sales.show', $sale) }}" class="btn btn-sm btn-detail">
                                        <i class="bi bi-eye me-1"></i>
                                        Ver venta
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-icon">
                                            <i class="bi bi-receipt"></i>
                                        </div>

                                        <h5 class="fw-bold mb-2">
                                            Sin compras registradas
                                        </h5>

                                        <p class="text-muted mb-0">
                                            Este cliente todavía no tiene ventas asociadas.
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if ($sales->hasPages())
                <div class="results-footer d-flex justify-content-between align-items-center gap-3">
                    {{ $sales->links() }}
                </div>
            @endif

        </div>

        <div class="mt-4" style="display: flex; justify-content: flex-end;">
            <div class="d-flex flex-wrap gap-2">

                <a href="{{ route('clients.index') }}" class="btn btn-outline-secondary" style="align-content: center">
                    <i class="bi bi-arrow-left me-1"></i>
                    Volver
                </a>

                @if (auth()->user()->role->name === 'Administrador')
                    <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-register"
                        style="align-content: center>
                <i class="bi bi-pencil-square me-1"></i>
                        Editar cliente
                    </a>
                @endif

            </div>
        </div>

    </div>
@endsection
