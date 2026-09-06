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
        Endereço de Entrega - D-tech
    </title>

    @vite([
        'resources/css/app.css',
        'resources/css/navLanding.css',
        'resources/css/checkout.css',
        'resources/js/checkout.js'
    ])

</head>

<body class="checkout-body">

    <x-nav-landing />


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


            <div class="checkout-step active">

                <div class="checkout-step-circle">
                    <i class="bi bi-geo-alt-fill"></i>
                </div>

                <span>
                    Endereço
                </span>

            </div>


            <div class="checkout-line"></div>


            <div class="checkout-step">

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

        @if(session('success'))

            <div class="checkout-alert success">

                <i class="bi bi-check-circle"></i>

                {{ session('success') }}

            </div>

        @endif


        @if(session('error'))

            <div class="checkout-alert error">

                <i class="bi bi-exclamation-circle"></i>

                {{ session('error') }}

            </div>

        @endif



        {{-- =========================================================
            CABEÇALHO
        ========================================================== --}}

        <section class="checkout-title">

            <div class="checkout-title-icon">

                <i class="bi bi-geo-alt"></i>

            </div>


            <div>

                <h1>
                    Endereço de entrega
                </h1>

                <p>
                    Escolha um endereço de entrega ou cadastre um novo.
                </p>

            </div>

        </section>



        {{-- =========================================================
            GRID
        ========================================================== --}}

        <section class="checkout-grid">


            {{-- =====================================================
                COLUNA PRINCIPAL
            ====================================================== --}}

            <div class="checkout-main">


                {{-- ===============================================
                    ENDEREÇOS
                ================================================ --}}

                <form
                    action="{{ route('checkout.endereco.selecionar') }}"
                    method="POST"
                    id="formSelecionarEndereco"
                    class="checkout-card endereco-card"
                >

                    @csrf


                    <div class="checkout-card-header">

                        <h2>
                            Meus endereços
                        </h2>


                        <button
                            type="button"
                            class="btn-novo-endereco"
                            id="btnNovoEndereco"
                        >

                            <i class="bi bi-plus-lg"></i>

                            Novo endereço

                        </button>

                    </div>



                    <div class="enderecos-lista">

                        @forelse($enderecos as $index => $endereco)

                            <label
                                class="
                                    endereco-item
                                    {{
                                        (int) $enderecoSelecionadoId
                                        ===
                                        (int) $endereco->id
                                            ? 'selected'
                                            : ''
                                    }}
                                "
                            >

                                <input
                                    type="radio"
                                    name="endereco_id"
                                    value="{{ $endereco->id }}"
                                    class="endereco-radio"

                                    {{
                                        (int) $enderecoSelecionadoId
                                        ===
                                        (int) $endereco->id
                                            ? 'checked'
                                            : ''
                                    }}
                                >


                                <span class="radio-custom"></span>


                                <div class="endereco-icon">

                                    <i class="bi bi-house-door"></i>

                                </div>


                                <div class="endereco-info">

                                    <strong>
                                        Endereço {{ $index + 1 }}
                                    </strong>

                                    <span>
                                        {{ $endereco->logradouro }},
                                        {{ $endereco->numero }}
                                    </span>


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


                                @if($index === 0)

                                    <span class="endereco-principal">
                                        Principal
                                    </span>

                                @endif

                            </label>


                        @empty

                            <div class="enderecos-empty">

                                <i class="bi bi-geo-alt"></i>

                                <h3>
                                    Nenhum endereço cadastrado
                                </h3>

                                <p>
                                    Cadastre um endereço para continuar com sua compra.
                                </p>

                            </div>

                        @endforelse

                    </div>

                </form>



                {{-- ===============================================
                    NOVO ENDEREÇO
                ================================================ --}}

                <form
                    action="{{ route('checkout.endereco.store') }}"
                    method="POST"
                    class="
                        checkout-card
                        novo-endereco-card
                        {{ $enderecos->isEmpty() ? 'show' : '' }}
                    "
                    id="novoEnderecoForm"
                >

                    @csrf


                    <div class="checkout-card-header">

                        <div>

                            <h2>
                                Adicionar novo endereço
                            </h2>

                            <p>
                                Preencha os dados abaixo para cadastrar um novo endereço.
                            </p>

                        </div>


                        @if($enderecos->isNotEmpty())

                            <button
                                type="button"
                                class="btn-fechar-endereco"
                                id="btnFecharEndereco"
                            >

                                <i class="bi bi-x-lg"></i>

                            </button>

                        @endif

                    </div>



                    <div class="checkout-form-grid">


                        {{-- CEP --}}

                        <div class="checkout-field cep-field">

                            <label for="cep">

                                CEP

                                <span>*</span>

                            </label>


                            <div class="cep-input-group">

                                <input
                                    type="text"
                                    id="cep"
                                    name="cep"
                                    maxlength="9"
                                    placeholder="00000-000"
                                    value="{{ old('cep') }}"
                                    required
                                >


                                <button
                                    type="button"
                                    id="buscarCep"
                                    class="btn-buscar-cep"
                                >

                                    <i class="bi bi-search"></i>

                                </button>

                            </div>


                            <small
                                class="cep-status"
                                id="cepStatus"
                            ></small>

                        </div>



                        {{-- LOGRADOURO --}}

                        <div class="checkout-field logradouro-field">

                            <label for="logradouro">

                                Rua

                                <span>*</span>

                            </label>


                            <input
                                type="text"
                                name="logradouro"
                                id="logradouro"
                                value="{{ old('logradouro') }}"
                                placeholder="Nome da rua"
                                required
                            >

                        </div>



                        {{-- NÚMERO --}}

                        <div class="checkout-field numero-field">

                            <label for="numero">

                                Número

                                <span>*</span>

                            </label>


                            <input
                                type="text"
                                name="numero"
                                id="numero"
                                value="{{ old('numero') }}"
                                placeholder="Ex: 123"
                                required
                            >

                        </div>



                        {{-- COMPLEMENTO --}}

                        <div class="checkout-field complemento-field">

                            <label for="complemento">
                                Complemento
                            </label>


                            <input
                                type="text"
                                name="complemento"
                                id="complemento"
                                value="{{ old('complemento') }}"
                                placeholder="Ex: Apto 101"
                            >

                        </div>



                        {{-- BAIRRO --}}

                        <div class="checkout-field">

                            <label for="bairro">

                                Bairro

                                <span>*</span>

                            </label>


                            <input
                                type="text"
                                name="bairro"
                                id="bairro"
                                value="{{ old('bairro') }}"
                                placeholder="Nome do bairro"
                                required
                            >

                        </div>



                        {{-- CIDADE --}}

                        <div class="checkout-field">

                            <label for="cidade">

                                Cidade

                                <span>*</span>

                            </label>


                            <input
                                type="text"
                                name="cidade"
                                id="cidade"
                                value="{{ old('cidade') }}"
                                placeholder="Nome da cidade"
                                required
                            >

                        </div>



                        {{-- ESTADO --}}

                        <div class="checkout-field">

                            <label for="estado">

                                Estado

                                <span>*</span>

                            </label>


                            <input
                                type="text"
                                name="estado"
                                id="estado"
                                maxlength="2"
                                value="{{ old('estado') }}"
                                placeholder="MG"
                                required
                            >

                        </div>

                    </div>



                    <div class="novo-endereco-actions">

                        <button
                            type="submit"
                            class="btn-salvar-endereco"
                        >

                            <i class="bi bi-floppy"></i>

                            Salvar endereço

                        </button>

                    </div>

                </form>

            </div>



            {{-- =====================================================
                RESUMO
            ====================================================== --}}

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

                            Voltar para o carrinho

                        </a>

                    </div>



                    <div class="resumo-produtos">

                        @foreach($itens as $item)

                            @php

                                $produto = $item->produto;

                                if (!$produto) {
                                    continue;
                                }

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

                                    $imagemUrl = $imagem;

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



                    {{-- SEM CUPOM --}}


                    <button
                        type="submit"
                        form="formSelecionarEndereco"
                        class="btn-continuar-pagamento"
                        {{ $enderecos->isEmpty() ? 'disabled' : '' }}
                    >

                        <i class="bi bi-credit-card"></i>

                        Continuar para o pagamento

                        <i class="bi bi-arrow-right"></i>

                    </button>



                    <div class="checkout-protegido">

                        <i class="bi bi-lock-fill"></i>

                        Seus dados estão protegidos

                    </div>



                    <div class="compra-segura">

                        <div class="compra-segura-icon">

                            <i class="bi bi-shield-check"></i>

                        </div>


                        <div>

                            <strong>
                                Compra segura
                            </strong>

                            <p>
                                Seus dados pessoais e de pagamento
                                são protegidos durante todo o processo.
                            </p>

                        </div>

                    </div>

                </div>

            </aside>

        </section>

    </main>

</body>

</html>