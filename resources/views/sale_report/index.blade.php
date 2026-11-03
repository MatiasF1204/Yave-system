@extends('layouts.admin')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/sales-report.css') }}">

    <div class="sales-report">

        {{-- ENCABEZADO --}}
        <header class="report-heading">
            <div>
                <span class="report-eyebrow">
                    <i class="fas fa-chart-line me-2"></i>
                    INFORMES Y ESTADÍSTICAS
                </span>

                <h1>Reporte de ventas</h1>
                <p>Analizá los resultados comerciales de Productos Yavé.</p>
            </div>

            <div class="report-heading-icon">
                <i class="fas fa-file-invoice-dollar"></i>
            </div>
        </header>

        {{-- FILTROS --}}
        <section class="report-panel report-filters">
            <div class="report-section-heading">
                <div class="report-section-icon">
                    <i class="fas fa-calendar-alt"></i>
                </div>

                <div>
                    <h2>Período del informe</h2>
                    <p>Los indicadores, pagos, clientes y ventas se actualizan con estas fechas.</p>
                </div>
            </div>

            <form method="GET" action="{{ route('admin.reports.sales') }}" id="salesReportFilters">
                <div class="report-filter-grid">
                    <div class="report-field">
                        <label for="from">Desde</label>
                        <input type="date" id="from" name="from" value="{{ $from }}" required>
                    </div>

                    <div class="report-field">
                        <label for="to">Hasta</label>
                        <input type="date" id="to" name="to" value="{{ $to }}"
                            min="{{ $from }}" required>
                    </div>

                    <div class="report-filter-actions">
                        <button type="submit" class="report-btn-primary">
                            <i class="fas fa-filter me-2"></i>
                            Aplicar filtros
                        </button>

                        <a href="{{ route('admin.reports.sales') }}" class="report-btn-secondary">
                            <i class="fas fa-calendar me-1"></i>
                            Mes actual
                        </a>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="report-validation">
                        {{ $errors->first() }}
                    </div>
                @endif
            </form>
        </section>

        {{-- INDICADORES --}}
        <div class="report-kpi-grid">
            <article class="report-kpi kpi-revenue">
                <div class="kpi-top">
                    <span>Importe vendido</span>
                    <span class="kpi-icon"><i class="fas fa-dollar-sign"></i></span>
                </div>
                <div class="kpi-value">
                    ${{ number_format($totalSales, 2, ',', '.') }}
                </div>
                <div class="kpi-caption">Ventas activas del período</div>
            </article>

            <article class="report-kpi kpi-count">
                <div class="kpi-top">
                    <span>Ventas realizadas</span>
                    <span class="kpi-icon"><i class="fas fa-receipt"></i></span>
                </div>
                <div class="kpi-value">
                    {{ number_format($salesCount, 0, ',', '.') }}
                </div>
                <div class="kpi-caption">Operaciones no anuladas</div>
            </article>

            <article class="report-kpi kpi-profit">
                <div class="kpi-top">
                    <span>Ganancia estimada</span>
                    <span class="kpi-icon"><i class="fas fa-chart-line"></i></span>
                </div>
                <div class="kpi-value">
                    ${{ number_format($estimatedProfit, 2, ',', '.') }}
                </div>
                <div class="kpi-caption">Calculada con costos actuales</div>
            </article>

            <article class="report-kpi kpi-ticket">
                <div class="kpi-top">
                    <span>Ticket promedio</span>
                    <span class="kpi-icon"><i class="fas fa-shopping-basket"></i></span>
                </div>
                <div class="kpi-value">
                    ${{ number_format($averageTicket, 2, ',', '.') }}
                </div>
                <div class="kpi-caption">Importe vendido dividido por ventas</div>
            </article>
        </div>

        {{-- EVOLUCIÓN DE VENTAS --}}
        <section class="report-panel">
            <div class="report-section-heading">
                <div class="report-section-icon">
                    <i class="fas fa-chart-bar"></i>
                </div>

                <div>
                    <h2>Evolución de ventas y ganancias</h2>
                    <p>Comparación diaria del importe vendido y la ganancia obtenida durante el mes.</p>
                </div>
            </div>

            @php
                $maxAmount = max(1, (float) $salesChart->max('amount'));
                $maxProfit = max(1, (float) $salesChart->max('profit'));
            @endphp

            {{-- Gráfico comparativo --}}
            <div class="sales-comparison-wrapper">

                <div class="sales-comparison-chart">
                    @foreach ($salesChart as $index => $day)
                        @php
                            $salesHeight = $day['amount'] > 0 ? max(3, ($day['amount'] / $maxAmount) * 100) : 0;

                            $profitHeight = $day['profit'] > 0 ? max(3, ($day['profit'] / $maxProfit) * 100) : 0;
                        @endphp

                        <div class="sales-comparison-column" tabindex="0" role="button"
                            aria-label="Consultar información del día {{ $day['date'] }}" data-date="{{ $day['date'] }}"
                            data-sales="{{ number_format($day['amount'], 2, ',', '.') }}"
                            data-profit="{{ number_format($day['profit'], 2, ',', '.') }}"
                            data-quantity="{{ $day['quantity'] }}">

                            <div class="sales-comparison-bars">
                                <div class="sales-comparison-bar bar-sales {{ $day['amount'] <= 0 ? 'bar-empty' : '' }}"
                                    style="height: {{ $salesHeight }}%; animation-delay: {{ min($index * 0.025, 0.8) }}s;">
                                </div>

                                <div class="sales-comparison-bar bar-profit {{ $day['profit'] <= 0 ? 'bar-empty' : '' }}"
                                    style="height: {{ $profitHeight }}%; animation-delay: {{ min($index * 0.025, 0.8) }}s;">
                                </div>
                            </div>

                            <span class="sales-comparison-label">
                                {{ $day['label'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            {{-- Información dinámica: fuera del área del gráfico para evitar recortes --}}
            <div class="sales-comparison-detail" id="salesComparisonDetail" aria-live="polite">
                <div class="comparison-detail-heading">
                    <i class="fas fa-calendar-day"></i>
                    <strong id="comparisonDate">Seleccioná un día del gráfico</strong>
                </div>

                <div class="comparison-detail-values">
                    <div class="comparison-detail-item">
                        <span class="comparison-detail-dot dot-sales"></span>
                        <div>
                            <small>Total vendido</small>
                            <strong id="comparisonSales">$0,00</strong>
                        </div>
                    </div>

                    <div class="comparison-detail-item">
                        <span class="comparison-detail-dot dot-profit"></span>
                        <div>
                            <small>Ganancia del día</small>
                            <strong id="comparisonProfit">$0,00</strong>
                        </div>
                    </div>

                    <div class="comparison-detail-item">
                        <span class="comparison-detail-dot dot-quantity"></span>
                        <div>
                            <small>Operaciones</small>
                            <strong id="comparisonQuantity">0 ventas</strong>
                        </div>
                    </div>
                </div>
            </div>

            <p class="report-note">
                <i class="fas fa-info-circle me-1"></i>
                Las ganancias se calculan utilizando los costos históricos registrados
                en cada detalle de venta. Los días sin operaciones muestran valores en cero.
            </p>
        </section>

        {{-- MEDIOS DE PAGO --}}
        <section class="report-panel">
            <div class="report-section-heading">
                <div class="report-section-icon payment-icon">
                    <i class="fas fa-wallet"></i>
                </div>
                <div>
                    <h2>Medios de pago</h2>
                    <p>Importe recibido según los pagos asociados a las ventas activas.</p>
                </div>
            </div>

            <div class="payment-summary">
                <div class="payment-total-card">
                    <span>Total de pagos registrados</span>
                    <strong>${{ number_format($totalPayments, 2, ',', '.') }}</strong>
                    <small>En el período seleccionado</small>
                </div>

                <div class="payment-method-list">
                    @forelse ($paymentSummary as $method)
                        <div class="payment-method-row">
                            <div class="payment-method-info">
                                <div class="payment-method-name">
                                    <span class="payment-method-dot"></span>
                                    <strong>{{ $method['name'] }}</strong>
                                </div>

                                <div class="payment-method-meta">
                                    {{ $method['payments_count'] }}
                                    {{ $method['payments_count'] === 1 ? 'pago' : 'pagos' }}
                                    ·
                                    {{ $method['sales_count'] }}
                                    {{ $method['sales_count'] === 1 ? 'venta' : 'ventas' }}
                                </div>
                            </div>

                            <div class="payment-method-amount">
                                <strong>${{ number_format($method['total'], 2, ',', '.') }}</strong>
                                <span>{{ number_format($method['percentage'], 1, ',', '.') }}%</span>
                            </div>

                            <div class="payment-progress">
                                <div class="payment-progress-fill"
                                    style="width: {{ min(100, max(0, $method['percentage'])) }}%;"></div>
                            </div>
                        </div>
                    @empty
                        <div class="report-empty">
                            <i class="fas fa-wallet"></i>
                            <strong>No hay pagos registrados en este período.</strong>
                            <span>Probá con otras fechas para consultar los pagos.</span>
                        </div>
                    @endforelse
                </div>
            </div>

            <p class="report-note">
                <i class="fas fa-info-circle me-1"></i>
                El total de pagos puede diferir del importe vendido si existen pagos
                incompletos, pagos adicionales o ventas sin pagos asociados.
            </p>
        </section>

        {{-- TOP 3 CLIENTES --}}
        <section class="report-panel">
            <div class="report-section-heading">
                <div class="report-section-icon client-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div>
                    <h2>Top 3 de clientes</h2>
                    <p>Clientes con mayor importe acumulado en ventas activas.</p>
                </div>
            </div>

            <div class="top-clients-list">
                @forelse ($topClients as $index => $client)
                    <article class="top-client-item">
                        <div class="top-client-rank">
                            {{ $index + 1 }}
                        </div>

                        <div class="top-client-avatar">
                            <i class="fas fa-user"></i>
                        </div>

                        <div class="top-client-info">
                            <strong>{{ $client->customer_name }}</strong>
                            <span>
                                {{ $client->purchases_count }}
                                {{ (int) $client->purchases_count === 1 ? 'compra' : 'compras' }}
                            </span>
                        </div>

                        <div class="top-client-total">
                            <strong>
                                ${{ number_format((float) $client->total_spent, 2, ',', '.') }}
                            </strong>
                            <span>Total comprado</span>
                        </div>
                    </article>
                @empty
                    <div class="report-empty">
                        <i class="fas fa-user-friends"></i>
                        <strong>No hay clientes con compras en este período.</strong>
                    </div>
                @endforelse
            </div>

            <p class="report-note">
                <i class="fas fa-info-circle me-1"></i>
                Las ventas sin cliente asociado se agrupan en «Cliente no registrado».
                Para identificar individualmente a compradores no registrados,
                el sistema debe guardar sus datos en cada venta.
            </p>
        </section>

        {{-- HISTORIAL DE VENTAS --}}
        <section class="report-panel report-table-panel">
            <div class="report-section-heading report-table-heading">
                <div class="report-section-heading-left">
                    <div class="report-section-icon">
                        <i class="fas fa-list-alt"></i>
                    </div>
                    <div>
                        <h2>Detalle de ventas</h2>
                        <p>Historial completo del período, incluidas las anuladas.</p>
                    </div>
                </div>

                <span class="report-record-count">
                    {{ $sales->total() }}
                    {{ $sales->total() === 1 ? 'registro' : 'registros' }}
                </span>
            </div>

            <div class="table-responsive">
                <table class="table report-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>N.º</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Total</th>
                            <th>Ganancia</th>
                            <th>Estado</th>
                            <th>Detalle</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($sales as $sale)
                            @php
                                $saleProfit = $sale->details->sum(function ($detail) {
                                    return (float) ($detail->unit_profit ?? 0) * (int) $detail->quantity;
                                });
                            @endphp
                            <tr>
                                <td>
                                    <span class="report-sale-number">
                                        #{{ $sale->sale_id }}
                                    </span>
                                </td>

                                <td class="text-nowrap">
                                    <div class="report-date-main">
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}
                                    </div>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($sale->sale_date)->format('H:i') }} hs
                                    </small>
                                </td>

                                <td>{{ $sale->client->full_name ?? 'Consumidor Final' }}</td>


                                <td class="report-sale-total">
                                    ${{ number_format((float) $sale->total, 2, ',', '.') }}
                                </td>

                                <td class="report-sale-profit">
                                    @if ($sale->status === 'active')
                                        ${{ number_format($saleProfit, 2, ',', '.') }}
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if ($sale->status === 'active')
                                        <span class="report-status status-active">
                                            <i class="fas fa-check-circle me-1"></i> Activa
                                        </span>
                                    @elseif ($sale->status === 'cancelled')
                                        <span class="report-status status-cancelled">
                                            <i class="fas fa-ban me-1"></i> Anulada
                                        </span>
                                    @else
                                        <span class="report-status status-other">
                                            {{ ucfirst($sale->status ?? 'Sin estado') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('sales.show', $sale) }}" class="report-view-btn"
                                        title="Consultar venta" aria-label="Consultar venta {{ $sale->sale_id }}">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="report-empty">
                                        <i class="fas fa-folder-open"></i>
                                        <strong>No hay ventas en este período.</strong>
                                        <span>Probá con otras fechas para consultar operaciones.</span>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación de todo el historial --}}
            @if ($sales->hasPages())
                <div class="report-pagination">
                    {{ $sales->links() }}
                </div>
            @endif
        </section>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('salesReportFilters');
            const fromInput = document.getElementById('from');
            const toInput = document.getElementById('to');

            if (fromInput && toInput) {
                fromInput.addEventListener('change', function() {
                    toInput.min = fromInput.value;

                    if (toInput.value && toInput.value < fromInput.value) {
                        toInput.value = fromInput.value;
                    }
                });
            }

            if (form) {
                form.addEventListener('submit', function(event) {
                    if (
                        fromInput.value &&
                        toInput.value &&
                        toInput.value < fromInput.value
                    ) {
                        event.preventDefault();
                        toInput.setCustomValidity(
                            'La fecha final debe ser igual o posterior a la fecha inicial.'
                        );
                        toInput.reportValidity();
                    } else {
                        toInput.setCustomValidity('');
                    }
                });
            }
        });

        // Interactividad para la tabla de reportes de venta de cada día
        document.addEventListener('DOMContentLoaded', function() {
            const columns = document.querySelectorAll('.sales-comparison-column');

            const dateElement = document.getElementById('comparisonDate');
            const salesElement = document.getElementById('comparisonSales');
            const profitElement = document.getElementById('comparisonProfit');
            const quantityElement = document.getElementById('comparisonQuantity');

            function showDayDetails(column) {
                const date = column.dataset.date;
                const sales = column.dataset.sales;
                const profit = column.dataset.profit;
                const quantity = parseInt(column.dataset.quantity, 10) || 0;

                dateElement.textContent = date;
                salesElement.textContent = '$' + sales;
                profitElement.textContent = '$' + profit;
                quantityElement.textContent =
                    quantity + (quantity === 1 ? ' venta' : ' ventas');
            }

            columns.forEach(function(column) {
                column.addEventListener('mouseenter', function() {
                    showDayDetails(this);
                });

                column.addEventListener('focus', function() {
                    showDayDetails(this);
                });

                column.addEventListener('click', function() {
                    showDayDetails(this);
                });
            });
        });
    </script>
@endsection
