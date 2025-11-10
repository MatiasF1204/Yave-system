<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Crear roles, administrador
        $adminRole = Role::create(['name' => 'Administrador']);
        // Vendedor
        $vendedorRole = Role::create(['name' => 'Vendedor']);

        // Crea un usuario inicial
        $admin = User::create([
            'name' => 'Administrador del Sistema',
            'email' => 'admin@yave.com',
            'password' => Hash::make('admin!123'),
        ]);

        // Asignar rol de administrador al usuario inicial
        $admin->assignRole($adminRole);
    }
}
