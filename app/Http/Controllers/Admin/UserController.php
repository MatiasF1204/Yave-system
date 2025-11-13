<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Listado de usuarios
    public function index()
    {
        $users = User::whereHas('role', function ($q) {
            $q->where('name', 'Vendedor');
        })->get();

        return view('admin.users.index', compact('users'));
    }

    // Formulario para editar usuario
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    // Actualizar usuario
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'Usuario actualizado correctamente.');
    }

    // Desactivar usuario (eliminación lógica)
    public function destroy(User $user)
    {
        // Cambiar el estado a 'inactive' en lugar de eliminarlo
        $user->status = 'inactive';
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario desactivado correctamente.'
        ]);
    }
}
