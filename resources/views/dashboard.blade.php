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
        Dashboard - D-tech
    </title>


    @vite([
        'resources/css/app.css',
        'resources/css/navLanding.css',
        'resources/css/dashboard.css'
    ])

</head>


@php

    /*
    |--------------------------------------------------------------------------
    | FOTO DO USUÁRIO
    |--------------------------------------------------------------------------
    */

    $fotoUsuario = null;


    if ($user->foto) {

        $fotoUsuario =
            str_starts_with(
                $user->foto,
                'http://'
            )
            ||
            str_starts_with(
                $user->foto,
                'https://'
            )
                ? $user->foto
                : asset(
                    'storage/' .
                    ltrim(
                        $user->foto,
                        '/'
                    )
                );
    }


    /*
    |--------------------------------------------------------------------------
    | PRIMEIRO NOME
    |--------------------------------------------------------------------------
    */

    $primeiroNome =
        explode(
            ' ',
            trim($user->nome)
        )[0]
        ??
        $user->nome;


    /*
    |--------------------------------------------------------------------------
    | INICIAIS
    |--------------------------------------------------------------------------
    */

    $partesNome =
        preg_split(
            '/\s+/',
            trim($user->nome)
        );


    $iniciais =
        strtoupper(
            substr(
                $partesNome[0] ?? 'U',
                0,
                1
            )
        );


    if (
        count($partesNome) > 1
    ) {

        $iniciais .= strtoupper(
            substr(
                end($partesNome),
                0,
                1
            )
        );
    }

@endphp


<body class="user-dashboard-body">

    {{-- =========================================================
        NAVBAR
    ========================================================== --}}

    <x-nav-landing />


    {{-- =========================================================
        DASHBOARD
    ========================================================== --}}

    <main class="user-dashboard">

        <div class="user-dashboard-container">


            {{-- =====================================================
                CABEÇALHO
            ====================================================== --}}

            <header class="user-dashboard-header">

                <div>

                    <span class="user-dashboard-label">
                        VISÃO GERAL
                    </span>


                    <h1>

                        Olá,
                        {{ $primeiroNome }}!

                    </h1>


                    <p>
                        Acompanhe seus produtos, compras e vendas na D-tech.
                    </p>

                </div>


                <div class="user-dashboard-header-actions">

                    @if(
                        Route::has(
                            'meus-produtos.create'
                        )
                    )

                        <a
                            href="{{ route('meus-produtos.create') }}"
                            class="user-dashboard-button user-dashboard-button-primary"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                            >
                                <path d="M12 5v14"></path>
                                <path d="M5 12h14"></path>
                            </svg>

                            Cadastrar Produto

                        </a>

                    @endif


                    <a
                        href="{{ route('profile.edit') }}"
                        class="user-dashboard-button user-dashboard-button-secondary"
                    >

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle
                                cx="12"
                                cy="8"
                                r="4"
                            ></circle>

                            <path
                                d="M4 21a8 8 0 0 1 16 0"
                            ></path>
                        </svg>

                        Meu Perfil

                    </a>

                </div>

            </header>


            {{-- =====================================================
                CARDS PRINCIPAIS
            ====================================================== --}}

            <section class="user-dashboard-stats">


                {{-- SALDO --}}
                <article class="user-dashboard-stat">

                    <div class="user-dashboard-stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <rect
                                x="3"
                                y="5"
                                width="18"
                                height="14"
                                rx="2"
                            ></rect>

                            <path
                                d="M16 12h5"
                            ></path>

                            <circle
                                cx="16"
                                cy="12"
                                r="1"
                            ></circle>
                        </svg>

                    </div>


                    <div class="user-dashboard-stat-content">

                        <span>
                            Saldo disponível
                        </span>

                        <strong>

                            R$
                            {{ number_format(
                                $saldo,
                                2,
                                ',',
                                '.'
                            ) }}

                        </strong>

                        <small>
                            saldo atual da sua conta
                        </small>

                    </div>

                </article>


                {{-- PRODUTOS --}}
                <article class="user-dashboard-stat">

                    <div class="user-dashboard-stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                d="M3 7l9-4 9 4-9 4-9-4Z"
                            ></path>

                            <path
                                d="M3 7v10l9 4 9-4V7"
                            ></path>

                            <path
                                d="M12 11v10"
                            ></path>
                        </svg>

                    </div>


                    <div class="user-dashboard-stat-content">

                        <span>
                            Meus produtos
                        </span>

                        <strong>
                            {{ $totalProdutos }}
                        </strong>

                        <small>

                            {{ $produtosDisponiveis }}
                            disponível(is)

                        </small>

                    </div>

                </article>


                {{-- VENDAS --}}
                <article class="user-dashboard-stat">

                    <div class="user-dashboard-stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path
                                d="M4 19V9"
                            ></path>

                            <path
                                d="M10 19V5"
                            ></path>

                            <path
                                d="M16 19v-7"
                            ></path>

                            <path
                                d="M22 19H2"
                            ></path>
                        </svg>

                    </div>


                    <div class="user-dashboard-stat-content">

                        <span>
                            Vendas realizadas
                        </span>

                        <strong>
                            {{ $totalVendas }}
                        </strong>

                        <small>

                            {{ $quantidadeProdutosVendidos }}
                            item(ns) vendido(s)

                        </small>

                    </div>

                </article>


                {{-- COMPRAS --}}
                <article class="user-dashboard-stat">

                    <div class="user-dashboard-stat-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <circle
                                cx="9"
                                cy="20"
                                r="1"
                            ></circle>

                            <circle
                                cx="19"
                                cy="20"
                                r="1"
                            ></circle>

                            <path
                                d="M3 4h2l2.5 11h11l2-7H7"
                            ></path>
                        </svg>

                    </div>


                    <div class="user-dashboard-stat-content">

                        <span>
                            Compras realizadas
                        </span>

                        <strong>
                            {{ $totalCompras }}
                        </strong>

                        <small>

                            {{ $comprasPagas }}
                            compra(s) paga(s)

                        </small>

                    </div>

                </article>

            </section>


            {{-- =====================================================
                SEGUNDA LINHA
            ====================================================== --}}

            <section class="user-dashboard-secondary-stats">


                <article>

                    <span>
                        Receita total
                    </span>

                    <strong>

                        R$
                        {{ number_format(
                            $receitaTotal,
                            2,
                            ',',
                            '.'
                        ) }}

                    </strong>

                </article>


                <article>

                    <span>
                        Total gasto
                    </span>

                    <strong>

                        R$
                        {{ number_format(
                            $valorTotalCompras,
                            2,
                            ',',
                            '.'
                        ) }}

                    </strong>

                </article>


                <article>

                    <span>
                        Sem estoque
                    </span>

                    <strong>
                        {{ $produtosSemEstoque }}
                    </strong>

                </article>


                <article>

                    <span>
                        Itens no carrinho
                    </span>

                    <strong>
                        {{ $itensCarrinho }}
                    </strong>

                </article>

            </section>


            {{-- =====================================================
                CONTEÚDO
            ====================================================== --}}

            <div class="user-dashboard-main-grid">


                {{-- =================================================
                    GRÁFICO
                ================================================== --}}

                <section class="user-dashboard-card user-dashboard-chart-card">

                    <div class="user-dashboard-card-header">

                        <div>

                            <h2>
                                Desempenho de vendas
                            </h2>

                            <p>
                                Receita obtida nos últimos 6 meses.
                            </p>

                        </div>


                        <span class="user-dashboard-card-badge">
                            Dinâmico
                        </span>

                    </div>


                    <div class="user-dashboard-chart">

                        @foreach($mesesGrafico as $index => $mes)

                            @php

                                $valor =
                                    $valoresGrafico[
                                        $index
                                    ]
                                    ?? 0;


                                $altura =
                                    $maiorValorGrafico > 0
                                        ? (
                                            $valor
                                            /
                                            $maiorValorGrafico
                                        )
                                        *
                                        100
                                        : 0;

                            @endphp


                            <div class="user-dashboard-chart-column">

                                <div class="user-dashboard-chart-value">

                                    @if($valor > 0)

                                        R$
                                        {{ number_format(
                                            $valor,
                                            0,
                                            ',',
                                            '.'
                                        ) }}

                                    @endif

                                </div>


                                <div class="user-dashboard-chart-track">

                                    <div
                                        class="user-dashboard-chart-bar"
                                        style="
                                            height:
                                            {{ max(
                                                $altura,
                                                $valor > 0
                                                    ? 8
                                                    : 0
                                            ) }}%;
                                        "
                                    ></div>

                                </div>


                                <span>
                                    {{ $mes }}
                                </span>

                            </div>

                        @endforeach

                    </div>


                    @if(
                        array_sum(
                            $valoresGrafico
                        )
                        == 0
                    )

                        <div class="user-dashboard-chart-empty">

                            Você ainda não possui vendas pagas neste período.

                        </div>

                    @endif

                </section>


                {{-- =================================================
                    MAIS VENDIDO
                ================================================== --}}

                <section class="user-dashboard-card">

                    <div class="user-dashboard-card-header">

                        <div>

                            <h2>
                                Destaque
                            </h2>

                            <p>
                                Seu produto mais vendido.
                            </p>

                        </div>

                    </div>


                    @if($produtoMaisVendido)

                        @php

                            $fotoDestaque =
                                $produtoMaisVendido
                                    ->foto;


                            $fotoDestaqueUrl =
                                $fotoDestaque
                                    ? (
                                        str_starts_with(
                                            $fotoDestaque,
                                            'http://'
                                        )
                                        ||
                                        str_starts_with(
                                            $fotoDestaque,
                                            'https://'
                                        )
                                            ? $fotoDestaque
                                            : asset(
                                                'storage/' .
                                                ltrim(
                                                    $fotoDestaque,
                                                    '/'
                                                )
                                            )
                                    )
                                    : null;

                        @endphp


                        <div class="user-dashboard-highlight">

                            <div class="user-dashboard-highlight-image">

                                @if($fotoDestaqueUrl)

                                    <img
                                        src="{{ $fotoDestaqueUrl }}"
                                        alt="{{ $produtoMaisVendido->nome }}"
                                    >

                                @else

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.8"
                                    >
                                        <path
                                            d="M3 7l9-4 9 4-9 4-9-4Z"
                                        ></path>

                                        <path
                                            d="M3 7v10l9 4 9-4V7"
                                        ></path>
                                    </svg>

                                @endif

                            </div>


                            <div class="user-dashboard-highlight-info">

                                <span>
                                    MAIS VENDIDO
                                </span>

                                <h3>
                                    {{ $produtoMaisVendido->nome }}
                                </h3>


                                <div>

                                    <strong>

                                        {{ $produtoMaisVendido
                                            ->quantidade_vendida
                                        }}

                                        vendido(s)

                                    </strong>


                                    <small>

                                        R$
                                        {{ number_format(
                                            $produtoMaisVendido
                                                ->valor_vendido,
                                            2,
                                            ',',
                                            '.'
                                        ) }}

                                        em vendas

                                    </small>

                                </div>


                                <a
                                    href="{{ route(
                                        'produto.show',
                                        $produtoMaisVendido->id
                                    ) }}"
                                >
                                    Ver produto →
                                </a>

                            </div>

                        </div>

                    @else

                        <div class="user-dashboard-empty">

                            <div class="user-dashboard-empty-icon">

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        d="M3 7l9-4 9 4-9 4-9-4Z"
                                    ></path>

                                    <path
                                        d="M3 7v10l9 4 9-4V7"
                                    ></path>
                                </svg>

                            </div>

                            <strong>
                                Nenhuma venda ainda
                            </strong>

                            <span>
                                Seu produto mais vendido aparecerá aqui.
                            </span>

                        </div>

                    @endif

                </section>

            </div>


            {{-- =====================================================
                PRODUTOS RECENTES
            ====================================================== --}}

            <section class="user-dashboard-card">

                <div class="user-dashboard-card-header">

                    <div>

                        <h2>
                            Meus produtos recentes
                        </h2>

                        <p>
                            Últimos anúncios cadastrados por você.
                        </p>

                    </div>


                    @if(
                        Route::has(
                            'meus-produtos.index'
                        )
                    )

                        <a
                            href="{{ route('meus-produtos.index') }}"
                            class="user-dashboard-link"
                        >
                            Ver todos →
                        </a>

                    @endif

                </div>


                @if(
                    $ultimosProdutos->count()
                    > 0
                )

                    <div class="user-dashboard-products">

                        @foreach($ultimosProdutos as $produto)

                            @php

                                $fotoProduto =
                                    $produto
                                        ->fotos
                                        ->firstWhere(
                                            'principal',
                                            true
                                        )?->foto
                                    ??
                                    $produto->foto;


                                $fotoProdutoUrl =
                                    $fotoProduto
                                        ? (
                                            str_starts_with(
                                                $fotoProduto,
                                                'http://'
                                            )
                                            ||
                                            str_starts_with(
                                                $fotoProduto,
                                                'https://'
                                            )
                                                ? $fotoProduto
                                                : asset(
                                                    'storage/' .
                                                    ltrim(
                                                        $fotoProduto,
                                                        '/'
                                                    )
                                                )
                                        )
                                        : null;

                            @endphp


                            <article class="user-dashboard-product">

                                <a
                                    href="{{ route(
                                        'produto.show',
                                        $produto->id
                                    ) }}"
                                    class="user-dashboard-product-image"
                                >

                                    @if($fotoProdutoUrl)

                                        <img
                                            src="{{ $fotoProdutoUrl }}"
                                            alt="{{ $produto->nome }}"
                                        >

                                    @else

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                d="M3 7l9-4 9 4-9 4-9-4Z"
                                            ></path>

                                            <path
                                                d="M3 7v10l9 4 9-4V7"
                                            ></path>
                                        </svg>

                                    @endif

                                </a>


                                <div class="user-dashboard-product-info">

                                    <span>
                                        {{ $produto->categoria?->nome
                                            ?? 'Sem categoria'
                                        }}
                                    </span>


                                    <h3>
                                        {{ $produto->nome }}
                                    </h3>


                                    <strong>

                                        R$
                                        {{ number_format(
                                            $produto->preco,
                                            2,
                                            ',',
                                            '.'
                                        ) }}

                                    </strong>


                                    <small>

                                        Estoque:
                                        {{ $produto->quantidade }}

                                    </small>

                                </div>

                            </article>

                        @endforeach

                    </div>

                @else

                    <div class="user-dashboard-empty">

                        <strong>
                            Você ainda não cadastrou produtos.
                        </strong>


                        @if(
                            Route::has(
                                'meus-produtos.create'
                            )
                        )

                            <a
                                href="{{ route('meus-produtos.create') }}"
                            >
                                Cadastrar primeiro produto
                            </a>

                        @endif

                    </div>

                @endif

            </section>


            {{-- =====================================================
                COMPRAS E VENDAS
            ====================================================== --}}

            <div class="user-dashboard-activities">


                {{-- =================================================
                    ÚLTIMAS VENDAS
                ================================================== --}}

                <section class="user-dashboard-card">

                    <div class="user-dashboard-card-header">

                        <div>

                            <h2>
                                Últimas vendas
                            </h2>

                            <p>
                                Seus itens vendidos recentemente.
                            </p>

                        </div>

                    </div>


                    @if(
                        $ultimasVendas->count()
                        > 0
                    )

                        <div class="user-dashboard-transaction-list">

                            @foreach($ultimasVendas as $venda)

                                <div class="user-dashboard-transaction">

                                    <div class="user-dashboard-transaction-icon sale">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                d="m7 17 10-10"
                                            ></path>

                                            <path
                                                d="M7 7h10v10"
                                            ></path>
                                        </svg>

                                    </div>


                                    <div class="user-dashboard-transaction-info">

                                        <strong>
                                            {{ $venda->produto_nome }}
                                        </strong>

                                        <span>

                                            {{ $venda->quantidade }}
                                            unidade(s)

                                            •

                                            {{ \Carbon\Carbon::parse(
                                                $venda->data_compra
                                            )->format('d/m/Y') }}

                                        </span>

                                    </div>


                                    <div class="user-dashboard-transaction-value">

                                        <strong>

                                            + R$
                                            {{ number_format(
                                                $venda->subtotal,
                                                2,
                                                ',',
                                                '.'
                                            ) }}

                                        </strong>


                                        <span
                                            class="
                                                user-dashboard-status
                                                status-{{ strtolower(
                                                    $venda->StatusPagamento
                                                ) }}
                                            "
                                        >

                                            {{ ucfirst(
                                                $venda->StatusPagamento
                                            ) }}

                                        </span>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="user-dashboard-empty small">

                            Nenhuma venda registrada.

                        </div>

                    @endif

                </section>


                {{-- =================================================
                    ÚLTIMAS COMPRAS
                ================================================== --}}

                <section class="user-dashboard-card">

                    <div class="user-dashboard-card-header">

                        <div>

                            <h2>
                                Últimas compras
                            </h2>

                            <p>
                                Seus pedidos mais recentes.
                            </p>

                        </div>

                    </div>


                    @if(
                        $ultimasCompras->count()
                        > 0
                    )

                        <div class="user-dashboard-transaction-list">

                            @foreach($ultimasCompras as $compra)

                                <div class="user-dashboard-transaction">

                                    <div class="user-dashboard-transaction-icon purchase">

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="2"
                                        >
                                            <path
                                                d="m17 7-10 10"
                                            ></path>

                                            <path
                                                d="M17 17H7V7"
                                            ></path>
                                        </svg>

                                    </div>


                                    <div class="user-dashboard-transaction-info">

                                        <strong>

                                            Compra
                                            #{{ $compra->id }}

                                        </strong>

                                        <span>

                                            {{ \Carbon\Carbon::parse(
                                                $compra->data_compra
                                            )->format('d/m/Y') }}

                                            •

                                            {{ ucfirst(
                                                $compra->LocalPagamento
                                            ) }}

                                        </span>

                                    </div>


                                    <div class="user-dashboard-transaction-value">

                                        <strong>

                                            - R$
                                            {{ number_format(
                                                $compra->ValorTotal,
                                                2,
                                                ',',
                                                '.'
                                            ) }}

                                        </strong>


                                        <span
                                            class="
                                                user-dashboard-status
                                                status-{{ strtolower(
                                                    $compra->StatusPagamento
                                                ) }}
                                            "
                                        >

                                            {{ ucfirst(
                                                $compra->StatusPagamento
                                            ) }}

                                        </span>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    @else

                        <div class="user-dashboard-empty small">

                            Nenhuma compra registrada.

                        </div>

                    @endif

                </section>

            </div>


            {{-- =====================================================
                ACESSOS RÁPIDOS
            ====================================================== --}}

            <section class="user-dashboard-card">

                <div class="user-dashboard-card-header">

                    <div>

                        <h2>
                            Acessos rápidos
                        </h2>

                        <p>
                            Acesse as principais áreas da sua conta.
                        </p>

                    </div>

                </div>


                <div class="user-dashboard-shortcuts">


                    <a href="{{ route('landing') }}">

                        <span>

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    d="M3 11.5 12 4l9 7.5"
                                ></path>

                                <path
                                    d="M5 10v10h14V10"
                                ></path>
                            </svg>

                        </span>

                        <div>
                            <strong>Loja</strong>
                            <small>Explorar produtos</small>
                        </div>

                    </a>


                    @if(
                        Route::has(
                            'meus-produtos.index'
                        )
                    )

                        <a href="{{ route('meus-produtos.index') }}">

                            <span>

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <path
                                        d="M3 7l9-4 9 4-9 4-9-4Z"
                                    ></path>

                                    <path
                                        d="M3 7v10l9 4 9-4V7"
                                    ></path>
                                </svg>

                            </span>

                            <div>
                                <strong>Meus Produtos</strong>
                                <small>Gerenciar anúncios</small>
                            </div>

                        </a>

                    @endif


                    @if(
                        Route::has(
                            'carrinho.index'
                        )
                    )

                        <a href="{{ route('carrinho.index') }}">

                            <span>

                                <svg
                                    viewBox="0 0 24 24"
                                    fill="none"
                                    stroke="currentColor"
                                    stroke-width="1.8"
                                >
                                    <circle
                                        cx="9"
                                        cy="20"
                                        r="1"
                                    ></circle>

                                    <circle
                                        cx="19"
                                        cy="20"
                                        r="1"
                                    ></circle>

                                    <path
                                        d="M3 4h2l2.5 11h11l2-7H7"
                                    ></path>
                                </svg>

                            </span>

                            <div>
                                <strong>Carrinho</strong>
                                <small>{{ $itensCarrinho }} item(ns)</small>
                            </div>

                        </a>

                    @endif


                    <a href="{{ route('profile.edit') }}">

                        <span>

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <circle
                                    cx="12"
                                    cy="8"
                                    r="4"
                                ></circle>

                                <path
                                    d="M4 21a8 8 0 0 1 16 0"
                                ></path>
                            </svg>

                        </span>

                        <div>
                            <strong>Meu Perfil</strong>
                            <small>Editar minha conta</small>
                        </div>

                    </a>

                </div>

            </section>

        </div>

    </main>

</body>

</html>