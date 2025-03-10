<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use App\Models\Pet;
use App\Models\Payment;
use App\Services\PaymentService;
use App\PaymentMethods\YappyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PetController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    // Método para crear la mascota, mantenemos el mismo
    public function createPet(Request $request, $userId)
    {
        // Validar los datos del formulario
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'especie' => 'required|string|max:255',
            'raza' => 'required|string|max:255',
            'edad' => 'required|string|max:255',
            'sexo' => 'required|string|max:255',
            'id_pago_yappy' => 'required|string|max:255',
        ]);

        // Crear la mascota y asociarla al usuario
        $pet = Pet::create([
            'nombre' => $validated['nombre'],
            'especie' => $validated['especie'],
            'raza' => $validated['raza'],
            'edad' => $validated['edad'],
            'sexo' => $validated['sexo'],
            'nombre_owner' => $request->input('nombre_owner'),
            'apellido_owner' => $request->input('apellido_owner'),
            'telefono_owner' => $request->input('telefono_owner'),
            'correo_owner' => $request->input('correo_owner'),
            'user_id' => $userId,
        ]);

        // Registrar la creación de la mascota
        Log::info('Mascota creada', [
            'id' => $pet->id,
            'nombre' => $pet->nombre,
            'dueño' => $pet->nombre_owner . ' ' . $pet->apellido_owner,
        ]);

        // Procesar el pago con Yappy
        $this->paymentService->setPaymentMethod(new YappyPayment());
        $paymentResult = $this->paymentService->processPayment(100.00, [ // Monto fijo para el ejemplo
            'payment_id' => $validated['id_pago_yappy'],
        ]);

        // Registrar el resultado del pago
        Log::info('Pago procesado', $paymentResult);

        // Crear el registro de pago
        $payment = Payment::create([
            'pet_id' => $pet->id,
            'payment_method' => 'yappy',
            'payment_id' => $validated['id_pago_yappy'],
            'status' => $paymentResult['success'] ? Payment::STATUS_VERIFIED : Payment::STATUS_REJECTED,
        ]);

        // Registrar la creación del pago
        Log::info('Pago creado', [
            'id' => $payment->id,
            'mascota_id' => $payment->pet_id,
            'payment_id' => $payment->payment_id,
            'status' => $payment->status,
        ]);

        return $pet;
    } 

    // Método para el dashboard del cliente
    public function dashboardCliente()
    {
        // Verificar si el usuario tiene el permiso 'ver_mascotas'
        if (!Auth::user()->can('ver_mascotas')) {
            return redirect()->route('dashboard')->with('error', 'No tienes permiso para ver tus mascotas.');
        }
    // Obtener el usuario actual
    $user = Auth::user();

    // Obtener las mascotas asociadas al cliente por ID y correo
    $pets = Pet::with('payment')
        ->where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                  ->orWhere('correo_owner', $user->email);
        })
        ->get();
        return view('dashboard.cliente', compact('pets'));
    }

    // Método para el dashboard del administrador
    public function adminDashboard()
    {
        // Solo los administradores pueden acceder aquí
        $pets = Pet::with('payment')->get(); // Aquí puedes mostrar todas las mascotas, o filtrar según sea necesario

        return view('dashboard.administrador', compact('pets'));
    }

    // Método para mostrar las solicitudes pendientes (solo para administradores)
    public function showPendingRequests(Request $request)
    {
        // Obtener las solicitudes con filtros
        $query = Pet::with('payment');
    
        // Filtrar por nombre de mascota o dueño
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('nombre_owner', 'like', "%{$search}%")
                  ->orWhere('apellido_owner', 'like', "%{$search}%");
            });
        }
    
        // Filtrar por estado del pago
        if ($request->has('status')) {
            $status = $request->input('status');
            $query->whereHas('payment', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }
    
        // Obtener las solicitudes pendientes
        $pendingCount = Pet::whereHas('payment', function ($query) {
            $query->where('status', 'pending');
        })->count();
    
        // Paginar los resultados
        $pets = $query->paginate(10);
    
        return view('dashboard.solicitudes', compact('pets', 'pendingCount'));
    }
    
}

