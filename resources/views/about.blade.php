<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @extends('layout')
    @section('title', ' Buky World | Sobre nosotros')
</head>
<body>
    
    <header class="H_header">
        <x-main-nav></x-main-nav>
    </header>
 
<main class="H_main"> 
    @section('main-content')
    <x-alert type="Info">
        <x-slot name="title">Bienvenido</x-slot>
       Quieres saber mas de nosotros?    
    </x-alert>
  
</main>
@endsection 

</body>
</html>