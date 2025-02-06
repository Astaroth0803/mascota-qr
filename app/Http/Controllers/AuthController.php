<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\User; 
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showRegistrationForm(Request $request)
    {
        // Validar parámetros de redirección
        $validated = $request->validate([
            'redirect' => 'sometimes|url',
            'product_id' => 'sometimes|integer',
            'product_type' => 'sometimes|string'
        ]);
    
        return view('auth.register', [
            'redirectTo' => $validated['redirect'] ?? route('registrar.mascota'),
            'product' => [
                'id' => $validated['product_id'] ?? null,
                'type' => $validated['product_type'] ?? null
            ]
        ]);
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|confirmed|min:8',
            'redirect' => 'required|url',
            'product_id' => 'required|integer',
            'product_type' => 'required|string'
        ]);
    
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'temp_product_data' => json_encode([
                'product_id' => $request->product_id,
                'product_type' => $request->product_type
            ])
        ]);
    
        // Autologin
        Auth::login($user);
    
        // Redirección con datos temporales en sesión
        return redirect($request->redirect)
               ->with('product_data', [
                   'id' => $request->product_id,
                   'type' => $request->product_type
               ]);
    }
}