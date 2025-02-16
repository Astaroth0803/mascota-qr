@extends('layouts.dashboard')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-6">Cambiar Contraseña</h1>

    @if($errors->any())
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('usuarios.updatePassword', $usuario->id) }}" method="POST" class="bg-white p-6 rounded-lg shadow">
        @csrf
        @method('PATCH')

        <div class="mb-4">
            <label class="block font-bold mb-2">Nueva Contraseña</label>
            <input type="password" name="password" class="p-2 border rounded w-full" required>
        </div>

        <div class="mb-4">
            <label class="block font-bold mb-2">Confirmar Contraseña</label>
            <input type="password" name="password_confirmation" class="p-2 border rounded w-full" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white p-2 rounded">Actualizar Contraseña</button>
        <a href="{{ route('dashboard.usuarios') }}" class="bg-gray-500 text-white p-2 rounded">Cancelar</a>
    </form>
</div>
@endsection
