<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;

class MascotaController extends Controller
{
   // app/Http/Controllers/MascotaController.php
 
public function create()
{
    // Verificar si viene de flujo de compra
    if (!session()->has('product_data')) {
        return redirect()->route('tienda')->with('error', 'Selecciona un producto primero');
    }

    return view('mascotas.registrar', [
        'product' => session('product_data')
    ]);
}

public function store(Request $request)
{
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'especie' => 'required|string|max:255',
        // ... otros campos
        'product_id' => 'required|integer',
        'product_type' => 'required|string'
    ]);

    // Crear mascota
    $mascota = auth()->user()->mascotas()->create($validated);

    // Crear pedido relacionado
    $pedido = auth()->user()->pedidos()->create([
        'product_id' => $validated['product_id'],
        'product_type' => $validated['product_type'],
        'mascota_id' => $mascota->id,
        'status' => 'pendiente'
    ]);

    // Redirigir a pasarela de pago
    return redirect()->route('pago.procesar', $pedido);
}
}
