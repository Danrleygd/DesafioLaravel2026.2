<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $produto->nome }} - D-Tech</title>

    @vite([
    'resources/css/app.css',
    'resources/css/navLanding.css',
    'resources/css/footer.css',
    'resources/css/produto.css',
    'resources/js/app.js'
    ])
</head>

<body class="produto-body">

    {{-- NAVBAR EXISTENTE DO PROJETO --}}
    <x-nav-landing />

    @php

    /*
    |--------------------------------------------------------------------------
    | IMAGENS
    |--------------------------------------------------------------------------
    */

    $fotoPrincipal = null;

    if ($produto->fotos->count() > 0) {

    $fotoPrincipal =
    $produto->fotos->firstWhere('principal', true)
    ?? $produto->fotos->first();

    }

    $imagemPrincipal = $fotoPrincipal?->foto ?? $produto->foto;

    /*
    |--------------------------------------------------------------------------
    | URL DA IMAGEM
    |--------------------------------------------------------------------------
    */

    $imagemUrl = function ($imagem) {

    if (!$imagem) {
    return asset('images/sem-imagem.png');
    }

    if (
    str_starts_with($imagem, 'http://') ||
    str_starts_with($imagem, 'https://')
    ) {
    return $imagem;
    }

    if (str_starts_with($imagem, '/')) {
    return asset(ltrim($imagem, '/'));
    }

    return asset('storage/' . ltrim($imagem, '/'));
    };

    /*
    |--------------------------------------------------------------------------
    | GALERIA
    |--------------------------------------------------------------------------
    */

    $galeria = collect();

    if ($imagemPrincipal) {
    $galeria->push($imagemPrincipal);
    }

    foreach ($produto->fotos as $foto) {

    if ($foto->foto !== $imagemPrincipal) {
    $galeria->push($foto->foto);
    }

    }

    /*
    |--------------------------------------------------------------------------
    | VALORES DO PROTÓTIPO
    |
    | O banco atual possui apenas o preço atual.
    | Esses valores são mantidos aqui para reproduzir
    | o visual do Figma.
    |--------------------------------------------------------------------------
    */

    $precoAnterior = 800.00;
    $avaliacao = 4.7;
    $quantidadeAvaliacoes = 218;

    @endphp


    <main class="produto-page">

        {{-- =========================================================
             PRODUTO
        ========================================================== --}}

        <section class="produto-principal">

            {{-- =====================================================
                 GALERIA
            ====================================================== --}}

            <div class="produto-galeria">

                <div class="produto-imagem-container">

                    <button
                        type="button"
                        class="galeria-seta galeria-anterior"
                        onclick="imagemAnterior()">
                        ‹
                    </button>

                    <img
                        id="imagemPrincipal"
                        src="{{ $imagemUrl($imagemPrincipal) }}"
                        alt="{{ $produto->nome }}"
                        class="produto-imagem-principal">

                    <button
                        type="button"
                        class="galeria-seta galeria-proxima"
                        onclick="proximaImagem()">
                        ›
                    </button>

                </div>


                {{-- MINIATURAS --}}

                <div class="produto-miniaturas">

                    @forelse($galeria as $index => $imagem)

                    <button
                        type="button"
                        class="miniatura {{ $index === 0 ? 'ativa' : '' }}"
                        onclick="selecionarImagem({{ $index }})">

                        <img
                            src="{{ $imagemUrl($imagem) }}"
                            alt="{{ $produto->nome }}">

                    </button>

                    @empty

                    <div class="sem-imagem">
                        Sem imagem
                    </div>

                    @endforelse

                </div>

            </div>


            {{-- =====================================================
                 INFORMAÇÕES
            ====================================================== --}}

            <div class="produto-informacoes">

                <span class="produto-categoria">
                    {{ $produto->categoria->nome ?? 'Categoria' }}
                </span>

                <h1>
                    {{ $produto->nome }}
                </h1>


                {{-- AVALIAÇÃO --}}

                <div class="produto-avaliacao">

                    <span class="estrelas">
                        ★
                    </span>

                    <span>
                        {{ number_format($avaliacao, 1, ',', '.') }}
                    </span>

                    <span class="avaliacoes">
                        ({{ $quantidadeAvaliacoes }})
                    </span>

                </div>


                {{-- PREÇO --}}

                <div class="produto-precos">

                    <strong>
                        R$
                        {{ number_format($produto->preco, 2, ',', '.') }}
                    </strong>

                    <span class="preco-anterior">
                        R$
                        {{ number_format($precoAnterior, 2, ',', '.') }}
                    </span>

                </div>


                {{-- COMPRA --}}

                <div class="produto-compra">

                    <div class="quantidade">

                        <button
                            type="button"
                            onclick="alterarQuantidade(-1)">
                            −
                        </button>

                        <input
                            type="number"
                            id="quantidade"
                            value="1"
                            min="1"
                            max="{{ $produto->quantidade }}">

                        <button
                            type="button"
                            onclick="alterarQuantidade(1)">
                            +
                        </button>

                    </div>


                    <button
                        type="button"
                        class="btn-carrinho"
                        onclick="adicionarCarrinho()">
                        Adicionar ao
                        <br>
                        Carrinho
                    </button>


                    <button
                        type="button"
                        class="btn-comprar">
                        Comprar
                    </button>


                    <button
                        type="button"
                        class="btn-favorito"
                        aria-label="Adicionar aos favoritos">
                        ♡
                    </button>

                </div>


                {{-- ESTOQUE --}}

                @if($produto->quantidade > 0)

                <span class="produto-estoque">
                    {{ $produto->quantidade }} unidades disponíveis
                </span>

                @else

                <span class="produto-sem-estoque">
                    Produto esgotado
                </span>

                @endif

            </div>

        </section>


        {{-- =========================================================
             DESCRIÇÃO / ESPECIFICAÇÃO
        ========================================================== --}}

        <section class="produto-detalhes">

            <div class="detalhe-bloco">

                <h2>
                    Descrição
                </h2>

                <p>
                    {{ $produto->descricao }}
                </p>

            </div>


            <div class="detalhe-divisor"></div>


            <div class="detalhe-bloco">

                <h2>
                    Especificação Técnica
                </h2>

                <p>
                    {{ $produto->descricao }}
                </p>

            </div>

        </section>


        {{-- =========================================================
             PRODUTOS RELACIONADOS
        ========================================================== --}}

        <section class="produtos-relacionados">

            <div class="relacionados-cabecalho">

                <div class="relacionados-titulo">

                    <span class="icone-categoria">
                        ◈
                    </span>

                    <strong>
                        Na Mesma Categoria
                    </strong>

                </div>


                <a href="{{ route('landing') }}">
                    Explorar mais →
                </a>

            </div>


            <div class="relacionados-container">

                <button
                    type="button"
                    class="relacionados-seta esquerda"
                    onclick="produtosAnteriores()">
                    ‹
                </button>


                <div
                    class="produtos-lista"
                    id="produtosLista">

                    @foreach($produtosRelacionados as $produtoRelacionado)

                    @php

                    $fotoRelacionado =
                    $produtoRelacionado->fotos
                    ->firstWhere('principal', true)
                    ?? $produtoRelacionado->fotos->first();

                    $imagemRelacionado =
                    $fotoRelacionado?->foto
                    ?? $produtoRelacionado->foto;

                    @endphp


                    <a
                        href="{{ route('produto.show', $produtoRelacionado->id) }}"
                        class="produto-card">

                        <div class="card-imagem">

                            <img
                                src="{{ $imagemUrl($imagemRelacionado) }}"
                                alt="{{ $produtoRelacionado->nome }}">

                            <button
                                type="button"
                                class="card-favorito"
                                onclick="event.preventDefault()">
                                ♡
                            </button>

                        </div>


                        <div class="card-informacoes">

                            <h3>
                                {{ $produtoRelacionado->nome }}
                            </h3>

                            <div class="card-preco">

                                <strong>
                                    R$
                                    {{ number_format($produtoRelacionado->preco, 2, ',', '.') }}
                                </strong>

                            </div>

                            <div class="card-avaliacao">

                                <span>
                                    ★
                                </span>

                                <span>
                                    4.7
                                </span>

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
                    class="relacionados-seta direita"
                    onclick="proximosProdutos()">
                    ›
                </button>

            </div>

        </section>

    </main>


    {{-- =============================================================
         JAVASCRIPT
    ============================================================== --}}

    <script>
        /* =========================================================
           GALERIA
        ========================================================== */

        const imagens = @json(
            $galeria->map(fn($imagem) => $imagemUrl($imagem))->values()
        );

        let imagemAtual = 0;


        function atualizarImagem() {

            const imagem = document.getElementById('imagemPrincipal');

            if (!imagens.length) {
                return;
            }

            imagem.src = imagens[imagemAtual];


            document
                .querySelectorAll('.miniatura')
                .forEach((miniatura, index) => {

                    miniatura.classList.toggle(
                        'ativa',
                        index === imagemAtual
                    );

                });

        }


        function selecionarImagem(index) {

            imagemAtual = index;

            atualizarImagem();

        }


        function proximaImagem() {

            if (!imagens.length) {
                return;
            }

            imagemAtual++;

            if (imagemAtual >= imagens.length) {
                imagemAtual = 0;
            }

            atualizarImagem();

        }


        function imagemAnterior() {

            if (!imagens.length) {
                return;
            }

            imagemAtual--;

            if (imagemAtual < 0) {
                imagemAtual = imagens.length - 1;
            }

            atualizarImagem();

        }


        /* =========================================================
           QUANTIDADE
        ========================================================== */

        function alterarQuantidade(valor) {

            const input = document.getElementById('quantidade');

            let quantidade = parseInt(input.value) || 1;

            quantidade += valor;

            const maximo = parseInt(input.max);

            if (quantidade < 1) {
                quantidade = 1;
            }

            if (quantidade > maximo) {
                quantidade = maximo;
            }

            input.value = quantidade;

        }


        /* =========================================================
        CARRINHO
        ========================================================== */

        function adicionarCarrinho() {

            const quantidade =
                document.getElementById('quantidade').value;

            console.log(
                'Produto:', {{ $produto->id }},
                'Quantidade:',
                quantidade
            );

        }


        /* =========================================================
        PRODUTOS RELACIONADOS
        ========================================================== */

        const produtosLista =
            document.getElementById('produtosLista');

        function proximosProdutos() {

            produtosLista.scrollBy({
                left: 300,
                behavior: 'smooth'
            });

        }


        function produtosAnteriores() {

            produtosLista.scrollBy({
                left: -300,
                behavior: 'smooth'
            });

        }
    </script>

</body>

</html>