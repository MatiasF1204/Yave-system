<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // Ejecuta el RoleSeeder que crea los tipos de roles
        $this->call(RoleSeeder::class);
        // Ejecuta el UserSeeder que crea el usuario admin por defecto
        $this->call(UserSeeder::class);
    }
}
