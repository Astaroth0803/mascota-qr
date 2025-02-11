<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ContactForm;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\SolicitudController;

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

// Ruta para registrar una solicitud (pública)
// El formulario en /mascotaqr enviará los datos a este endpoint,
// y se guardarán en la tabla "solicitudes" a través del método store de SolicitudController.
Route::post('/mascotaqr', [SolicitudController::class, 'store'])->name('solicitudes.store');

// Authenticated routes (require login)
Route::middleware('auth')->group(function () {
    // Ruta base para el dashboard (redirige según el rol)
    Route::get('/dashboard', function () {
        if (Auth::user()->hasRole('cliente_qr')) {
            return redirect()->route('dashboard.cliente');
        } elseif (Auth::user()->hasRole('administrador')) {
            return redirect()->route('dashboard.administrador');
        }
        return redirect('/');
    })->name('dashboard');

    // Rutas del dashboard para clientes (solo para clientes_qr)
    Route::prefix('dashboard/cliente')->middleware('role:cliente_qr')->group(function () {
        // Vista principal para clientes
        Route::get('/', [PetController::class, 'dashboardCliente'])->name('dashboard.cliente');
        // Rutas para registrar mascotas (cliente)
        Route::get('/registrar-mascota', [PetController::class, 'create'])->name('registrar.mascota');
    });

    // Rutas del dashboard para administradores (solo para administradores)
    Route::prefix('dashboard/administrador')->middleware('role:administrador')->group(function () {
        // Vista principal para administradores
        Route::get('/', [PetController::class, 'adminDashboard'])->name('dashboard.administrador');

          // Ruta para mostrar todas las solicitudes
    Route::get('solicitudes', [SolicitudController::class, 'index'])->name('dashboard.solicitudes');
    
    // Ruta para aceptar solicitudes
       // Ruta para aceptar solicitudes
       Route::patch('solicitudes/accept/{id}', [SolicitudController::class, 'accept'])->name('solicitudes.accept');

       // Ruta para rechazar solicitudes
       Route::delete('solicitudes/reject/{id}', [SolicitudController::class, 'reject'])->name('solicitudes.reject');
});

    // Rutas de perfil (para todos los usuarios autenticados)
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});

// Rutas de registro (para invitados, si no se manejan ya en auth.php)
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
});

// Ruta de prueba para verificar el rol 'administrador'
Route::get('/test-role', function () {
    return 'Tienes el rol de administrador';
})->middleware('role:administrador');
