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

        @yield('js')
    </head>
    <body class="font-sans antialiased bg-gray-100 overflow-y-hidden">
        @include('layouts.navigation')

        <!-- start wrapper -->
        <div class="h-screen w-full flex flex-row justify-between">
            @include('layouts.sidebar')
            
            <!-- strat content -->
            <div id="main" class="bg-gray-100 md:mb-16 flex-1 pt-8 overflow-y-scroll">
                @yield('main')
            </div>
            <!-- end content -->
        </div>
        @flasher_render
        <x-dollar-exchange-modal />
    </body>
</html>
