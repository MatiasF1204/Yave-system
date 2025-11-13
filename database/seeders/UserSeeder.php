<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los roles existentes
        $adminRole = Role::where('name', 'Administrador')->first();
        $sellerRole = Role::where('name', 'Vendedor')->first();

        // Crear el usuario administrador
        DB::table('users')->insert([
            'name' => 'Juan Perez',
            'email' => 'admin@yave.com',
            'password' => Hash::make('admin1234'),
            'role_id' => $adminRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Crear un usuario vendedor
        DB::table('users')->insert([
            'name' => 'María Gómez',
            'email' => 'vendedor@yave.com',
            'password' => Hash::make('vendedor1234'),
            'role_id' => $sellerRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
