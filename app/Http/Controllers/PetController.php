<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Pet;
use Illuminate\Support\Facades\Storage;
class PetController extends Controller
{
        // Mostrar el dashboard con la lista de mascotas
    public function dashboard()
    {
        $pets = Pet::paginate(10); // 10 mascotas por página
        return view('dashboard', compact('pets'));
    }

    // Método para registrar una nueva mascota
    public function store(Request $request)
    {
        return DB::transaction(function () use ($request) {
        // Validar los datos recibidos
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'required|string|max:255',
            'edad' => 'required|string|max:255',
            'sexo' => 'required|string|max:255',
            'vaccine_file' => 'nullable|file|mimes:pdf|max:4096', // Validar solo archivos PDF
        ]);
    
        // Subir archivo PDF si está presente
        $filePath = null;
        if ($request->hasFile('vaccine_file')) {
            $filePath = $request->file('vaccine_file')->store('vaccines', 'public'); // Guardar en storage/app/public/vaccines
        }
    
        // Crear y guardar la nueva mascota
        Pet::create([
            'nombre' => $validated['nombre'],
            'especie' => $validated['especie'],
            'raza' => $validated['raza'],
            'edad' => $validated['edad'],
            'sexo' => $validated['sexo'],
            'vaccine_file' => $filePath, // Guardar la ruta del archivo en la base de datos
        ]);
    
        // Redirigir al dashboard con mensaje de éxito
      
        return redirect()->route('dashboard')->with('success', 'Archivo subido y mascota registrada con éxito.');
    });
    }
    

    // Método para actualizar un campo específico de la mascota
    public function update(Request $request, Pet $pet)
    {
        $pet = Pet::findOrFail($pet);
        
        $validated = $request->validate([
            'nombre' => 'sometimes|string|max:255',
            'especie' => 'sometimes|string|max:255',
            'raza' => 'sometimes|string|max:255',
            'edad' => 'sometimes|string|max:255',
            'sexo' => 'sometimes|string|max:255',
            'vaccine_file' => 'nullable|file|mimes:pdf|max:4096',
        ], [
            // Mensajes personalizados para todos los campos
            'nombre.required' => 'El nombre es obligatorio',
            'especie.required' => 'La especie es obligatoria',
            // ... otros mensajes
        ]);
    
        // Manejo de archivo
        if ($request->hasFile('vaccine_file')) {
            // Eliminar archivo antiguo si existe
            if ($pet->vaccine_file) {
                Storage::disk('public')->delete($pet->vaccine_file);
            }
            
            $filePath = $request->file('vaccine_file')->store('vaccines', 'public');
            $validated['vaccine_file'] = $filePath;
        }
    
        $pet->update($validated);
    
        // Respuesta consistente para API y navegador
        return request()->expectsJson() 
             ? response()->json(['success' => true])
             : redirect()->back()->with('success', 'Mascota actualizada');
    }
}
