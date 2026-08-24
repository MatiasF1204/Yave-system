<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Mostrar listado de categorías más búsqueda
    public function index(Request $request)
    {
        // Se crea una consulta usando el modelo Category
        $query = Category::query();

        // Buscador de categorías según nombre
        if ($request->filled('search')) { // Valida que el request contenga el campo search y no esté vacío
            // Busca todas las coincidencias en la columna name
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // Ejecuta la consulta
        $categories = $query->get();

        // Retornamos vista y pasamos el resultado de la query
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
        // Recibe la categoría y le cambia el status a inactivo
        $category->update(['status' => 'inactive']);
        // Mensaje de éxito
        return response()->json(['success' => true]);
    }

    // Activar categoría
    public function activate(Category $category)
    {
        $category->update(['status' => 'active']);
        return response()->json(['success' => true]);
    }
}
