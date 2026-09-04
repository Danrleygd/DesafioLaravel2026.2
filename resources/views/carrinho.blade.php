<x-app-layout>

    @vite(['resources/css/carrinho.css'])

    <main class="carrinho-page">

        {{-- =========================================================
            MENSAGENS
        ========================================================== --}}

        @if(session('success'))
            <div class="carrinho-alert success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="carrinho-alert error">
                {{ session('error') }}
            </div>
        @endif


        {{-- =========================================================
            CABEÇALHO
        ========================================================== --}}

        <section class="carrinho-topo">

            <div class="carrinho-titulo-area">

                <div class="carrinho-icon">
                    <i class="bi bi-cart3"></i>
                </div>

                <div>
                    <h1>Seu Carrinho</h1>
                    <p>
                        Revise seus produtos antes de continuar.
                    </p>
                </div>

            </div>


            {{-- ETAPAS --}}

            <div class="checkout-steps">

                <div class="step active">

                    <div class="step-number">
                        1
                    </div>

                    <span>
                        Carrinho
                    </span>

                </div>

                <div class="step-line active"></div>

                <div class="step">

                    <div class="step-number">
                        2
                    </div>

                    <span>
                        Endereço
                    </span>

                </div>

                <div class="step-line"></div>

                <div class="step">

                    <div class="step-number">
                        3
                    </div>

                    <span>
                        Pagamento
                    </span>

                </div>

            </div>

        </section>


        {{-- =========================================================
            LAYOUT
        ========================================================== --}}

        <section class="carrinho-layout">


            {{-- =====================================================
                PRODUTOS
            ====================================================== --}}

            <div class="carrinho-produtos">

                @if($itens->count() > 0)

                    <div class="carrinho-actions">

                        <label class="selecionar-todos">

                            <input
                                type="checkbox"
                                id="selecionarTodos"
                                checked
                            >

                            <span>
                                <strong id="quantidadeSelecionados">
                                    {{ $itens->count() }}
                                </strong>
                                /
                                <span id="quantidadeTotal">
                                    {{ $itens->count() }}
                                </span>
                                selecionados
                            </span>

                        </label>


                        <div class="actions-buttons">

                            <button
                                type="button"
                                class="btn-remover-selecionados"
                                id="removerSelecionados"
                            >

                                <i class="bi bi-trash3"></i>

                                Remover selecionados

                            </button>


                            <button
                                type="button"
                                class="btn-limpar"
                                id="limparCarrinho"
                            >

                                <i class="bi bi-trash"></i>

                                Limpar carrinho

                            </button>

                        </div>

                    </div>

                @endif


                {{-- =================================================
                    LOOP DOS PRODUTOS
                ================================================== --}}

                <div id="listaProdutos">

                    @forelse($itens as $item)

                        @php

                            $produto = $item->produto;

                            $fotoPrincipal = null;

                            if (
                                $produto &&
                                $produto->fotos->count() > 0
                            ) {

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
                            }

                            $imagem =
                                $fotoPrincipal?->foto
                                ??
                                $produto?->foto;

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


                        @if($produto)

                            <article
                                class="produto-card selecionado"
                                id="produto-item-{{ $item->id }}"
                                data-item-id="{{ $item->id }}"
                                data-price="{{ (float) $produto->preco }}"
                                data-update-url="{{ route('carrinho.atualizar', $item->id) }}"
                                data-remove-url="{{ route('carrinho.remover', $item->id) }}"
                            >

                                {{-- CHECKBOX --}}

                                <div class="produto-checkbox">

                                    <input
                                        type="checkbox"
                                        class="produto-select"
                                        checked
                                    >

                                </div>


                                {{-- IMAGEM --}}

                                <a
                                    href="{{ route('produto.show', $produto->id) }}"
                                    class="produto-imagem"
                                >

                                    <img
                                        src="{{ $imagemUrl }}"
                                        alt="{{ $produto->nome }}"
                                    >

                                </a>


                                {{-- INFORMAÇÕES --}}

                                <div class="produto-info">

                                    <a
                                        href="{{ route('produto.show', $produto->id) }}"
                                        class="produto-link"
                                    >

                                        <h2>
                                            {{ $produto->nome }}
                                        </h2>

                                    </a>


                                    @if($produto->categoria)

                                        <p class="produto-modelo">

                                            {{ $produto->categoria->nome }}

                                        </p>

                                    @endif


                                    <p class="produto-vendedor">

                                        Vendido por:

                                        <strong>

                                            {{ $produto->vendedor->nome ?? 'Vendedor' }}

                                        </strong>

                                    </p>


                                    <p class="produto-entrega">

                                        <i class="bi bi-truck"></i>

                                        Chega em até 3 dias

                                    </p>

                                </div>


                                {{-- PREÇO / QUANTIDADE --}}

                                <div class="produto-lateral">

                                    <strong class="produto-preco">

                                        R$
                                        {{ number_format(
                                            $produto->preco,
                                            2,
                                            ',',
                                            '.'
                                        ) }}

                                    </strong>


                                    <div class="produto-controls">

                                        <div class="quantidade">

                                            <button
                                                type="button"
                                                class="quantidade-btn diminuir"
                                                aria-label="Diminuir quantidade"
                                            >
                                                −
                                            </button>


                                            <span
                                                class="quantidade-valor"
                                                data-stock="{{ $produto->quantidade }}"
                                            >
                                                {{ $item->quantidade }}
                                            </span>


                                            <button
                                                type="button"
                                                class="quantidade-btn aumentar"
                                                aria-label="Aumentar quantidade"
                                            >
                                                +
                                            </button>

                                        </div>


                                        <button
                                            type="button"
                                            class="produto-remover"
                                            aria-label="Remover produto"
                                        >

                                            <i class="bi bi-trash3"></i>

                                        </button>

                                    </div>

                                </div>

                            </article>

                        @endif


                    @empty

                        <div
                            class="carrinho-vazio"
                            id="carrinhoVazio"
                        >

                            <i class="bi bi-cart-x"></i>

                            <h2>
                                Seu carrinho está vazio
                            </h2>

                            <p>
                                Explore nossos produtos e adicione
                                itens ao carrinho.
                            </p>

                            <a
                                href="{{ route('produtos.index') }}"
                                class="btn-explorar"
                            >
                                Explorar produtos
                            </a>

                        </div>

                    @endforelse

                </div>

            </div>


            {{-- =====================================================
                RESUMO
            ====================================================== --}}

            <aside class="carrinho-sidebar">

                <div class="resumo-card">

                    <div class="resumo-header">

                        <i class="bi bi-receipt"></i>

                        <h2>
                            Resumo da Compra
                        </h2>

                    </div>


                    <div class="resumo-body">

                        <div class="resumo-row">

                            <span>

                                Produtos
                                (
                                <span id="resumoQuantidade">
                                    {{ $itens->count() }}
                                </span>
                                )

                            </span>

                            <strong id="subtotal">

                                R$
                                {{ number_format(
                                    $total,
                                    2,
                                    ',',
                                    '.'
                                ) }}

                            </strong>

                        </div>


                        <div class="resumo-row">

                            <span>
                                Frete
                            </span>

                            <strong class="gratis">
                                Grátis
                            </strong>

                        </div>


                        <div class="resumo-divisor"></div>


                        <div class="resumo-total">

                            <span>
                                Total
                            </span>

                            <strong id="total">

                                R$
                                {{ number_format(
                                    $total,
                                    2,
                                    ',',
                                    '.'
                                ) }}

                            </strong>

                        </div>


                        <button
                            type="button"
                            class="btn-continuar"
                            id="btnContinuar"
                            {{ $itens->count() === 0 ? 'disabled' : '' }}
                        >

                            <i class="bi bi-lock-fill"></i>

                            Continuar

                            <i class="bi bi-arrow-right"></i>

                        </button>


                        <div class="compra-segura">

                            <i class="bi bi-shield-check"></i>

                            Compra 100% segura

                        </div>

                    </div>

                </div>


                {{-- BENEFÍCIOS --}}

                <div class="beneficios-card">

                    <div class="beneficio">

                        <i class="bi bi-truck"></i>

                        <div>

                            <strong>
                                Frete grátis
                            </strong>

                            <span>
                                Em compras acima de R$ 199
                            </span>

                        </div>

                    </div>


                    <div class="beneficio">

                        <i class="bi bi-shield-check"></i>

                        <div>

                            <strong>
                                Pagamento seguro
                            </strong>

                            <span>
                                Seus dados protegidos
                            </span>

                        </div>

                    </div>


                    <div class="beneficio">

                        <i class="bi bi-box-seam"></i>

                        <div>

                            <strong>
                                Devolução fácil
                            </strong>

                            <span>
                                Até 7 dias após o recebimento
                            </span>

                        </div>

                    </div>

                </div>

            </aside>

        </section>


        {{-- =========================================================
            RODAPÉ
        ========================================================== --}}

        <div class="carrinho-footer">

            <a
                href="{{ route('produtos.index') }}"
                class="continuar-comprando"
            >

                <i class="bi bi-chevron-left"></i>

                Continuar comprando

            </a>


            <div class="ajuda">

                <i class="bi bi-question-circle"></i>

                <span>
                    Precisa de ajuda?
                </span>

                <a href="#">
                    Fale conosco
                </a>

            </div>

        </div>

    </main>


    {{-- =============================================================
        JAVASCRIPT DO CARRINHO
    ============================================================== --}}

    <script>

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const csrfToken =
                    '{{ csrf_token() }}';

                const selecionarTodos =
                    document.getElementById(
                        'selecionarTodos'
                    );

                const quantidadeSelecionados =
                    document.getElementById(
                        'quantidadeSelecionados'
                    );

                const quantidadeTotal =
                    document.getElementById(
                        'quantidadeTotal'
                    );

                const resumoQuantidade =
                    document.getElementById(
                        'resumoQuantidade'
                    );

                const subtotal =
                    document.getElementById(
                        'subtotal'
                    );

                const total =
                    document.getElementById(
                        'total'
                    );

                const listaProdutos =
                    document.getElementById(
                        'listaProdutos'
                    );

                const btnContinuar =
                    document.getElementById(
                        'btnContinuar'
                    );


                /*
                |--------------------------------------------------------------------------
                | FORMATA MOEDA
                |--------------------------------------------------------------------------
                */

                function moeda(valor) {

                    return valor.toLocaleString(
                        'pt-BR',
                        {
                            style: 'currency',
                            currency: 'BRL'
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | PRODUTOS ATUAIS
                |--------------------------------------------------------------------------
                */

                function pegarProdutos() {

                    return document.querySelectorAll(
                        '.produto-card'
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | ATUALIZA RESUMO
                |--------------------------------------------------------------------------
                */

                function atualizarResumo() {

                    const produtos =
                        pegarProdutos();

                    let selecionados = 0;
                    let quantidadeItens = 0;
                    let valorTotal = 0;


                    produtos.forEach(
                        function (produto) {

                            const checkbox =
                                produto.querySelector(
                                    '.produto-select'
                                );

                            const quantidadeElement =
                                produto.querySelector(
                                    '.quantidade-valor'
                                );

                            const quantidade =
                                parseInt(
                                    quantidadeElement.textContent
                                ) || 1;

                            const preco =
                                parseFloat(
                                    produto.dataset.price
                                ) || 0;


                            if (checkbox.checked) {

                                selecionados++;

                                quantidadeItens +=
                                    quantidade;

                                valorTotal +=
                                    preco * quantidade;

                                produto.classList.add(
                                    'selecionado'
                                );

                            } else {

                                produto.classList.remove(
                                    'selecionado'
                                );
                            }

                        }
                    );


                    if (quantidadeSelecionados) {

                        quantidadeSelecionados.textContent =
                            selecionados;
                    }


                    if (quantidadeTotal) {

                        quantidadeTotal.textContent =
                            produtos.length;
                    }


                    if (resumoQuantidade) {

                        resumoQuantidade.textContent =
                            quantidadeItens;
                    }


                    if (subtotal) {

                        subtotal.textContent =
                            moeda(valorTotal);
                    }


                    if (total) {

                        total.textContent =
                            moeda(valorTotal);
                    }


                    if (selecionarTodos) {

                        selecionarTodos.checked =
                            produtos.length > 0 &&
                            selecionados === produtos.length;

                        selecionarTodos.indeterminate =
                            selecionados > 0 &&
                            selecionados < produtos.length;
                    }


                    if (btnContinuar) {

                        btnContinuar.disabled =
                            selecionados === 0;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | ATUALIZA QUANTIDADE NO BANCO
                |--------------------------------------------------------------------------
                */

                async function salvarQuantidade(
                    card,
                    novaQuantidade
                ) {

                    const url =
                        card.dataset.updateUrl;


                    try {

                        const response =
                            await fetch(
                                url,
                                {
                                    method: 'PATCH',

                                    headers: {

                                        'Content-Type':
                                            'application/json',

                                        'Accept':
                                            'application/json',

                                        'X-CSRF-TOKEN':
                                            csrfToken
                                    },

                                    body:
                                        JSON.stringify({
                                            quantidade:
                                                novaQuantidade
                                        })
                                }
                            );


                        const dados =
                            await response.json();


                        if (!response.ok) {

                            alert(
                                dados.message
                                ??
                                'Não foi possível atualizar a quantidade.'
                            );

                            return false;
                        }


                        return true;


                    } catch (erro) {

                        console.error(erro);

                        alert(
                            'Erro ao atualizar o carrinho.'
                        );

                        return false;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | AUMENTAR
                |--------------------------------------------------------------------------
                */

                document.addEventListener(
                    'click',
                    async function (event) {

                        const button =
                            event.target.closest(
                                '.aumentar'
                            );

                        if (!button) {
                            return;
                        }


                        const card =
                            button.closest(
                                '.produto-card'
                            );

                        const valor =
                            card.querySelector(
                                '.quantidade-valor'
                            );

                        const atual =
                            parseInt(
                                valor.textContent
                            );

                        const estoque =
                            parseInt(
                                valor.dataset.stock
                            );


                        if (atual >= estoque) {

                            alert(
                                'Você atingiu o limite disponível em estoque.'
                            );

                            return;
                        }


                        button.disabled = true;

                        const novaQuantidade =
                            atual + 1;


                        const sucesso =
                            await salvarQuantidade(
                                card,
                                novaQuantidade
                            );


                        if (sucesso) {

                            valor.textContent =
                                novaQuantidade;

                            atualizarResumo();
                        }


                        button.disabled = false;
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | DIMINUIR
                |--------------------------------------------------------------------------
                */

                document.addEventListener(
                    'click',
                    async function (event) {

                        const button =
                            event.target.closest(
                                '.diminuir'
                            );

                        if (!button) {
                            return;
                        }


                        const card =
                            button.closest(
                                '.produto-card'
                            );

                        const valor =
                            card.querySelector(
                                '.quantidade-valor'
                            );

                        const atual =
                            parseInt(
                                valor.textContent
                            );


                        if (atual <= 1) {
                            return;
                        }


                        button.disabled = true;

                        const novaQuantidade =
                            atual - 1;


                        const sucesso =
                            await salvarQuantidade(
                                card,
                                novaQuantidade
                            );


                        if (sucesso) {

                            valor.textContent =
                                novaQuantidade;

                            atualizarResumo();
                        }


                        button.disabled = false;
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | CHECKBOX INDIVIDUAL
                |--------------------------------------------------------------------------
                */

                document.addEventListener(
                    'change',
                    function (event) {

                        if (
                            event.target.classList.contains(
                                'produto-select'
                            )
                        ) {

                            atualizarResumo();
                        }
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | SELECIONAR TODOS
                |--------------------------------------------------------------------------
                */

                if (selecionarTodos) {

                    selecionarTodos.addEventListener(
                        'change',
                        function () {

                            pegarProdutos()
                                .forEach(
                                    function (produto) {

                                        produto
                                            .querySelector(
                                                '.produto-select'
                                            )
                                            .checked =
                                                selecionarTodos.checked;
                                    }
                                );

                            atualizarResumo();
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | REMOVER ITEM
                |--------------------------------------------------------------------------
                */

                async function removerItem(card) {

                    const url =
                        card.dataset.removeUrl;


                    try {

                        const response =
                            await fetch(
                                url,
                                {
                                    method: 'DELETE',

                                    headers: {

                                        'Accept':
                                            'application/json',

                                        'X-CSRF-TOKEN':
                                            csrfToken
                                    }
                                }
                            );


                        const dados =
                            await response.json();


                        if (!response.ok) {

                            alert(
                                dados.message
                                ??
                                'Não foi possível remover o produto.'
                            );

                            return false;
                        }


                        card.remove();

                        verificarCarrinhoVazio();

                        atualizarResumo();

                        return true;


                    } catch (erro) {

                        console.error(erro);

                        alert(
                            'Erro ao remover produto.'
                        );

                        return false;
                    }
                }


                /*
                |--------------------------------------------------------------------------
                | BOTÃO REMOVER
                |--------------------------------------------------------------------------
                */

                document.addEventListener(
                    'click',
                    async function (event) {

                        const button =
                            event.target.closest(
                                '.produto-remover'
                            );

                        if (!button) {
                            return;
                        }


                        const card =
                            button.closest(
                                '.produto-card'
                            );


                        await removerItem(card);
                    }
                );


                /*
                |--------------------------------------------------------------------------
                | REMOVER SELECIONADOS
                |--------------------------------------------------------------------------
                */

                const removerSelecionados =
                    document.getElementById(
                        'removerSelecionados'
                    );


                if (removerSelecionados) {

                    removerSelecionados.addEventListener(
                        'click',
                        async function () {

                            const selecionados =
                                Array.from(
                                    pegarProdutos()
                                )
                                .filter(
                                    function (produto) {

                                        return produto
                                            .querySelector(
                                                '.produto-select'
                                            )
                                            .checked;
                                    }
                                );


                            if (
                                selecionados.length === 0
                            ) {

                                return;
                            }


                            removerSelecionados.disabled =
                                true;


                            for (
                                const card
                                of selecionados
                            ) {

                                await removerItem(card);
                            }


                            removerSelecionados.disabled =
                                false;
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | LIMPAR CARRINHO
                |--------------------------------------------------------------------------
                */

                const limparCarrinho =
                    document.getElementById(
                        'limparCarrinho'
                    );


                if (limparCarrinho) {

                    limparCarrinho.addEventListener(
                        'click',
                        async function () {

                            if (
                                !confirm(
                                    'Deseja remover todos os produtos do carrinho?'
                                )
                            ) {

                                return;
                            }


                            limparCarrinho.disabled =
                                true;


                            try {

                                const response =
                                    await fetch(
                                        '{{ route('carrinho.limpar') }}',
                                        {
                                            method:
                                                'DELETE',

                                            headers: {

                                                'Accept':
                                                    'application/json',

                                                'X-CSRF-TOKEN':
                                                    csrfToken
                                            }
                                        }
                                    );


                                const dados =
                                    await response.json();


                                if (!response.ok) {

                                    alert(
                                        dados.message
                                        ??
                                        'Não foi possível limpar o carrinho.'
                                    );

                                    return;
                                }


                                pegarProdutos()
                                    .forEach(
                                        function (produto) {

                                            produto.remove();
                                        }
                                    );


                                verificarCarrinhoVazio();

                                atualizarResumo();


                            } catch (erro) {

                                console.error(erro);

                                alert(
                                    'Erro ao limpar o carrinho.'
                                );


                            } finally {

                                limparCarrinho.disabled =
                                    false;
                            }
                        }
                    );
                }


                /*
                |--------------------------------------------------------------------------
                | CARRINHO VAZIO
                |--------------------------------------------------------------------------
                */

                function verificarCarrinhoVazio() {

                    const produtos =
                        pegarProdutos();


                    if (produtos.length > 0) {
                        return;
                    }


                    const actions =
                        document.querySelector(
                            '.carrinho-actions'
                        );


                    if (actions) {
                        actions.remove();
                    }


                    if (
                        !document.getElementById(
                            'carrinhoVazio'
                        )
                    ) {

                        listaProdutos.innerHTML = `

                            <div
                                class="carrinho-vazio"
                                id="carrinhoVazio"
                            >

                                <i class="bi bi-cart-x"></i>

                                <h2>
                                    Seu carrinho está vazio
                                </h2>

                                <p>
                                    Explore nossos produtos
                                    e adicione itens ao carrinho.
                                </p>

                                <a
                                    href="{{ route('produtos.index') }}"
                                    class="btn-explorar"
                                >
                                    Explorar produtos
                                </a>

                            </div>

                        `;
                    }
                }


                atualizarResumo();
            }
        );

    </script>

</x-app-layout>