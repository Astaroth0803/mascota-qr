<x-app-layout>
    @can('registrar_mascotas')
        <x-slot name="header">
            <h2>{{ __('Registrar Nueva Mascota') }}</h2>
        </x-slot>

        <form action="{{ route('pets.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre</label>
                <input type="text" name="nombre" id="nombre" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="especie" class="block text-sm font-medium text-gray-700">Especie</label>
                <input type="text" name="especie" id="especie" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="raza" class="block text-sm font-medium text-gray-700">Raza</label>
                <input type="text" name="raza" id="raza" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="edad" class="block text-sm font-medium text-gray-700">Edad</label>
                <input type="number" name="edad" id="edad" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
            </div>

            <div class="mb-4">
                <label for="sexo" class="block text-sm font-medium text-gray-700">Sexo</label>
                <select name="sexo" id="sexo" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm" required>
                    <option value="Macho">Macho</option>
                    <option value="Hembra">Hembra</option>
                </select>
            </div>

            <div class="mb-4">
                <label for="vaccine_file" class="block text-sm font-medium text-gray-700">Vacunas (PDF)</label>
                <input type="file" name="vaccine_file" id="vaccine_file" class="mt-1 block w-full" accept=".pdf">
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition">Registrar Mascota</button>
        </form>
    @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p>No tienes permiso para registrar una mascota.</p>
        </div>
    @endcan
</x-app-layout>
