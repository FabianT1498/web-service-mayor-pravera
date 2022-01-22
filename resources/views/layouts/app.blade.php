<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Control de cajas') }}</title>

        <!-- FontAwesome Icons -->
        <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css">

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        @include('layouts.navigation')

        <!-- strat wrapper -->
        <div class="h-screen flex flex-row flex-wrap">
            @include('layouts.sidebar')
        </div>

        <!-- strat content -->
        <div class="bg-gray-100 flex-1 p-6 md:mt-16">     
            @yield
        </div>
    	<!-- end content -->

    </body>
</html>
