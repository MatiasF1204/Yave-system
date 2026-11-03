<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Sale;
use App\Models\Client;
use App\Models\Product;
use App\Models\User;
use App\Models\PaymentMethod;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener clientes, vendedores y medios de pago existentes.
        $clients = Client::active()->get();
        $users = User::all();
        $paymentMethods = PaymentMethod::active()->get();

        if ($clients->isEmpty()) {
            throw new \RuntimeException('No hay clientes para generar las ventas.');
        }

        if ($users->isEmpty()) {
            throw new \RuntimeException('No hay usuarios para generar las ventas.');
        }

        if ($paymentMethods->isEmpty()) {
            throw new \RuntimeException('No hay medios de pago activos.');
        }

        // Generar 20 ventas de prueba.
        for ($i = 0; $i < 20; $i++) {

            DB::transaction(function () use (
                $clients,
                $users,
                $paymentMethods
            ) {
                $client = $clients->random();
                $user = $users->random();

                // Seleccionar entre 1 y 4 productos con stock disponible.
                $products = Product::where('status', 'active')
                    ->where('stock', '>', 0)
                    ->inRandomOrder()
                    ->take(rand(1, 4))
                    ->get();

                if ($products->isEmpty()) {
                    return;
                }

                $total = 0;
                $details = [];

                foreach ($products as $product) {

                    $quantity = rand(1, min(3, $product->stock));

                    // Copiar los valores económicos del producto.
                    $unitCost = (float) $product->cost;
                    $unitTransportCost = (float) $product->transport_cost;
                    $unitTotalCost = (float) $product->total_cost;
                    $unitPrice = (float) $product->price;
                    $unitProfit = round(
                        $unitPrice - $unitTotalCost,
                        2
                    );

                    $subtotal = round($unitPrice * $quantity, 2);

                    $total = round($total + $subtotal, 2);

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

                // Crear la venta principal.
                $sale = Sale::create([
                    'client_id' => $client->id,
                    'user_id' => $user->id,
                    'total' => $total,
                    'observations' => rand(0, 1)
                        ? 'Venta de prueba generada automáticamente.'
                        : null,
                    'sale_date' => now()->subDays(rand(0, 30))
                        ->setTime(rand(8, 20), rand(0, 59)),
                    'status' => 'active',
                ]);

                // Registrar los detalles y descontar el stock.
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

                // Seleccionar uno o dos medios de pago.
                $selectedMethods = $paymentMethods
                    ->shuffle()
                    ->take(rand(1, min(2, $paymentMethods->count())))
                    ->values();

                if ($selectedMethods->count() === 1) {

                    // Pago completo con un solo medio.
                    $sale->payments()->create([
                        'payment_method_id' => $selectedMethods[0]->id,
                        'amount' => $total,
                    ]);
                } else {

                    // Dividir el pago entre dos medios.
                    $firstAmount = round($total * 0.5, 2);
                    $secondAmount = round($total - $firstAmount, 2);

                    $sale->payments()->create([
                        'payment_method_id' => $selectedMethods[0]->id,
                        'amount' => $firstAmount,
                    ]);

                    $sale->payments()->create([
                        'payment_method_id' => $selectedMethods[1]->id,
                        'amount' => $secondAmount,
                    ]);
                }
            });
        }
    }
}
