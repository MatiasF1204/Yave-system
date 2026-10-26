
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            // Clave primaria
            $table->id('sale_id');

            // Relaciones
            $table->foreignId('client_id')
                ->constrained('clients', 'id')
                ->restrictOnDelete();

            $table->foreignId('user_id')
                ->constrained('users', 'id')
                ->restrictOnDelete();

            $table->foreignId('payment_method_id')
                ->constrained('payment_methods', 'id')
                ->restrictOnDelete();

            // Datos de la venta
            $table->decimal('total', 12, 2)->default(0);
            $table->text('observations')->nullable();
            $table->dateTime('sale_date');
            $table->string('status', 10)->default('active');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
