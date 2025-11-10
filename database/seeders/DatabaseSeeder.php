<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public function run(): void
    {
        // Ejecuta el RoleSeeder
        $this->call(RoleSeeder::class);
    }
}
