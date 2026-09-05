@php

    $isAdmin = $isAdmin ?? false;

@endphp


<div class="pm-page">

    {{-- =========================================================
        CABEÇALHO
    ========================================================== --}}

    <header class="pm-header">

        <div>

            <span class="pm-header-label">

                {{ $isAdmin
                    ? 'GERENCIAMENTO'
                    : 'MINHA CONTA'
                }}

            </span>


            <h1>

                {{ $isAdmin
                    ? 'Produtos'
                    : 'Meus Produtos'
                }}

            </h1>


            <p>

                {{ $isAdmin
                    ? 'Gerencie os produtos cadastrados na D-tech.'
                    : 'Gerencie os produtos anunciados por você.'
                }}

            </p>

        </div>


        {{-- =====================================================
            NOVO PRODUTO
            SOMENTE USUÁRIO NORMAL
        ====================================================== --}}

        @if(!$isAdmin)

            <a
                href="{{ route('meus-produtos.create') }}"
                class="pm-primary-button pm-new-product-button"
            >

                <svg
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                >
                    <path d="M12 5v14"></path>
                    <path d="M5 12h14"></path>
                </svg>

                Novo Produto

            </a>

        @endif

    </header>


    {{-- =========================================================
        MENSAGENS
    ========================================================== --}}

    @if(session('success'))

        <div class="pm-alert pm-alert-success">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <circle
                    cx="12"
                    cy="12"
                    r="9"
                ></circle>

                <path
                    d="m8 12 3 3 5-6"
                ></path>
            </svg>

            {{ session('success') }}

        </div>

    @endif


    @if(session('error'))

        <div class="pm-alert pm-alert-error">

            <svg
                viewBox="0 0 24 24"
                fill="none"
                stroke="currentColor"
                stroke-width="2"
            >
                <circle
                    cx="12"
                    cy="12"
                    r="9"
                ></circle>

                <path d="M12 8v5"></path>
                <path d="M12 16h.01"></path>
            </svg>

            {{ session('error') }}

        </div>

    @endif


    {{-- =========================================================
        ESTATÍSTICAS
    ========================================================== --}}

    <section class="pm-stats">

        {{-- TOTAL --}}
        <article class="pm-stat">

            <div class="pm-stat-icon">

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


            <div>

                <small>

                    {{ $isAdmin
                        ? 'Total de produtos'
                        : 'Meus produtos'
                    }}

                </small>


                <strong>
                    {{ $totalProdutos }}
                </strong>


                <span>
                    cadastrados
                </span>

            </div>

        </article>


        {{-- DISPONÍVEIS --}}
        <article class="pm-stat">

            <div class="pm-stat-icon">

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
                        d="m8 12 3 3 5-6"
                    ></path>
                </svg>

            </div>


            <div>

                <small>
                    Disponíveis
                </small>


                <strong>
                    {{ $produtosDisponiveis }}
                </strong>


                <span>
                    com estoque
                </span>

            </div>

        </article>


        {{-- SEM ESTOQUE --}}
        <article class="pm-stat">

            <div class="pm-stat-icon">

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

                    <path d="M12 8v5"></path>
                    <path d="M12 16h.01"></path>
                </svg>

            </div>


            <div>

                <small>
                    Sem estoque
                </small>


                <strong>
                    {{ $produtosSemEstoque }}
                </strong>


                <span>
                    indisponíveis
                </span>

            </div>

        </article>


        {{-- VENDEDORES - SOMENTE ADMIN --}}
        @if($isAdmin)

            <article class="pm-stat">

                <div class="pm-stat-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <circle
                            cx="9"
                            cy="8"
                            r="4"
                        ></circle>

                        <path
                            d="M2 21a7 7 0 0 1 14 0"
                        ></path>

                        <path
                            d="M17 8h5"
                        ></path>

                        <path
                            d="M19.5 5.5v5"
                        ></path>
                    </svg>

                </div>


                <div>

                    <small>
                        Vendedores
                    </small>


                    <strong>
                        {{ $totalVendedores ?? 0 }}
                    </strong>


                    <span>
                        anunciantes
                    </span>

                </div>

            </article>

        @endif

    </section>


    {{-- =========================================================
        CARD DA LISTAGEM
    ========================================================== --}}

    <section class="pm-card">


        {{-- =====================================================
            FILTROS
        ====================================================== --}}

        <form
            action="{{ $isAdmin
                ? route('admin.produtos.index')
                : route('meus-produtos.index')
            }}"
            method="GET"
            class="pm-filters"
        >

            {{-- PESQUISA --}}
            <div class="pm-search">

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
                        ? 'Pesquisar produto ou vendedor...'
                        : 'Pesquisar meus produtos...'
                    }}"
                >

            </div>


            {{-- CATEGORIA --}}
            <select
                name="categoria"
                class="pm-filter-select"
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


            {{-- FILTRAR --}}
            <button
                type="submit"
                class="pm-filter-button"
            >
                Filtrar
            </button>


            {{-- LIMPAR --}}
            @if(
                request()->filled('busca')
                ||
                request()->filled('categoria')
            )

                <a
                    href="{{ $isAdmin
                        ? route('admin.produtos.index')
                        : route('meus-produtos.index')
                    }}"
                    class="pm-clear-button"
                >
                    Limpar
                </a>

            @endif

        </form>


        {{-- =====================================================
            TABELA
        ====================================================== --}}

        <div class="pm-table-wrapper">

            <table class="pm-table">

                <thead>

                    <tr>

                        <th>
                            Produto
                        </th>


                        <th>
                            Categoria
                        </th>


                        @if($isAdmin)

                            <th>
                                Vendedor
                            </th>

                        @endif


                        <th>
                            Preço
                        </th>


                        <th>
                            Estoque
                        </th>


                        <th>
                            Cadastro
                        </th>


                        <th>
                            Ações
                        </th>

                    </tr>

                </thead>


                <tbody>

                    @forelse($produtos as $produto)

                        @php

                            /*
                            |--------------------------------------------------------------------------
                            | FOTO PRINCIPAL
                            |--------------------------------------------------------------------------
                            */

                            $fotoPrincipal = null;


                            if (
                                $produto->relationLoaded('fotos')
                            ) {

                                $fotoPrincipal =
                                    $produto
                                        ->fotos
                                        ->firstWhere(
                                            'principal',
                                            true
                                        )?->foto;
                            }


                            $fotoPrincipal =
                                $fotoPrincipal
                                ?? $produto->foto;


                            $fotoUrl =
                                null;


                            if ($fotoPrincipal) {

                                if (
                                    str_starts_with(
                                        $fotoPrincipal,
                                        'http://'
                                    )
                                    ||
                                    str_starts_with(
                                        $fotoPrincipal,
                                        'https://'
                                    )
                                ) {

                                    $fotoUrl =
                                        $fotoPrincipal;

                                } else {

                                    $fotoUrl =
                                        asset(
                                            'storage/' .
                                            ltrim(
                                                $fotoPrincipal,
                                                '/'
                                            )
                                        );
                                }
                            }

                        @endphp


                        <tr>

                            {{-- =========================================
                                PRODUTO
                            ========================================== --}}

                            <td>

                                <div class="pm-product">

                                    <a
                                        href="{{ route(
                                            'produto.show',
                                            $produto->id
                                        ) }}"
                                        class="pm-product-image"
                                    >

                                        @if($fotoUrl)

                                            <img
                                                src="{{ $fotoUrl }}"
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


                                    <div>

                                        <strong>
                                            {{ $produto->nome }}
                                        </strong>


                                        <small>
                                            Código #{{ $produto->id }}
                                        </small>

                                    </div>

                                </div>

                            </td>


                            {{-- =========================================
                                CATEGORIA
                            ========================================== --}}

                            <td>

                                <span class="pm-category-badge">

                                    {{ $produto->categoria?->nome
                                        ?? 'Sem categoria'
                                    }}

                                </span>

                            </td>


                            {{-- =========================================
                                VENDEDOR
                            ========================================== --}}

                            @if($isAdmin)

                                <td>

                                    <div class="pm-seller">

                                        <strong>

                                            {{ $produto->vendedor?->nome
                                                ?? 'Não informado'
                                            }}

                                        </strong>


                                        <small>

                                            {{ $produto->vendedor?->email
                                                ?? ''
                                            }}

                                        </small>

                                    </div>

                                </td>

                            @endif


                            {{-- =========================================
                                PREÇO
                            ========================================== --}}

                            <td>

                                <strong class="pm-price">

                                    R$
                                    {{ number_format(
                                        $produto->preco,
                                        2,
                                        ',',
                                        '.'
                                    ) }}

                                </strong>

                            </td>


                            {{-- =========================================
                                ESTOQUE
                            ========================================== --}}

                            <td>

                                @if(
                                    $produto->quantidade
                                    > 0
                                )

                                    <span class="pm-stock pm-stock-ok">

                                        {{ $produto->quantidade }}

                                        disponível(is)

                                    </span>

                                @else

                                    <span class="pm-stock pm-stock-empty">

                                        Sem estoque

                                    </span>

                                @endif

                            </td>


                            {{-- =========================================
                                DATA
                            ========================================== --}}

                            <td>

                                {{ $produto->created_at
                                    ? $produto
                                        ->created_at
                                        ->format('d/m/Y')
                                    : '—'
                                }}

                            </td>


                            {{-- =========================================
                                AÇÕES
                            ========================================== --}}

                            <td>

                                <div class="pm-actions">


                                    {{-- VISUALIZAR --}}
                                    <a
                                        href="{{ route(
                                            'produto.show',
                                            $produto->id
                                        ) }}"
                                        class="pm-action-button"
                                        title="Visualizar produto"
                                    >

                                        <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            stroke="currentColor"
                                            stroke-width="1.8"
                                        >
                                            <path
                                                d="M2 12s3.5-6 10-6 10 6 10 6-3.5 6-10 6S2 12 2 12Z"
                                            ></path>

                                            <circle
                                                cx="12"
                                                cy="12"
                                                r="2.5"
                                            ></circle>
                                        </svg>

                                    </a>


                                    {{-- =================================
                                        EDITAR
                                    ================================== --}}

                                    @if($isAdmin)

                                        <a
                                            href="{{ route(
                                                'produto.show',
                                                $produto->id
                                            ) }}"
                                            class="pm-action-button"
                                            title="Visualizar"
                                        >

                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path
                                                    d="M12 20h9"
                                                ></path>

                                                <path
                                                    d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"
                                                ></path>
                                            </svg>

                                        </a>

                                    @else

                                        <a
                                            href="{{ route(
                                                'meus-produtos.edit',
                                                $produto
                                            ) }}"
                                            class="pm-action-button"
                                            title="Editar produto"
                                        >

                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path
                                                    d="M12 20h9"
                                                ></path>

                                                <path
                                                    d="M16.5 3.5a2.1 2.1 0 0 1 3 3L8 18l-4 1 1-4Z"
                                                ></path>
                                            </svg>

                                        </a>

                                    @endif


                                    {{-- =================================
                                        EXCLUIR
                                    ================================== --}}

                                    <form
                                        action="{{ $isAdmin
                                            ? route(
                                                'admin.produtos.destroy',
                                                $produto
                                            )
                                            : route(
                                                'meus-produtos.destroy',
                                                $produto
                                            )
                                        }}"
                                        method="POST"
                                        class="pm-delete-form"
                                        data-product-name="{{ $produto->nome }}"
                                    >

                                        @csrf
                                        @method('DELETE')


                                        <button
                                            type="submit"
                                            class="pm-action-button pm-action-delete"
                                            title="Excluir produto"
                                        >

                                            <svg
                                                viewBox="0 0 24 24"
                                                fill="none"
                                                stroke="currentColor"
                                                stroke-width="1.8"
                                            >
                                                <path
                                                    d="M3 6h18"
                                                ></path>

                                                <path
                                                    d="M8 6V4h8v2"
                                                ></path>

                                                <path
                                                    d="M19 6l-1 14H6L5 6"
                                                ></path>

                                                <path
                                                    d="M10 11v5"
                                                ></path>

                                                <path
                                                    d="M14 11v5"
                                                ></path>
                                            </svg>

                                        </button>

                                    </form>

                                </div>

                            </td>

                        </tr>


                    @empty

                        <tr>

                            <td
                                colspan="{{ $isAdmin ? 7 : 6 }}"
                                class="pm-empty"
                            >

                                <div class="pm-empty-content">

                                    <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        stroke="currentColor"
                                        stroke-width="1.6"
                                    >
                                        <path
                                            d="M3 7l9-4 9 4-9 4-9-4Z"
                                        ></path>

                                        <path
                                            d="M3 7v10l9 4 9-4V7"
                                        ></path>
                                    </svg>


                                    <strong>

                                        {{ $isAdmin
                                            ? 'Nenhum produto encontrado.'
                                            : 'Você ainda não cadastrou produtos.'
                                        }}

                                    </strong>


                                    @if(!$isAdmin)

                                        <a
                                            href="{{ route('meus-produtos.create') }}"
                                        >
                                            Cadastrar meu primeiro produto
                                        </a>

                                    @endif

                                </div>

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>


        {{-- =====================================================
            RODAPÉ / PAGINAÇÃO
        ====================================================== --}}

        <footer class="pm-footer">

            <span>

                @if(
                    $produtos->total()
                    > 0
                )

                    Mostrando

                    {{ $produtos->firstItem() }}

                    a

                    {{ $produtos->lastItem() }}

                    de

                    {{ $produtos->total() }}

                    produto(s)

                @else

                    Nenhum produto encontrado.

                @endif

            </span>


            @if(
                $produtos->lastPage()
                > 1
            )

                <div class="pm-pagination">


                    {{-- ANTERIOR --}}
                    @if(
                        $produtos->onFirstPage()
                    )

                        <span class="pm-page-link disabled">
                            ‹
                        </span>

                    @else

                        <a
                            href="{{ $produtos->previousPageUrl() }}"
                            class="pm-page-link"
                        >
                            ‹
                        </a>

                    @endif


                    {{-- PÁGINAS --}}
                    @for(
                        $pagina = 1;
                        $pagina <= $produtos->lastPage();
                        $pagina++
                    )

                        <a
                            href="{{ $produtos->url($pagina) }}"
                            class="
                                pm-page-link
                                {{ $produtos->currentPage() === $pagina
                                    ? 'active'
                                    : ''
                                }}
                            "
                        >
                            {{ $pagina }}
                        </a>

                    @endfor


                    {{-- PRÓXIMO --}}
                    @if(
                        $produtos->hasMorePages()
                    )

                        <a
                            href="{{ $produtos->nextPageUrl() }}"
                            class="pm-page-link"
                        >
                            ›
                        </a>

                    @else

                        <span class="pm-page-link disabled">
                            ›
                        </span>

                    @endif

                </div>

            @endif

        </footer>

    </section>

</div>