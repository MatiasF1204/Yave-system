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
        $baldes = Category::where('name', 'Baldes')->first();
        $desinfectantes = Category::where('name', 'Desinfectantes')->first();
        $detergentes = Category::where('name', 'Detergentes')->first();
        $aromatizantes = Category::where('name', 'Aromatizantes')->first();
        $suavizantes = Category::where('name', 'Suavizantes')->first();

        // ==========================================
        // PRODUCTO 1
        // ==========================================

        $cost = 3500;
        $transportCost = 500;
        $price = 6000;

        $product = Product::create([
            'name' => 'Balde plástico reforzado 10 litros',
            'description' => 'Balde plástico reforzado con capacidad de 10 litros.',
            'cost' => $cost,
            'transport_cost' => $transportCost,
            'total_cost' => $cost + $transportCost,
            'price' => $price,
            'profit' => $price - ($cost + $transportCost),
            'stock' => 25,
            'image' => null,
            'status' => 'active',
        ]);

        $product->categories()->attach([
            $baldes->id,
        ]);


        // ==========================================
        // PRODUCTO 2
        // ==========================================

        $cost = 1800;
        $transportCost = 300;
        $price = 3500;

        $product = Product::create([
            'name' => 'Desinfectante lavanda 1 litro',
            'description' => 'Desinfectante líquido con fragancia de lavanda.',
            'cost' => $cost,
            'transport_cost' => $transportCost,
            'total_cost' => $cost + $transportCost,
            'price' => $price,
            'profit' => $price - ($cost + $transportCost),
            'stock' => 40,
            'image' => null,
            'status' => 'active',
        ]);

        $product->categories()->attach([
            $desinfectantes->id,
            $aromatizantes->id,
        ]);


        // ==========================================
        // PRODUCTO 3
        // ==========================================

        $cost = 1500;
        $transportCost = 250;
        $price = 2800;

        $product = Product::create([
            'name' => 'Detergente concentrado 750 ml',
            'description' => 'Detergente concentrado para limpieza de vajilla.',
            'cost' => $cost,
            'transport_cost' => $transportCost,
            'total_cost' => $cost + $transportCost,
            'price' => $price,
            'profit' => $price - ($cost + $transportCost),
            'stock' => 50,
            'image' => null,
            'status' => 'active',
        ]);

        $product->categories()->attach([
            $detergentes->id,
        ]);


        // ==========================================
        // PRODUCTO 4
        // ==========================================

        $cost = 2800;
        $transportCost = 400;
        $price = 5000;

        $product = Product::create([
            'name' => 'Suavizante floral 2 litros',
            'description' => 'Suavizante para ropa con fragancia floral.',
            'cost' => $cost,
            'transport_cost' => $transportCost,
            'total_cost' => $cost + $transportCost,
            'price' => $price,
            'profit' => $price - ($cost + $transportCost),
            'stock' => 30,
            'image' => null,
            'status' => 'active',
        ]);

        $product->categories()->attach([
            $suavizantes->id,
            $aromatizantes->id,
        ]);


        // ==========================================
        // PRODUCTO 5
        // ==========================================

        $cost = 2200;
        $transportCost = 350;
        $price = 4200;

        $product = Product::create([
            'name' => 'Aromatizante ambiental vainilla 500 ml',
            'description' => 'Aromatizante ambiental con fragancia de vainilla.',
            'cost' => $cost,
            'transport_cost' => $transportCost,
            'total_cost' => $cost + $transportCost,
            'price' => $price,
            'profit' => $price - ($cost + $transportCost),
            'stock' => 35,
            'image' => null,
            'status' => 'active',
        ]);

        $product->categories()->attach([
            $aromatizantes->id,
        ]);


        // ==========================================
        // PRODUCTO 6
        // ==========================================

        $cost = 3200;
        $transportCost = 450;
        $price = 5800;

        $product = Product::create([
            'name' => 'Limpiador desinfectante multiuso 1 litro',
            'description' => 'Limpiador multiuso con acción desinfectante y fragancia cítrica.',
            'cost' => $cost,
            'transport_cost' => $transportCost,
            'total_cost' => $cost + $transportCost,
            'price' => $price,
            'profit' => $price - ($cost + $transportCost),
            'stock' => 20,
            'image' => null,
            'status' => 'active',
        ]);

        $product->categories()->attach([
            $desinfectantes->id,
            $aromatizantes->id,
        ]);
    }
}