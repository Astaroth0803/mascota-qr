<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        try {
            $user = $request->user();

            // Rellenar con los datos validados
            $user->fill($request->validated());

            // Si el correo electrónico ha sido modificado, establecemos la verificación como nula
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }

            // Guardamos los cambios
            $user->save();

            // Registrar la actualización en el log
            Log::info('Perfil actualizado', ['user_id' => $user->id]);

            return Redirect::route('profile.edit')->with('status', 'profile-updated');
        } catch (\Exception $e) {
            Log::error('Error al actualizar el perfil', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);
            return Redirect::route('profile.edit')->with('error', 'Ocurrió un error al actualizar el perfil.');
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validar la contraseña antes de eliminar la cuenta
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        try {
            $user = $request->user();

            // Cerrar sesión y eliminar el usuario
            Auth::logout();

            // Registrar la eliminación del usuario en los logs
            Log::info('Usuario eliminado', ['user_id' => $user->id]);

            // Eliminar el usuario
            $user->delete();

            // Invalidar sesión y regenerar token CSRF
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')->with('status', 'account-deleted');
        } catch (\Exception $e) {
            Log::error('Error al eliminar la cuenta', [
                'user_id' => $request->user()->id,
                'error' => $e->getMessage(),
            ]);
            return Redirect::route('profile.edit')->with('error', 'Ocurrió un error al eliminar la cuenta.');
        }
    }
}
