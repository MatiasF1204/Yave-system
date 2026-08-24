<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
    // Crea un usuario
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'email.required' => 'El email es obligatorio.',
            'email.email' => 'Ingrese un email válido.',
            'email.unique' => 'Ya existe un usuario registrado con ese email.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role_id' => 2,
            'status' => 'active',
        ]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario creado correctamente.');
    }
    // Listado de usuarios
    public function index()
    {
        $users = User::whereHas('role', function ($q) {
            $q->where('name', 'Vendedor');
        })->get();

        return view('users.index', compact('users'));
    }

    // Formulario para editar usuario
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    // Actualizar usuario
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                'unique:users,email,' . $user->id
            ],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        // Si ingresó una nueva contraseña
        if ($request->filled('password')) {

            // Comprobar que no sea la misma contraseña actual
            if (Hash::check($request->password, $user->password)) {
                return back()
                    ->withErrors([
                        'password' => 'La nueva contraseña debe ser diferente de la contraseña actual.'
                    ])
                    ->withInput();
            }

            // Guardar la nueva contraseña
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()
            ->route('admin.users.index')
            ->with('success', 'Usuario actualizado correctamente.');
    }


    /**
     * Desactivar usuario (lógica)
     */
    public function deactivate($id)
    {
        $user = User::findOrFail($id);

        $user->update(['status' => 'inactive']);

        return response()->json(['success' => true]);
    }

    /**
     * Activar usuario
     */
    public function activate($id)
    {
        $user = User::findOrFail($id);

        $user->update(['status' => 'active']);

        return response()->json(['success' => true]);
    }
}
