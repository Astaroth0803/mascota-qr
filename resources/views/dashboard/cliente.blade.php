<x-app-layout>
    @can('ver_mascotas')
        <x-slot name="header">
            <h2>{{ __('Dashboard - Pet Management') }}</h2>
        </x-slot>

        <br>
        @if (session('success'))
        <div class="bg-green-500 text-white p-4 rounded mb-4">
            {{ session('success') }}
        </div>
        @endif

        @if (isset($error))
            <div class="bg-red-500 text-white p-2 rounded mb-4">
                {{ $error }}
            </div>
        @endif

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
            <tbody style="text-align: center">
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
                            <!-- Botón de "Ver más" o "Editar" -->
                            <a href="{{ route('dashboard.show', $pet->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Ver</a>

                            <a href="{{ route('dashboard.edit', $pet->id) }}" class="bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">Editar</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p>No tienes permiso para ver las mascotas.</p>
        </div>
    @endcan

    <br>
    <!-- Botón para agregar mascota -->
    <div style="margin-left: 0.3rem"  class="mb-4">
        <a href="{{ route('pets.create') }}" 
           class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">
            + Agregar Mascota
        </a>
    </div>

    <x-sidebar />
</x-app-layout>
