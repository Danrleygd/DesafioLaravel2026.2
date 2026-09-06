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
        Minhas Vendas - D-tech
    </title>


    @vite([
        'resources/css/app.css',
        'resources/css/vendas.css',
        'resources/css/userSidebar.css',

        'resources/js/userSidebar.js'
    ])

</head>


<body class="sales-public-body">

    <x-user-sidebar />


    <main class="sales-public-main">

        @include(
            'vendas._content'
        )

    </main>

</body>

</html>