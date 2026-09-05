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
        Vendas - Administração D-tech
    </title>


    @vite([
        'resources/css/app.css',
        'resources/css/vendas.css'
    ])

</head>


<body class="sales-public-body">

    <main class="sales-public-main">

        @include(
            'vendas._content'
        )

    </main>

</body>

</html>