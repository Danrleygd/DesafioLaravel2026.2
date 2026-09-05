<!DOCTYPE html>
<html lang="pt-BR">

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
        Meus Produtos - D-tech
    </title>


    @vite([
        'resources/css/app.css',
        'resources/css/navLanding.css',
        'resources/css/productManagement.css',
        'resources/js/productManagement.js'
    ])

</head>


<body class="pm-public-body">

    {{-- NAVBAR --}}
    <x-nav-landing />


    <main class="pm-public-main">

        @include(
            'products.manage._content'
        )

    </main>

</body>

</html>