<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @extends('layout')
    @section('title', ' Buky World | Tienda')
</head>
<body>
    
    <header class="H_header">
        <x-main-nav></x-main-nav>
    </header>
 
<main class="H_main"> 
    @section('main-content')
    <x-alert type="Info" >
        <x-slot name="title">Bienvenido</x-slot>
        Buky world shop     
    </x-alert> 

    <h1>Placa con qr personalizado</h1>
    <p>Nuestro servicio de <strong>Medical information in cloud</strong>, es un servicio en la nube en donde guardamos la informacion medica de tu mascota en la nube para que tu amigo pueda llevarlo a donde sea que vaya </p>
    <br>
    <div class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow-sm dark:bg-gray-800 dark:border-gray-700">
    <a href="/mascotaqr">
        <h5 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">MEDICAL PET INFORMATION IN CLOUD</h5>
</div>

</main>

<footer>
    
</footer> 
@endsection 

</body>
</html>