@extends(auth()->user()->role?->name === 'Administrador' ? 'layouts.admin' : 'layouts.seller')

@section('content')
@php
    $totalCollected = $paymentMethods->sum('total');
@endphp

<div class="daily-sheet">

    {{-- ENCABEZADO --}}
    <div class="sheet-header">
        <div>
            <h2>Control diario de caja</h2>
            <p>Resumen de ventas, ganancias y movimientos del día</p>
        </div>

        <div class="sheet-date">
            <span>FECHA DEL INFORME</span>
            <strong>{{ \Carbon\Carbon::parse($date)->translatedFormat('d/m/Y') }}</strong>
        </div>
    </div>

    {{-- FILTROS --}}
    <form action="{{ route('daily-control.index') }}" method="GET" class="sheet-filters">
        <div class="filter-field">
            <label for="date">Fecha</label>
            <input type="date" id="date" name="date" value="{{ $date }}">
        </div>

        <div class="filter-field">
            <label for="user_id">Vendedor</label>
            <select name="user_id" id="user_id">
                <option value="">Todos los vendedores</option>
                @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                        {{ request('user_id') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="filter-actions">
            <button type="submit" class="btn-sheet-primary">
                <i class="bi bi-search"></i> Consultar
            </button>

            <a href="{{ route('daily-control.index') }}" class="btn-sheet-light">
                <i class="bi bi-calendar-day"></i> Hoy
            </a>
        </div>
    </form>

    {{-- RESUMEN GENERAL --}}
    <div class="sheet-section">
        <div class="section-heading">
            <span>01</span>
            <h5>Resumen general</h5>
        </div>

        <div class="table-responsive">
            <table class="sheet-table">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><i class="bi bi-receipt"></i> Ventas realizadas</td>
                        <td class="text-end">{{ $salesCount }}</td>
                        <td class="text-end fw-semibold">$ {{ number_format($totalSales, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-graph-up-arrow"></i> Ganancia obtenida</td>
                        <td class="text-end">—</td>
                        <td class="text-end text-success fw-semibold">$ {{ number_format($totalProfit, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-people"></i> Clientes registrados atendidos</td>
                        <td class="text-end">{{ $clientsCount }}</td>
                        <td class="text-end">—</td>
                    </tr>
                    <tr>
                        <td><i class="bi bi-person"></i> Consumidores finales</td>
                        <td class="text-end">{{ $consumerFinalCount }}</td>
                        <td class="text-end">—</td>
                    </tr>
                    <tr class="sheet-total">
                        <td colspan="2">TOTAL VENDIDO</td>
                        <td class="text-end">$ {{ number_format($totalSales, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- MEDIOS DE PAGO --}}
    <div class="sheet-section">
        <div class="section-heading">
            <span>02</span>
            <h5>Recaudación por medio de pago</h5>
        </div>

        <div class="table-responsive">
            <table class="sheet-table">
                <thead>
                    <tr>
                        <th>Medio de pago</th>
                        <th class="text-end">Importe recaudado</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($paymentMethods as $method)
                        <tr>
                            <td>{{ $method['name'] }}</td>
                            <td class="text-end">$ {{ number_format($method['total'], 2, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="2" class="sheet-empty">No hay movimientos registrados.</td>
                        </tr>
                    @endforelse

                    <tr class="sheet-total">
                        <td>TOTAL RECAUDADO</td>
                        <td class="text-end">$ {{ number_format($totalCollected, 2, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- VENTAS ANULADAS --}}
    <div class="sheet-section">
        <div class="section-heading">
            <span>03</span>
            <h5>Ventas anuladas</h5>
        </div>

        <div class="table-responsive">
            <table class="sheet-table">
                <thead>
                    <tr>
                        <th>Concepto</th>
                        <th class="text-end">Cantidad</th>
                        <th class="text-end">Importe</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Ventas anuladas durante el día</td>
                        <td class="text-end">{{ $cancelledCount }}</td>
                        <td class="text-end text-danger fw-semibold">
                            $ {{ number_format($cancelledAmount, 2, ',', '.') }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- ACTIVIDAD POR VENDEDOR --}}
    <div class="sheet-section">
        <div class="section-heading">
            <span>04</span>
            <h5>Actividad por vendedor</h5>
        </div>

        <div class="table-responsive">
            <table class="sheet-table">
                <thead>
                    <tr>
                        <th>Vendedor</th>
                        <th class="text-end">Ventas realizadas</th>
                        <th class="text-end">Total vendido</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($salesByUser as $item)
                        <tr>
                            <td>{{ $item->user->name ?? 'Usuario eliminado' }}</td>
                            <td class="text-end">{{ $item->sales_count }}</td>
                            <td class="text-end fw-semibold">
                                $ {{ number_format($item->total, 2, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="sheet-empty">
                                No hay ventas registradas para esta fecha.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PIE --}}
    <div class="sheet-footer">
        <span>Control diario de caja</span>
        <span>Informe generado el {{ now()->format('d/m/Y H:i') }}</span>
    </div>

</div>
@endsection
