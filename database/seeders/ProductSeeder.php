<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Obtener categorías por nombre
        $categories = Category::whereIn('name', [
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
        ])->pluck('id', 'name');

        // Productos de prueba
        $products = [
            [
                'name' => 'Balde plástico reforzado 10 litros',
                'description' => 'Balde plástico reforzado con capacidad de 10 litros.',
                'cost' => 3500,
                'transport_cost' => 500,
                'price' => 6000,
                'stock' => 25,
                'categories' => ['Baldes'],
            ],
            [
                'name' => 'Desinfectante lavanda 1 litro',
                'description' => 'Desinfectante líquido con fragancia de lavanda.',
                'cost' => 1800,
                'transport_cost' => 300,
                'price' => 3500,
                'stock' => 40,
                'categories' => ['Desinfectantes', 'Aromatizantes'],
            ],
            [
                'name' => 'Detergente concentrado 750 ml',
                'description' => 'Detergente concentrado para limpieza de vajilla.',
                'cost' => 1500,
                'transport_cost' => 250,
                'price' => 2800,
                'stock' => 50,
                'categories' => ['Detergentes'],
            ],
            [
                'name' => 'Suavizante floral 2 litros',
                'description' => 'Suavizante para ropa con fragancia floral.',
                'cost' => 2800,
                'transport_cost' => 400,
                'price' => 5000,
                'stock' => 30,
                'categories' => ['Suavizantes', 'Aromatizantes'],
            ],
            [
                'name' => 'Aromatizante ambiental vainilla 500 ml',
                'description' => 'Aromatizante ambiental con fragancia de vainilla.',
                'cost' => 2200,
                'transport_cost' => 350,
                'price' => 4200,
                'stock' => 35,
                'categories' => ['Aromatizantes'],
            ],
            [
                'name' => 'Limpiador desinfectante multiuso 1 litro',
                'description' => 'Limpiador multiuso con acción desinfectante y fragancia cítrica.',
                'cost' => 3200,
                'transport_cost' => 450,
                'price' => 5800,
                'stock' => 20,
                'categories' => ['Desinfectantes', 'Aromatizantes'],
            ],
            [
                'name' => 'Lavandina concentrada 1 litro',
                'description' => 'Lavandina concentrada para limpieza y desinfección de superficies.',
                'cost' => 1200,
                'transport_cost' => 200,
                'price' => 2500,
                'stock' => 60,
                'categories' => ['Lavandinas', 'Desinfectantes'],
            ],
            [
                'name' => 'Lavandina clásica 2 litros',
                'description' => 'Lavandina líquida de uso doméstico en envase de 2 litros.',
                'cost' => 1900,
                'transport_cost' => 300,
                'price' => 3600,
                'stock' => 45,
                'categories' => ['Lavandinas'],
            ],
            [
                'name' => 'Limpiador de pisos aroma limón 1 litro',
                'description' => 'Limpiador líquido para pisos con agradable fragancia a limón.',
                'cost' => 2100,
                'transport_cost' => 300,
                'price' => 3900,
                'stock' => 38,
                'categories' => ['Limpiadores de pisos', 'Aromatizantes'],
            ],
            [
                'name' => 'Limpiador de pisos lavanda 5 litros',
                'description' => 'Limpiador para pisos en presentación económica de 5 litros.',
                'cost' => 6500,
                'transport_cost' => 800,
                'price' => 10500,
                'stock' => 15,
                'categories' => ['Limpiadores de pisos', 'Aromatizantes'],
            ],
            [
                'name' => 'Desengrasante industrial 1 litro',
                'description' => 'Desengrasante líquido para eliminar grasa difícil de superficies.',
                'cost' => 2800,
                'transport_cost' => 400,
                'price' => 4900,
                'stock' => 25,
                'categories' => ['Desengrasantes'],
            ],
            [
                'name' => 'Desengrasante cocina 500 ml',
                'description' => 'Desengrasante para hornallas, cocinas y superficies lavables.',
                'cost' => 1700,
                'transport_cost' => 250,
                'price' => 3200,
                'stock' => 32,
                'categories' => ['Desengrasantes', 'Limpiadores de pisos'],
            ],
            [
                'name' => 'Jabón líquido para manos 500 ml',
                'description' => 'Jabón líquido para manos con fragancia suave.',
                'cost' => 1600,
                'transport_cost' => 250,
                'price' => 3100,
                'stock' => 40,
                'categories' => ['Jabones líquidos'],
            ],
            [
                'name' => 'Jabón líquido para ropa 3 litros',
                'description' => 'Jabón líquido para lavado de prendas en lavarropas o a mano.',
                'cost' => 4800,
                'transport_cost' => 600,
                'price' => 7800,
                'stock' => 22,
                'categories' => ['Jabones líquidos', 'Detergentes'],
            ],
            [
                'name' => 'Detergente limón 1 litro',
                'description' => 'Detergente líquido para vajilla con fragancia a limón.',
                'cost' => 1900,
                'transport_cost' => 300,
                'price' => 3500,
                'stock' => 42,
                'categories' => ['Detergentes', 'Aromatizantes'],
            ],
            [
                'name' => 'Detergente bidón 5 litros',
                'description' => 'Detergente líquido para vajilla en presentación familiar de 5 litros.',
                'cost' => 6500,
                'transport_cost' => 800,
                'price' => 10500,
                'stock' => 12,
                'categories' => ['Detergentes'],
            ],
            [
                'name' => 'Suavizante para ropa bebé 1 litro',
                'description' => 'Suavizante para ropa con fragancia delicada.',
                'cost' => 1900,
                'transport_cost' => 300,
                'price' => 3600,
                'stock' => 28,
                'categories' => ['Suavizantes', 'Aromatizantes'],
            ],
            [
                'name' => 'Suavizante concentrado 5 litros',
                'description' => 'Suavizante concentrado para ropa en bidón de 5 litros.',
                'cost' => 6200,
                'transport_cost' => 750,
                'price' => 9900,
                'stock' => 14,
                'categories' => ['Suavizantes'],
            ],
            [
                'name' => 'Aromatizante textil floral 500 ml',
                'description' => 'Aromatizante para telas, cortinas y tapizados.',
                'cost' => 2300,
                'transport_cost' => 350,
                'price' => 4300,
                'stock' => 26,
                'categories' => ['Aromatizantes'],
            ],
            [
                'name' => 'Aromatizante ambiental cítrico 250 ml',
                'description' => 'Aromatizante ambiental en presentación práctica de 250 ml.',
                'cost' => 1400,
                'transport_cost' => 200,
                'price' => 2700,
                'stock' => 35,
                'categories' => ['Aromatizantes'],
            ],
            [
                'name' => 'Balde plástico con escurridor 12 litros',
                'description' => 'Balde plástico con escurridor incorporado para facilitar el trapeado.',
                'cost' => 5200,
                'transport_cost' => 600,
                'price' => 8500,
                'stock' => 18,
                'categories' => ['Baldes', 'Accesorios de limpieza'],
            ],
            [
                'name' => 'Balde plástico reforzado 20 litros',
                'description' => 'Balde de gran capacidad fabricado en plástico resistente.',
                'cost' => 5800,
                'transport_cost' => 700,
                'price' => 9500,
                'stock' => 12,
                'categories' => ['Baldes'],
            ],
            [
                'name' => 'Trapeador de algodón',
                'description' => 'Trapeador de algodón absorbente para limpieza de pisos.',
                'cost' => 2500,
                'transport_cost' => 350,
                'price' => 4500,
                'stock' => 30,
                'categories' => ['Accesorios de limpieza'],
            ],
            [
                'name' => 'Escoba de plástico reforzada',
                'description' => 'Escoba de cerdas resistentes para limpieza de interiores y exteriores.',
                'cost' => 2800,
                'transport_cost' => 400,
                'price' => 4900,
                'stock' => 24,
                'categories' => ['Accesorios de limpieza'],
            ],
            [
                'name' => 'Secador de piso 40 cm',
                'description' => 'Secador de piso con goma resistente de 40 centímetros.',
                'cost' => 2200,
                'transport_cost' => 300,
                'price' => 4100,
                'stock' => 20,
                'categories' => ['Accesorios de limpieza'],
            ],
            [
                'name' => 'Paño de microfibra',
                'description' => 'Paño de microfibra reutilizable para limpiar distintas superficies.',
                'cost' => 900,
                'transport_cost' => 150,
                'price' => 1900,
                'stock' => 70,
                'categories' => ['Accesorios de limpieza'],
            ],
            [
                'name' => 'Esponja multiuso doble cara',
                'description' => 'Esponja de limpieza con doble superficie para diferentes usos.',
                'cost' => 500,
                'transport_cost' => 100,
                'price' => 1200,
                'stock' => 100,
                'categories' => ['Accesorios de limpieza'],
            ],
            [
                'name' => 'Guantes de limpieza reutilizables',
                'description' => 'Guantes de goma reutilizables para tareas domésticas de limpieza.',
                'cost' => 1300,
                'transport_cost' => 200,
                'price' => 2500,
                'stock' => 40,
                'categories' => ['Accesorios de limpieza'],
            ],
            [
                'name' => 'Cepillo de limpieza multiuso',
                'description' => 'Cepillo con cerdas resistentes para limpieza de distintas superficies.',
                'cost' => 1100,
                'transport_cost' => 200,
                'price' => 2200,
                'stock' => 35,
                'categories' => ['Accesorios de limpieza'],
            ],
            [
                'name' => 'Limpiavidrios 500 ml',
                'description' => 'Limpiador líquido para vidrios, espejos y superficies brillantes.',
                'cost' => 1500,
                'transport_cost' => 250,
                'price' => 2900,
                'stock' => 32,
                'categories' => ['Limpiadores de pisos'],
            ],
        ];

        // Registrar productos y asociar sus categorías
        foreach ($products as $data) {
            $product = Product::create([
                'name' => $data['name'],
                'description' => $data['description'],
                'cost' => $data['cost'],
                'transport_cost' => $data['transport_cost'],
                'total_cost' => $data['cost'] + $data['transport_cost'],
                'price' => $data['price'],
                'profit' => $data['price'] - ($data['cost'] + $data['transport_cost']),
                'stock' => $data['stock'],
                'image' => null,
                'status' => 'active',
            ]);

            $categoryIds = collect($data['categories'])
                ->map(fn($name) => $categories->get($name))
                ->filter()
                ->values()
                ->all();

            $product->categories()->attach($categoryIds);
        }
    }
}
