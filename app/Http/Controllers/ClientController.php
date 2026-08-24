<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    // Mostrar listado de clientes y barra de búsqueda
    public function index(Request $request)
    {
        $query = Client::query();

        // Búsqueda por DNI
        if ($request->filled('search')) {
            $query->where('dni', 'LIKE', '%' . $request->search . '%');
        }

        $clients = $query->get();

        return view('clients.index', compact('clients'));
    }


    public function create()
    {
        return view('clients.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|min:5|max:150',
            'dni' => 'required|string|regex:/^[0-9]+$/',
            'phone' => 'required|string',
        ], [
            'full_name.required' => 'El nombre completo es obligatorio.',
            'full_name.min' => 'El nombre debe tener al menos 5 caracteres.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.regex' => 'El DNI solo puede contener números.',
            'phone.required' => 'El teléfono es obligatorio.',
        ]);

        // Buscar si el cliente ya existe por DNI
        $existingClient = Client::where('dni', $validated['dni'])->first();

        // Si existe
        if ($existingClient) {

            // Si ya está activo, no permitimos duplicarlo
            if ($existingClient->status === 'active') {
                return back()
                    ->withErrors([
                        'dni' => 'Ya existe un cliente activo registrado con ese DNI.'
                    ])
                    ->withInput();
            }

            // Si estaba inactivo, lo reactivamos
            $existingClient->update([
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'status' => 'active',
            ]);

            return redirect()
                ->route('clients.index')
                ->with(
                    'success',
                    'El cliente ya había sido registrado anteriormente. Se reactivó correctamente y se conservó todo su historial.'
                );
        }

        // Si nunca existió, creamos un cliente nuevo
        Client::create([
            'full_name' => $validated['full_name'],
            'dni' => $validated['dni'],
            'phone' => $validated['phone'],
            'status' => 'active',
        ]);

        return redirect()
            ->route('clients.index')
            ->with('success', 'Cliente registrado correctamente.');
    }


    // Formulario para editar cliente (solo admin)
    public function edit(Client $client)
    {
        if (auth()->user()->role->name !== 'Administrador') {
            abort(403, 'No tienes permiso para editar clientes.');
        }

        return view('clients.edit', compact('client'));
    }

    // Actualizar cliente
    public function update(Request $request, Client $client)
    {
        if (auth()->user()->role->name !== 'Administrador') {
            abort(403, 'No tienes permiso para editar clientes.');
        }

        $validated = $request->validate([
            'full_name' => 'required|string|min:5|max:150',
            'dni' => 'required|integer|unique:clients,dni,' . $client->id . ',id',
            'phone' => 'required|string|unique:clients,phone,' . $client->id . ',id',
        ]);

        $client->update($validated);

        return redirect()->route('clients.index')->with('success', 'Cliente actualizado correctamente.');
    }

    // Desactivar cliente
    public function deactivate(Client $client)
    {
        $client->deactivate();
        return response()->json(['success' => true]);
    }

    // Activar cliente
    public function activate(Client $client)
    {
        $client->update(['status' => 'active']);
        return response()->json(['success' => true]);
    }
}
