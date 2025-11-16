<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // Ejecuta el RoleSeeder que crea los tipos de roles
        $this->call(RoleSeeder::class);
        // Crea el usuario admin y un vendedor por defecto
        $this->call(UserSeeder::class);
        // Crea 5 categorías por defecto
        $this->call(CategorySeeder::class);
    }
}
