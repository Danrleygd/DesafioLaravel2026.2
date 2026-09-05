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
        {{ $produto->nome }} - D-tech
    </title>


    @vite([
        'resources/css/app.css',
        'resources/css/navLanding.css',
        'resources/css/footer.css',
        'resources/css/produto.css'
    ])

</head>


@php

    /*
    |--------------------------------------------------------------------------
    | FUNÇÃO DE IMAGEM
    |--------------------------------------------------------------------------
    */

    $imagemUrl = function ($imagem) {

        if (!$imagem) {
            return asset(
                'assets/images/sem-imagem.png'
            );
        }


        if (
            str_starts_with(
                $imagem,
                'http://'
            )
            ||
            str_starts_with(
                $imagem,
                'https://'
            )
        ) {
            return $imagem;
        }


        if (
            str_starts_with(
                $imagem,
                '/'
            )
        ) {
            return asset(
                ltrim(
                    $imagem,
                    '/'
                )
            );
        }


        return asset(
            'storage/' .
            ltrim(
                $imagem,
                '/'
            )
        );
    };


    /*
    |--------------------------------------------------------------------------
    | GALERIA
    |--------------------------------------------------------------------------
    */

    $galeria = collect();


    /*
     * Primeiro procura a imagem
     * marcada como principal.
     */

    $fotoPrincipal = $produto
        ->fotos
        ->firstWhere(
            'principal',
            true
        );


    $imagemPrincipal =
        $fotoPrincipal?->foto
        ??
        $produto->foto;


    if ($imagemPrincipal) {

        $galeria->push(
            $imagemPrincipal
        );
    }


    /*
     * Adiciona as demais imagens,
     * sem repetir a principal.
     */

    foreach (
        $produto->fotos
        as
        $foto
    ) {

        if (
            $foto->foto
            !==
            $imagemPrincipal
        ) {

            $galeria->push(
                $foto->foto
            );
        }
    }


    /*
     * Evita imagens duplicadas.
     */

    $galeria =
        $galeria
            ->filter()
            ->unique()
            ->values();


    /*
    |--------------------------------------------------------------------------
    | DADOS VISUAIS
    |--------------------------------------------------------------------------
    */

    $avaliacao = 4.7;

    $quantidadeAvaliacoes = 218;


    /*
     * Seu banco atualmente utiliza
     * o preço atual do produto.
     *
     * Criamos apenas um preço antigo
     * visual de 25% acima.
     */

    $precoAnterior =
        (float) $produto->preco
        /
        0.75;


    $parcelamento =
        (float) $produto->preco
        /
        10;


    /*
    |--------------------------------------------------------------------------
    | VENDEDOR
    |--------------------------------------------------------------------------
    */

    $vendedor =
        $produto->vendedor
        ?? null;

@endphp


<body class="produto-body">

    {{-- =========================================================
        NAVBAR
    ========================================================== --}}

    <x-nav-landing />


    {{-- =========================================================
        CONTEÚDO
    ========================================================== --}}

    <main class="produto-page">


        {{-- =====================================================
            BREADCRUMB
        ====================================================== --}}

        <nav class="produto-breadcrumb">

            <a href="{{ route('landing') }}">
                Início
            </a>

            <span>
                ›
            </span>

            <a
                href="{{ route('landing', [
                    'categoria' => $produto->categoria_id
                ]) }}"
            >
                {{ $produto->categoria?->nome
                    ?? 'Produtos'
                }}
            </a>

            <span>
                ›
            </span>

            <strong>
                {{ $produto->nome }}
            </strong>

        </nav>


        {{-- =====================================================
            PRODUTO PRINCIPAL
        ====================================================== --}}

        <section class="produto-principal">


            {{-- =================================================
                GALERIA
            ================================================== --}}

            <div class="produto-galeria">


                {{-- IMAGEM GRANDE --}}
                <div class="produto-imagem-container">

                    @if(
                        $galeria->count()
                        > 1
                    )

                        <button
                            type="button"
                            class="produto-galeria-seta produto-galeria-anterior"
                            id="galeriaAnterior"
                            aria-label="Imagem anterior"
                        >
                            ‹
                        </button>

                    @endif


                    <img
                        src="{{ $imagemUrl(
                            $galeria->first()
                            ?? $produto->foto
                        ) }}"
                        alt="{{ $produto->nome }}"
                        class="produto-imagem-principal"
                        id="produtoImagemPrincipal"
                    >


                    @if(
                        $galeria->count()
                        > 1
                    )

                        <button
                            type="button"
                            class="produto-galeria-seta produto-galeria-proxima"
                            id="galeriaProxima"
                            aria-label="Próxima imagem"
                        >
                            ›
                        </button>

                    @endif


                    {{-- AMPLIAR --}}
                    <button
                        type="button"
                        class="produto-ampliar"
                        id="produtoAmpliar"
                        aria-label="Ampliar imagem"
                    >

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >
                            <path d="M8 3H3v5"></path>
                            <path d="M16 3h5v5"></path>
                            <path d="M8 21H3v-5"></path>
                            <path d="M16 21h5v-5"></path>
                        </svg>

                    </button>

                </div>


                {{-- MINIATURAS --}}
                @if(
                    $galeria->count()
                    > 1
                )

                    <div class="produto-miniaturas">

                        @foreach($galeria as $index => $imagem)

                            <button
                                type="button"
                                class="
                                    produto-miniatura
                                    {{ $index === 0
                                        ? 'ativa'
                                        : ''
                                    }}
                                "
                                data-index="{{ $index }}"
                            >

                                <img
                                    src="{{ $imagemUrl($imagem) }}"
                                    alt="Imagem {{ $index + 1 }} de {{ $produto->nome }}"
                                >

                            </button>

                        @endforeach

                    </div>

                @endif

            </div>


            {{-- =================================================
                INFORMAÇÕES
            ================================================== --}}

            <div class="produto-informacoes">


                {{-- CATEGORIA --}}
                <span class="produto-marca">

                    {{ $produto->categoria?->nome
                        ?? 'Produto'
                    }}

                </span>


                {{-- TÍTULO --}}
                <div class="produto-titulo-row">

                    <h1>
                        {{ $produto->nome }}
                    </h1>


                    @if(
                        $produto->created_at
                        &&
                        $produto->created_at
                            ->greaterThan(
                                now()->subDays(15)
                            )
                    )

                        <span class="produto-lancamento">
                            NOVO
                        </span>

                    @endif

                </div>


                {{-- AVALIAÇÃO --}}
                <div class="produto-avaliacao">

                    <div class="produto-estrelas">

                        @for(
                            $i = 0;
                            $i < 5;
                            $i++
                        )

                            <span>
                                ★
                            </span>

                        @endfor

                    </div>


                    <strong>
                        {{ number_format(
                            $avaliacao,
                            1,
                            ',',
                            '.'
                        ) }}
                    </strong>


                    <span>
                        ({{ $quantidadeAvaliacoes }})
                    </span>

                </div>


                {{-- DESCRIÇÃO CURTA --}}
                <p class="produto-resumo">

                    {{ \Illuminate\Support\Str::limit(
                        $produto->descricao,
                        180
                    ) }}

                </p>


                {{-- PREÇO --}}
                <div class="produto-preco-area">

                    <div class="produto-preco-linha">

                        <strong class="produto-preco">

                            R$
                            {{ number_format(
                                $produto->preco,
                                2,
                                ',',
                                '.'
                            ) }}

                        </strong>


                        <span class="produto-preco-antigo">

                            R$
                            {{ number_format(
                                $precoAnterior,
                                2,
                                ',',
                                '.'
                            ) }}

                        </span>


                        <span class="produto-desconto">
                            25% OFF
                        </span>

                    </div>


                    <span class="produto-parcelamento">

                        Em até 10x de

                        <strong>

                            R$
                            {{ number_format(
                                $parcelamento,
                                2,
                                ',',
                                '.'
                            ) }}

                        </strong>

                        sem juros

                    </span>

                </div>


                {{-- ESTOQUE --}}
                @if(
                    $produto->quantidade
                    > 0
                )

                    <div class="produto-estoque produto-estoque-ok">

                        <span></span>

                        {{ $produto->quantidade }}
                        unidade(s) disponível(is)

                    </div>

                @else

                    <div class="produto-estoque produto-estoque-indisponivel">

                        Produto esgotado

                    </div>

                @endif


                {{-- =================================================
                    COMPRA
                ================================================== --}}

                @if(
                    $produto->quantidade
                    > 0
                )

                    <div class="produto-acoes">


                        {{-- QUANTIDADE --}}
                        <div class="produto-quantidade">

                            <button
                                type="button"
                                id="quantidadeMenos"
                                aria-label="Diminuir quantidade"
                            >
                                −
                            </button>


                            <input
                                type="number"
                                id="produtoQuantidade"
                                value="1"
                                min="1"
                                max="{{ $produto->quantidade }}"
                                readonly
                            >


                            <button
                                type="button"
                                id="quantidadeMais"
                                aria-label="Aumentar quantidade"
                            >
                                +
                            </button>

                        </div>


                        {{-- CARRINHO --}}
                        @auth

                            <form
                                action="{{ route(
                                    'carrinho.adicionar',
                                    $produto
                                ) }}"
                                method="POST"
                                class="produto-carrinho-form"
                                id="produtoCarrinhoForm"
                            >

                                @csrf


                                <input
                                    type="hidden"
                                    name="quantidade"
                                    id="produtoQuantidadeCarrinho"
                                    value="1"
                                >


                                <button
                                    type="submit"
                                    class="produto-btn-carrinho"
                                >

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

                                    Adicionar ao Carrinho

                                </button>

                            </form>

                        @else

                            <a
                                href="{{ route('login') }}"
                                class="produto-btn-carrinho"
                            >

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

                                Entrar para comprar

                            </a>

                        @endauth


                        {{-- FAVORITO --}}
                        <button
                            type="button"
                            class="produto-btn-favorito"
                            id="produtoFavorito"
                            aria-label="Adicionar aos favoritos"
                        >

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.6l-1-1a5.5 5.5 0 0 0-7.8 7.8l1 1L12 21l7.8-7.6 1-1a5.5 5.5 0 0 0 0-7.8Z"
                                ></path>
                            </svg>

                        </button>

                    </div>


                    {{-- COMPRAR AGORA --}}
                    @if(
                        Route::has(
                            'carrinho.index'
                        )
                    )

                        <a
                            href="{{ route('carrinho.index') }}"
                            class="produto-btn-comprar"
                        >
                            Comprar Agora
                        </a>

                    @endif

                @endif


                {{-- =================================================
                    BENEFÍCIOS
                ================================================== --}}

                <div class="produto-beneficios">


                    <div class="produto-beneficio">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path d="M3 7h11v9H3z"></path>
                            <path d="M14 10h4l3 3v3h-7z"></path>
                            <circle cx="7" cy="18" r="2"></circle>
                            <circle cx="18" cy="18" r="2"></circle>
                        </svg>


                        <div>

                            <strong>
                                Entrega segura
                            </strong>

                            <span>
                                acompanhe seu pedido
                            </span>

                        </div>

                    </div>


                    <div class="produto-beneficio">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path
                                d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10"
                            ></path>

                            <path
                                d="m9 12 2 2 4-5"
                            ></path>
                        </svg>


                        <div>

                            <strong>
                                Compra segura
                            </strong>

                            <span>
                                seus dados protegidos
                            </span>

                        </div>

                    </div>


                    <div class="produto-beneficio">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.7"
                        >
                            <path
                                d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"
                            ></path>

                            <path
                                d="m3.3 7 8.7 5 8.7-5"
                            ></path>

                            <path
                                d="M12 22V12"
                            ></path>
                        </svg>


                        <div>

                            <strong>
                                Produto verificado
                            </strong>

                            <span>
                                anúncio identificado
                            </span>

                        </div>

                    </div>

                </div>


                {{-- =================================================
                    VENDEDOR
                ================================================== --}}

                @if($vendedor)

                    <div class="produto-vendedor">

                        <div class="produto-vendedor-avatar">

                            {{ strtoupper(
                                substr(
                                    $vendedor->nome,
                                    0,
                                    1
                                )
                            ) }}

                        </div>


                        <div>

                            <span>
                                Vendido por
                            </span>

                            <strong>
                                {{ $vendedor->nome }}
                            </strong>

                        </div>

                    </div>

                @endif

            </div>

        </section>


        {{-- =====================================================
            DESCRIÇÃO E ESPECIFICAÇÃO
        ====================================================== --}}

        <section class="produto-detalhes">

            <div class="produto-detalhe">

                <h2>
                    Descrição
                </h2>

                <div class="produto-detalhe-texto">

                    {!! nl2br(
                        e(
                            $produto->descricao
                        )
                    ) !!}

                </div>

            </div>


            <div class="produto-detalhe-divisor"></div>


            <div class="produto-detalhe">

                <h2>
                    Especificação Técnica
                </h2>


                <div class="produto-especificacoes">

                    <div>

                        <span>
                            Categoria
                        </span>

                        <strong>

                            {{ $produto->categoria?->nome
                                ?? 'Não informada'
                            }}

                        </strong>

                    </div>


                    <div>

                        <span>
                            Disponibilidade
                        </span>

                        <strong>

                            {{ $produto->quantidade > 0
                                ? $produto->quantidade . ' unidade(s)'
                                : 'Esgotado'
                            }}

                        </strong>

                    </div>


                    <div>

                        <span>
                            Código do produto
                        </span>

                        <strong>
                            #{{ $produto->id }}
                        </strong>

                    </div>


                    @if($vendedor)

                        <div>

                            <span>
                                Vendedor
                            </span>

                            <strong>
                                {{ $vendedor->nome }}
                            </strong>

                        </div>

                    @endif

                </div>

            </div>

        </section>


        {{-- =====================================================
            MESMA CATEGORIA
        ====================================================== --}}

        @if(
            $produtosRelacionados->count()
            > 0
        )

            <section class="produto-relacionados">


                <div class="produto-relacionados-header">

                    <div>

                        <span class="produto-relacionados-icone">

                            <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="1.8"
                            >
                                <path
                                    d="m12 2 3 3 4-.5.5 4 3 3-3 3-.5 4-4-.5-3 3-3-3-4 .5-.5-4-3-3 3-3 .5-4 4 .5 3-3Z"
                                ></path>
                            </svg>

                        </span>


                        <h2>
                            Na Mesma Categoria
                        </h2>

                    </div>


                    <a
                        href="{{ route(
                            'landing',
                            [
                                'categoria' =>
                                    $produto->categoria_id
                            ]
                        ) }}"
                    >
                        Explorar mais →

                    </a>

                </div>


                <div class="produto-relacionados-wrapper">

                    <button
                        type="button"
                        class="produto-relacionados-seta produto-relacionados-esquerda"
                        id="relacionadosAnterior"
                    >
                        ‹
                    </button>


                    <div
                        class="produto-relacionados-lista"
                        id="relacionadosLista"
                    >

                        @foreach($produtosRelacionados as $produtoRelacionado)

                            @php

                                $fotoRelacionado =
                                    $produtoRelacionado
                                        ->fotos
                                        ->firstWhere(
                                            'principal',
                                            true
                                        )
                                    ??
                                    $produtoRelacionado
                                        ->fotos
                                        ->first();


                                $imagemRelacionado =
                                    $fotoRelacionado?->foto
                                    ??
                                    $produtoRelacionado->foto;

                            @endphp


                            <a
                                href="{{ route(
                                    'produto.show',
                                    $produtoRelacionado->id
                                ) }}"
                                class="produto-relacionado-card"
                            >

                                <div class="produto-relacionado-imagem">

                                    <img
                                        src="{{ $imagemUrl(
                                            $imagemRelacionado
                                        ) }}"
                                        alt="{{ $produtoRelacionado->nome }}"
                                    >


                                    <button
                                        type="button"
                                        class="produto-relacionado-favorito"
                                        onclick="
                                            event.preventDefault();
                                            event.stopPropagation();
                                        "
                                    >
                                        ♡
                                    </button>

                                </div>


                                <div class="produto-relacionado-info">

                                    <span>

                                        {{ $produtoRelacionado
                                            ->categoria?->nome
                                            ??
                                            $produto
                                                ->categoria?->nome
                                            ??
                                            'Produto'
                                        }}

                                    </span>


                                    <h3>
                                        {{ $produtoRelacionado->nome }}
                                    </h3>


                                    <div class="produto-relacionado-preco">

                                        <strong>

                                            R$
                                            {{ number_format(
                                                $produtoRelacionado->preco,
                                                2,
                                                ',',
                                                '.'
                                            ) }}

                                        </strong>

                                    </div>


                                    <div class="produto-relacionado-avaliacao">

                                        <span>
                                            ★
                                        </span>

                                        <strong>
                                            4,7
                                        </strong>

                                        <small>
                                            (218)
                                        </small>

                                    </div>

                                </div>

                            </a>

                        @endforeach

                    </div>


                    <button
                        type="button"
                        class="produto-relacionados-seta produto-relacionados-direita"
                        id="relacionadosProximo"
                    >
                        ›
                    </button>

                </div>

            </section>

        @endif

    </main>


    {{-- =========================================================
        MODAL DE IMAGEM
    ========================================================== --}}

    <div
        class="produto-modal-imagem"
        id="produtoModalImagem"
    >

        <button
            type="button"
            class="produto-modal-fechar"
            id="produtoModalFechar"
        >
            ×
        </button>


        <img
            src=""
            alt="{{ $produto->nome }}"
            id="produtoModalImagemConteudo"
        >

    </div>


    {{-- =========================================================
        FOOTER
    ========================================================== --}}

    <x-footer />


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                /*
                |--------------------------------------------------------------------------
                | GALERIA
                |--------------------------------------------------------------------------
                */

                const imagens = @json(
                    $galeria
                        ->map(
                            fn ($imagem) =>
                                $imagemUrl($imagem)
                        )
                        ->values()
                );


                let imagemAtual =
                    0;


                const imagemPrincipal =
                    document.getElementById(
                        'produtoImagemPrincipal'
                    );


                const miniaturas =
                    document.querySelectorAll(
                        '.produto-miniatura'
                    );


                const anterior =
                    document.getElementById(
                        'galeriaAnterior'
                    );


                const proxima =
                    document.getElementById(
                        'galeriaProxima'
                    );


                function atualizarGaleria() {

                    if (
                        !imagemPrincipal
                        ||
                        imagens.length === 0
                    ) {

                        return;
                    }


                    imagemPrincipal.src =
                        imagens[imagemAtual];


                    miniaturas.forEach(
                        function (
                            miniatura,
                            index
                        ) {

                            miniatura.classList.toggle(
                                'ativa',
                                index === imagemAtual
                            );
                        }
                    );
                }


                miniaturas.forEach(
                    function (miniatura) {

                        miniatura.addEventListener(
                            'click',
                            function () {

                                imagemAtual =
                                    Number(
                                        this.dataset.index
                                    );


                                atualizarGaleria();
                            }
                        );
                    }
                );


                if (anterior) {

                    anterior.addEventListener(
                        'click',
                        function () {

                            imagemAtual--;


                            if (
                                imagemAtual < 0
                            ) {

                                imagemAtual =
                                    imagens.length - 1;
                            }


                            atualizarGaleria();
                        }
                    );
                }


                if (proxima) {

                    proxima.addEventListener(
                        'click',
                        function () {

                            imagemAtual++;


                            if (
                                imagemAtual >=
                                imagens.length
                            ) {

                                imagemAtual =
                                    0;
                            }


                            atualizarGaleria();
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | QUANTIDADE
                |--------------------------------------------------------------------------
                */

                const quantidade =
                    document.getElementById(
                        'produtoQuantidade'
                    );


                const quantidadeCarrinho =
                    document.getElementById(
                        'produtoQuantidadeCarrinho'
                    );


                const menos =
                    document.getElementById(
                        'quantidadeMenos'
                    );


                const mais =
                    document.getElementById(
                        'quantidadeMais'
                    );


                function atualizarQuantidade(
                    valor
                ) {

                    if (!quantidade) {
                        return;
                    }


                    let atual =
                        Number(
                            quantidade.value
                        )
                        || 1;


                    const maximo =
                        Number(
                            quantidade.max
                        )
                        || 1;


                    atual += valor;


                    if (atual < 1) {
                        atual = 1;
                    }


                    if (
                        atual > maximo
                    ) {
                        atual = maximo;
                    }


                    quantidade.value =
                        atual;


                    if (
                        quantidadeCarrinho
                    ) {

                        quantidadeCarrinho.value =
                            atual;
                    }
                }


                if (menos) {

                    menos.addEventListener(
                        'click',
                        function () {

                            atualizarQuantidade(
                                -1
                            );
                        }
                    );
                }


                if (mais) {

                    mais.addEventListener(
                        'click',
                        function () {

                            atualizarQuantidade(
                                1
                            );
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | FAVORITO VISUAL
                |--------------------------------------------------------------------------
                */

                const favorito =
                    document.getElementById(
                        'produtoFavorito'
                    );


                if (favorito) {

                    favorito.addEventListener(
                        'click',
                        function () {

                            this.classList.toggle(
                                'ativo'
                            );
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | MODAL
                |--------------------------------------------------------------------------
                */

                const ampliar =
                    document.getElementById(
                        'produtoAmpliar'
                    );


                const modal =
                    document.getElementById(
                        'produtoModalImagem'
                    );


                const modalImagem =
                    document.getElementById(
                        'produtoModalImagemConteudo'
                    );


                const modalFechar =
                    document.getElementById(
                        'produtoModalFechar'
                    );


                function abrirModal() {

                    if (
                        !modal
                        ||
                        !modalImagem
                        ||
                        !imagemPrincipal
                    ) {

                        return;
                    }


                    modalImagem.src =
                        imagemPrincipal.src;


                    modal.classList.add(
                        'aberto'
                    );


                    document.body.style.overflow =
                        'hidden';
                }


                function fecharModal() {

                    if (!modal) {
                        return;
                    }


                    modal.classList.remove(
                        'aberto'
                    );


                    document.body.style.overflow =
                        '';
                }


                if (ampliar) {

                    ampliar.addEventListener(
                        'click',
                        abrirModal
                    );
                }


                if (modalFechar) {

                    modalFechar.addEventListener(
                        'click',
                        fecharModal
                    );
                }


                if (modal) {

                    modal.addEventListener(
                        'click',
                        function (event) {

                            if (
                                event.target ===
                                modal
                            ) {

                                fecharModal();
                            }
                        }
                    );
                }


                document.addEventListener(
                    'keydown',
                    function (event) {

                        if (
                            event.key ===
                            'Escape'
                        ) {

                            fecharModal();
                        }
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | RELACIONADOS
                |--------------------------------------------------------------------------
                */

                const relacionados =
                    document.getElementById(
                        'relacionadosLista'
                    );


                const relacionadosAnterior =
                    document.getElementById(
                        'relacionadosAnterior'
                    );


                const relacionadosProximo =
                    document.getElementById(
                        'relacionadosProximo'
                    );


                if (
                    relacionados
                    &&
                    relacionadosAnterior
                ) {

                    relacionadosAnterior
                        .addEventListener(
                            'click',
                            function () {

                                relacionados.scrollBy({
                                    left:
                                        -relacionados.clientWidth
                                        * .75,

                                    behavior:
                                        'smooth'
                                });
                            }
                        );
                }


                if (
                    relacionados
                    &&
                    relacionadosProximo
                ) {

                    relacionadosProximo
                        .addEventListener(
                            'click',
                            function () {

                                relacionados.scrollBy({
                                    left:
                                        relacionados.clientWidth
                                        * .75,

                                    behavior:
                                        'smooth'
                                });
                            }
                        );
                }

            }
        );

    </script>

</body>

</html>