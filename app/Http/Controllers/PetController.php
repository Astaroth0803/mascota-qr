<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Pet;
use App\Models\Payment;
use App\Services\PaymentService;
use App\PaymentMethods\YappyPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    protected $paymentService;

    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

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
            'nombre_owner' => 'required|string|max:255',
            'apellido_owner' => 'required|string|max:255',
            'telefono_owner' => 'required|string|max:15',
            'correo_owner' => 'required|email|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Crear la mascota y asociarla al usuario
            $pet = Pet::create([
                'nombre' => $validated['nombre'],
                'especie' => $validated['especie'],
                'raza' => $validated['raza'],
                'edad' => $validated['edad'],
                'sexo' => $validated['sexo'],
                'nombre_owner' => $validated['nombre_owner'],
                'apellido_owner' => $validated['apellido_owner'],
                'telefono_owner' => $validated['telefono_owner'],
                'correo_owner' => $validated['correo_owner'],
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
            $paymentResult = $this->paymentService->processPayment(100.00, [
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

            DB::commit();

            return $pet;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error al crear mascota y procesar pago', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Hubo un error al procesar la solicitud.'], 500);
        }
    }

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

    public function adminDashboard()
    {
        $pets = Pet::with('payment')->get();
        return view('dashboard.administrador', compact('pets'));
    }

    public function showPendingRequests(Request $request)
    {
        $query = Pet::with('payment');
    
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nombre', 'like', "%{$search}%")
                  ->orWhere('nombre_owner', 'like', "%{$search}%")
                  ->orWhere('apellido_owner', 'like', "%{$search}%");
            });
        }
    
        if ($request->has('status')) {
            $status = $request->input('status');
            $query->whereHas('payment', function ($q) use ($status) {
                $q->where('status', $status);
            });
        }

        $pendingCount = Pet::whereHas('payment', function ($query) {
            $query->where('status', 'pending');
        })->count();

        $pets = $query->paginate(10);

        return view('dashboard.solicitudes', compact('pets', 'pendingCount'));
    }
    public function create()
{
    return view('dashboard.create');
}
public function store(Request $request)
{
    // Validación de los campos
    $validated = $request->validate([
        'nombre' => 'required|string|max:255',
        'especie' => 'required|string|max:255',
        'raza' => 'required|string|max:255',
        'edad' => 'required|integer|min:0',
        'sexo' => 'required|string|in:Macho,Hembra',
        'vaccine_file' => 'nullable|file|mimes:pdf|max:10240',  // El archivo PDF de vacunas (opcional)
    ]);

    // Guardar el archivo de vacunas si se ha subido
    $vaccineFilePath = null;
    if ($request->hasFile('vaccine_file')) {
        $vaccineFilePath = $request->file('vaccine_file')->store('vaccines', 'public');
    }

    // Crear la mascota
    $pet = new Pet();
    $pet->user_id = Auth::id(); // Asociar al usuario autenticado
    $pet->nombre = $validated['nombre'];
    $pet->especie = $validated['especie'];
    $pet->raza = $validated['raza'];
    $pet->edad = $validated['edad'];
    $pet->sexo = $validated['sexo'];
    $pet->vaccine_file = $vaccineFilePath;
    $pet->save();

    // Redirigir al dashboard del cliente con un mensaje de éxito
    return redirect()->route('dashboard.cliente')->with('success', 'Mascota registrada exitosamente.');
}
public function show($id)
{
    // Obtener la mascota por su id
    $pet = Pet::findOrFail($id);

    // Retornar la vista con la mascota
    return view('dashboard.show', compact('pet'));
}

// Método para mostrar el formulario de edición
public function edit($id)
{
    $pet = Pet::findOrFail($id);
    return view('dashboard.edit', compact('pet'));
}

// Método para actualizar la mascota
public function update(Request $request, $id)
{
    $pet = Pet::findOrFail($id);

    // Validación de los datos
    $request->validate([
        'nombre' => 'required|string|max:255',
        'especie' => 'required|string|max:255',
        'raza' => 'required|string|max:255',
        'edad' => 'required|integer',
        'sexo' => 'required|string|max:255',
        'vaccine_file' => 'nullable|file|mimes:pdf|max:2048',
    ]);

    // Actualizar los datos de la mascota
    $pet->nombre = $request->nombre;
    $pet->especie = $request->especie;
    $pet->raza = $request->raza;
    $pet->edad = $request->edad;
    $pet->sexo = $request->sexo;

    // Subir el archivo de vacunas si se proporciona
    if ($request->hasFile('vaccine_file')) {
        $path = $request->file('vaccine_file')->store('vacunas', 'public');
        $pet->vaccine_file = $path;
    }

    $pet->save();

    // Redirigir con mensaje de éxito
    return redirect()->route('pets.show', $pet->id)->with('success', 'La mascota se ha actualizado correctamente.');
}


}
