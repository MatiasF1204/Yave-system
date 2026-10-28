<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Sale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    // Mostrar el listado de ventas.


    public function index(Request $request)
    {
        $query = Sale::with(['client', 'user'])
            ->orderByDesc('sale_date');

        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                if (is_numeric($search)) {
                    $q->where('sale_id', (int) $search);
                }

                $q->orWhereHas('client', function ($clientQuery) use ($search) {
                    $clientQuery->where('full_name', 'like', "%{$search}%");
                });
            });
        }

        $sales = $query->paginate(15)->withQueryString();

        return view('sale.index', compact('sales'));
    }


    // Muestra formulario para registrar una venta
    public function create()
    {
        $clients = Client::all();

        $products = Product::where('status', 'active')
            ->where('stock', '>', 0)
            ->orderBy('name')
            ->get();

        $paymentMethods = PaymentMethod::active()
            ->orderBy('name')
            ->get();

        $clientsData = Client::active()
            ->select('id', 'full_name', 'dni')
            ->get();
        $productsData = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'price' => (float) $product->price,
                'stock' => (int) $product->stock,
            ];
        })->values();

        $paymentMethodsData = $paymentMethods->map(function ($method) {
            return [
                'id' => $method->id,
                'name' => $method->name,
            ];
        })->values();

        return view('sale.create', compact(
            'clients',
            'products',
            'paymentMethods',
            'clientsData',
            'productsData',
            'paymentMethodsData'
        ));
    }


    // Guardar una venta con sus productos y pagos
    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id' => [
                'required',
                'integer',
                'exists:clients,id',
            ],

            'observations' => [
                'nullable',
                'string',
                'max:5000',
            ],

            'products' => [
                'required',
                'array',
                'min:1',
            ],

            'products.*.product_id' => [
                'required',
                'integer',
                'distinct',
                'exists:products,id',
            ],

            'products.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],

            'payments' => [
                'required',
                'array',
                'min:1',
            ],

            'payments.*.payment_method_id' => [
                'required',
                'integer',
                'distinct',
                'exists:payment_methods,id',
            ],

            'payments.*.amount' => [
                'required',
                'numeric',
                'gt:0',
                'decimal:0,2',
            ],
        ]);

        $sale = DB::transaction(function () use ($validated, $request) {
            $total = 0;
            $details = [];

            // Verificar el stock y calcular los importes
            foreach ($validated['products'] as $item) {
                $product = Product::whereKey($item['product_id'])
                    ->lockForUpdate()
                    ->first();

                if (!$product || $product->status !== 'active') {
                    throw ValidationException::withMessages([
                        'products' => 'Uno de los productos no está activo.',
                    ]);
                }

                $quantity = (int) $item['quantity'];

                if ($product->stock < $quantity) {
                    throw ValidationException::withMessages([
                        'products' => "Stock insuficiente para {$product->name}. Disponible: {$product->stock}.",
                    ]);
                }

                // El precio se toma de la base de datos,
                // nunca del importe enviado por el formulario
                $unitPrice = (float) $product->price;
                $subtotal = round($unitPrice * $quantity, 2);

                $total = round($total + $subtotal, 2);

                $details[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'subtotal' => $subtotal,
                ];
            }

            // Comprobar que los métodos de pago estén activos y que la suma de los pagos coincida con el total
            $paymentTotal = 0;
            $payments = [];

            foreach ($validated['payments'] as $payment) {
                $method = PaymentMethod::whereKey(
                    $payment['payment_method_id']
                )->first();

                if (!$method || $method->status !== 'active') {
                    throw ValidationException::withMessages([
                        'payments' => 'Uno de los medios de pago no está activo.',
                    ]);
                }

                $amount = round((float) $payment['amount'], 2);
                $paymentTotal = round($paymentTotal + $amount, 2);

                $payments[] = [
                    'payment_method_id' => $method->id,
                    'amount' => $amount,
                ];
            }

            if (abs($paymentTotal - $total) > 0.001) {
                throw ValidationException::withMessages([
                    'payments' => 'La suma de los pagos debe coincidir con el total.',
                ]);
            }

            /*
             * Crear la venta principal.
             */
            $sale = Sale::create([
                'client_id' => $validated['client_id'],
                'user_id' => $request->user()->id,
                'total' => $total,
                'observations' => $validated['observations'] ?? null,
                'sale_date' => now(),
                'status' => 'active',
            ]);

            /*
             * Registrar los productos y descontar el stock.
             */
            foreach ($details as $detail) {
                $sale->details()->create([
                    'product_id' => $detail['product']->id,
                    'quantity' => $detail['quantity'],
                    'unit_price' => $detail['unit_price'],
                    'subtotal' => $detail['subtotal'],
                ]);

                $detail['product']->decrement(
                    'stock',
                    $detail['quantity']
                );
            }

            /*
             * Registrar todos los medios de pago.
             */
            foreach ($payments as $payment) {
                $sale->payments()->create($payment);
            }

            return $sale;
        }, 3);

        return redirect()
            ->route('sales.show', $sale->sale_id)
            ->with('success', 'La venta se registró correctamente.');
    }

    /**
     * Mostrar el detalle de una venta.
     */
    public function show(Sale $sale)
    {
        $sale->load([
            'client',
            'user',
            'details.product',
            'payments.paymentMethod',
        ]);

        return view('sale.show', compact('sale'));
    }
}
