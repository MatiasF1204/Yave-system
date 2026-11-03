<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Baldes',
            'Desinfectantes',
            'Detergentes',
            'Aromatizantes',
            'Suavizantes',
            'Lavandinas',
            'Limpiadores de pisos',
            'Desengrasantes',
            'Jabones líquidos',
            'Accesorios de limpieza',
        ];

        foreach ($categories as $name) {
            Category::create([
                'name'   => $name,
                'status' => 'active',
            ]);
        }
    }
}
