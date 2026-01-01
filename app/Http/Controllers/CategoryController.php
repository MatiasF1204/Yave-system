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
        return view('admin.categories.index', compact('categories'));
    }

    // Vista para crear
    public function create()
    {
        return view('admin.categories.create');
    }

    // Registrar categoría
    public function store(Request $request)
    {
        // Validar formulario
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:150|unique:categories,name',
        ]);

        // Crear categoría registrada
        Category::create([
            // Le asignamos el nombre validado y el status activo
            'name' => $validated['name'], 
            'status' => 'active',
        ]);

        // Redirigimos a la vista con mensaje de éxito
        return redirect()->route('admin.categories.index')->with('success', 'Categoría registrada correctamente.');
    }

    // Formulario editar categoría
    public function edit(Category $category)
    {
        // Retorna vista con la categoría a editar
        return view('admin.categories.edit', compact('category'));
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
