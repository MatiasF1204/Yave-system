<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class StockReportController extends Controller
{
    public function index(Request $request)
    {
        // Validar los filtros recibidos.
        $validated = $request->validate([
            'search' => ['nullable', 'string', 'max:150'],
            'category' => ['nullable', 'integer', 'exists:categories,id'],
            'stock_status' => ['nullable', 'in:all,available,low,out'],
            'status' => ['nullable', 'in:active,inactive,all'],
        ]);

        $search = trim($validated['search'] ?? '');
        $categoryId = $validated['category'] ?? '';
        $stockStatus = $validated['stock_status'] ?? 'all';

        // Por defecto, mostrar solamente productos activos.
        $productStatus = $validated['status'] ?? 'active';

        /*
         * Indicadores generales del inventario.
         * Se calculan sobre los productos activos, independientemente
         * de los filtros aplicados al listado.
         */
        $activeProducts = Product::where('status', 'active');

        $totalProducts = (clone $activeProducts)->count();

        $totalStock = (clone $activeProducts)->sum('stock');

        $lowStock = (clone $activeProducts)
            ->whereBetween('stock', [1, 5])
            ->count();

        $outOfStock = (clone $activeProducts)
            ->where('stock', '<=', 0)
            ->count();

        // Construir la consulta del listado.
        $query = Product::with('categories');

        if ($productStatus !== 'all') {
            $query->where('status', $productStatus);
        }

        if ($search !== '') {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($categoryId !== '') {
            $query->whereHas('categories', function ($categoryQuery) use ($categoryId) {
                $categoryQuery->where('categories.id', $categoryId);
            });
        }

        // Filtros por disponibilidad de stock.
        switch ($stockStatus) {
            case 'available':
                $query->where('stock', '>', 5);
                break;

            case 'low':
                $query->whereBetween('stock', [1, 5]);
                break;

            case 'out':
                $query->where('stock', '<=', 0);
                break;
        }

        // Orden alfabético y paginación.
        $products = $query
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        // Categorías disponibles para el filtro.
        $categories = Category::orderBy('name')->get();

        // Máximo de stock de la página actual para las barras visuales.
        $maxStock = max(
            (int) ($products->getCollection()->max('stock') ?? 0),
            1
        );

        return view('stock_report.index', compact(
            'products',
            'categories',
            'totalProducts',
            'totalStock',
            'lowStock',
            'outOfStock',
            'search',
            'categoryId',
            'stockStatus',
            'productStatus',
            'maxStock'
        ));
    }
}
