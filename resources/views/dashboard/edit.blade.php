<x-app-layout>
    @can('editar_mascotas')
        <x-slot name="header">
            <h2>{{ __('Editar Información de la Mascota') }}</h2>
        </x-slot>

        <div class="bg-white p-4 rounded shadow-md">
            <form action="{{ route('pets.update', $pet->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label for="nombre" class="block font-bold">Nombre</label>
                    <input type="text" id="nombre" name="nombre" value="{{ old('nombre', $pet->nombre) }}" class="w-full px-3 py-2 border rounded" required>
                </div>

                <div class="mb-4">
                    <label for="especie" class="block font-bold">Especie</label>
                    <input type="text" id="especie" name="especie" value="{{ old('especie', $pet->especie) }}" class="w-full px-3 py-2 border rounded" required>
                </div>

                <div class="mb-4">
                    <label for="raza" class="block font-bold">Raza</label>
                    <input type="text" id="raza" name="raza" value="{{ old('raza', $pet->raza) }}" class="w-full px-3 py-2 border rounded" required>
                </div>

                <div class="mb-4">
                    <label for="edad" class="block font-bold">Edad</label>
                    <input type="number" id="edad" name="edad" value="{{ old('edad', $pet->edad) }}" class="w-full px-3 py-2 border rounded" required>
                </div>

                <div class="mb-4">
                    <label for="sexo" class="block font-bold">Sexo</label>
                    <select id="sexo" name="sexo" class="w-full px-3 py-2 border rounded" required>
                        <option value="Macho" {{ old('sexo', $pet->sexo) == 'Macho' ? 'selected' : '' }}>Macho</option>
                        <option value="Hembra" {{ old('sexo', $pet->sexo) == 'Hembra' ? 'selected' : '' }}>Hembra</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="vaccine_file" class="block font-bold">Archivo de Vacunas (PDF)</label>
                    <input type="file" id="vaccine_file" name="vaccine_file" class="w-full px-3 py-2 border rounded">
                    @if ($pet->vaccine_file)
                        <p class="mt-2 text-sm">Vacuna actual: <a href="{{ asset('storage/' . $pet->vaccine_file) }}" target="_blank" class="text-blue-500">Ver PDF</a></p>
                    @endif
                </div>

                <div class="mb-4">
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition">Actualizar Mascota</button>
                </div>

                <div class="mb-4">
                    <a href="{{ route('pets.show', $pet->id) }}" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600 transition">Cancelar</a>
                </div>
            </form>
        </div>
    @else
        <div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4" role="alert">
            <p>No tienes permiso para editar esta mascota.</p>
        </div>
    @endcan
</x-app-layout>
