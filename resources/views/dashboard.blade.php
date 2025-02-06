<!-- resources/views/dashboard.blade.php -->

<x-app-layout>
    <x-slot name="header">
        <h2>{{ __('Dashboard - Pet Management') }}</h2>
    </x-slot>

    <x-formulario_mascotas /> 
<br>
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
                        <!-- Ejemplo de botón para editar -->
                        <a href="{{ route('pet.edit', $pet->id) }}" 
                           class="text-indigo-500 hover:underline">Editar</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-app-layout>