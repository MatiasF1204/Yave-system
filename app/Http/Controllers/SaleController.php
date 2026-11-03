<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\PaymentMethod;
use App\Models\Product;
use App\Models\Sale;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class SaleController extends Controller
{
    // Mostrar el listado de ventas

    public function index(Request $request)
    {
        $query = Sale::with(['client', 'user'])
            ->orderByDesc('sale_date');

        // Número de venta
        if ($request->filled('sale_id')) {
            $query->where('sale_id', $request->sale_id);
        }

        // DNI del cliente
        if ($request->filled('dni')) {
            $dni = trim($request->dni);

            $query->whereHas('client', function ($clientQuery) use ($dni) {
                $clientQuery->where('dni', 'like', "%{$dni}%");
            });
        }

        // Vendedor
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Estado de la venta
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Medio de pago
        if ($request->filled('payment_method_id')) {
            $query->whereHas('payments', function ($paymentQuery) use ($request) {
                $paymentQuery->where('payment_method_id', $request->payment_method_id);
            });
        }

        // Rango de fechas
        $fechaDesde = $request->input('date_from');
        $fechaHasta = $request->input('date_to');

        if ($fechaDesde && $fechaHasta) {
            $query->whereBetween('sale_date', [
                $fechaDesde . ' 00:00:00',
                $fechaHasta . ' 23:59:59',
            ]);
        } elseif ($fechaDesde) {
            $query->where('sale_date', '>=', $fechaDesde . ' 00:00:00');
        } elseif ($fechaHasta) {
            $query->where('sale_date', '<=', $fechaHasta . ' 23:59:59');
        } else {
            // Sin fechas seleccionadas, mostrar solamente las ventas de hoy.
            $query->whereDate('sale_date', today());
        }

        // 10 ventas por página y conservación de los filtros
        $sales = $query->paginate(10)->withQueryString();

        // Opciones para los filtros
        $users = User::orderBy('name')->get();

        $paymentMethods = PaymentMethod::orderBy('name')->get();

        return view('sale.index', compact('sales', 'users', 'paymentMethods'));
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
                'nullable',
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

            // Verificar el stock y obtener los valores económicos del producto.
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

                // Copiar los valores económicos actuales del producto.
                $unitCost = (float) $product->cost;
                $unitTransportCost = (float) $product->transport_cost;
                $unitTotalCost = (float) $product->total_cost;
                $unitPrice = (float) $product->price;
                $unitProfit = (float) $product->profit;

                // Calcular el subtotal de venta.
                $subtotal = round($unitPrice * $quantity, 2);
                $total = round($total + $subtotal, 2);

                // Preparar los datos históricos del detalle.
                $details[] = [
                    'product' => $product,
                    'quantity' => $quantity,
                    'unit_cost' => $unitCost,
                    'unit_transport_cost' => $unitTransportCost,
                    'unit_total_cost' => $unitTotalCost,
                    'unit_price' => $unitPrice,
                    'unit_profit' => $unitProfit,
                    'subtotal' => $subtotal,
                ];
            }

            // Comprobar los medios de pago y sus importes.
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

            // Crear la venta principal.
            $sale = Sale::create([
                'client_id' => $validated['client_id'] ?? null,
                'user_id' => $request->user()->id,
                'total' => $total,
                'observations' => $validated['observations'] ?? null,
                'sale_date' => now(),
                'status' => 'active',
            ]);

            // Registrar los productos y descontar el stock.
            foreach ($details as $detail) {
                $sale->details()->create([
                    'product_id' => $detail['product']->id,
                    'quantity' => $detail['quantity'],
                    'unit_cost' => $detail['unit_cost'],
                    'unit_transport_cost' => $detail['unit_transport_cost'],
                    'unit_total_cost' => $detail['unit_total_cost'],
                    'unit_price' => $detail['unit_price'],
                    'unit_profit' => $detail['unit_profit'],
                    'subtotal' => $detail['subtotal'],
                ]);

                $detail['product']->decrement(
                    'stock',
                    $detail['quantity']
                );
            }

            // Registrar todos los medios de pago.
            foreach ($payments as $payment) {
                $sale->payments()->create($payment);
            }

            return $sale;
        }, 3);

        return redirect()
            ->route('sales.show', $sale->sale_id)
            ->with('success', 'La venta se registró correctamente.');
    }


    // Mostrar el detalle de una venta.
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

    // Anular una venta, restablecer el stock y conservar el historial de la operación
    public function cancel(Request $request, Sale $sale)
    {
        // Verificación adicional del rol.
        abort_unless(
            $request->user()->role->name === 'Administrador',
            403,
            'Solo un administrador puede anular ventas.'
        );

        // Validar que se haya ingresado la contraseña.
        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        // Verificar la contraseña del usuario autenticado.
        if (!Hash::check(
            $validated['password'],
            $request->user()->password
        )) {
            throw ValidationException::withMessages([
                'password' => 'La contraseña ingresada es incorrecta.',
            ]);
        }

        DB::transaction(function () use ($sale) {
            // Bloquear la venta para evitar anulaciones simultáneas.
            $lockedSale = Sale::whereKey($sale->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            // Solo se puede anular una venta activa.
            if ($lockedSale->status !== 'active') {
                throw ValidationException::withMessages([
                    'sale' => 'Esta venta ya está anulada o no se encuentra activa.',
                ]);
            }

            // Obtener los detalles de la venta.
            $details = $lockedSale->details()->get();

            // Devolver al stock las cantidades vendidas.
            foreach ($details as $detail) {
                $product = Product::whereKey($detail->product_id)
                    ->lockForUpdate()
                    ->first();

                if (!$product) {
                    throw ValidationException::withMessages([
                        'sale' => 'No se pudo anular la venta porque uno de sus productos ya no existe.',
                    ]);
                }

                $product->increment('stock', $detail->quantity);
            }

            // Conservar la venta y marcarla como anulada.
            $lockedSale->status = 'cancelled';
            $lockedSale->save();
        }, 3);

        return response()->json([
            'success' => true,
            'message' => 'La venta fue anulada y el stock se restableció correctamente.',
        ]);
    }
}
