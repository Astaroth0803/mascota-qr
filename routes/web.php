<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContactForm;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;

// Static pages (public access)
Route::view('/', 'home')->name('home');
Route::view('/comunidad', 'comunidad')->name('comunidad');
Route::view('/about', 'about')->name('about');
Route::view('/mascotaqr', 'mascotaqr')->name('mascotaqr');
Route::view('/comprarealizada', 'comprarealizada')->name('comprarealizada');
Route::view('/tienda', 'tienda')->name('tienda');

// Contact form routes (public access)
Route::get('/contactanos', function () {
    return view('contactanos');
})->name('contactanos');

Route::post('/contactanos', [ContactForm::class, 'store'])->name('contactanos.store');

// Authentication routes (from auth.php)
require __DIR__.'/auth.php';

// Ruta para registrar una mascota (pública)
Route::post('/mascotaqr', function (Illuminate\Http\Request $request) {
    // Crear el usuario
    $userData = app(UserController::class)->createUser($request);

    // Crear la mascota asociada al usuario
    $pet = app(PetController::class)->createPet($request, $userData['user']->id);

    return redirect()->route('comprarealizada')->with('success', 'Servicio solicitado correctamente. Un administrador verificará el pago y te enviará los datos de acceso por correo.');
})->name('mascotaqr.store');

// Authenticated routes (require login)
Route::middleware('auth')->group(function () {
    // Ruta base para el dashboard (Redirigir al cliente o admin según el rol)
    Route::get('/dashboard', function () {
        if (Auth::user()->hasRole('cliente_qr')) {
            return redirect()->route('dashboard.cliente');
        } else if (Auth::user()->hasRole('administrador')) {
            return redirect()->route('dashboard.administrador');
        }
        return redirect('/');
    })->name('dashboard');

    // Cliente dashboard routes (solo para clientes_qr)
    Route::prefix('dashboard/cliente')->middleware('role:cliente_qr')->group(function () {
        // Main dashboard view
        Route::get('/', [PetController::class, 'dashboardCliente'])->name('dashboard.cliente');

        // Pet registration routes (solo para clientes_qr)
        Route::get('/registrar-mascota', [PetController::class, 'create'])->name('registrar.mascota');
    });

    // Admin dashboard routes (solo para administradores)
    Route::prefix('dashboard/administrador')->middleware('role:administrador')->group(function () {
        // Main admin dashboard view
        Route::get('/', [PetController::class, 'adminDashboard'])->name('dashboard.administrador');

        // Ver solicitudes
        Route::get('/dashboard/solicitudes', [PetController::class, 'showPendingRequests'])->name('dashboard.solicitudes');

        // Verificar pago
        Route::get('/verificar-pago/{id}', [PaymentController::class, 'verifyPayment'])->name('dashboard.verificar.pago');

        // Rechazar solicitud
        Route::delete('/rechazar-solicitud/{id}', [PaymentController::class, 'rejectRequest'])->name('dashboard.rechazar.solicitud');
    });

    // Profile routes (accesible para todos los usuarios autenticados)
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

// Registration routes (if not already handled by auth.php)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Ruta de prueba para verificar el rol 'administrador'
Route::get('/test-role', function () {
    return 'Tienes el rol de administrador';
})->middleware('role:administrador');
