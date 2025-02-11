<?php
namespace App\Http\Controllers;

use App\Models\Solicitud;
use App\Models\User;
use App\Models\Pet;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsMail;

class SolicitudController extends Controller
{
    // Listar solicitudes (para el administrador)
    public function index(Request $request)
    {
        // Obtener solicitudes con paginación
        $solicitudes = Solicitud::paginate(10);

        // Retornar la vista 'dashboard.solicitudes' con las solicitudes
        return view('dashboard.solicitudes', compact('solicitudes'));
    }

    // Aceptar la solicitud: crea el usuario y la mascota, luego elimina la solicitud
    public function accept($id)
    {
        $solicitud = Solicitud::findOrFail($id);

        // Generar contraseña aleatoria
        $password = Str::random(10);

        // Crear el usuario con los datos del dueño
        $user = User::create([
            'name' => $solicitud->nombre_owner . ' ' . $solicitud->apellido_owner,
            'email' => $solicitud->correo_owner,
            'password' => Hash::make($password),
        ]);

        // Verificar que el usuario se creó correctamente
        if (!$user) {
            return redirect()->route('dashboard.solicitudes')
                ->with('error', 'No se pudo crear el usuario, verifique los datos.');
        }

        // Asignar rol al usuario (si utilizas Spatie o similar)
        $user->assignRole('cliente_qr');

        // Crear la mascota asociada al usuario
        $pet = Pet::create([
            'nombre' => $solicitud->nombre,
            'especie' => $solicitud->especie,
            'raza' => $solicitud->raza,
            'edad' => $solicitud->edad,
            'sexo' => $solicitud->sexo,
            'nombre_owner' => $solicitud->nombre_owner,
            'apellido_owner' => $solicitud->apellido_owner,
            'telefono_owner' => $solicitud->telefono_owner,
            'correo_owner' => $solicitud->correo_owner,
            'user_id' => $user->id,
        ]);

        // Si la creación de la mascota falla
        if (!$pet) {
            return redirect()->route('dashboard.solicitudes')
                ->with('error', 'No se pudo crear la mascota.');
        }

        // Enviar correo con credenciales (opcional)
        Mail::to($solicitud->correo_owner)->send(new UserCredentialsMail($solicitud->correo_owner, $password));

        // Eliminar la solicitud
        $solicitud->delete();

        return redirect()->route('dashboard.solicitudes')
            ->with('success', 'Solicitud aceptada. Usuario y mascota creados.');
    }

    public function store(Request $request)
    {
        // Validación de los datos de entrada
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'required|string|max:255',
            'edad' => 'required|integer',
            'sexo' => 'required|string|max:10',
            'nombre_owner' => 'required|string|max:255',
            'apellido_owner' => 'required|string|max:255',
            'telefono_owner' => 'required|string|max:15',
            'correo_owner' => 'required|email',
            'id_pago_yappy' => 'required|string|max:255',
        ]);

        // Crear la solicitud
        $solicitud = Solicitud::create([
            'nombre' => $validated['nombre'],
            'especie' => $validated['especie'],
            'raza' => $validated['raza'],
            'edad' => $validated['edad'],
            'sexo' => $validated['sexo'],
            'nombre_owner' => $validated['nombre_owner'],
            'apellido_owner' => $validated['apellido_owner'],
            'telefono_owner' => $validated['telefono_owner'],
            'correo_owner' => $validated['correo_owner'],
            'id_pago_yappy' => $validated['id_pago_yappy'],
        ]);

        // Redirigir al usuario con un mensaje de éxito
        return redirect()->route('comprarealizada')->with('success', 'Solicitud recibida correctamente. Un administrador verificará el pago.');
    }
}
