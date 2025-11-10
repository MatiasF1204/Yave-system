<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role; // 👈 Importá el modelo Role
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        // 👇 Buscamos el rol de vendedor
        $vendedorRole = Role::where('name', 'Vendedor')->first();

        // Creamos el usuario con el rol por defecto
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $vendedorRole->id, // 👈 asignación automática
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Redirección según el rol
        if ($user->role && $user->role->name === 'Administrador') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role && $user->role->name === 'Vendedor') {
            return redirect()->route('seller.dashboard');
        }


        return redirect()->route('dashboard');
    }
}
