<x-app-layout>
    <x-sidebar-menu /> {{-- Si usas el mismo layout con sidebar --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    {{-- Título de la página --}}
                    <div class="flex items-center justify-between mb-8">
                        <h1 class="text-2xl font-bold text-gray-800">Historial de {{ $pet->nombre }}</h1>
                        {{-- Botón para volver a la lista de mascotas o detalles de la mascota --}}
                        <a href="{{ route('dashboard.cliente.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-200 border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-200 disabled:opacity-25 transition">
                            Volver a Mis Mascotas
                        </a>
                    </div>

                    {{-- Mensajes de estado (ej: éxito al subir) --}}
                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($pet->vaccinationRecords->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            <p class="mt-2 text-lg text-gray-600">No hay registros de vacunación para {{ $pet->nombre }}.</p>
                        </div>
                    @else
                        {{-- Tabla o lista de registros de vacunación --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tipo de Vacuna</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fecha de Vacunación</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Notas</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Certificado</th>
                                        <th scope="col" class="relative px-6 py-3">
                                            <span class="sr-only">Acciones</span>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($pet->vaccinationRecords as $record)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $record->vaccine_type ?? 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->vaccination_date ? \Carbon\Carbon::parse($record->vaccination_date)->format('d/m/Y') : 'N/A' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $record->notes ?? 'Sin notas' }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                @if($record->file_path)
                                                    <a href="{{ Storage::url($record->file_path) }}" target="_blank" class="text-blue-600 hover:underline">Ver Archivo</a>
                                                @else
                                                    Sin archivo
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                {{-- Botones de acción por registro (ej: Eliminar) --}}
                                                {{-- <a href="#" class="text-red-600 hover:text-red-900 ml-4">Eliminar</a> --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{-- Formulario para subir nuevo certificado --}}
                    <div class="mt-8 p-6 bg-gray-50 rounded-lg shadow">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4">Subir Nuevo Certificado de Vacunación</h3>
                        <form action="{{ route('dashboard.cliente.mascotas.vaccination-records.store', $pet->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                            @csrf

                            {{-- Campo para el archivo --}}
                            <div>
                                <label for="vaccination_file" class="block text-sm font-medium text-gray-700">Archivo del Certificado (PDF, DOC, DOCX)</label>
                                <input type="file" name="vaccination_file" id="vaccination_file" accept=".pdf,.doc,.docx" required
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('vaccination_file')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo para el tipo de vacuna --}}
                            <div>
                                <label for="vaccine_type" class="block text-sm font-medium text-gray-700">Tipo de Vacuna</label>
                                <input type="text" name="vaccine_type" id="vaccine_type" value="{{ old('vaccine_type') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('vaccine_type')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Campo para la fecha de vacunación --}}
                            <div>
                                <label for="vaccination_date" class="block text-sm font-medium text-gray-700">Fecha de Vacunación</label>
                                <input type="date" name="vaccination_date" id="vaccination_date" value="{{ old('vaccination_date') }}"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                @error('vaccination_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                             {{-- Campo para notas (opcional) --}}
                            <div>
                                <label for="notes" class="block text-sm font-medium text-gray-700">Notas Adicionales</label>
                                <textarea name="notes" id="notes" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
<x-app-layout>
    <x-sidebar-menu />
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Historial Médico') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50" style="margin-left: 4rem;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="mb-6">
                <a href="{{ route('dashboard.cliente.mascotas.show', $pet->id) }}" 
                   class="flex items-center text-sm text-blue-600 hover:text-blue-800">
                    <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Volver a detalles de {{ $pet->nombre }}
                </a>
            </div>

            <div class="bg-white rounded-xl shadow-md overflow-hidden">
                <div class="px-6 py-4 bg-gradient-to-r from-orange-500 to-orange-600">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-white p-2 rounded-full">
                            @if($pet->profile_image)
                                <img src="{{ Storage::url($pet->profile_image) }}" 
                                     alt="{{ $pet->nombre }}" 
                                     class="w-12 h-12 rounded-full object-cover">
                            @else
                                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="ml-4 text-white">
                            <h2 class="text-2xl font-bold">{{ $pet->nombre }}</h2>
                            <p class="text-orange-100">{{ $pet->especie }} - {{ $pet->raza }}</p>
                        </div>
                    </div>
                </div>

                <!-- Pestañas de navegación -->
                <div class="border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
                        <button 
                            class="historyTab border-orange-500 text-orange-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm active"
                            data-tab="historyTab">
                            Historial Médico
                        </button>
                        <button 
                            class="addRecordTab border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                            data-tab="addRecordTab">
                            Agregar Registro
                        </button>
                    </nav>
                </div>

                <!-- Contenido: Historial Médico -->
                <div id="historyTab" class="px-6 py-6">
                    @if($pet->vaccinationRecords->isEmpty())
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No hay registros médicos</h3>
                            <p class="mt-1 text-sm text-gray-500">Comienza agregando el primer registro médico de tu mascota.</p>
                            <div class="mt-6">
                                <button type="button" 
                                        class="showAddRecord inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                    <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                    </svg>
                                    Agregar registro médico
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="space-y-8">
                            @foreach($pet->vaccinationRecords->sortByDesc('date') as $record)
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200 hover:shadow-md transition-shadow duration-200">
                                    <div class="flex justify-between items-start">
                                        <div>
                                            <div class="flex items-center">
                                                <!-- Icono según tipo de registro -->
                                                @if($record->record_type == 'vacuna')
                                                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-500 mr-3">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                                        </svg>
                                                    </span>
                                                    <h3 class="text-lg font-semibold text-gray-900">Vacuna: {{ $record->vaccine_name }}</h3>
                                                @elseif($record->record_type == 'checkeo')
                                                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-500 mr-3">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                                                        </svg>
                                                    </span>
                                                    <h3 class="text-lg font-semibold text-gray-900">Cita de control</h3>
                                                @elseif($record->record_type == 'peluqueria')
                                                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 text-purple-500 mr-3">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                                        </svg>
                                                    </span>
                                                    <h3 class="text-lg font-semibold text-gray-900">Peluquería/Estética</h3>
                                                @elseif($record->record_type == 'operacion')
                                                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-500 mr-3">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                        </svg>
                                                    </span>
                                                    <h3 class="text-lg font-semibold text-gray-900">Operación/Cirugía</h3>
                                                @else
                                                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 text-gray-500 mr-3">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    </span>
                                                    <h3 class="text-lg font-semibold text-gray-900">Registro médico</h3>
                                                @endif
                                            </div>
                                            <p class="mt-2 text-sm text-gray-500">
                                                Fecha: {{ $record->date->format('d/m/Y') }} a las {{ \Carbon\Carbon::parse($record->time)->format('H:i') }}
                                            </p>
                                            @if($record->vet_name)
                                                <p class="text-sm text-gray-500">Veterinario: {{ $record->vet_name }}</p>
                                            @endif
                                            @if($record->location)
                                                <p class="text-sm text-gray-500">Lugar: {{ $record->location }}</p>
                                            @endif
                                        </div>
                                        @if($record->document_path)
                                            <a href="{{ Storage::url($record->document_path) }}" target="_blank" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-5 font-medium rounded-md text-white bg-orange-600 hover:bg-orange-500 focus:outline-none focus:border-orange-700 focus:shadow-outline-orange active:bg-orange-700 transition ease-in-out duration-150">
                                                <svg class="-ml-1 mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122" />
                                                </svg>
                                                Ver documento
                                            </a>
                                        @endif
                                    </div>

                                    <div class="mt-4 space-y-3">
                                        <!-- Campos específicos según tipo -->
                                        @if($record->record_type == 'vacuna')
                                            @if($record->next_date)
                                                <div class="bg-yellow-50 border border-yellow-200 rounded-md p-3">
                                                    <div class="flex">
                                                        <div class="flex-shrink-0">
                                                            <svg class="h-5 w-5 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                            </svg>
                                                        </div>
                                                        <div class="ml-3">
                                                            <h3 class="text-sm font-medium text-yellow-800">Próxima vacunación</h3>
                                                            <div class="mt-2 text-sm text-yellow-700">
                                                                <p>{{ $record->next_date->format('d/m/Y') }}</p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif

                                        @if($record->diagnosis)
                                            <div class="rounded-md bg-white p-3 border border-gray-200">
                                                <h4 class="text-sm font-medium text-gray-900">Diagnóstico:</h4>
                                                <p class="mt-1 text-sm text-gray-600">{{ $record->diagnosis }}</p>
                                            </div>
                                        @endif

                                        @if($record->treatment)
                                            <div class="rounded-md bg-white p-3 border border-gray-200">
                                                <h4 class="text-sm font-medium text-gray-900">Tratamiento:</h4>
                                                <p class="mt-1 text-sm text-gray-600">{{ $record->treatment }}</p>
                                            </div>
                                        @endif

                                        @if($record->observations)
                                            <div class="rounded-md bg-white p-3 border border-gray-200">
                                                <h4 class="text-sm font-medium text-gray-900">Observaciones:</h4>
                                                <p class="mt-1 text-sm text-gray-600">{{ $record->observations }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Contenido: Formulario para agregar registro -->
                <div id="addRecordTab" class="px-6 py-6 hidden">
                    <form action="{{ route('dashboard.cliente.mascotas.vaccination-records.store', $pet->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Tipo de registro -->
                        <div>
                            <label for="record_type" class="block text-sm font-medium text-gray-700">Tipo de registro</label>
                            <select id="record_type" name="record_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-orange-500 focus:border-orange-500 sm:text-sm rounded-md">
                                @foreach($recordTypes as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Campos comunes para todos los tipos -->
                        <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-3">
                                <label for="date" class="block text-sm font-medium text-gray-700">Fecha</label>
                                <input type="date" name="date" id="date" value="{{ date('Y-m-d') }}" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="sm:col-span-3">
                                <label for="time" class="block text-sm font-medium text-gray-700">Hora</label>
                                <input type="time" name="time" id="time" value="{{ date('H:i') }}" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="sm:col-span-3">
                                <label for="vet_name" class="block text-sm font-medium text-gray-700">Nombre del veterinario</label>
                                <input type="text" name="vet_name" id="vet_name" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>

                            <div class="sm:col-span-3">
                                <label for="location" class="block text-sm font-medium text-gray-700">Lugar</label>
                                <input type="text" name="location" id="location" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <!-- Campos dinámicos según el tipo de registro -->
                        <div id="dynamic-fields">
                            <!-- Se cargarán dinámicamente según el tipo seleccionado -->
                            <!-- Campos para vacuna (predeterminados) -->
                            <div id="vaccine-fields" class="space-y-6">
                                <div>
                                    <label for="vaccine_name" class="block text-sm font-medium text-gray-700">Nombre de la vacuna</label>
                                    <input type="text" name="vaccine_name" id="vaccine_name" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="next_date" class="block text-sm font-medium text-gray-700">Fecha de próxima vacunación</label>
                                    <input type="date" name="next_date" id="next_date" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        </div>

                        <!-- Observaciones (común para todos) -->
                        <div>
                            <label for="observations" class="block text-sm font-medium text-gray-700">Observaciones</label>
                            <textarea name="observations" id="observations" rows="3" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>

                        <!-- Documento o evidencia -->
                        <div>
                            <label for="document" class="block text-sm font-medium text-gray-700">Documento o evidencia (opcional)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="document" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                            <span>Subir un archivo</span>
                                            <input id="document" name="document" type="file" class="sr-only">
                                        </label>
                                        <p class="pl-1">o arrastrar y soltar</p>
                                    </div>
                                    <p class="text-xs text-gray-500">PDF, JPG, JPEG o PNG hasta 10MB</p>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500">
                                Guardar registro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Script para manejar las pestañas
        document.addEventListener('DOMContentLoaded', function() {
            const tabs = document.querySelectorAll('[data-tab]');
            const contents = document.querySelectorAll('#historyTab, #addRecordTab');
            const showAddRecordButton = document.querySelector('.showAddRecord');
            const recordTypeSelect = document.getElementById('record_type');
            const dynamicFields = document.getElementById('dynamic-fields');

            // Función para cambiar las pestañas
            function switchTab(tabId) {
                tabs.forEach(tab => {
                    if (tab.dataset.tab === tabId) {
                        tab.classList.add('border-orange-500', 'text-orange-600');
                        tab.classList.remove('border-transparent', 'text-gray-500');
                    } else {
                        tab.classList.remove('border-orange-500', 'text-orange-600');
                        tab.classList.add('border-transparent', 'text-gray-500');
                    }
                });

                contents.forEach(content => {
                    if (content.id === tabId) {
                        content.classList.remove('hidden');
                    } else {
                        content.classList.add('hidden');
                    }
                });
            }

            // Evento para cambiar entre pestañas
            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    switchTab(tab.dataset.tab);
                });
            });

            // Evento para el botón de mostrar formulario
            if (showAddRecordButton) {
                showAddRecordButton.addEventListener('click', () => {
                    switchTab('addRecordTab');
                });
            }

            // Función para actualizar los campos dinámicos según el tipo de registro
            function updateDynamicFields(recordType) {
                let fieldsHTML = '';

                switch(recordType) {
                    case 'vacuna':
                        fieldsHTML = `
                            <div id="vaccine-fields" class="space-y-6">
                                <div>
                                    <label for="vaccine_name" class="block text-sm font-medium text-gray-700">Nombre de la vacuna</label>
                                    <input type="text" name="vaccine_name" id="vaccine_name" required class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div>
                                    <label for="next_date" class="block text-sm font-medium text-gray-700">Fecha de próxima vacunación</label>
                                    <input type="date" name="next_date" id="next_date" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        `;
                        break;

                    case 'checkeo':
                        fieldsHTML = `
                            <div id="checkup-fields" class="space-y-6">
                                <div>
                                    <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnóstico</label>
                                    <textarea name="diagnosis" id="diagnosis" rows="3" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                                <div>
                                    <label for="treatment" class="block text-sm font-medium text-gray-700">Tratamiento prescrito</label>
                                    <textarea name="treatment" id="treatment" rows="3" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                                <div>
                                    <label for="next_date" class="block text-sm font-medium text-gray-700">Fecha de próxima cita</label>
                                    <input type="date" name="next_date" id="next_date" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        `;
                        break;

                    case 'peluqueria':
                        fieldsHTML = `
                            <div id="grooming-fields" class="space-y-6">
                                <div>
                                    <label for="observations" class="block text-sm font-medium text-gray-700">Detalles del servicio</label>
                                    <textarea name="observations" id="observations" rows="3" required class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                                <div>
                                    <label for="next_date" class="block text-sm font-medium text-gray-700">Fecha del próximo servicio</label>
                                    <input type="date" name="next_date" id="next_date" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        `;
                        break;

                    case 'operacion':
                        fieldsHTML = `
                            <div id="surgery-fields" class="space-y-6">
                                <div>
                                    <label for="diagnosis" class="block text-sm font-medium text-gray-700">Diagnóstico/Motivo de la cirugía</label>
                                    <textarea name="diagnosis" id="diagnosis" rows="3" required class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                                <div>
                                    <label for="treatment" class="block text-sm font-medium text-gray-700">Procedimiento realizado y cuidados posteriores</label>
                                    <textarea name="treatment" id="treatment" rows="3" required class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                                <div>
                                    <label for="next_date" class="block text-sm font-medium text-gray-700">Fecha de control post-operatorio</label>
                                    <input type="date" name="next_date" id="next_date" class="mt-1 focus:ring-orange-500 focus:border-orange-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                        `;
                        break;
                }

                dynamicFields.innerHTML = fieldsHTML;
            }

            // Inicializar campos dinámicos con el tipo predeterminado
            updateDynamicFields(recordTypeSelect.value);

            // Cambiar campos cuando cambie el tipo de registro
            recordTypeSelect.addEventListener('change', function() {
                updateDynamicFields(this.value);
            });
        });
    </script>
</x-app-layout>

                            <div class="flex justify-end">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-800 focus:outline-none focus:border-green-800 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                    Subir Certificado
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>