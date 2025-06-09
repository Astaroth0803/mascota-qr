<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PetController;
use App\Http\Controllers\ContactForm;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SolicitudController;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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
Route::post('/mascotaqr', [SolicitudController::class, 'store'])->name('solicitudes.store');

// Authenticated routes (require login)
Route::middleware('auth')->group(function () {
    // Ruta base para el dashboard (redirige según el rol)
    Route::get('/dashboard', function () {
        /** @var \App\Models\User */
        $user = Auth::user();

        if ($user->hasRole('cliente_qr')) {
            return redirect()->route('dashboard.cliente.index');
        } elseif ($user->hasAnyRole(['administrador', 'super_admin'])) {
            return redirect()->route('dashboard.administrador');
        }
        return redirect('/');
    })->name('dashboard');

    // Rutas del dashboard para clientes (solo para clientes_qr)
    Route::middleware('role:cliente_qr')->prefix('dashboard/cliente')->name('dashboard.cliente.')->group(function () {
        Route::get('/', [PetController::class, 'dashboardCliente'])->name('index');
        Route::get('/registrar-mascota', [PetController::class, 'create'])->name('registrar.mascota');
        Route::get('/mascotas/{id}', [PetController::class, 'show'])->name('mascotas.show');
        Route::get('/mascotas/{pet}/edit', [PetController::class, 'edit'])->name('mascotas.edit');
        Route::put('/mascotas/{pet}', [PetController::class, 'update'])->name('mascotas.update');
        Route::put('/mascotas/{pet}/image', [PetController::class, 'updateImage'])->name('mascotas.update-image');
        Route::get('/mascotas/{pet}/vaccination-history', [PetController::class, 'showVaccinationHistory'])->name('mascotas.vaccination-history');
        Route::post('/mascotas/{pet}/vaccination-records', [PetController::class, 'storeVaccinationRecord'])->name('mascotas.vaccination-records.store');
        Route::post('/solicitudes/store-pet', [SolicitudController::class, 'storePetRequest'])->name('solicitudes.store-pet');
    });

    // Rutas del dashboard para administradores (solo para administradores y super_admins)
    Route::prefix('dashboard/administrador')->middleware('role:administrador|super_admin')->group(function () {
        Route::get('/', [PetController::class, 'adminDashboard'])->name('dashboard.administrador');

        // Rutas para solicitudes
        Route::get('solicitudes', [SolicitudController::class, 'index'])->name('dashboard.solicitudes');
        Route::patch('solicitudes/accept/{id}', [SolicitudController::class, 'accept'])->name('solicitudes.accept');
        Route::delete('solicitudes/reject/{id}', [SolicitudController::class, 'reject'])->name('solicitudes.reject');
        Route::get('solicitudes/{id}', [SolicitudController::class, 'show'])->name('solicitudes.show');

        // Rutas para gestionar usuarios
        Route::prefix('usuarios')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('dashboard.usuarios');
            Route::get('create', [UserController::class, 'create'])->name('usuarios.create');
            Route::post('/', [UserController::class, 'store'])->name('usuarios.store');
            Route::get('{id}/edit', [UserController::class, 'edit'])->name('usuarios.edit');
            Route::patch('{id}', [UserController::class, 'update'])->name('usuarios.update');
            Route::delete('{id}', [UserController::class, 'destroy'])->name('usuarios.destroy');

            // Reset password routes
            Route::post('{id}/reset-password', [UserController::class, 'resetPassword'])->name('usuarios.resetPassword');
            Route::get('{id}/edit-password', [UserController::class, 'editPassword'])->name('usuarios.editPassword');        
            Route::patch('{id}/edit-password', [UserController::class, 'updatePassword'])->name('usuarios.updatePassword');

            // Role and permissions routes
            Route::get('{id}/edit-roles', [UserController::class, 'editRoles'])->name('usuarios.editRoles');
            Route::patch('{id}/edit-roles', [UserController::class, 'updateRoles'])->name('usuarios.updateRoles');
            Route::get('{id}/edit-permissions', [UserController::class, 'editPermissions'])->name('usuarios.editPermissions');
            Route::patch('{id}/edit-permissions', [UserController::class, 'updatePermissions'])->name('usuarios.updatePermissions');

            // Profile edit route
            Route::get('{id}/edit-profile', [UserController::class, 'editProfile'])->name('usuarios.editProfile');
        });
    });

    // Rutas de perfil (para todos los usuarios autenticados)
    Route::prefix('profile')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

});
