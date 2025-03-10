<x-app-layout>
    <x-slot name="header">
        
        <h2>{{ __('Dashboard - Pet Management') }}</h2>
    </x-slot>
    
@if (session('error'))
    <div class="bg-red-500 text-white p-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif


        <!-- Formulario de filtrado -->
        <form action="{{ route('dashboard.usuarios') }}" method="GET" class="mb-6 bg-white p-4 rounded-lg shadow">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <input type="text" name="search" class="p-2 border rounded" placeholder="Buscar por nombre o email" value="{{ request('search') }}">
                <select name="role" class="p-2 border rounded">
                    <option value="">Todos los roles</option>
                    <option value="administrador" {{ request('role') == 'administrador' ? 'selected' : '' }}>Administrador</option>
                    <option value="cliente_qr" {{ request('role') == 'cliente_qr' ? 'selected' : '' }}>Clientes</option>
                </select>
                <button type="submit" class="bg-blue-500 text-white p-2 rounded">Filtrar</button>
                <a href="{{ route('dashboard.usuarios') }}" class="bg-gray-500 text-white p-2 rounded text-center">Limpiar</a>
            </div>
        </form>

        <!-- Tabla de usuarios -->
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="min-w-full">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="p-3 text-left">ID</th>
                        <th class="p-3 text-left">Nombre</th>
                        <th class="p-3 text-left">Email</th>
                        <th class="p-3 text-left">Rol</th>
                        <th class="p-3 text-left">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuario)
                        <tr class="border-b">
                            <td class="p-3">{{ $usuario->id }}</td>
                            <td class="p-3">{{ $usuario->name }}</td>
                            <td class="p-3">{{ $usuario->email }}</td>
                            <td class="p-3">{{ $usuario->getRoleNames()->first() ?? 'Sin rol' }}</td>
                            <td class="p-3 flex space-x-2">
                                <a href="{{ route('usuarios.updatePassword', $usuario->id) }}" class="bg-yellow-500 text-white p-2 rounded">Cambiar Clave</a>
                            
                                <form action="{{ route('usuarios.destroy', $usuario->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 text-white p-2 rounded">Eliminar</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Paginación -->
        <div class="mt-6">
            {{ $usuarios->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
<x-sidebar />
