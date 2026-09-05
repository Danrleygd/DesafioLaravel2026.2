<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>
        @yield('title', 'Administração') | D-tech
    </title>


    @vite([
        'resources/css/sidebarAdmin.css',
        'resources/js/sidebarAdmin.js'
    ])


    @stack('styles')

</head>

<body>

    <div
        class="admin-layout"
        id="adminLayout"
    >

        {{-- SIDEBAR --}}
        <x-sidebar-admin />


        {{-- CONTEÚDO --}}
        <main class="admin-main">

            @yield('content')

        </main>

    </div>


    @stack('scripts')

</body>

</html>