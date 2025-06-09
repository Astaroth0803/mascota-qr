<x-app-layout>
    <x-sidebar-menu />
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight" style="margin-left: 4rem;">
                {{ __('Mis Mascotas') }}
                {{ __('Dashboard - Buky Pet Admin') }}
            </h2>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Buscar en el sistema..." class="border border-gray-300 rounded-md py-2 px-4 pl-10 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>
    <div class="min-h-screen bg-gray-50" style="margin-left: 4rem;">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 bg-white bg-opacity-80 rounded-xl shadow-lg">
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 pb-4 border-b border-gray-200">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Mis Mascotas</h1>
                    <p class="mt-1 text-gray-600">Administra y visualiza la información de tus mascotas</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <a href="{{ route('dashboard.cliente.registrar.mascota') }}" 
                       class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                        <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Nueva Mascota
                    </a>
                </div>
            </div>

            @if($pets->isEmpty())
                <div class="rounded-xl shadow-sm p-8 bg-gray-50">
                    <div class="text-center">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-orange-100 mb-4">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">No tienes mascotas registradas</h3>
                        <p class="text-gray-600 mb-6">Comienza registrando tu primera mascota para mantener su información segura.</p>
                        <a href="{{ route('dashboard.cliente.registrar.mascota') }}" 
                           class="inline-flex items-center px-6 py-3 border border-transparent text-sm font-medium rounded-full shadow-sm text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-all duration-200">
                            <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Registrar Mascota
                        </a>
                    </div>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($pets as $pet)
                        <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-200 flex flex-col">
                            <div class="flex items-center p-6 bg-gray-50 border-b border-gray-200">
                                <div class="flex-shrink-0 mr-4">
                                    <div class="w-16 h-16 rounded-full overflow-hidden bg-gray-100 flex items-center justify-center">
                                        @if($pet->profile_image)
                                            <img src="{{ Storage::url($pet->profile_image) }}"
                                                 alt="{{ $pet->nombre }}"
                                                 class="w-full h-full object-cover">
                                        @else
                                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        @endif
                                    </div>
                                </div>
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-900">{{ $pet->nombre }}</h3>
                                    <p class="text-sm text-gray-600">{{ $pet->especie }} - {{ $pet->raza }}</p>
                                </div>
                            </div>

                            <div class="p-6 space-y-6 flex-grow">
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                        <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" /></svg>
                                        Datos de {{ $pet->nombre }}
                                    </h4>
                                    <div class="text-gray-700 space-y-2">
                                        <p class="text-sm"><span class="font-medium">Raza:</span> {{ $pet->raza }}</p>
                                        <p class="text-sm"><span class="font-medium">Edad:</span> {{ $pet->edad_anios }} año{{ $pet->edad_anios == 1 ? '' : 's' }} y {{ $pet->edad_meses }} mes{{ $pet->edad_meses == 1 ? '' : 'es' }}</p>
                                        <p class="text-sm"><span class="font-medium">Sexo:</span> {{ $pet->sexo }}</p>
                                    </div>
                                </div>

                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                        <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        Historial Médico
                                    </h4>
                                    @if($pet->vaccine_file)
                                         <div class="flex items-center justify-between">
                                             <span class="text-sm text-gray-700">Certificado adjuntado (anterior)</span>
                                         </div>
                                    @else
                                        @if($pet->vaccinationRecords->count() > 0)
                                             <span class="text-sm text-gray-700">{{ $pet->vaccinationRecords->count() }} registros en el historial</span>
                                        @else
                                            <span class="text-sm text-gray-500">No hay registros de vacunación</span>
                                        @endif
                                    @endif

                                    <div class="mt-4 text-right">
                                         <a href="{{ route('dashboard.cliente.mascotas.vaccination-history', $pet->id) }}"
                                            class="inline-flex items-center px-4 py-2 bg-green-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-600 focus:outline-none focus:border-green-700 focus:ring focus:ring-green-300 disabled:opacity-25 transition">
                                             <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7H9m1.5-4H9m3 4H9m6 4H9" /></svg>
                                            Ver Historial
                                         </a>
                                    </div>
                                </div>

                                @if(isset($pet->next_appointment))
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <h4 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                                        <svg class="w-5 h-5 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                        Próxima Cita Veterinaria
                                    </h4>
                                    <p class="text-sm text-gray-700">{{ $pet->next_appointment->fecha }} a las {{ $pet->next_appointment->hora }}</p>
                                    <a href="{{ route('dashboard.cliente.citas.show', $pet->next_appointment->id) }}" class="mt-3 inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600">Ver Detalles</a>
                                </div>
                                @endif

                            </div>

                            <div class="p-6 bg-gray-50 border-t border-gray-200 flex justify-end space-x-3">
                                <a href="{{ route('dashboard.cliente.mascotas.edit', $pet->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-blue-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-700 focus:outline-none focus:border-blue-700 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                    Editar Mascota
                                </a>
                                <a href="{{ route('dashboard.cliente.mascotas.show', $pet->id) }}"
                                   class="inline-flex items-center px-4 py-2 bg-orange-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-600 active:bg-orange-700 focus:outline-none focus:border-orange-700 focus:ring focus:ring-orange-300 disabled:opacity-25 transition">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                    Ver Detalles
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
