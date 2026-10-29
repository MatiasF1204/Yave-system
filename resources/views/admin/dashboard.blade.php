@extends('layouts.admin')

@section('content')
    <div class="admin-dashboard">

        {{-- Encabezado --}}
        <div class="dashboard-heading dashboard-animate">
            <div>
                <span class="dashboard-eyebrow">
                    <i class="fas fa-chart-line me-2"></i>
                    PANEL DE CONTROL
                </span>

                <h1>¡Bienvenido a Productos Yavé!</h1>

                <p>Resumen general del negocio y actividad reciente.</p>
            </div>

            <div class="dashboard-date">
                <i class="far fa-calendar-alt me-2"></i>
                {{ now()->format('d/m/Y') }}
            </div>
        </div>

        {{-- Indicadores principales --}}
        <div class="dashboard-stats">

            <div class="stat-card stat-sales">
                <div class="stat-top">
                    <span>Ventas de hoy</span>
                    <span class="stat-icon">
                        <i class="fas fa-dollar-sign"></i>
                    </span>
                </div>

                <div class="stat-value" data-counter="{{ (float) $totalSalesToday }}" data-decimals="2" data-prefix="$">
                    ${{ number_format($totalSalesToday, 2, ',', '.') }}</div>

                <div class="stat-description">
                    <i class="fas fa-shopping-cart me-1"></i>
                    <span data-counter="{{ (int) $salesToday }}" data-decimals="0">{{ $salesToday }}</span>
                    {{ $salesToday === 1 ? 'venta realizada' : 'ventas realizadas' }}
                </div>
            </div>

            <div class="stat-card stat-profit">
                <div class="stat-top">
                    <span>Ganancia estimada</span>
                    <span class="stat-icon">
                        <i class="fas fa-chart-line"></i>
                    </span>
                </div>

                <div class="stat-value" data-counter="{{ (float) $estimatedProfitToday }}" data-decimals="2"
                    data-prefix="$">${{ number_format($estimatedProfitToday, 2, ',', '.') }}</div>

                <div class="stat-description">
                    Estimación correspondiente a hoy
                </div>
            </div>

            <div class="stat-card stat-products">
                <div class="stat-top">
                    <span>Productos activos</span>
                    <span class="stat-icon">
                        <i class="fas fa-boxes"></i>
                    </span>
                </div>

                <div class="stat-value" data-counter="{{ (int) $totalProducts }}" data-decimals="0">{{ $totalProducts }}
                </div>

                <div class="stat-description">
                    Productos disponibles en el catálogo
                </div>
            </div>

            <div class="stat-card stat-clients">
                <div class="stat-top">
                    <span>Clientes activos</span>
                    <span class="stat-icon">
                        <i class="fas fa-users"></i>
                    </span>
                </div>

                <div class="stat-value" data-counter="{{ (int) $totalClients }}" data-decimals="0">{{ $totalClients }}
                </div>

                <div class="stat-description">
                    Clientes registrados y activos
                </div>
            </div>

        </div>

        {{-- Gráfico y alertas --}}
        <div class="dashboard-main-grid">

            {{-- Gráfico de ventas --}}
            <section class="dashboard-panel dashboard-animate">
                <div class="panel-heading">
                    <div class="panel-title-icon">
                        <i class="fas fa-chart-bar"></i>
                    </div>

                    <div>
                        <h2>Ventas de los últimos 7 días</h2>
                        <p>Importe de ventas activas por día</p>
                    </div>
                </div>

                @php
                    $maxSales = max(1, (float) $salesChart->max('total'));
                @endphp

                <div class="sales-chart">
                    @foreach ($salesChart as $index => $day)
                        @php
                            $height = $day['total'] > 0 ? max(5, ($day['total'] / $maxSales) * 100) : 0;
                        @endphp

                        <div class="chart-column">
                            <div class="chart-bar-area">
                                @if ($day['total'] > 0)
                                    <div class="chart-tooltip">
                                        ${{ number_format($day['total'], 2, ',', '.') }}
                                    </div>
                                @endif

                                <div class="chart-bar {{ $day['total'] > 0 ? '' : 'chart-bar-empty' }}"
                                    style="height: {{ $height }}%; animation-delay: {{ $index * 0.08 }}s;"
                                    title="{{ $day['count'] }} venta(s)"></div>
                            </div>

                            <span class="chart-label">{{ $day['label'] }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="chart-legend">
                    <span class="legend-dot"></span>
                    Importe total vendido
                </div>
            </section>

            {{-- Alertas de inventario --}}
            <section class="dashboard-panel dashboard-animate">
                <div class="panel-heading">
                    <div class="panel-title-icon warning-icon">
                        <i class="fas fa-box-open"></i>
                    </div>

                    <div>
                        <h2>Alertas de stock</h2>
                        <p>Productos que necesitan atención</p>
                    </div>
                </div>

                <div class="stock-summary">
                    <div class="stock-summary-item">
                        <span class="stock-summary-number text-danger" data-counter="{{ (int) $outOfStockCount }}"
                            data-decimals="0">{{ $outOfStockCount }}</span>
                        <span>Agotados</span>
                    </div>

                    <div class="stock-summary-item">
                        <span class="stock-summary-number text-warning" data-counter="{{ (int) $lowStockCount }}"
                            data-decimals="0">{{ $lowStockCount }}</span>
                        <span>Stock bajo</span>
                    </div>
                </div>

                <div class="stock-list">
                    @forelse ($lowStockProducts as $product)
                        <div class="stock-item">
                            <div class="stock-product-icon">
                                <i class="fas fa-box"></i>
                            </div>

                            <div class="stock-product-info">
                                <span class="stock-product-name">
                                    {{ $product->name }}
                                </span>

                                <small>
                                    @if ($product->stock <= 0)
                                        <span class="text-danger">Sin existencias</span>
                                    @else
                                        Stock disponible: {{ $product->stock }}
                                    @endif
                                </small>
                            </div>

                            @if ($product->stock <= 0)
                                <span class="stock-status stock-empty">Agotado</span>
                            @else
                                <span class="stock-status stock-low">Bajo</span>
                            @endif
                        </div>
                    @empty
                        <div class="dashboard-empty">
                            <i class="fas fa-check-circle"></i>
                            <p>Todos los productos tienen stock suficiente.</p>
                        </div>
                    @endforelse
                </div>

                <a href="{{ route('products.index') }}" class="panel-link">
                    Ver productos
                    <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </section>

        </div>

        {{-- Últimas ventas --}}
        <section class="dashboard-panel recent-sales-panel dashboard-animate">
            <div class="panel-heading panel-heading-between">
                <div class="d-flex align-items-center gap-3">
                    <div class="panel-title-icon">
                        <i class="fas fa-receipt"></i>
                    </div>

                    <div>
                        <h2>Últimas ventas</h2>
                        <p>Operaciones recientes, incluidas las anuladas</p>
                    </div>
                </div>

                <a href="{{ route('sales.index') }}" class="panel-link">
                    Ver todas
                    <i class="fas fa-arrow-right ms-1"></i>
                </a>
            </div>

            <div class="table-responsive">
                <table class="table dashboard-table align-middle mb-0">
                    <thead>
                        <tr>
                            <th>N.º</th>
                            <th>Fecha</th>
                            <th>Cliente</th>
                            <th>Vendedor</th>
                            <th class="text-end">Total</th>
                            <th class="text-center">Estado</th>
                            <th class="text-center">Detalle</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($recentSales as $sale)
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

                                <td>{{ $sale->client->full_name ?? 'Sin cliente' }}</td>

                                <td>{{ $sale->user->name ?? 'Sin vendedor' }}</td>

                                <td class="text-end fw-bold">
                                    ${{ number_format((float) $sale->total, 2, ',', '.') }}
                                </td>

                                <td class="text-center">
                                    @if ($sale->status === 'active')
                                        <span class="dashboard-status status-active">Activa</span>
                                    @elseif ($sale->status === 'cancelled')
                                        <span class="dashboard-status status-cancelled">Anulada</span>
                                    @else
                                        <span class="dashboard-status status-other">
                                            {{ ucfirst($sale->status ?? 'Sin estado') }}
                                        </span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    <a href="{{ route('sales.show', $sale) }}" class="btn-view-sale"
                                        title="Ver detalle">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">
                                    <div class="dashboard-empty">
                                        <i class="fas fa-receipt"></i>
                                        <p>Todavía no hay ventas registradas.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

    </div>

    {{-- Estilos de las animaciones --}}
    <style>
        .admin-dashboard .stat-card {
            opacity: 0;
            animation: dashboardCardEnter 0.55s ease forwards;
            transition:
                transform 0.25s ease,
                box-shadow 0.25s ease,
                border-color 0.25s ease;
            will-change: transform;
        }

        .admin-dashboard .stat-card:nth-child(1) {
            animation-delay: 0.05s;
        }

        .admin-dashboard .stat-card:nth-child(2) {
            animation-delay: 0.15s;
        }

        .admin-dashboard .stat-card:nth-child(3) {
            animation-delay: 0.25s;
        }

        .admin-dashboard .stat-card:nth-child(4) {
            animation-delay: 0.35s;
        }

        .admin-dashboard .stat-card:hover {
            transform: translateY(-5px) scale(1.025);
            box-shadow: 0 12px 28px rgba(20, 43, 74, 0.16);
            border-color: #b8cce3;
            z-index: 2;
        }

        .admin-dashboard .stat-value,
        .admin-dashboard .stock-summary-number {
            font-variant-numeric: tabular-nums;
        }

        .admin-dashboard .dashboard-animate {
            opacity: 0;
            animation: dashboardFadeUp 0.6s ease forwards;
        }

        .admin-dashboard .dashboard-main-grid>section:nth-child(1) {
            animation-delay: 0.2s;
        }

        .admin-dashboard .dashboard-main-grid>section:nth-child(2) {
            animation-delay: 0.3s;
        }

        .admin-dashboard .recent-sales-panel {
            animation-delay: 0.4s;
        }

        .admin-dashboard .chart-bar {
            transform: scaleY(0);
            transform-origin: bottom;
            animation: dashboardBarGrow 0.85s cubic-bezier(.22, 1, .36, 1) forwards;
        }

        .admin-dashboard .chart-bar-empty {
            animation: none;
            transform: scaleY(1);
        }

        .admin-dashboard .stock-item {
            transition: background-color 0.2s ease, transform 0.2s ease;
        }

        .admin-dashboard .stock-item:hover {
            background-color: #f3f7fc;
            transform: translateX(3px);
        }

        .admin-dashboard .btn-view-sale {
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .admin-dashboard .btn-view-sale:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 10px rgba(20, 43, 74, 0.15);
        }

        @keyframes dashboardCardEnter {
            from {
                opacity: 0;
                transform: translateY(18px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes dashboardFadeUp {
            from {
                opacity: 0;
                transform: translateY(16px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes dashboardBarGrow {
            to {
                transform: scaleY(1);
            }
        }

        @media (prefers-reduced-motion: reduce) {

            .admin-dashboard *,
            .admin-dashboard *::before,
            .admin-dashboard *::after {
                animation-duration: 0.01ms !important;
                animation-delay: 0ms !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>

    {{-- Contadores animados --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dashboard = document.querySelector('.admin-dashboard');

            if (!dashboard) {
                return;
            }

            const counters = dashboard.querySelectorAll('[data-counter]');
            const reducedMotion = window.matchMedia(
                '(prefers-reduced-motion: reduce)'
            ).matches;

            counters.forEach(function(counter, index) {
                const target = Number(counter.dataset.counter);
                const decimals = Number(counter.dataset.decimals || 0);
                const prefix = counter.dataset.prefix || '';
                const suffix = counter.dataset.suffix || '';

                if (!Number.isFinite(target)) {
                    return;
                }

                const formatter = new Intl.NumberFormat('es-AR', {
                    minimumFractionDigits: decimals,
                    maximumFractionDigits: decimals
                });

                function render(value) {
                    counter.textContent = prefix + formatter.format(value) + suffix;
                }

                // Si el usuario prefiere movimiento reducido, mostrar el valor final.
                if (reducedMotion) {
                    render(target);
                    return;
                }

                render(0);

                const duration = 1400;
                const delay = index * 70;

                function startAnimation() {
                    const startTime = performance.now();

                    function updateCounter(now) {
                        const progress = Math.min((now - startTime) / duration, 1);

                        // Comienza rápido y desacelera suavemente al finalizar.
                        const easedProgress = 1 - Math.pow(1 - progress, 4);
                        const currentValue = target * easedProgress;

                        render(progress === 1 ? target : currentValue);

                        if (progress < 1) {
                            requestAnimationFrame(updateCounter);
                        }
                    }

                    requestAnimationFrame(updateCounter);
                }

                window.setTimeout(startAnimation, delay);
            });
        });
    </script>
@endsection
