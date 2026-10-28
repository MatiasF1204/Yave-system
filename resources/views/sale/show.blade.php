@extends('layouts.admin')

@section('content')
    <div class="container-fluid p-0">

        {{-- Mensaje de confirmación --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4" role="alert">
                <i class="fas fa-check-circle fs-5"></i>
                <div>
                    <strong>¡Venta registrada correctamente!</strong>
                    <div class="small">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        {{-- Encabezado --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Detalle de venta</h1>
                <p class="text-muted mb-0">
                    Comprobante de la venta #{{ $sale->sale_id }}
                </p>
            </div>
            <span class="badge bg-success fs-6">
                {{ ucfirst($sale->status) }}
            </span>
        </div>

        {{-- Datos generales --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Información de la venta</h5>
            </div>

            <div class="card-body">
                <div class="row g-3">

                    <div class="col-md-4">
                        <strong>Número de venta:</strong>
                        <p class="mb-0">#{{ $sale->sale_id }}</p>
                    </div>

                    <div class="col-md-4">
                        <strong>Fecha:</strong>
                        <p class="mb-0">
                            {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}
                        </p>
                    </div>

                    <div class="col-md-4">
                        <strong>Vendedor:</strong>
                        <p class="mb-0">
                            {{ $sale->user->name ?? 'No disponible' }}
                        </p>
                    </div>

                </div>
            </div>
        </div>

        {{-- Datos del cliente --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Datos del cliente</h5>
            </div>

            <div class="card-body">
                @if ($sale->client)
                    <div class="row g-3">

                        <div class="col-md-4">
                            <strong>Nombre completo:</strong>
                            <p class="mb-0">{{ $sale->client->full_name }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>DNI:</strong>
                            <p class="mb-0">{{ $sale->client->dni }}</p>
                        </div>

                        <div class="col-md-4">
                            <strong>Teléfono:</strong>
                            <p class="mb-0">
                                {{ $sale->client->phone ?: 'No registrado' }}
                            </p>
                        </div>

                    </div>
                @else
                    <p class="text-muted mb-0">
                        No hay un cliente asociado a esta venta.
                    </p>
                @endif
            </div>
        </div>

        {{-- Productos vendidos --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Productos vendidos</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Producto</th>
                                <th class="text-center">Cantidad</th>
                                <th class="text-center">Precio unitario</th>
                                <th class="text-center">Subtotal</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($sale->details as $detail)
                                <tr>
                                    <td>
                                        {{ $detail->product->name ?? 'Producto no disponible' }}
                                    </td>

                                    <td class="text-center">
                                        {{ $detail->quantity }}
                                    </td>

                                    <td class="text-center">
                                        ${{ number_format((float) $detail->unit_price, 2, ',', '.') }}
                                    </td>

                                    <td class="text-center">
                                        ${{ number_format((float) $detail->subtotal, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        No hay productos registrados en esta venta.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Observaciones --}}
        @if ($sale->observations)
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">Observaciones</h5>
                </div>

                <div class="card-body">
                    {{ $sale->observations }}
                </div>
            </div>
        @endif

        {{-- Medios de pago --}}
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Medios de pago</h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Medio de pago</th>
                                <th class="text-center">Importe</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($sale->payments as $payment)
                                <tr>
                                    <td>
                                        {{ $payment->paymentMethod->name ?? 'Medio no disponible' }}
                                    </td>

                                    <td class="text-center">
                                        ${{ number_format((float) $payment->amount, 2, ',', '.') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="text-center text-muted py-3">
                                        No hay pagos registrados.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Total de la venta --}}
        <div class="row justify-content-end mb-4">
            <div class="col-md-5 col-lg-4">
                <div class="card shadow-sm border-primary">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Total de la venta</h5>

                            <h4 class="text-primary mb-0">
                                ${{ number_format((float) $sale->total, 2, ',', '.') }}
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Botón aceptar --}}
        <div class="d-flex justify-content-end mb-4">
            <a href="{{ route('sales.index') }}" class="btn btn-primary px-4">
                <i class="fas fa-check me-1"></i>
                Aceptar
            </a>
        </div>

    </div>
@endsection
