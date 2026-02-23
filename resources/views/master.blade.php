<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title', 'Manajemen Barang')</title>
    
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
        <!-- Font Lato dari Google Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap" rel="stylesheet">
    
        {{-- Apex Chart --}}
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    
        {{-- Alpine JS --}}
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
        {{-- Sweetalert 2 --}}
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
        {{-- Flowbite --}} 
        <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/1.8.1/flowbite.min.js"></script>
    
        {{-- Custom Style --}}
        <style>
            body {
                font-family: 'Lato', sans-serif;
            }
            [x-cloak] { 
                display: none !important; 
            }
        </style>
        @stack('styles')
    </head>
    
<body class="relative min-h-screen">
    <div class="fixed inset-0 bg-gradient-to-br from-gray-50 via-blue-50/30 to-indigo-50/30 backdrop-blur-[100px] -z-10"></div>
    <div class="relative z-0">
        @yield('content')
        @stack('scripts')
    </div>
</body>
</html>