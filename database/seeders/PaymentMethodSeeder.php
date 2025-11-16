<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PaymentMethod;

class PaymentMethodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $methods = [
            ['name' => 'Efectivo', 'status' => 'active'],
            ['name' => 'Transferencia', 'status' => 'active'],
            ['name' => 'Cuotas con Mercado Pago', 'status' => 'active'],
        ];

        foreach ($methods as $method) {
            PaymentMethod::create($method);
        }
    }
}
