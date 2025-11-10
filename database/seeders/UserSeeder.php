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
        // Obtener el ID del rol Administrador
        $adminRole = Role::where('name', 'Administrador')->first();

        // Crear el usuario administrador inicial
        DB::table('users')->insert([
            'name' => 'Administrador del Sistema',
            'email' => 'admin@yave.com',
            'password' => Hash::make('admin1234'),
            'role_id' => $adminRole->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
