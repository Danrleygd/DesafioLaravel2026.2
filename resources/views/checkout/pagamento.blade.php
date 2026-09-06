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
        Pagamento - D-tech
    </title>

    @vite([
        'resources/css/app.css',
        'resources/css/navLanding.css',
        'resources/css/checkout.css',
        'resources/css/checkoutPagamento.css'
    ])

</head>

<body class="checkout-body">

    <x-nav-landing />


    @php

        $metodoSelecionado =
            old(
                'metodo_pagamento',
                session(
                    'checkout_payment_method',
                    'PIX'
                )
            );

    @endphp


    <main class="checkout-page">


        {{-- =========================================================
            ETAPAS
        ========================================================== --}}

        <section class="checkout-progress">

            <div class="checkout-step completed">

                <div class="checkout-step-circle">
                    <i class="bi bi-cart3"></i>
                </div>

                <span>
                    Carrinho
                </span>

            </div>


            <div class="checkout-line active"></div>


            <div class="checkout-step completed">

                <div class="checkout-step-circle">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>

                <span>
                    Endereço
                </span>

            </div>


            <div class="checkout-line active"></div>


            <div class="checkout-step active">

                <div class="checkout-step-circle">
                    <i class="bi bi-credit-card"></i>
                </div>

                <span>
                    Pagamento
                </span>

            </div>


            <div class="checkout-line"></div>


            <div class="checkout-step">

                <div class="checkout-step-circle">
                    4
                </div>

                <span>
                    Confirmação
                </span>

            </div>

        </section>


        {{-- =========================================================
            MENSAGENS
        ========================================================== --}}

        @if(session('error'))

            <div class="checkout-alert error">

                <i class="bi bi-exclamation-circle"></i>

                {{ session('error') }}

            </div>

        @endif


        @error('metodo_pagamento')

            <div class="checkout-alert error">

                <i class="bi bi-exclamation-circle"></i>

                {{ $message }}

            </div>

        @enderror


        {{-- =========================================================
            CABEÇALHO
        ========================================================== --}}

        <section class="checkout-title">

            <div class="checkout-title-icon">

                <i class="bi bi-credit-card"></i>

            </div>


            <div>

                <h1>
                    Forma de pagamento
                </h1>

                <p>
                    Escolha como deseja pagar sua compra.
                </p>

            </div>

        </section>


        {{-- =========================================================
            FORMULÁRIO ÚNICO DA ETAPA
        ========================================================== --}}

        <form
            action="{{ route('checkout.pagamento.pagbank') }}"
            method="POST"
            id="formPagamento"
        >

            @csrf


            <section class="checkout-grid">


                {{-- =================================================
                    COLUNA PRINCIPAL
                ================================================== --}}

                <div class="checkout-main">


                    {{-- =============================================
                        MÉTODOS
                    ============================================== --}}

                    <section class="checkout-card pagamento-card">

                        <div class="pagamento-card-header">

                            <div class="pagbank-logo-box">

                                <i class="bi bi-shield-lock-fill"></i>

                            </div>


                            <div>

                                <h2>
                                    Escolha uma opção
                                </h2>

                                <p>
                                    A opção selecionada será utilizada no Checkout seguro do PagBank.
                                </p>

                            </div>

                        </div>


                        <div class="pagamento-metodos">


                            {{-- PIX --}}

                            <label
                                class="
                                    pagamento-metodo
                                    {{
                                        $metodoSelecionado === 'PIX'
                                            ? 'selected'
                                            : ''
                                    }}
                                "
                                data-payment-card
                            >

                                <input
                                    type="radio"
                                    name="metodo_pagamento"
                                    value="PIX"
                                    class="pagamento-metodo-radio"
                                    {{
                                        $metodoSelecionado === 'PIX'
                                            ? 'checked'
                                            : ''
                                    }}
                                >


                                <span class="pagamento-radio"></span>


                                <div class="pagamento-metodo-icon">

                                    <i class="bi bi-qr-code"></i>

                                </div>


                                <div class="pagamento-metodo-conteudo">

                                    <strong>
                                        Pix
                                    </strong>

                                    <span>
                                        Pagamento rápido por QR Code.
                                    </span>

                                    <small>
                                        Aprovação geralmente imediata
                                    </small>

                                </div>

                            </label>


                            {{-- CARTÃO --}}

                            <label
                                class="
                                    pagamento-metodo
                                    {{
                                        $metodoSelecionado === 'CREDIT_CARD'
                                            ? 'selected'
                                            : ''
                                    }}
                                "
                                data-payment-card
                            >

                                <input
                                    type="radio"
                                    name="metodo_pagamento"
                                    value="CREDIT_CARD"
                                    class="pagamento-metodo-radio"
                                    {{
                                        $metodoSelecionado === 'CREDIT_CARD'
                                            ? 'checked'
                                            : ''
                                    }}
                                >


                                <span class="pagamento-radio"></span>


                                <div class="pagamento-metodo-icon">

                                    <i class="bi bi-credit-card-2-front"></i>

                                </div>


                                <div class="pagamento-metodo-conteudo">

                                    <strong>
                                        Cartão de crédito
                                    </strong>

                                    <span>
                                        Pague com cartão no ambiente PagBank.
                                    </span>

                                    <small>
                                        Parcelamento disponível
                                    </small>

                                </div>

                            </label>


                            {{-- BOLETO --}}

                            <label
                                class="
                                    pagamento-metodo
                                    {{
                                        $metodoSelecionado === 'BOLETO'
                                            ? 'selected'
                                            : ''
                                    }}
                                "
                                data-payment-card
                            >

                                <input
                                    type="radio"
                                    name="metodo_pagamento"
                                    value="BOLETO"
                                    class="pagamento-metodo-radio"
                                    {{
                                        $metodoSelecionado === 'BOLETO'
                                            ? 'checked'
                                            : ''
                                    }}
                                >


                                <span class="pagamento-radio"></span>


                                <div class="pagamento-metodo-icon">

                                    <i class="bi bi-upc-scan"></i>

                                </div>


                                <div class="pagamento-metodo-conteudo">

                                    <strong>
                                        Boleto
                                    </strong>

                                    <span>
                                        Gere o boleto para pagamento.
                                    </span>

                                    <small>
                                        Confirmação após compensação
                                    </small>

                                </div>

                            </label>

                        </div>


                        <div class="pagamento-selecionado-info">

                            <i class="bi bi-check-circle-fill"></i>

                            <span>
                                Forma selecionada:
                            </span>

                            <strong id="metodoSelecionadoTexto">
                                Pix
                            </strong>

                        </div>


                        <div class="pagamento-info">

                            <i class="bi bi-info-circle"></i>

                            <p>
                                Os dados financeiros são informados diretamente
                                no ambiente seguro do PagBank. A D-tech não
                                armazena números de cartão.
                            </p>

                        </div>

                    </section>


                    {{-- =============================================
                        ENDEREÇO
                    ============================================== --}}

                    <section class="checkout-card pagamento-endereco-card">

                        <div class="pagamento-section-header">

                            <div>

                                <span class="pagamento-section-icon">

                                    <i class="bi bi-geo-alt"></i>

                                </span>

                                <h2>
                                    Endereço de entrega
                                </h2>

                            </div>


                            <a
                                href="{{ route('checkout.endereco') }}"
                                class="pagamento-alterar"
                            >
                                Alterar
                            </a>

                        </div>


                        <div class="pagamento-endereco">

                            <strong>

                                {{ $endereco->logradouro }},
                                {{ $endereco->numero }}

                            </strong>


                            @if($endereco->complemento)

                                <span>
                                    {{ $endereco->complemento }}
                                </span>

                            @endif


                            <span>

                                {{ $endereco->bairro }}

                                -

                                {{ $endereco->cidade }}/{{ $endereco->estado }}

                            </span>


                            <span>

                                CEP:

                                {{
                                    strlen($endereco->cep) === 8
                                        ? substr($endereco->cep, 0, 5)
                                            . '-'
                                            . substr($endereco->cep, 5)
                                        : $endereco->cep
                                }}

                            </span>

                        </div>

                    </section>

                </div>


                {{-- =================================================
                    RESUMO
                ================================================== --}}

                <aside class="checkout-sidebar">

                    <div class="checkout-card resumo-card">

                        <div class="resumo-header">

                            <div class="resumo-header-title">

                                <div class="resumo-icon">

                                    <i class="bi bi-receipt"></i>

                                </div>

                                <h2>
                                    Resumo do pedido
                                </h2>

                            </div>


                            <a
                                href="{{ route('carrinho.index') }}"
                                class="voltar-carrinho"
                            >
                                Editar carrinho
                            </a>

                        </div>


                        <div class="resumo-produtos">

                            @foreach($itens as $item)

                                @php

                                    $produto =
                                        $item->produto;

                                    $fotoPrincipal =
                                        $produto
                                            ->fotos
                                            ->firstWhere(
                                                'principal',
                                                true
                                            )
                                        ??
                                        $produto
                                            ->fotos
                                            ->first();

                                    $imagem =
                                        $fotoPrincipal?->foto
                                        ??
                                        $produto->foto;

                                    if (!$imagem) {

                                        $imagemUrl =
                                            asset(
                                                'images/sem-imagem.png'
                                            );

                                    } elseif (
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

                                        $imagemUrl =
                                            $imagem;

                                    } elseif (
                                        str_starts_with(
                                            $imagem,
                                            '/'
                                        )
                                    ) {

                                        $imagemUrl =
                                            asset(
                                                ltrim(
                                                    $imagem,
                                                    '/'
                                                )
                                            );

                                    } else {

                                        $imagemUrl =
                                            asset(
                                                'storage/'
                                                .
                                                ltrim(
                                                    $imagem,
                                                    '/'
                                                )
                                            );
                                    }

                                @endphp


                                <div class="resumo-produto">

                                    <img
                                        src="{{ $imagemUrl }}"
                                        alt="{{ $produto->nome }}"
                                    >


                                    <div class="resumo-produto-info">

                                        <strong>
                                            {{ $produto->nome }}
                                        </strong>


                                        @if($produto->categoria)

                                            <span>
                                                {{ $produto->categoria->nome }}
                                            </span>

                                        @endif


                                        <span>
                                            Qtd: {{ $item->quantidade }}
                                        </span>

                                    </div>


                                    <strong class="resumo-produto-preco">

                                        R$

                                        {{
                                            number_format(
                                                $produto->preco
                                                *
                                                $item->quantidade,
                                                2,
                                                ',',
                                                '.'
                                            )
                                        }}

                                    </strong>

                                </div>

                            @endforeach

                        </div>


                        <div class="resumo-valores">

                            <div class="resumo-row">

                                <span>

                                    Subtotal
                                    (
                                    {{ $itens->sum('quantidade') }}
                                    itens
                                    )

                                </span>

                                <strong>

                                    R$
                                    {{
                                        number_format(
                                            $total,
                                            2,
                                            ',',
                                            '.'
                                        )
                                    }}

                                </strong>

                            </div>


                            <div class="resumo-row">

                                <span>
                                    Frete
                                </span>

                                <strong class="frete-gratis">
                                    Grátis
                                </strong>

                            </div>


                            <div class="resumo-divider"></div>


                            <div class="resumo-total">

                                <span>
                                    Total
                                </span>


                                <strong>

                                    R$
                                    {{
                                        number_format(
                                            $total,
                                            2,
                                            ',',
                                            '.'
                                        )
                                    }}

                                </strong>

                            </div>

                        </div>


                        <button
                            type="submit"
                            class="btn-pagar-pagbank"
                            id="btnPagarPagBank"
                        >

                            <i class="bi bi-lock-fill"></i>

                            <span id="btnPagamentoTexto">
                                Continuar com Pix
                            </span>

                            <i class="bi bi-arrow-right"></i>

                        </button>


                        <div class="checkout-protegido">

                            <i class="bi bi-shield-check"></i>

                            Você será redirecionado para o PagBank

                        </div>

                    </div>

                </aside>

            </section>

        </form>

    </main>


    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const radios =
                    document.querySelectorAll(
                        '.pagamento-metodo-radio'
                    );

                const cards =
                    document.querySelectorAll(
                        '[data-payment-card]'
                    );

                const metodoTexto =
                    document.getElementById(
                        'metodoSelecionadoTexto'
                    );

                const btnTexto =
                    document.getElementById(
                        'btnPagamentoTexto'
                    );


                const nomes = {
                    PIX: 'Pix',
                    CREDIT_CARD:
                        'Cartão de crédito',
                    BOLETO:
                        'Boleto'
                };


                function atualizarSelecao() {

                    let valorSelecionado =
                        'PIX';


                    radios.forEach(
                        function (radio) {

                            const card =
                                radio.closest(
                                    '[data-payment-card]'
                                );

                            if (radio.checked) {

                                valorSelecionado =
                                    radio.value;

                                if (card) {
                                    card.classList.add(
                                        'selected'
                                    );
                                }

                            } else {

                                if (card) {
                                    card.classList.remove(
                                        'selected'
                                    );
                                }
                            }
                        }
                    );


                    const nome =
                        nomes[
                            valorSelecionado
                        ]
                        ??
                        'Pagamento';


                    if (metodoTexto) {
                        metodoTexto.textContent =
                            nome;
                    }


                    if (btnTexto) {
                        btnTexto.textContent =
                            'Continuar com '
                            +
                            nome;
                    }


                    try {
                        localStorage.setItem(
                            'dtech_checkout_payment_method',
                            valorSelecionado
                        );
                    } catch (erro) {
                        console.warn(erro);
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | RESTAURA A OPÇÃO DO NAVEGADOR
                |--------------------------------------------------------------------------
                |
                | A sessão/old() do Laravel continua sendo prioritária.
                | O localStorage ajuda quando o usuário apenas recarrega a tela.
                |
                */

                try {

                    const salvo =
                        localStorage.getItem(
                            'dtech_checkout_payment_method'
                        );

                    const possuiErroLaravel =
                        @json(
                            $errors->has(
                                'metodo_pagamento'
                            )
                        );

                    const possuiSessao =
                        @json(
                            session()->has(
                                'checkout_payment_method'
                            )
                        );

                    if (
                        salvo
                        &&
                        !possuiErroLaravel
                        &&
                        !possuiSessao
                    ) {

                        const radioSalvo =
                            document.querySelector(
                                '.pagamento-metodo-radio[value="'
                                +
                                salvo
                                +
                                '"]'
                            );

                        if (radioSalvo) {
                            radioSalvo.checked =
                                true;
                        }
                    }

                } catch (erro) {

                    console.warn(erro);
                }


                radios.forEach(
                    function (radio) {

                        radio.addEventListener(
                            'change',
                            atualizarSelecao
                        );
                    }
                );


                cards.forEach(
                    function (card) {

                        card.addEventListener(
                            'keydown',
                            function (event) {

                                if (
                                    event.key
                                    !==
                                    'Enter'
                                    &&
                                    event.key
                                    !==
                                    ' '
                                ) {
                                    return;
                                }

                                event.preventDefault();

                                const radio =
                                    card.querySelector(
                                        '.pagamento-metodo-radio'
                                    );

                                if (radio) {
                                    radio.checked =
                                        true;

                                    radio.dispatchEvent(
                                        new Event(
                                            'change',
                                            {
                                                bubbles:
                                                    true
                                            }
                                        )
                                    );
                                }
                            }
                        );
                    }
                );


                atualizarSelecao();
            }
        );

    </script>

</body>

</html>
