@extends('layouts.dashboard')
@include('layouts.navigation')
@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-2xl font-bold mb-6">Solicitudes Pendientes</h1>

        <!-- Formulario de filtrado -->
        <form action="{{ route('dashboard.solicitudes') }}" method="GET" class="mb-6 bg-white p-4 rounded-lg shadow">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" class="p-2 border rounded" placeholder="Buscar por nombre de mascota o dueño" value="{{ request('search') }}">
                <select name="status" class="p-2 border rounded">
                    <option value="">Todos los estados</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente</option>
                    <option value="verified" {{ request('status') == 'verified' ? 'selected' : '' }}>Verificado</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded">Filtrar</button>
                <a href="{{ route('dashboard.solicitudes') }}" class="bg-gray-500 text-white p-2 rounded text-center">Limpiar</a>
            </div>
        </form>

        <!-- Tabla de solicitudes -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Nombre de la mascota</th>
                        <th class="p-3 text-left">Dueño</th>
                        <th class="p-3 text-left">ID de pago</th>
                        <th class="p-3 text-left">Estado</th>
                        <th class="p-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($solicitudes as $solicitud)
                        <tr class="border-b">
                            <td class="p-3">{{ $solicitud->id }}</td>
                            <td class="p-3">{{ $solicitud->nombre }}</td>
                            <td class="p-3">{{ $solicitud->nombre_owner }} {{ $solicitud->apellido_owner }}</td>
                            <td class="p-3">{{ $solicitud->id_pago_yappy }}</td>
                            <td class="p-3">
                                <span class="px-2 py-1 text-sm rounded-full 
                                    {{ $solicitud->status == 'verified' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ $solicitud->status == 'verified' ? 'Verificado' : 'Pendiente' }}
                                </span>
                            </td>
                            <td class="p-3">
                                <form action="{{ route('solicitudes.accept', $solicitud->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('PATCH') <!-- Esto indica que el formulario usará el método PATCH -->
                                    <button type="submit" class="bg-green-500 text-white p-2 rounded">Aceptar</button>
                                </form>
                                
                                <form action="{{ route('solicitudes.reject', $solicitud->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white p-2 rounded" onclick="return confirm('¿Estás seguro de rechazar esta solicitud?')">Rechazar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $solicitudes->appends(request()->query())->links() }}
        </div>
    </div>
@endsection
