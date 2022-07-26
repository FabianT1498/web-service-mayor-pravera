<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Control Interno') }}</title>

        <!-- FontAwesome Icons -->
        <!-- <link rel="stylesheet" href="https://kit-pro.fontawesome.com/releases/v5.12.1/css/pro.min.css"> -->

        <!-- Fonts -->
        <!-- <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap"> -->

        <!-- Styles -->
        <link rel="stylesheet" href="{{ auto_version(asset('css/app.css'))  }}">

        <!-- Scripts -->
        <script src="{{ auto_version(asset('js/app.js')) }}" defer></script>

        <!-- Global scripts -->
        @if (session('current_module') === 'cash_register')
            <script src="{{ auto_version(asset('js/dollar_exchange_modal_index.js')) }}" defer></script>
        @endif
        
        @yield('js')
    </head>
    <body class="font-sans antialiased bg-gray-100 overflow-y-hidden">
        @include('layouts.navigation')

        <!-- start wrapper -->
        <div class="h-screen w-full flex flex-row justify-between">

            @if (Request::route()->getName() !== 'dashboard')
                @include('layouts.sidebar')
            @endif

            <!-- strat content -->
            <div id="main" class="relative bg-gray-100 md:mb-16 flex-1 pt-8 overflow-y-scroll">
                @yield('main')
            </div>
            <!-- end content -->
        </div>
        @flasher_render


        <!-- Global modal -->
        @if (session('current_module') === 'cash_register')
            <x-dollar-exchange-modal />
        @endif

    </body>
</html>
