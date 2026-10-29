 @extends('layouts.admin')

 @section('content')
     <div class="container-fluid sale-detail">

         {{-- Mensaje de confirmación --}}
         @if (session('success'))
             <div class="alert alert-success alert-dismissible fade show d-flex align-items-center gap-2 mb-4 shadow-sm"
                 role="alert">
                 <i class="fas fa-check-circle fs-5"></i>
                 <div>
                     <strong>¡Venta registrada correctamente!</strong>
                     <div class="small">{{ session('success') }}</div>
                 </div>
                 <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Cerrar"></button>
             </div>
         @endif

         {{-- Encabezado --}}
         <div class="sale-heading mb-4">
             <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
                 <div>
                     <div class="small mb-2">
                         <i class="fas fa-file-invoice me-1"></i>
                         GESTIÓN COMERCIAL
                     </div>
                     <h1 class="h3 fw-bold mb-2">Detalle de venta</h1>
                     <p class="mb-0">Comprobante y detalle de los movimientos de la venta.</p>
                 </div>

                 <div class="text-md-end">
                     <span class="sale-number">
                         Venta #{{ $sale->sale_id }}
                     </span>
                     <div class="mt-2">
                         @if ($sale->status === 'active')
                             <span class="badge status-active px-3 py-2">Activa</span>
                         @elseif ($sale->status === 'cancelled')
                             <span class="badge status-cancelled px-3 py-2">Cancelada</span>
                         @else
                             <span class="badge status-other px-3 py-2">
                                 {{ ucfirst($sale->status ?? 'Sin estado') }}
                             </span>
                         @endif
                     </div>
                 </div>
             </div>
         </div>

         {{-- Datos generales --}}
         <div class="card sale-card mb-4">
             <div class="card-header">
                 <h5 class="mb-0 fw-bold">
                     <span class="section-icon"><i class="fas fa-info-circle"></i></span>
                     Información de la venta
                 </h5>
             </div>

             <div class="card-body p-4">
                 <div class="row g-4">
                     <div class="col-md-4">
                         <span class="field-label">Número de venta</span>
                         <p class="field-value">#{{ $sale->sale_id }}</p>
                     </div>

                     <div class="col-md-4">
                         <span class="field-label">Fecha y hora</span>
                         <p class="field-value">
                             {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y H:i') }}
                         </p>
                     </div>

                     <div class="col-md-4">
                         <span class="field-label">Vendedor</span>
                         <p class="field-value">{{ $sale->user->name ?? 'No disponible' }}</p>
                     </div>
                 </div>
             </div>
         </div>

         {{-- Datos del cliente --}}
         <div class="card sale-card mb-4">
             <div class="card-header">
                 <h5 class="mb-0 fw-bold">
                     <span class="section-icon"><i class="fas fa-user"></i></span>
                     Datos del cliente
                 </h5>
             </div>

             <div class="card-body p-4">
                 @if ($sale->client)
                     <div class="row g-4">
                         <div class="col-md-4">
                             <span class="field-label">Nombre completo</span>
                             <p class="field-value">{{ $sale->client->full_name }}</p>
                         </div>

                         <div class="col-md-4">
                             <span class="field-label">DNI</span>
                             <p class="field-value">{{ $sale->client->dni }}</p>
                         </div>

                         <div class="col-md-4">
                             <span class="field-label">Teléfono</span>
                             <p class="field-value">
                                 {{ $sale->client->phone ?: 'No registrado' }}
                             </p>
                         </div>
                     </div>
                 @else
                     <p class="text-muted mb-0">No hay un cliente asociado a esta venta.</p>
                 @endif
             </div>
         </div>

         {{-- Productos vendidos --}}
         <div class="card sale-card mb-4">
             <div class="card-header">
                 <h5 class="mb-0 fw-bold">
                     <span class="section-icon"><i class="fas fa-shopping-basket"></i></span>
                     Productos vendidos
                 </h5>
             </div>

             <div class="card-body p-0">
                 <div class="table-responsive">
                     <table class="table table-hover align-middle mb-0">
                         <thead>
                             <tr>
                                 <th>Producto</th>
                                 <th class="text-center">Cantidad</th>
                                 <th class="text-end">Precio unitario</th>
                                 <th class="text-end">Subtotal</th>
                             </tr>
                         </thead>

                         <tbody>
                             @forelse ($sale->details as $detail)
                                 <tr>
                                     <td class="fw-semibold">
                                         {{ $detail->product->name ?? 'Producto no disponible' }}
                                     </td>
                                     <td class="text-center">{{ $detail->quantity }}</td>
                                     <td class="text-end text-nowrap">
                                         ${{ number_format((float) $detail->unit_price, 2, ',', '.') }}
                                     </td>
                                     <td class="text-end text-nowrap fw-semibold">
                                         ${{ number_format((float) $detail->subtotal, 2, ',', '.') }}
                                     </td>
                                 </tr>
                             @empty
                                 <tr>
                                     <td colspan="4" class="text-center text-muted py-4">
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
             <div class="card sale-card mb-4">
                 <div class="card-header">
                     <h5 class="mb-0 fw-bold">
                         <span class="section-icon"><i class="fas fa-comment-alt"></i></span>
                         Observaciones
                     </h5>
                 </div>
                 <div class="card-body p-4">
                     {{ $sale->observations }}
                 </div>
             </div>
         @endif

         {{-- Medios de pago --}}
         <div class="card sale-card mb-4">
             <div class="card-header">
                 <h5 class="mb-0 fw-bold">
                     <span class="section-icon"><i class="fas fa-credit-card"></i></span>
                     Medios de pago
                 </h5>
             </div>

             <div class="card-body p-0">
                 <div class="table-responsive">
                     <table class="table table-hover align-middle mb-0">
                         <thead>
                             <tr>
                                 <th>Medio de pago</th>
                                 <th class="text-end">Importe</th>
                             </tr>
                         </thead>

                         <tbody>
                             @forelse ($sale->payments as $payment)
                                 <tr>
                                     <td>{{ $payment->paymentMethod->name ?? 'Medio no disponible' }}</td>
                                     <td class="text-end text-nowrap fw-semibold">
                                         ${{ number_format((float) $payment->amount, 2, ',', '.') }}
                                     </td>
                                 </tr>
                             @empty
                                 <tr>
                                     <td colspan="2" class="text-center text-muted py-4">
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
             <div class="col-md-6 col-lg-5">
                 <div class="card total-card">
                     <div class="card-body p-4">
                         <div class="d-flex justify-content-between align-items-center gap-3">
                             <div>
                                 <div class="total-label mb-1">IMPORTE TOTAL</div>
                                 <div class="small" style="color:#d8e5f4">
                                     Venta #{{ $sale->sale_id }}
                                 </div>
                             </div>
                             <div class="total-amount">
                                 ${{ number_format((float) $sale->total, 2, ',', '.') }}
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>

         {{-- Botón aceptar --}}
         <div class="d-flex justify-content-end mb-4">
             <a href="{{ route('sales.index') }}" class="btn btn-accept">
                 <i class="fas fa-check me-1"></i>
                 Aceptar
             </a>
         </div>

     </div>
 @endsection
