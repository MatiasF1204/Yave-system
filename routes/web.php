<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\Seller\DashboardController as SellerDashboardController;
use Illuminate\Support\Facades\Route;


// ==========================================
// PÁGINA DE BIENVENIDA
// ==========================================

Route::get('/', function () {
    return view('welcome');
});


// ==========================================
// REDIRECCIÓN AL DASHBOARD SEGÚN EL ROL
// ==========================================

Route::get('/dashboard', function () {
    $user = auth()->user();

    if (!$user) {
        return redirect()->route('login');
    }

    // Redirige al dashboard segun el rol
    if ($user->role && $user->role->name === 'Administrador') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role && $user->role->name === 'Vendedor') {
        return redirect()->route('seller.dashboard');
    }

    return redirect()->route('login');
})->middleware(['auth', 'verified'])->name('dashboard');


// ==========================================
// RUTAS EXCLUSIVAS DEL ADMINISTRADOR
// ==========================================

Route::middleware(['auth', 'verified', 'role:Administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // ------------------------------------------
        // Dashboard
        // ------------------------------------------

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');


        // ------------------------------------------
        // Gestión de Usuarios
        // ------------------------------------------

        Route::get('/users', [UserController::class, 'index'])
            ->name('users.index');

        Route::get('/users/create', [UserController::class, 'create'])
            ->name('users.create');

        Route::post('/users', [UserController::class, 'store'])
            ->name('users.store');

        Route::get('/users/{user}/edit', [UserController::class, 'edit'])
            ->name('users.edit');

        Route::put('/users/{user}', [UserController::class, 'update'])
            ->name('users.update');

        Route::put('/users/{id}/deactivate', [UserController::class, 'deactivate'])
            ->name('users.deactivate');

        Route::put('/users/{id}/activate', [UserController::class, 'activate'])
            ->name('users.activate');


        // ------------------------------------------
        // Gestión de Categorías
        // ------------------------------------------

        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');

        Route::get('/categories/create', [CategoryController::class, 'create'])
            ->name('categories.create');

        Route::post('/categories', [CategoryController::class, 'store'])
            ->name('categories.store');

        Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])
            ->name('categories.edit');

        Route::put('/categories/{category}', [CategoryController::class, 'update'])
            ->name('categories.update');

        Route::put('/categories/{category}/deactivate', [CategoryController::class, 'deactivate'])
            ->name('categories.deactivate');

        Route::put('/categories/{category}/activate', [CategoryController::class, 'activate'])
            ->name('categories.activate');


        // ------------------------------------------
        // Gestión de Métodos de Pago
        // ------------------------------------------

        Route::get('/payment-methods', [PaymentMethodController::class, 'index'])
            ->name('payment_methods.index');

        Route::get('/payment-methods/create', [PaymentMethodController::class, 'create'])
            ->name('payment_methods.create');

        Route::post('/payment-methods', [PaymentMethodController::class, 'store'])
            ->name('payment_methods.store');

        Route::get('/payment-methods/{payment_method}/edit', [PaymentMethodController::class, 'edit'])
            ->name('payment_methods.edit');

        Route::put('/payment-methods/{payment_method}', [PaymentMethodController::class, 'update'])
            ->name('payment_methods.update');

        Route::put('/payment-methods/{payment_method}/deactivate', [PaymentMethodController::class, 'deactivate'])
            ->name('payment_methods.deactivate');

        Route::put('/payment-methods/{payment_method}/activate', [PaymentMethodController::class, 'activate'])
            ->name('payment_methods.activate');


        // ------------------------------------------
        // Gestión de Productos - ADMIN
        // ------------------------------------------

        Route::get('/products/create', [ProductController::class, 'create'])
            ->name('products.create');

        Route::post('/products', [ProductController::class, 'store'])
            ->name('products.store');

        Route::get('/products/{product}/edit', [ProductController::class, 'edit'])
            ->name('products.edit');

        Route::put('/products/{product}', [ProductController::class, 'update'])
            ->name('products.update');

        Route::put('/products/{product}/deactivate', [ProductController::class, 'deactivate'])
            ->name('products.deactivate');

        Route::put('/products/{product}/activate', [ProductController::class, 'activate'])
            ->name('products.activate');
    });


// ==========================================
// PRODUCTOS - ADMINISTRADOR Y VENDEDOR
// ==========================================

Route::middleware(['auth', 'verified', 'role:Administrador,Vendedor'])
    ->group(function () {

        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');
            
        Route::get('/products/{product}', [ProductController::class, 'show'])
            ->name('products.show');
    });


// ==========================================
// RUTAS DE VENDEDOR
// ==========================================

Route::middleware(['auth', 'verified', 'role:Vendedor'])
    ->prefix('seller')
    ->name('seller.')
    ->group(function () {

        Route::get('/dashboard', [SellerDashboardController::class, 'index'])
            ->name('dashboard');
    });

// ==========================================
// CLIENTES - ADMINISTRADOR Y VENDEDOR
// ==========================================

Route::middleware(['auth', 'verified', 'role:Administrador,Vendedor'])
    ->group(function () {

        Route::get('/clients', [ClientController::class, 'index'])
            ->name('clients.index');

        Route::get('/clients/create', [ClientController::class, 'create'])
            ->name('clients.create');

        Route::post('/clients', [ClientController::class, 'store'])
            ->name('clients.store');

        Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])
            ->name('clients.edit');

        Route::put('/clients/{client}', [ClientController::class, 'update'])
            ->name('clients.update');

        Route::put('/clients/{client}/deactivate', [ClientController::class, 'deactivate'])
            ->name('clients.deactivate');

        Route::put('/clients/{client}/activate', [ClientController::class, 'activate'])
            ->name('clients.activate');
    });

// ==========================================
// PERFIL - SOLO ADMINISTRADOR
// ==========================================

Route::middleware(['auth', 'verified', 'role:Administrador'])
    ->group(function () {

        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');
    });


require __DIR__ . '/auth.php';
