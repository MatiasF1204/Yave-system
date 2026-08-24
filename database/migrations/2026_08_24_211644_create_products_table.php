<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();

            // Información del producto
            $table->string('name', 150);
            $table->text('description')->nullable();

            // Costos
            $table->decimal('cost', 10, 2);
            $table->decimal('transport_cost', 10, 2)->default(0);
            $table->decimal('total_cost', 10, 2);

            // Precio y ganancia
            $table->decimal('price', 10, 2);
            $table->decimal('profit', 10, 2);

            // Inventario
            $table->integer('stock')->default(0);

            // Imagen
            $table->string('image')->nullable();

            // Estado
            $table->string('status')->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};