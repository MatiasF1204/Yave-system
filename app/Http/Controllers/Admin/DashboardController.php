<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();

        // Ventas del dia
        $salesToday = Sale::where('status', 'active')
            ->whereDate('sale_date', $today)
            ->count();

        // Importe total vendido durante el día
        $totalSalesToday = (float) Sale::where('status', 'active')
            ->whereDate('sale_date', $today)
            ->sum('total');

        // Ganancia estimada
        $estimatedProfitToday = (float) DB::table('sale_details as sd')
            ->join('sales as s', 's.sale_id', '=', 'sd.sale_id')
            ->join('products as p', 'p.id', '=', 'sd.product_id')
            ->where('s.status', 'active')
            ->whereDate('s.sale_date', $today)
            ->selectRaw(
                'COALESCE(SUM((sd.unit_price - COALESCE(p.total_cost, 0)) * sd.quantity), 0) as profit'
            )
            ->value('profit');

        // Cantidad de productos y clientes (solo activos)
        $totalProducts = Product::where('status', 'active')->count();

        $totalClients = Client::where('status', 'active')->count();

        // Productos agotados
        $outOfStockCount = Product::where('status', 'active')
            ->where('stock', '<=', 0)
            ->count();

        // Productos con stock bajo (1 a 3 unidades)
        $lowStockCount = Product::where('status', 'active')
            ->whereBetween('stock', [1, 3])
            ->count();

        // Listado de productos que requieren atención
        $lowStockProducts = Product::where('status', 'active')
            ->where('stock', '<=', 3)
            ->orderBy('stock')
            ->orderBy('name')
            ->limit(6)
            ->get();

        // Ventas de los últimos 7 días, excluyendo las anuladas.
        $salesByDayQuery = Sale::query()
            ->where('status', 'active')
            ->whereBetween('sale_date', [
                now()->subDays(6)->startOfDay(),
                now()->endOfDay(),
            ])
            ->selectRaw('DATE(sale_date) as sale_day')
            ->selectRaw('COUNT(*) as sales_count')
            ->selectRaw('SUM(total) as sales_total')
            ->groupByRaw('DATE(sale_date)')
            ->orderBy('sale_day')
            ->get()
            ->keyBy('sale_day');

        // Incluir también los días sin ventas para que el gráfico
        // siempre muestre los siete días completos
        $salesChart = collect(range(6, 0))->map(function ($daysAgo) use ($salesByDayQuery) {
            $date = today()->subDays($daysAgo);
            $dayData = $salesByDayQuery->get($date->toDateString());

            return [
                'label' => $date->format('d/m'),
                'total' => (float) ($dayData->sales_total ?? 0),
                'count' => (int) ($dayData->sales_count ?? 0),
            ];
        });

        // Últimas ventas, incluyendo anuladas para conservar su trazabilidad
        $recentSales = Sale::with(['client', 'user'])
            ->orderByDesc('sale_date')
            ->limit(6)
            ->get();

        return view('admin.dashboard', compact(
            'salesToday',
            'totalSalesToday',
            'estimatedProfitToday',
            'totalProducts',
            'totalClients',
            'outOfStockCount',
            'lowStockCount',
            'lowStockProducts',
            'salesChart',
            'recentSales'
        ));
    }
}
