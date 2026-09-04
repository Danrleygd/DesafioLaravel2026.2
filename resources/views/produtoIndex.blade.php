<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>{{ $titulo }} - D-tech</title>

    @vite([
        'resources/css/app.css',
        'resources/css/produtoIndex.css',
        'resources/css/navLanding.css',
        'resources/css/footer.css',
        'resources/js/app.js'
    ])

</head>

<body>

    <x-nav-landing />

    <main class="produtos-page">


        {{-- =========================================
             TÍTULO
        ========================================== --}}

        <div class="produtos-topo">

            <h1>
                {{ $titulo }}
            </h1>

        </div>


        <div class="produtos-conteudo">


            {{-- =========================================
                 FILTROS
            ========================================== --}}

            <aside class="filtros">

                <h2>
                    FILTROS
                </h2>


                {{-- =========================================
                     BUSCA E PREÇO
                ========================================== --}}

                <form
                    method="GET"
                    action="{{ route('produtos.index') }}"
                >

                    {{-- PRESERVA A SEÇÃO --}}

                    @if (!empty($secao))

                        <input
                            type="hidden"
                            name="secao"
                            value="{{ $secao }}"
                        >

                    @endif


                    {{-- PRESERVA CATEGORIA --}}

                    @if ($categoriaSelecionada)

                        <input
                            type="hidden"
                            name="categoria"
                            value="{{ $categoriaSelecionada->id }}"
                        >

                    @endif


                    <div class="filtro-busca">

                        <input
                            type="text"
                            name="busca"
                            value="{{ request('busca') }}"
                            placeholder="Buscar nos resultados"
                        >

                        <button type="submit">
                            ⌕
                        </button>

                    </div>


                    <div class="filtro-grupo">

                        <h3>
                            Preço
                        </h3>

                        <div class="preco-linha">

                            <input
                                type="number"
                                name="preco_min"
                                value="{{ request('preco_min') }}"
                                placeholder="Mínimo"
                                min="0"
                                step="0.01"
                            >

                            <span>
                                —
                            </span>

                            <input
                                type="number"
                                name="preco_max"
                                value="{{ request('preco_max') }}"
                                placeholder="Máximo"
                                min="0"
                                step="0.01"
                            >

                        </div>

                        <button
                            class="botao-filtrar"
                            type="submit"
                        >
                            Filtrar
                        </button>

                    </div>

                </form>


                {{-- =========================================
                     CATEGORIAS
                ========================================== --}}

                <div class="filtro-grupo categorias-filtro">

                    <h3>
                        Categoria
                    </h3>


                    {{-- TODOS --}}

                    <label class="categoria-item">

                        <input
                            type="radio"
                            name="categoria_visual"
                            onchange="window.location.href='{{ route('produtos.index') }}'"
                            {{ empty(request('categoria')) && empty(request('secao')) ? 'checked' : '' }}
                        >

                        <span>
                            Todos
                        </span>

                    </label>


                    @foreach ($categorias as $cat)

                        <label class="categoria-item">

                            <input
                                type="radio"
                                name="categoria_visual"

                                onchange="window.location.href='{{ route('produtos.index', [
                                    'categoria' => $cat->id
                                ]) }}'"

                                {{ request('categoria') == $cat->id ? 'checked' : '' }}
                            >

                            <span>
                                {{ $cat->nome }}
                            </span>

                            <small>
                                ({{ $quantidadesCategorias[$cat->id] ?? 0 }})
                            </small>

                        </label>

                    @endforeach

                </div>

            </aside>


            {{-- =========================================
                 LISTAGEM
            ========================================== --}}

            <section class="produtos-listagem">


                <div class="produtos-listagem-topo">

                    <strong>

                        Exibindo

                        {{ $produtos->firstItem() ?? 0 }}

                        –

                        {{ $produtos->lastItem() ?? 0 }}

                        de

                        {{ $produtos->total() }}

                        resultados

                    </strong>


                    {{-- ORDENAÇÃO --}}

                    <form
                        method="GET"
                        action="{{ route('produtos.index') }}"
                    >


                        {{-- MANTÉM SEÇÃO --}}

                        @if (!empty($secao))

                            <input
                                type="hidden"
                                name="secao"
                                value="{{ $secao }}"
                            >

                        @endif


                        {{-- MANTÉM CATEGORIA --}}

                        @if (request('categoria'))

                            <input
                                type="hidden"
                                name="categoria"
                                value="{{ request('categoria') }}"
                            >

                        @endif


                        {{-- MANTÉM BUSCA --}}

                        @if (request('busca'))

                            <input
                                type="hidden"
                                name="busca"
                                value="{{ request('busca') }}"
                            >

                        @endif


                        {{-- MANTÉM PREÇO MÍNIMO --}}

                        @if (request('preco_min') !== null)

                            <input
                                type="hidden"
                                name="preco_min"
                                value="{{ request('preco_min') }}"
                            >

                        @endif


                        {{-- MANTÉM PREÇO MÁXIMO --}}

                        @if (request('preco_max') !== null)

                            <input
                                type="hidden"
                                name="preco_max"
                                value="{{ request('preco_max') }}"
                            >

                        @endif


                        <select
                            name="ordenar"
                            onchange="this.form.submit()"
                            class="ordenacao"
                        >

                            <option
                                value="relevancia"
                                {{ request('ordenar', 'relevancia') === 'relevancia' ? 'selected' : '' }}
                            >
                                Relevância
                            </option>

                            <option
                                value="menor_preco"
                                {{ request('ordenar') === 'menor_preco' ? 'selected' : '' }}
                            >
                                Menor preço
                            </option>

                            <option
                                value="maior_preco"
                                {{ request('ordenar') === 'maior_preco' ? 'selected' : '' }}
                            >
                                Maior preço
                            </option>

                            <option
                                value="mais_recentes"
                                {{ request('ordenar') === 'mais_recentes' ? 'selected' : '' }}
                            >
                                Mais recentes
                            </option>

                        </select>

                    </form>

                </div>


                {{-- =========================================
                     PRODUTOS
                ========================================== --}}

                <div class="produtos-grid">

                    @forelse ($produtos as $produto)

                        <article class="produto-card">


                            <span class="produto-desconto">
                                OFERTA
                            </span>


                            <button
                                type="button"
                                class="produto-favorito"
                            >
                                ♡
                            </button>


                            <a
                                href="{{ route('produto.show', $produto->id) }}"
                                class="produto-imagem"
                            >

                                @if ($produto->foto)

                                    <img
                                        src="{{ str_starts_with($produto->foto, 'http://') ||
                                            str_starts_with($produto->foto, 'https://')
                                                ? $produto->foto
                                                : asset(
                                                    'storage/' .
                                                    ltrim($produto->foto, '/')
                                                ) }}"
                                        alt="{{ $produto->nome }}"
                                    >

                                @else

                                    <img
                                        src="{{ asset('assets/images/foneCase.png') }}"
                                        alt="{{ $produto->nome }}"
                                    >

                                @endif

                            </a>


                            <div class="produto-info">

                                <h3>
                                    {{ $produto->nome }}
                                </h3>


                                <div class="produto-preco">

                                    <strong>

                                        R$
                                        {{ number_format(
                                            $produto->preco,
                                            2,
                                            ',',
                                            '.'
                                        ) }}

                                    </strong>

                                </div>


                                <div class="produto-avaliacao">

                                    <span>★</span>

                                    Disponível:
                                    {{ $produto->quantidade }}

                                </div>

                            </div>

                        </article>

                    @empty


                        <div class="nenhum-produto">

                            <h2>
                                Nenhum produto encontrado
                            </h2>

                            <p>
                                Tente alterar os filtros.
                            </p>

                        </div>


                    @endforelse

                </div>


                {{-- =========================================
                     PAGINAÇÃO
                ========================================== --}}

                @if ($produtos->hasPages())

                    <div class="produtos-paginacao">

                        {{ $produtos->withQueryString()->links() }}

                    </div>

                @endif

            </section>

        </div>

    </main>

    <x-footer />

</body>

</html>