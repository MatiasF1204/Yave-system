<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use App\Http\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Redirección general según rol
Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    if ($user->role && $user->role->name === 'Administrador') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role && $user->role->name === 'Vendedor') {
        return redirect()->route('seller.dashboard');
    }

    // Si no tiene rol definido
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');


// Rutas para Administrador
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Gestión de usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::put('/users/{id}/deactivate', [UserController::class, 'deactivate'])->name('users.deactivate');
    Route::put('/users/{id}/activate', [UserController::class, 'activate'])->name('users.activate');

    // Gestión de categorías
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::put('/categories/{category}/deactivate', [CategoryController::class, 'deactivate'])->name('categories.deactivate');
    Route::put('/categories/{category}/activate', [CategoryController::class, 'activate'])->name('categories.activate');
});


// Rutas para Vendedor
Route::middleware(['auth', 'verified'])->prefix('seller')->name('seller.')->group(function () {
    Route::get('/dashboard', [SellerController::class, 'index'])->name('dashboard');
});


// Rutas compartidas entre Admin y Vendedor 
Route::middleware(['auth', 'verified'])->group(function () {

    // Gestión de clientes
    Route::get('/clients', [ClientController::class, 'index'])->name('client.index');
    Route::get('/clients/create', [ClientController::class, 'create'])->name('client.create');
    Route::post('/clients', [ClientController::class, 'store'])->name('client.store');
    Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])->name('client.edit');
    Route::put('/clients/{client}', [ClientController::class, 'update'])->name('client.update');
    Route::put('/clients/{client}/deactivate', [ClientController::class, 'deactivate'])->name('client.deactivate');
    Route::put('/clients/{client}/activate', [ClientController::class, 'activate'])->name('client.activate');
});


// Perfil del usuario autenticado
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
