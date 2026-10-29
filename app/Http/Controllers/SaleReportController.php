<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleReportController extends Controller
{
    public function index(Request $request)
    {
        $defaultFrom = now()->startOfMonth()->toDateString();
        $defaultTo = now()->toDateString();

        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ], [
            'from.date' => 'La fecha inicial no es válida.',
            'to.date' => 'La fecha final no es válida.',
            'to.after_or_equal' =>
            'La fecha final debe ser igual o posterior a la inicial.',
        ]);

        $from = $validated['from'] ?? $defaultFrom;
        $to = $validated['to'] ?? $defaultTo;

        $startDate = Carbon::parse($from)->startOfDay();
        $endDate = Carbon::parse($to)->endOfDay();

        // Todas las ventas del período, incluidas las anuladas.
        $salesQuery = Sale::query()
            ->whereBetween('sale_date', [$startDate, $endDate]);

        // Solo ventas activas para los cálculos comerciales.
        $activeSalesQuery = (clone $salesQuery)
            ->where('status', 'active');

        $salesCount = (clone $activeSalesQuery)->count();

        $totalSales = (float) (clone $activeSalesQuery)->sum('total');

        $averageTicket = $salesCount > 0
            ? $totalSales / $salesCount
            : 0;

        $cancelledSales = (clone $salesQuery)
            ->where('status', 'cancelled')
            ->count();

        // Ganancia estimada con el costo actual del producto.
        $estimatedProfit = (float) DB::table('sale_details as sd')
            ->join('sales as s', 's.sale_id', '=', 'sd.sale_id')
            ->join('products as p', 'p.id', '=', 'sd.product_id')
            ->where('s.status', 'active')
            ->whereBetween('s.sale_date', [$startDate, $endDate])
            ->selectRaw(
                'COALESCE(
                    SUM(
                        (sd.unit_price - COALESCE(p.total_cost, 0))
                        * sd.quantity
                    ),
                    0
                ) as profit'
            )
            ->value('profit');

        /*
         * MEDIOS DE PAGO
         *
         * Se utiliza la relación existente:
         * Sale -> payments -> paymentMethod.
         *
         * Se cuentan los pagos registrados y las ventas distintas
         * que utilizaron cada medio de pago.
         */
        $activeSalesWithPayments = (clone $activeSalesQuery)
            ->with('payments.paymentMethod')
            ->get();

        $paymentGroups = [];

        foreach ($activeSalesWithPayments as $sale) {
            foreach ($sale->payments as $payment) {
                $methodName = $payment->paymentMethod->name
                    ?? 'Método no disponible';

                if (!isset($paymentGroups[$methodName])) {
                    $paymentGroups[$methodName] = [
                        'name' => $methodName,
                        'total' => 0,
                        'payments_count' => 0,
                        'sales_ids' => [],
                    ];
                }

                $paymentGroups[$methodName]['total'] +=
                    (float) $payment->amount;

                $paymentGroups[$methodName]['payments_count']++;

                $paymentGroups[$methodName]['sales_ids'][$sale->sale_id] = true;
            }
        }

        $paymentSummary = collect(array_values($paymentGroups))
            ->map(function ($item) {
                $item['sales_count'] = count($item['sales_ids']);
                unset($item['sales_ids']);

                return $item;
            })
            ->sortByDesc('total')
            ->values();

        $totalPayments = (float) $paymentSummary->sum('total');

        // Porcentaje de cada medio sobre el importe de pagos registrado.
        $paymentSummary = $paymentSummary->map(function ($item) use ($totalPayments) {
            $item['percentage'] = $totalPayments > 0
                ? ($item['total'] / $totalPayments) * 100
                : 0;

            return $item;
        });

        /*
         * TOP 3 DE CLIENTES
         *
         * Ordenados por importe total comprado, de mayor a menor.
         * Las ventas sin client_id quedan agrupadas como
         * "Cliente no registrado".
         */
        $topClients = DB::table('sales as s')
            ->leftJoin('clients as c', 'c.id', '=', 's.client_id')
            ->where('s.status', 'active')
            ->whereBetween('s.sale_date', [$startDate, $endDate])
            ->selectRaw(
                "s.client_id,
                 COALESCE(c.full_name, 'Cliente no registrado') as customer_name,
                 COUNT(s.sale_id) as purchases_count,
                 SUM(s.total) as total_spent"
            )
            ->groupBy('s.client_id', 'c.full_name')
            ->orderByDesc('total_spent')
            ->orderByDesc('purchases_count')
            ->limit(3)
            ->get();

        /*
         * EVOLUCIÓN DIARIA
         */
        $dailySales = (clone $activeSalesQuery)
            ->selectRaw('DATE(sale_date) as day')
            ->selectRaw('COUNT(*) as quantity')
            ->selectRaw('SUM(total) as amount')
            ->groupByRaw('DATE(sale_date)')
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $salesChart = collect();
        $currentDate = Carbon::parse($from)->startOfDay();
        $lastDate = Carbon::parse($to)->startOfDay();

        while ($currentDate->lte($lastDate)) {
            $dayKey = $currentDate->toDateString();
            $day = $dailySales->get($dayKey);

            $salesChart->push([
                'label' => $currentDate->format('d/m'),
                'date' => $dayKey,
                'quantity' => (int) ($day->quantity ?? 0),
                'amount' => (float) ($day->amount ?? 0),
            ]);

            $currentDate->addDay();
        }

        /*
         * HISTORIAL COMPLETO
         *
         * Se muestran 10 ventas por página.
         * La paginación conserva los filtros de fechas.
         */
        $sales = (clone $salesQuery)
            ->with(['client', 'user'])
            ->orderByDesc('sale_date')
            ->orderByDesc('sale_id')
            ->paginate(10)
            ->withQueryString();

        return view('sale_report.index', compact(
            'from',
            'to',
            'salesCount',
            'totalSales',
            'averageTicket',
            'estimatedProfit',
            'cancelledSales',
            'paymentSummary',
            'totalPayments',
            'topClients',
            'salesChart',
            'sales'
        ));
    }
}
