<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log; // Para registrar los errores

class ContactForm extends Controller
{
    public function store(Request $request)
    {
        // Validación de los datos
        $validated = $request->validate([
            'email' => 'required|email|max:255',
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        try {
            // Enviar el correo
            Mail::raw($validated['message'], function ($mail) use ($validated) {
                $mail->from($validated['email']);
                $mail->to('elawebapp@gmail.com')
                     ->subject($validated['subject']);
            });

            // Redirigir con mensaje de éxito
            return redirect()->route('contactanos')->with('success', '¡Mensaje enviado con éxito!');

        } catch (\Exception $e) {
            // Registrar el error
            Log::error('Error al enviar el correo de contacto: ' . $e->getMessage());

            // Redirigir con mensaje de error
            return redirect()->route('contactanos')->with('error', 'Hubo un problema al enviar el mensaje. Intenta de nuevo.');
        }
    }
}
