<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // Ejecuta el seeder de roles
        $this->call(RoleSeeder::class);
    }
}
