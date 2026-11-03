<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\User;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DailyControlController extends Controller
{
    public function index(Request $request)
    {
        // Fecha seleccionada (Por defecto el día actual)
        $validated = $request->validate([
            'date' => ['nullable', 'date_format:Y-m-d'],
            'user_id' => ['nullable', 'integer', 'exists:users,id'],
        ]);

        $date = $validated['date'] ?? now()->toDateString();

        $start = Carbon::parse($date)->startOfDay();
        $end = Carbon::parse($date)->endOfDay();

        // Consulta base de ventas del día
        $salesQuery = Sale::query()
            ->whereBetween('sale_date', [$start, $end]);

        // Filtro opcional por vendedor
        if (!empty($validated['user_id'])) {
            $salesQuery->where('user_id', $validated['user_id']);
        }

        // Ventas activas
        $activeSalesQuery = (clone $salesQuery)
            ->where('status', 'active');

        $totalSales = (clone $activeSalesQuery)->sum('total');

        $salesCount = (clone $activeSalesQuery)->count();

        // Clientes registrados distintos atendidos
        $clientsCount = (clone $activeSalesQuery)
            ->whereNotNull('client_id')
            ->distinct('client_id')
            ->count('client_id');

        // Ventas realizadas a consumidores finales
        $consumerFinalCount = (clone $activeSalesQuery)
            ->whereNull('client_id')
            ->count();

        // Ganancias
        $totalProfit = DB::table('sale_details')
            ->join('sales', 'sale_details.sale_id', '=', 'sales.sale_id')
            ->whereBetween('sales.sale_date', [$start, $end])
            ->where('sales.status', 'active')
            ->when(
                !empty($validated['user_id']),
                fn($query) => $query->where('sales.user_id', $validated['user_id'])
            )
            ->sum(DB::raw(
                'sale_details.unit_profit * sale_details.quantity'
            ));

        //Ventas anuladas
        $cancelledSalesQuery = (clone $salesQuery)
            ->where('status', 'cancelled');

        $cancelledCount = (clone $cancelledSalesQuery)->count();

        $cancelledAmount = (clone $cancelledSalesQuery)->sum('total');

        // Recaudación por medio de pago
        $payments = DB::table('sale_payments')
            ->join('sales', 'sale_payments.sale_id', '=', 'sales.sale_id')
            ->whereBetween('sales.sale_date', [$start, $end])
            ->where('sales.status', 'active')
            ->when(
                !empty($validated['user_id']),
                fn($query) => $query->where('sales.user_id', $validated['user_id'])
            )
            ->select(
                'sale_payments.payment_method_id',
                DB::raw('SUM(sale_payments.amount) as total')
            )
            ->groupBy('sale_payments.payment_method_id')
            ->pluck('total', 'payment_method_id');

        // Se muestran todos los medios de pago, incluso los que
        // no tuvieron movimientos durante el día
        $paymentMethods = PaymentMethod::orderBy('name')
            ->get()
            ->map(function ($method) use ($payments) {
                return [
                    'id' => $method->id,
                    'name' => $method->name,
                    'total' => (float) ($payments[$method->id] ?? 0),
                ];
            });

        //Actividad por vendedor
        $salesByUser = (clone $activeSalesQuery)
            ->select(
                'user_id',
                DB::raw('COUNT(*) as sales_count'),
                DB::raw('SUM(total) as total')
            )
            ->with('user:id,name')
            ->groupBy('user_id')
            ->get();

        // Usuarios para el filtro
        $users = User::orderBy('name')->get(['id', 'name']);

        return view('daily_control.index', compact(
            'date',
            'users',
            'paymentMethods',
            'totalSales',
            'salesCount',
            'totalProfit',
            'clientsCount',
            'consumerFinalCount',
            'cancelledCount',
            'cancelledAmount',
            'salesByUser'
        ));
    }
}
