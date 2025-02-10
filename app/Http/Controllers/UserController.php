<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombre_owner' => 'required|string|max:255',
            'apellido_owner' => 'required|string|max:255',
            'correo_owner' => 'required|email|unique:users,email',
            'telefono_owner' => 'required|string|max:255',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $validated['nombre_owner'] . ' ' . $validated['apellido_owner'],
            'email' => $validated['correo_owner'],
            'password' => Hash::make(Str::random(10)), // Contraseña aleatoria
        ]);

        // Asignar el rol "cliente_qr" al usuario
        $user->assignRole('cliente_qr');

        return [
            'user' => $user,
            'password' => $user->password, // Devolver la contraseña para usarla en el siguiente paso
        ];
    }
}