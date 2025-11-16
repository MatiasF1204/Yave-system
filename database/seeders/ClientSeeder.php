<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ClientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('clients')->insert([
            [
                'full_name' => 'María López',
                'dni' => 40123456,
                'phone' => '1167891234',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Carlos Pérez',
                'dni' => 39234567,
                'phone' => '1134567890',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Laura Fernández',
                'dni' => 42123456,
                'phone' => '1123456789',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Diego Ramírez',
                'dni' => 38543210,
                'phone' => '1145678912',
                'status' => 'inactive',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'full_name' => 'Sofía Gómez',
                'dni' => 41456789,
                'phone' => '1176543210',
                'status' => 'active',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
