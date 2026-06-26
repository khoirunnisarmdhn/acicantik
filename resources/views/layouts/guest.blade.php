<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- <title>{{ config('app.name', 'Laravel') }}</title> --}}
    <title>SIM Konstruksi - Login</title>


    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 relative"
        style="background-image: url('{{ asset('images/login.jpg') }}'); background-size: cover; background-position: center; background-repeat: no-repeat;">
        
        <!-- Overlay blur & dark tint to make login card pop -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(17, 24, 39, 0.45); backdrop-filter: blur(2px); z-index: 1;"></div>

        <div class="w-full sm:max-w-md mt-6 px-8 py-8 shadow-2xl overflow-hidden sm:rounded-2xl"
            style="position: relative; z-index: 10; background-color: #ffffff;">
            {{ $slot }}
        </div>
    </div>
</body>

</html>
