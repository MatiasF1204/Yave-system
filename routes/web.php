<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SellerController;
use Illuminate\Support\Facades\Route;

// Bienvenida
Route::get('/', function () {
    return view('welcome');
});

// 🔐 Redirección general al dashboard (veremos cómo dirigir según rol)
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

    // Por si no tiene rol definido
    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas para administrador
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Rutas para vendedor
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/seller/dashboard', [SellerController::class, 'index'])->name('seller.dashboard');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
