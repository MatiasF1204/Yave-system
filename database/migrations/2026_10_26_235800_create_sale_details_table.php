
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sale_details', function (Blueprint $table) {
            // Clave primaria
            $table->id('sale_detail_id');

            // Relaciones
            $table->foreignId('sale_id')
                ->constrained('sales', 'sale_id')
                ->restrictOnDelete();

            $table->foreignId('product_id')
                ->constrained('products', 'id')
                ->restrictOnDelete();

            // Información del producto vendido
            $table->unsignedInteger('quantity');

            // Copia histórica de los costos y ganancias
            $table->decimal('unit_cost', 10, 2);
            $table->decimal('unit_transport_cost', 10, 2);
            $table->decimal('unit_total_cost', 10, 2);

            // Precio y ganancia al momento de la venta
            $table->decimal('unit_price', 10, 2);
            $table->decimal('unit_profit', 10, 2);

            // Importe total de las unidades vendidas
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();

            // Evita repetir un producto en una misma venta
            $table->unique(
                ['sale_id', 'product_id'],
                'sale_details_sale_product_unique'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sale_details');
    }
};
