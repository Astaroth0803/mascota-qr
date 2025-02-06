<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @extends('layout')
    @section('title', ' Buky World | Home')
</head>
<body>
    
    <header class="H_header">
        <x-main-nav></x-main-nav>
    </header>
 
<main class="H_main"> 
    @section('main-content')
    <x-alert type="Info" >
        <x-slot name="title">Bienvenido</x-slot>
        Compra aquí tu servicio de Medical Pet Information In Cloud  
    </x-alert>
</main>

<footer>
    
</footer> 
@endsection 

</body>
</html>