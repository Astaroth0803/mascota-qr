<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Dashboard - Buky Pet Admin') }}
            </h2>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Buscar en el sistema..." class="border border-gray-300 rounded-md py-2 px-4 pl-10 focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </div>
                </div>
                <a href="{{ route('dashboard.usuarios') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    <i class="fas fa-users mr-2"></i>Gestionar Usuarios
                </a>
            </div>
        </div>
    </x-slot>

    {{-- Incluir el sidebar funcional --}}
    <x-sidebar-menu :active="'dashboard'" :pendingRequests="$solicitudCount ?? 0" />

    <div class="py-6" id="main-content"> {{-- Añadir ID para que el sidebar script lo referencie --}}
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex space-x-2 mb-6">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Hoy</button>
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Esta semana</button>
                <button class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded">Este mes</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                {{-- Tarjeta de Usuarios --}}
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-users text-blue-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Usuarios</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $userCount ?? 0 }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                        <svg class="self-center flex-shrink-0 mr-1 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="sr-only">Increased by</span>
                                        0%
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta de Mascotas --}}
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                     <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-paw text-green-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Mascotas</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ count($pets) }}</div>
                                    <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                        <svg class="self-center flex-shrink-0 mr-1 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="sr-only">Increased by</span>
                                        0%
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>

                {{-- Tarjeta de Solicitudes Pendientes --}}
                <div class="bg-white overflow-hidden shadow rounded-lg p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-500 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Solicitudes Pendientes</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-2xl font-semibold text-gray-900">{{ $solicitudCount ?? 0 }}</div>
                                     <div class="ml-2 flex items-baseline text-sm font-semibold text-green-600">
                                        <svg class="self-center flex-shrink-0 mr-1 h-5 w-5 text-green-500" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        </svg>
                                        <span class="sr-only">Increased by</span>
                                        0%
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sección de Gráficos --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                {{-- Gráfico de Actividad de Usuarios --}}
                <div class="bg-white overflow-hidden shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Actividad de Usuarios</h3>
                    <div class="h-64"><canvas id="user-activity-chart"></canvas></div>
                </div>

                {{-- Gráfico de Distribución de Mascotas --}}
                <div class="bg-white overflow-hidden shadow rounded-lg p-6">
                     <h3 class="text-lg font-medium text-gray-900 mb-4">Distribución de Mascotas</h3>
                    <div class="h-64"><canvas id="pet-distribution-chart"></canvas></div>
                </div>
            </div>

            {{-- Sección de Listado de Mascotas (tabla existente) --}}
            
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Datos de actividad de usuarios (pasados desde el controlador)
        const userDataForChart = @json($userDataForChart);

        // Datos de distribución de mascotas (pasados desde el controlador)
        const petDistributionData = @json($petDistributionData);

        // Gráfico de Actividad de Usuarios
        const userActivityCtx = document.getElementById('user-activity-chart').getContext('2d');
        new Chart(userActivityCtx, {
            type: 'line',
            data: {
                labels: ['Día 1', 'Día 2', 'Día 3', 'Día 4', 'Día 5', 'Día 6', 'Día 7'], // Ejemplo de etiquetas, ajusta según tu lógica de tiempo
                datasets: [{
                    label: 'Usuarios Activos',
                    data: userDataForChart,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                }
            }
        });

        // Gráfico de Distribución de Mascotas
        const petDistributionCtx = document.getElementById('pet-distribution-chart').getContext('2d');
        new Chart(petDistributionCtx, {
            type: 'pie',
            data: {
                labels: Object.keys(petDistributionData),
                datasets: [{
                    label: 'Distribución de Mascotas',
                    data: Object.values(petDistributionData),
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)', // Azul para Perros
                        'rgba(75, 192, 192, 0.6)', // Verde azulado para Gatos
                        'rgba(255, 159, 64, 0.6)'  // Naranja para Otros
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 159, 64, 1)'
                    ],
                    borderWidth: 1
                }]
            },
             options: {
                responsive: true,
                maintainAspectRatio: false,
                 plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    }
                }
            }
        });
    </script>
</x-app-layout> 
