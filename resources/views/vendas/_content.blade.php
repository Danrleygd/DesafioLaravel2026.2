@php

    $isAdmin =
        $isAdmin ?? false;


    $fotoUrl =
        function ($foto) {

            if (!$foto) {
                return null;
            }


            if (
                str_starts_with(
                    $foto,
                    'http://'
                )
                ||
                str_starts_with(
                    $foto,
                    'https://'
                )
            ) {
                return $foto;
            }


            return asset(
                'storage/' .
                ltrim(
                    $foto,
                    '/'
                )
            );
        };


    $rotaIndex =
        $isAdmin
            ? route(
                'admin.vendas.index'
            )
            : route(
                'vendas.index'
            );


    $rotaPdf =
        $isAdmin
            ? route(
                'admin.vendas.relatorio.pdf'
            )
            : route(
                'vendas.relatorio.pdf'
            );

@endphp


<div class="sales-page">


    {{-- =========================================================
        HEADER
    ========================================================== --}}

    <header class="sales-header">

        <div>

            <span class="sales-header-label">

                {{ $isAdmin
                    ? 'ADMINISTRAÇÃO'
                    : 'MINHA CONTA'
                }}

            </span>


            <h1>

                {{ $isAdmin
                    ? 'Vendas'
                    : 'Minhas Vendas'
                }}

            </h1>


            <p>

                {{ $isAdmin
                    ? 'Visualize todas as vendas realizadas na plataforma.'
                    : 'Acompanhe os produtos vendidos por você.'
                }}

            </p>

        </div>

    </header>


    {{-- =========================================================
        ERROS
    ========================================================== --}}

    @if($errors->any())

        <div class="sales-alert sales-alert-error">

            <strong>
                Não foi possível gerar o relatório.
            </strong>


            <ul>

                @foreach($errors->all() as $error)

                    <li>
                        {{ $error }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- =========================================================
        ESTATÍSTICAS
    ========================================================== --}}

    <section class="sales-stats">


        {{-- VENDAS --}}
        <article class="sales-stat">

            <div class="sales-stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path d="M4 19V9"></path>
                    <path d="M10 19V5"></path>
                    <path d="M16 19v-7"></path>
                    <path d="M22 19H2"></path>
                </svg>

            </div>


            <div>

                <span>
                    Total de vendas
                </span>

                <strong>
                    {{ $totalVendas }}
                </strong>

                <small>
                    transações
                </small>

            </div>

        </article>


        {{-- ITENS --}}
        <article class="sales-stat">

            <div class="sales-stat-icon">

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


            <div>

                <span>
                    Itens vendidos
                </span>

                <strong>
                    {{ $itensVendidos }}
                </strong>

                <small>
                    unidades
                </small>

            </div>

        </article>


        {{-- FATURAMENTO --}}
        <article class="sales-stat">

            <div class="sales-stat-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <circle
                        cx="12"
                        cy="12"
                        r="9"
                    ></circle>

                    <path
                        d="M16 8h-5a2 2 0 0 0 0 4h2a2 2 0 0 1 0 4H8"
                    ></path>

                    <path
                        d="M12 6v12"
                    ></path>
                </svg>

            </div>


            <div>

                <span>
                    Valor vendido
                </span>

                <strong class="sales-stat-money">

                    R$
                    {{ number_format(
                        $valorTotalVendido,
                        2,
                        ',',
                        '.'
                    ) }}

                </strong>

                <small>
                    valor das vendas exibidas
                </small>

            </div>

        </article>


        {{-- COMPRADORES --}}
        <article class="sales-stat">

            <div class="sales-stat-icon">

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

            </div>


            <div>

                <span>
                    Compradores
                </span>

                <strong>
                    {{ $totalCompradores }}
                </strong>

                <small>
                    usuários diferentes
                </small>

            </div>

        </article>

    </section>


    {{-- =========================================================
        RELATÓRIO
    ========================================================== --}}

    <section class="sales-report-card">

        <div class="sales-report-info">

            <div class="sales-report-icon">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        d="M6 2h9l5 5v15H6z"
                    ></path>

                    <path
                        d="M14 2v6h6"
                    ></path>

                    <path
                        d="M9 13h8"
                    ></path>

                    <path
                        d="M9 17h8"
                    ></path>
                </svg>

            </div>


            <div>

                <h2>
                    Relatório de vendas
                </h2>

                <p>
                    Escolha um período para gerar o relatório.
                </p>

            </div>

        </div>


        <form
            method="GET"
            class="sales-report-form"
        >

            <div class="sales-report-date">

                <label for="relatorio_data_inicio">
                    De
                </label>

                <input
                    type="date"
                    name="data_inicio"
                    id="relatorio_data_inicio"
                    value="{{ request(
                        'data_inicio',
                        now()
                            ->startOfMonth()
                            ->format('Y-m-d')
                    ) }}"
                    required
                >

            </div>


            <div class="sales-report-date">

                <label for="relatorio_data_fim">
                    Até
                </label>

                <input
                    type="date"
                    name="data_fim"
                    id="relatorio_data_fim"
                    value="{{ request(
                        'data_fim',
                        now()->format('Y-m-d')
                    ) }}"
                    required
                >

            </div>


            {{-- PDF --}}
            <button
                type="submit"
                formaction="{{ $rotaPdf }}"
                formtarget="_blank"
                class="sales-report-button sales-pdf-button"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="1.8"
                >
                    <path
                        d="M6 2h9l5 5v15H6z"
                    ></path>

                    <path
                        d="M14 2v6h6"
                    ></path>
                </svg>

                Gerar PDF

            </button>


            {{-- XLSX APENAS ADMIN --}}
            @if($isAdmin)

                <button
                    type="submit"
                    formaction="{{ route(
                        'admin.vendas.relatorio.xlsx'
                    ) }}"
                    class="sales-report-button sales-excel-button"
                >

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path
                            d="M6 2h9l5 5v15H6z"
                        ></path>

                        <path
                            d="M14 2v6h6"
                        ></path>

                        <path
                            d="m9 13 6 6"
                        ></path>

                        <path
                            d="m15 13-6 6"
                        ></path>
                    </svg>

                    Gerar XLSX

                </button>

            @endif

        </form>

    </section>


    {{-- =========================================================
        LISTAGEM
    ========================================================== --}}

    <section class="sales-card">


        {{-- =====================================================
            FILTROS
        ====================================================== --}}

        <form
            action="{{ $rotaIndex }}"
            method="GET"
            class="sales-filters"
        >

            {{-- BUSCA --}}
            <div class="sales-search">

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                >
                    <circle
                        cx="11"
                        cy="11"
                        r="7"
                    ></circle>

                    <path
                        d="m20 20-4-4"
                    ></path>
                </svg>


                <input
                    type="text"
                    name="busca"
                    value="{{ request('busca') }}"
                    placeholder="{{ $isAdmin
                        ? 'Produto, comprador, vendedor ou transação...'
                        : 'Produto, comprador ou transação...'
                    }}"
                >

            </div>


            {{-- CATEGORIA --}}
            <select
                name="categoria"
                class="sales-filter-select"
            >

                <option value="">
                    Todas as categorias
                </option>


                @foreach($categorias as $categoria)

                    <option
                        value="{{ $categoria->id }}"
                        @selected(
                            request('categoria')
                            ==
                            $categoria->id
                        )
                    >

                        {{ $categoria->nome }}

                    </option>

                @endforeach

            </select>


            {{-- STATUS --}}
            <select
                name="status"
                class="sales-filter-select"
            >

                <option value="">
                    Todos os status
                </option>

                <option
                    value="pago"
                    @selected(
                        request('status')
                        ===
                        'pago'
                    )
                >
                    Pago
                </option>

                <option
                    value="pendente"
                    @selected(
                        request('status')
                        ===
                        'pendente'
                    )
                >
                    Pendente
                </option>

                <option
                    value="cancelado"
                    @selected(
                        request('status')
                        ===
                        'cancelado'
                    )
                >
                    Cancelado
                </option>

                <option
                    value="reembolsado"
                    @selected(
                        request('status')
                        ===
                        'reembolsado'
                    )
                >
                    Reembolsado
                </option>

            </select>


            {{-- INÍCIO --}}
            <input
                type="date"
                name="data_inicio"
                class="sales-filter-date"
                value="{{ request('data_inicio') }}"
                title="Data inicial"
            >


            {{-- FIM --}}
            <input
                type="date"
                name="data_fim"
                class="sales-filter-date"
                value="{{ request('data_fim') }}"
                title="Data final"
            >


            <button
                type="submit"
                class="sales-filter-button"
            >
                Filtrar
            </button>


            @if(
                request()->filled('busca')
                ||
                request()->filled('categoria')
                ||
                request()->filled('status')
                ||
                request()->filled('data_inicio')
                ||
                request()->filled('data_fim')
            )

                <a
                    href="{{ $rotaIndex }}"
                    class="sales-clear-button"
                >
                    Limpar
                </a>

            @endif

        </form>


        {{-- =====================================================
            TABELA
        ====================================================== --}}

        <div class="sales-table-wrapper">

            <table class="sales-table">

                <thead>

                    <tr>

                        <th>
                            Produto
                        </th>

                        <th>
                            Comprador
                        </th>

                        @if($isAdmin)

                            <th>
                                Vendedor
                            </th>

                        @endif

                        <th>
                            Data
                        </th>

                        <th>
                            Quantidade
                        </th>

                        <th>
                            Valor
                        </th>

                        <th>
                            Status
                        </th>

                        <th>
                            Pagamento
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($vendas as $venda)

                        @php

                            $imagem =
                                $fotoUrl(
                                    $venda->produto_foto
                                );

                        @endphp


                        <tr>

                            {{-- PRODUTO --}}
                            <td>

                                <div class="sales-product">

                                    <a
                                        href="{{ route(
                                            'produto.show',
                                            $venda->produto_id
                                        ) }}"
                                        class="sales-product-image"
                                    >

                                        @if($imagem)

                                            <img
                                                src="{{ $imagem }}"
                                                alt="{{ $venda->produto_nome }}"
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


                                    <div>

                                        <strong>
                                            {{ $venda->produto_nome }}
                                        </strong>

                                        <span>
                                            {{ $venda->categoria_nome }}
                                        </span>

                                        <small>
                                            Venda #{{ $venda->venda_id }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- COMPRADOR --}}
                            <td>

                                <div class="sales-person">

                                    <strong>
                                        {{ $venda->comprador_nome }}
                                    </strong>

                                    <small>
                                        {{ $venda->comprador_email }}
                                    </small>

                                </div>

                            </td>


                            {{-- VENDEDOR --}}
                            @if($isAdmin)

                                <td>

                                    <div class="sales-person">

                                        <strong>
                                            {{ $venda->vendedor_nome }}
                                        </strong>

                                        <small>
                                            {{ $venda->vendedor_email }}
                                        </small>

                                    </div>

                                </td>

                            @endif


                            {{-- DATA --}}
                            <td class="sales-date">

                                {{ \Carbon\Carbon::parse(
                                    $venda->data_compra
                                )->format(
                                    'd/m/Y'
                                ) }}

                                <small>

                                    {{ \Carbon\Carbon::parse(
                                        $venda->data_compra
                                    )->format(
                                        'H:i'
                                    ) }}

                                </small>

                            </td>


                            {{-- QUANTIDADE --}}
                            <td>

                                <span class="sales-quantity">

                                    {{ $venda->quantidade }}

                                    × R$

                                    {{ number_format(
                                        $venda->valor_unitario,
                                        2,
                                        ',',
                                        '.'
                                    ) }}

                                </span>

                            </td>


                            {{-- VALOR --}}
                            <td>

                                <strong class="sales-price">

                                    R$
                                    {{ number_format(
                                        $venda->subtotal,
                                        2,
                                        ',',
                                        '.'
                                    ) }}

                                </strong>

                            </td>


                            {{-- STATUS --}}
                            <td>

                                <span
                                    class="
                                        sales-status
                                        sales-status-{{ strtolower(
                                            $venda->status_pagamento
                                        ) }}
                                    "
                                >

                                    {{ ucfirst(
                                        $venda->status_pagamento
                                    ) }}

                                </span>

                            </td>


                            {{-- PAGAMENTO --}}
                            <td>

                                <div class="sales-payment">

                                    <strong>

                                        {{ $venda->local_pagamento
                                            === 'mercadopago'
                                            ? 'Mercado Pago'
                                            : (
                                                $venda->local_pagamento
                                                === 'pagseguro'
                                                    ? 'PagSeguro'
                                                    : ucfirst(
                                                        $venda->local_pagamento
                                                    )
                                            )
                                        }}

                                    </strong>

                                    <small>
                                        {{ $venda->codigo_transacao }}
                                    </small>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="{{ $isAdmin ? 8 : 7 }}"
                                class="sales-empty"
                            >

                                <div>

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


                                    <strong>
                                        Nenhuma venda encontrada.
                                    </strong>


                                    <span>

                                        {{ $isAdmin
                                            ? 'Ainda não existem vendas correspondentes aos filtros.'
                                            : 'Quando alguém comprar um de seus produtos, a venda aparecerá aqui.'
                                        }}

                                    </span>

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            PAGINAÇÃO
        ====================================================== --}}

        <footer class="sales-footer">

            <span>

                @if(
                    $vendas->total()
                    > 0
                )

                    Mostrando
                    {{ $vendas->firstItem() }}
                    a
                    {{ $vendas->lastItem() }}
                    de
                    {{ $vendas->total() }}
                    item(ns)

                @else

                    Nenhum resultado

                @endif

            </span>


            @if(
                $vendas->lastPage()
                > 1
            )

                <div class="sales-pagination">


                    @if($vendas->onFirstPage())

                        <span class="sales-page-link disabled">
                            ‹
                        </span>

                    @else

                        <a
                            href="{{ $vendas->previousPageUrl() }}"
                            class="sales-page-link"
                        >
                            ‹
                        </a>

                    @endif


                    @for(
                        $pagina = 1;
                        $pagina <= $vendas->lastPage();
                        $pagina++
                    )

                        <a
                            href="{{ $vendas->url($pagina) }}"
                            class="
                                sales-page-link
                                {{ $pagina === $vendas->currentPage()
                                    ? 'active'
                                    : ''
                                }}
                            "
                        >
                            {{ $pagina }}
                        </a>

                    @endfor


                    @if($vendas->hasMorePages())

                        <a
                            href="{{ $vendas->nextPageUrl() }}"
                            class="sales-page-link"
                        >
                            ›
                        </a>

                    @else

                        <span class="sales-page-link disabled">
                            ›
                        </span>

                    @endif

                </div>

            @endif

        </footer>

    </section>

</div>