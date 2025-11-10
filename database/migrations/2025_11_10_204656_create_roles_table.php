<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->timestamps();
        });

        // Agregamos la clave foránea en la tabla users
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->constrained('roles')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Primero, modificamos la tabla 'users' para revertir los cambios
        Schema::table('users', function (Blueprint $table) {
            // Eliminamos la clave foránea 'role_id' que apuntaba a la tabla 'roles'
            $table->dropForeign(['role_id']);

            // Luego eliminamos la propia columna 'role_id' de la tabla 'users'
            $table->dropColumn('role_id');
        });

        // Finalmente, eliminamos completamente la tabla 'roles'
        Schema::dropIfExists('roles');
    }
};
