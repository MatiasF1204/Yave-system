
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // En esta tabla se guardan los medios de pago
        Schema::create('sale_payments', function (Blueprint $table) {
            // Clave primaria
            $table->id('sale_payment_id');

            // El id de sale como FK
            $table->foreignId('sale_id')
                ->constrained('sales', 'sale_id')
                ->restrictOnDelete();

            // EL id de los metodos de pago como FK
            $table->foreignId('payment_method_id')
                ->constrained('payment_methods', 'id')
                ->restrictOnDelete();

            // El monto de dinero pagado con cada método de pago
            $table->decimal('amount', 12, 2);

            $table->timestamps();

            $table->unique(
                ['sale_id', 'payment_method_id'],
                'sale_payments_sale_method_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_payments');
    }
};
