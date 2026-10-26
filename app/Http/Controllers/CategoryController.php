<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Mostrar listado de categorías
    public function index(Request $request)
    {
        $query = Category::where('status', 'active');

        // Búsqueda por nombre
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Orden alfabético y paginación
        $categories = $query
            ->orderBy('name', 'asc')
            ->paginate(10)
            ->withQueryString();

        return view('categories.index', compact('categories'));
    }

    // Vista para crear
    public function create()
    {
        return view('categories.create');
    }

    // Registrar categoría
    public function store(Request $request)
    {
        // Validar formulario
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:150',
        ], [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede superar los 150 caracteres.',
        ]);

        // Buscar si la categoría ya existe
        $existingCategory = Category::where('name', $validated['name'])->first();

        // Si existe...
        if ($existingCategory) {

            // Si ya está activa, no permitimos duplicarla
            if ($existingCategory->status === 'active') {
                return back()
                    ->withErrors([
                        'name' => 'Ya existe una categoría activa con ese nombre.'
                    ])
                    ->withInput();
            }

            // Si estaba inactiva, la reactivamos
            $existingCategory->update([
                'status' => 'active',
            ]);

            return redirect()
                ->route('admin.categories.index')
                ->with(
                    'success',
                    'La categoría ya había sido registrada anteriormente. Se reactivó correctamente y se conservó todo su historial.'
                );
        }

        // Si nunca existió, creamos una categoría nueva
        Category::create([
            'name' => $validated['name'],
            'status' => 'active',
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', 'Categoría registrada correctamente.');
    }

    // Formulario editar categoría
    public function edit(Category $category)
    {
        // Retorna vista con la categoría a editar
        return view('categories.edit', compact('category'));
    }

    // Actualizar categoría
    public function update(Request $request, Category $category)
    {
        // Ejecuta validacion
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:150|unique:categories,name,' . $category->id,
        ]);

        // Actualiza la categoría
        $category->update($validated);

        // Redirige a la vista con mensaje de éxito
        return redirect()->route('admin.categories.index')->with('success', 'Categoría actualizada correctamente.');
    }


    // Desactivar categoría
    public function deactivate(Category $category)
    {
        // Verificar si la categoría está asociada a algún producto
        if ($category->products()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar esta categoría porque está asociada a uno o más productos.'
            ], 422);
        }

        // Si no tiene productos asociados, desactivarla
        $category->update([
            'status' => 'inactive'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'La categoría fue eliminada correctamente.'
        ]);
    }


    // Activar categoría
    public function activate(Category $category)
    {
        $category->update(['status' => 'active']);
        return response()->json(['success' => true]);
    }
}
