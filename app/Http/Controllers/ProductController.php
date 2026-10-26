<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // LISTADO DE PRODUCTOS

    public function index(Request $request)
    {
        $query = Product::with('categories')
            ->where('status', 'active');

        // Buscar por nombre del producto o nombre de la categoría
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhereHas('categories', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('name', 'LIKE', '%' . $search . '%');
                    });
            });
        }

        // Paginar conservando el término de búsqueda
        $products = $query->paginate(10)->withQueryString();

        return view('products.index', compact('products'));
    }


    // MOSTRAR DETALLE DEL PRODUCTO
    public function show(Product $product)
    {
        $product->load('categories');

        return view('products.show', compact('product'));
    }

    // FORMULARIO PARA CREAR PRODUCTO
    public function create()
    {
        $categories = Category::active()->get();

        return view('products.create', compact('categories'));
    }

    // REGISTRAR PRODUCTO
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:150',
            'description' => 'nullable|string|max:1000',
            'cost' => 'required|numeric|min:0',
            'transport_cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',

            'cost.required' => 'El costo del producto es obligatorio.',
            'cost.numeric' => 'El costo debe ser un número.',
            'cost.min' => 'El costo no puede ser negativo.',

            'transport_cost.required' => 'El costo del transporte es obligatorio.',
            'transport_cost.numeric' => 'El costo del transporte debe ser un número.',
            'transport_cost.min' => 'El costo del transporte no puede ser negativo.',

            'price.required' => 'El precio de venta es obligatorio.',
            'price.numeric' => 'El precio de venta debe ser un número.',
            'price.min' => 'El precio no puede ser negativo.',

            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',

            'categories.required' => 'Debe seleccionar al menos una categoría.',
            'categories.min' => 'Debe seleccionar al menos una categoría.',
            'categories.*.exists' => 'La categoría seleccionada no es válida.',

            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
            'image.max' => 'La imagen no puede superar los 2 MB.',
        ]);

        // BUSCAR SI EL PRODUCTO YA EXISTE
        $existingProduct = Product::where('name', $validated['name'])->first();

        // SI YA EXISTE
        if ($existingProduct) {

            // El producto está activo
            if ($existingProduct->status === 'active') {
                return back()
                    ->withErrors([
                        'name' => 'Ya existe un producto activo con ese nombre.'
                    ])
                    ->withInput();
            }

            // CALCULAR COSTOS
            $totalCost = $validated['cost'] + $validated['transport_cost'];
            $profit = $validated['price'] - $totalCost;

            // Mantener la imagen anterior
            $imagePath = $existingProduct->image;

            // Si se carga una nueva imagen, reemplazar la anterior
            if ($request->hasFile('image')) {

                if ($existingProduct->image) {
                    Storage::disk('public')->delete($existingProduct->image);
                }

                $imagePath = $request->file('image')->store('products', 'public');
            }

            // Reactivar y actualizar los datos
            $existingProduct->update([
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,

                'cost' => $validated['cost'],
                'transport_cost' => $validated['transport_cost'],
                'total_cost' => $totalCost,

                'price' => $validated['price'],
                'profit' => $profit,

                'stock' => $validated['stock'],

                'image' => $imagePath,

                'status' => 'active',
            ]);

            // Actualizar categorías
            $existingProduct->categories()->sync($validated['categories']);

            return redirect()
                ->route('products.index')
                ->with(
                    'success',
                    'El producto ya había sido registrado anteriormente. Se reactivó correctamente y se conservó todo su historial.'
                );
        }

        // ==========================================
        // CREAR PRODUCTO NUEVO
        // ==========================================

        // CALCULAR COSTOS
        $totalCost = $validated['cost'] + $validated['transport_cost'];
        $profit = $validated['price'] - $totalCost;

        // GUARDAR IMAGEN
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // CREAR PRODUCTO
        $product = Product::create([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,

            'cost' => $validated['cost'],
            'transport_cost' => $validated['transport_cost'],
            'total_cost' => $totalCost,

            'price' => $validated['price'],
            'profit' => $profit,

            'stock' => $validated['stock'],

            'image' => $imagePath,

            'status' => 'active',
        ]);

        // ASIGNAR CATEGORÍAS
        $product->categories()->attach($validated['categories']);

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto registrado correctamente.');
    }


    // FORMULARIO PARA EDITAR PRODUCTO
    public function edit(Product $product)
    {
        // Solo administrador
        if (auth()->user()->role->name !== 'Administrador') {
            abort(403, 'No tienes permiso para editar productos.');
        }

        $categories = Category::active()->get();

        // Obtener IDs de las categorías actuales
        $selectedCategories = $product->categories->pluck('id')->toArray();

        return view(
            'products.edit',
            compact('product', 'categories', 'selectedCategories')
        );
    }

    // ACTUALIZAR PRODUCTO
    public function update(Request $request, Product $product)
    {
        // Solo administrador
        if (auth()->user()->role->name !== 'Administrador') {
            abort(403, 'No tienes permiso para editar productos.');
        }

        $validated = $request->validate([
            'name' => 'required|string|min:3|max:150|unique:products,name,' . $product->id,
            'description' => 'nullable|string|max:1000',
            'cost' => 'required|numeric|min:0',
            'transport_cost' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.unique' => 'Ya existe un producto registrado con ese nombre.',

            'cost.required' => 'El costo del producto es obligatorio.',
            'cost.numeric' => 'El costo debe ser un número.',
            'cost.min' => 'El costo no puede ser negativo.',

            'transport_cost.required' => 'El costo del transporte es obligatorio.',
            'transport_cost.numeric' => 'El costo del transporte debe ser un número.',
            'transport_cost.min' => 'El costo del transporte no puede ser negativo.',

            'price.required' => 'El precio de venta es obligatorio.',
            'price.numeric' => 'El precio de venta debe ser un número.',
            'price.min' => 'El precio no puede ser negativo.',

            'stock.required' => 'El stock es obligatorio.',
            'stock.integer' => 'El stock debe ser un número entero.',
            'stock.min' => 'El stock no puede ser negativo.',

            'categories.required' => 'Debe seleccionar al menos una categoría.',
            'categories.min' => 'Debe seleccionar al menos una categoría.',
            'categories.*.exists' => 'La categoría seleccionada no es válida.',

            'image.image' => 'El archivo debe ser una imagen.',
            'image.mimes' => 'La imagen debe ser JPG, JPEG, PNG o WEBP.',
            'image.max' => 'La imagen no puede superar los 2 MB.',
        ]);


        // CALCULAR COSTOS
        $totalCost = $validated['cost'] + $validated['transport_cost'];
        $profit = $validated['price'] - $totalCost;

        // ACTUALIZAR IMAGEN
        $imagePath = $product->image;
        if ($request->hasFile('image')) {

            // Eliminar imagen anterior
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }

            // Guardar nueva imagen
            $imagePath = $request->file('image')->store('products', 'public');
        }

        // ACTUALIZAR PRODUCTO
        $product->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,

            'cost' => $validated['cost'],
            'transport_cost' => $validated['transport_cost'],
            'total_cost' => $totalCost,

            'price' => $validated['price'],
            'profit' => $profit,

            'stock' => $validated['stock'],

            'image' => $imagePath,
        ]);

        // ACTUALIZAR CATEGORÍAS
        $product->categories()->sync($validated['categories']);

        return redirect()
            ->route('products.index')
            ->with('success', 'Producto actualizado correctamente.');
    }

    // DESACTIVAR PRODUCTO
    public function deactivate(Product $product)
    {
        // Solo administrador
        if (auth()->user()->role->name !== 'Administrador') {
            abort(403, 'No tienes permiso para desactivar productos.');
        }

        $product->deactivate();

        return response()->json([
            'success' => true
        ]);
    }

    // ACTIVAR PRODUCTO
    public function activate(Product $product)
    {
        // Solo administrador
        if (auth()->user()->role->name !== 'Administrador') {
            abort(403, 'No tienes permiso para activar productos.');
        }

        $product->update([
            'status' => 'active'
        ]);

        return response()->json([
            'success' => true
        ]);
    }
}
