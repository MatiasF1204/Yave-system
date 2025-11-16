<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // Mostrar listado de categorías + búsqueda
    public function index(Request $request)
    {
        $query = Category::query();

        // Buscador por nombre
        if ($request->filled('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        $categories = $query->get();

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
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:150|unique:categories,name',
        ]);

        Category::create([
            'name' => $validated['name'],
            'status' => 'active',
        ]);

        return redirect()->route('admin.categories.index')->with('success', 'Categoría registrada correctamente.');
    }

    // Formulario editar categoría
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }

    // Actualizar categoría
    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:3|max:150|unique:categories,name,' . $category->id,
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Categoría actualizada correctamente.');
    }

    // Desactivar categoría
    public function deactivate(Category $category)
    {
        $category->update(['status' => 'inactive']);
        return response()->json(['success' => true]);
    }

    // Activar categoría
    public function activate(Category $category)
    {
        $category->update(['status' => 'active']);
        return response()->json(['success' => true]);
    }
}
