<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DailyControlController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\SaleReportController;
use App\Http\Controllers\StockReportController;
use Illuminate\Support\Facades\Route;


// ======================================================
// 1. INICIO
// ======================================================

Route::get('/', function () {
    return view('welcome');
});


// ======================================================
// 2. REDIRECCIÓN SEGÚN EL ROL
// ======================================================

Route::get('/dashboard', function () {

    $user = auth()->user();

    if ($user->role?->name === 'Administrador') {
        return redirect()->route('admin.dashboard');
    }

    if ($user->role?->name === 'Vendedor') {
        return redirect()->route('sales.create');
    }

    abort(403, 'No tenés permisos para acceder al sistema.');

})->middleware(['auth', 'verified'])->name('dashboard');


// ======================================================
// 3. RUTAS EXCLUSIVAS DEL ADMINISTRADOR
// ======================================================

Route::middleware(['auth', 'verified', 'role:Administrador'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // Reportes
        Route::prefix('reports')->name('reports.')->group(function () {

            Route::get('/sales', [SaleReportController::class, 'index'])
                ->name('sales');

            Route::get('/stock', [StockReportController::class, 'index'])
                ->name('stock');
        });


        // Gestión de usuarios
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


        // Gestión de categorías
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


        // Gestión de productos
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


        // Gestión de medios de pago
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

        // Gestión de clientes: desactivar y activar
        Route::put('/clients/{client}/deactivate', [ClientController::class, 'deactivate'])
            ->name('clients.deactivate');

        Route::put('/clients/{client}/activate', [ClientController::class, 'activate'])
            ->name('clients.activate');


        // Perfil
        Route::get('/profile', [ProfileController::class, 'edit'])
            ->name('profile.edit');

        Route::patch('/profile', [ProfileController::class, 'update'])
            ->name('profile.update');

        Route::delete('/profile', [ProfileController::class, 'destroy'])
            ->name('profile.destroy');
    });


// ======================================================
// 4. RUTAS COMPARTIDAS: ADMINISTRADOR Y VENDEDOR
// ======================================================

Route::middleware(['auth', 'verified', 'role:Administrador,Vendedor'])
    ->group(function () {

        // CLIENTES
        Route::get('/clients', [ClientController::class, 'index'])
            ->name('clients.index');

        Route::get('/clients/create', [ClientController::class, 'create'])
            ->name('clients.create');

        Route::post('/clients', [ClientController::class, 'store'])
            ->name('clients.store');

        Route::get('/clients/{client}', [ClientController::class, 'show'])
            ->name('clients.show');

        Route::get('/clients/{client}/edit', [ClientController::class, 'edit'])
            ->name('clients.edit');

        Route::put('/clients/{client}', [ClientController::class, 'update'])
            ->name('clients.update');


        // CATEGORÍAS
        Route::get('/categories', [CategoryController::class, 'index'])
            ->name('categories.index');


        // PRODUCTOS
        Route::get('/products', [ProductController::class, 'index'])
            ->name('products.index');

        Route::get('/products/{product}', [ProductController::class, 'show'])
            ->name('products.show');


        // MEDIOS DE PAGO
        Route::get('/payment-methods', [PaymentMethodController::class, 'index'])
            ->name('payment_methods.index');


        // INTRODUCCIÓN DE TICKET Y GESTIÓN DE VENTAS
        Route::prefix('sales')
            ->name('sales.')
            ->controller(SaleController::class)
            ->group(function () {

                // Listado de ventas
                Route::get('/', 'index')
                    ->name('index');

                // Formulario para registrar una venta
                Route::get('/create', 'create')
                    ->name('create');

                // Guardar una venta
                Route::post('/', 'store')
                    ->name('store');

                // Detalle de una venta
                Route::get('/{sale}', 'show')
                    ->whereNumber('sale')
                    ->name('show');

                // Solicitar anulación de una venta
                // La autorización del administrador se valida
                // dentro del método cancel del controlador
                Route::put('/{sale}/cancel', 'cancel')
                    ->whereNumber('sale')
                    ->name('cancel');
            });


        // CONTROL DIARIO DE CAJA
        Route::get('/control-diario', [DailyControlController::class, 'index'])
            ->name('daily-control.index');

    });


require __DIR__ . '/auth.php';