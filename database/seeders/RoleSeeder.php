<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crea en la tabla los 2 roles
        DB::table('roles')->insert([
            ['name' => 'Administrador', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vendedor', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
