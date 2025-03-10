{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Dashboard - Pet Management') }}</h2>
    </x-slot>
    <div class="container mx-auto px-4">
        <table class="min-w-full table-auto mt-4">
            <thead>
                <tr>
                    <th class="py-2 px-4 border-b">Nombre</th>
                    <th class="py-2 px-4 border-b">Especie</th>
                    <th class="py-2 px-4 border-b">Raza</th>
                    <th class="py-2 px-4 border-b">Edad</th>
                    <th class="py-2 px-4 border-b">Sexo</th>
                    <th class="py-2 px-4 border-b">Vacunas (PDF)</th>
                    <th class="py-2 px-4 border-b">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pets as $pet)
                    <tr>
                        <td class="py-2 px-4 border-b">{{ $pet->nombre }}</td>
                        <td class="py-2 px-4 border-b">{{ $pet->especie }}</td>
                        <td class="py-2 px-4 border-b">{{ $pet->raza }}</td>
                        <td class="py-2 px-4 border-b">{{ $pet->edad }}</td>
                        <td class="py-2 px-4 border-b">{{ $pet->sexo }}</td>
                        <td class="py-2 px-4 border-b">
                            @if ($pet->vaccine_file)
                                <a href="{{ asset('storage/' . $pet->vaccine_file) }}" 
                                   target="_blank" 
                                   class="text-blue-500 hover:underline">
                                    Ver PDF
                                </a>
                            @else
                                <span class="text-gray-500 italic">No adjuntado</span>
                            @endif
                        </td>
                        <td class="py-2 px-4 border-b">
                            <!-- Acciones como editar o eliminar pueden ir aquí -->
                            <a href="{{ route('edit.pet', $pet->id) }}" class="text-green-500 hover:underline">Editar</a>
                            <form action="{{ route('delete.pet', $pet->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline" onclick="return confirm('¿Estás seguro de eliminar esta mascota?')">Eliminar</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</x-app-layout>
<x-footer /> 
