<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ContactForm;
use App\Http\Controllers\PetController;
use App\Http\Controllers\TiendaController;
use App\Http\Controllers\MascotaController;
use App\Http\Controllers\AuthController;


// Static page
Route::view('/', 'home')->name('home');
Route::view('/comunidad', 'comunidad')->name('comunidad');
Route::view('/tienda', 'tienda')->name('tienda');
Route::view('/mascotaqr', 'mascotaqr')->name('mascotaqr');
Route::view('/about', 'about')->name('about');
Route::get('/contactanos', function () {
    return view('contactanos');
})->name('contactanos');

Route::post('/contactanos', [ContactForm::class, 'store'])->name('contactanos.store');


// Dashboard and edit profile
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
require __DIR__.'/auth.php';


// Pet dashboard routes
Route::prefix('dashboard')->middleware('auth')->group(function() {
    // Ruta para mostrar el dashboard principal
    Route::get('/', [PetController::class, 'dashboard'])->name('dashboard');

    // Ruta para registrar una mascota
    Route::post('/pet/store', [PetController::class, 'store'])->name('pet.store');

    // Ruta para editar los datos de una mascota
    Route::get('/pets/{id}/edit', [PetController::class, 'edit'])->name('pet.edit');

    // Ruta para actualizar los datos de la mascota
    Route::put('/pets/{id}', [PetController::class, 'update'])->name('pet.update');
    

    Route::get('/tienda', [TiendaController::class, 'index'])->name('tienda');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.submit');
    Route::get('/registrar-mascota', [MascotaController::class, 'create'])->name('registrar.mascota');
});