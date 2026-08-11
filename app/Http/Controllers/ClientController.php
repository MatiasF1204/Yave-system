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
            'dni' => 'required|string|regex:/^[0-9]+$/|unique:clients,dni',
            'phone' => 'required|unique:clients,phone',
        ]);

        Client::create($validated);

        return redirect()->route('client.index')->with('success', 'Cliente registrado correctamente.');
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
            'dni' => 'required|integer|unique:clients,dni,' . $client->client_id . ',client_id',
            'phone' => 'required|string|unique:clients,phone,' . $client->client_id . ',client_id',
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
