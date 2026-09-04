<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>D-tech</title>

    @vite([
    'resources/css/app.css',
    'resources/css/landing.css',
    'resources/css/navLanding.css',
    'resources/css/footer.css',
    'resources/js/app.js'
    ])
</head>

<body class="landing-body">

    {{-- =========================================
         NAVBAR
    ========================================== --}}
    <x-nav-landing />

    {{-- =========================================
         CONTEÚDO
    ========================================== --}}
    <main class="landing-content">

        {{-- =========================================
             BANNERS
        ========================================== --}}
        <section class="landing-banners">

            {{-- BANNER PRINCIPAL --}}
            <div class="landing-banner-main">

                <img
                    src="{{ asset('assets/images/razer-kraken-kitty-v2-gengar_inner-details_desktop-1920x700.webp') }}"
                    alt="Razer Kraken Kitty V2 Gengar">

                <div class="landing-banner-overlay">

                    <span>RAZER KRAKEN</span>
                    <span>THY V2</span>
                    <span>EDIÇÃO GENGAR</span>

                    <a href="#">
                        Compre Agora
                    </a>

                </div>

                {{-- CONTROLES --}}
                <div class="landing-slider-dots">

                    <button class="active"></button>
                    <button></button>
                    <button></button>
                    <button></button>

                </div>

            </div>

            {{-- BANNERS LATERAIS --}}
            <div class="landing-banner-side">

                <div class="landing-side-banner">

                    <img
                        src="{{ asset('assets/images/DeadpoolControl.jpeg') }}"
                        alt="Cheeky Controller">

                    <div class="landing-side-banner-content">

                        <h2>
                            Cheeky Controller<br>
                            by Deadpool
                        </h2>

                        <a href="#">
                            Compre Agora →
                        </a>

                    </div>

                </div>

                <div class="landing-side-banner">

                    <img
                        src="{{ asset('assets/images/wolverineAlexa.webp') }}"
                        alt="Alexarine">

                    <div class="landing-side-banner-content">

                        <h2>
                            Alexarine
                        </h2>

                        <a href="#">
                            Compre Agora →
                        </a>

                    </div>

                </div>

            </div>

        </section>


        {{-- =========================================
             CATEGORIAS
        ========================================== --}}
        <section
            id="categorias"
            class="landing-categories">

            @php

            $imagensCategorias = [

            'Smartphones' => 'Celular.png',
            'Tablets' => 'tablet.png',
            'Computadores' => 'Pc.png',
            'Controles' => 'controleSwt.png',
            'Consoles' => 'play5.png',
            'Audio' => 'fone.png',
            'Acessorios' => 'carregador.png',
            'Eletrodomesticos' => 'geladeira.png',

            ];

            @endphp


            @foreach ($categorias as $categoria)

            <a
                href="{{ route('landing', ['categoria' => $categoria->id]) }}"
                class="landing-category">

                <div class="landing-category-image">

                    <img
                        src="{{ asset('assets/images/' . ($imagensCategorias[$categoria->nome] ?? 'fone.png')) }}"
                        alt="{{ $categoria->nome }}">

                </div>

                <span>
                    {{ $categoria->nome }}
                </span>

            </a>

            @endforeach

        </section>


        {{-- =========================================
             PRINCIPAIS PROMOÇÕES
        ========================================== --}}
        <section
            id="produtos"
            class="landing-products-section">

            <div class="landing-section-header">

                <div class="landing-section-title">

                    <div class="landing-section-icon">
                        %
                    </div>

                    <strong>
                        Principais Promoções
                    </strong>

                </div>

                <a href="#">
                    Explorar mais →
                </a>

            </div>


            <div class="landing-products-wrapper">

                <button
                    class="landing-arrow landing-arrow-left"
                    type="button">

                    ‹

                </button>


                <div class="landing-products">

                    @forelse ($produtos as $produto)

                    <article class="landing-product-card">

                        {{-- DESCONTO --}}
                        <div class="landing-discount">
                            OFERTA
                        </div>


                        {{-- FAVORITO --}}
                        <button
                            class="landing-favorite"
                            type="button">

                            ♡

                        </button>


                        {{-- IMAGEM --}}
                        <a
                            href="{{ route('produto.show', $produto->id) }}"
                            class="landing-product-image">

                            @if ($produto->foto)

                            <img
                                src="{{ str_starts_with($produto->foto, 'http://') || str_starts_with($produto->foto, 'https://')
                                            ? $produto->foto
                                            : asset('storage/' . ltrim($produto->foto, '/')) }}"
                                alt="{{ $produto->nome }}">

                            @else

                            <img
                                src="{{ asset('assets/images/foneCase.png') }}"
                                alt="{{ $produto->nome }}">

                            @endif

                        </a>


                        {{-- INFORMAÇÕES --}}
                        <div class="landing-product-info">

                            <h3>
                                {{ $produto->nome }}
                            </h3>


                            <div class="landing-price">

                                <strong>
                                    R$
                                    {{ number_format($produto->preco, 2, ',', '.') }}
                                </strong>

                            </div>


                            <div class="landing-rating">

                                <span>
                                    ★
                                </span>

                                Disponível:
                                {{ $produto->quantidade }}

                            </div>

                        </div>

                    </article>

                    @empty

                    <p>
                        Nenhum produto encontrado.
                    </p>

                    @endforelse

                </div>


                <button
                    class="landing-arrow landing-arrow-right"
                    type="button">

                    ›

                </button>

            </div>

        </section>


        {{-- =========================================
             HORA DO UPGRADE
        ========================================== --}}
        <section class="landing-products-section">

            <div class="landing-section-header">

                <div class="landing-section-title">

                    <div class="landing-section-icon upgrade">
                        ▣
                    </div>

                    <strong>
                        Hora do Upgrade
                    </strong>

                </div>

                <a href="#">
                    Explorar mais →
                </a>

            </div>


            <div class="landing-products-wrapper">

                <button
                    class="landing-arrow landing-arrow-left"
                    type="button">

                    ‹

                </button>


                <div class="landing-products">

                    @forelse ($produtosUpgrade as $produto)

                    <article class="landing-product-card">

                        {{-- DESCONTO --}}
                        <div class="landing-discount">
                            -20%
                        </div>


                        {{-- FAVORITO --}}
                        <button
                            class="landing-favorite"
                            type="button">

                            ♡

                        </button>


                        {{-- IMAGEM --}}
                        <a
                            href="{{ route('produto.show', $produto->id) }}"
                            class="landing-product-image">

                            @if ($produto->foto)

                            <img
                                src="{{ str_starts_with($produto->foto, 'http://') || str_starts_with($produto->foto, 'https://')
                                            ? $produto->foto
                                            : asset('storage/' . ltrim($produto->foto, '/')) }}"
                                alt="{{ $produto->nome }}">

                            @else

                            <img
                                src="{{ asset('assets/images/foneCase.png') }}"
                                alt="{{ $produto->nome }}">

                            @endif

                        </a>


                        {{-- INFORMAÇÕES --}}
                        <div class="landing-product-info">

                            <h3>
                                {{ $produto->nome }}
                            </h3>


                            <div class="landing-price">

                                <strong>
                                    R$
                                    {{ number_format($produto->preco, 2, ',', '.') }}
                                </strong>

                            </div>


                            <div class="landing-rating">

                                <span>
                                    ★
                                </span>

                                Disponível:
                                {{ $produto->quantidade }}

                            </div>

                        </div>

                    </article>

                    @empty

                    <p>
                        Nenhum produto encontrado.
                    </p>

                    @endforelse

                </div>


                <button
                    class="landing-arrow landing-arrow-right"
                    type="button">

                    ›

                </button>

            </div>

        </section>


        {{-- =========================================
             O QUE FALTA NA SUA CASA
        ========================================== --}}
        <section class="landing-products-section">

            <div class="landing-section-header">

                <div class="landing-section-title">

                    <div class="landing-section-icon upgrade">
                        ▣
                    </div>

                    <strong>
                        O Que falta na sua Casa
                    </strong>

                </div>

                <a href="#">
                    Explorar mais →
                </a>

            </div>


            <div class="landing-products-wrapper">

                <button
                    class="landing-arrow landing-arrow-left"
                    type="button">

                    ‹

                </button>


                <div class="landing-products">

                    @forelse ($produtosCasa as $produto)

                    <article class="landing-product-card">

                        {{-- DESCONTO --}}
                        <div class="landing-discount">
                            -20%
                        </div>


                        {{-- FAVORITO --}}
                        <button
                            class="landing-favorite"
                            type="button">

                            ♡

                        </button>


                        {{-- IMAGEM --}}
                        <a
                            href="{{ route('produto.show', $produto->id) }}"
                            class="landing-product-image">

                            @if ($produto->foto)

                            <img
                                src="{{ str_starts_with($produto->foto, 'http://') || str_starts_with($produto->foto, 'https://')
                                            ? $produto->foto
                                            : asset('storage/' . ltrim($produto->foto, '/')) }}"
                                alt="{{ $produto->nome }}">

                            @else

                            <img
                                src="{{ asset('assets/images/foneCase.png') }}"
                                alt="{{ $produto->nome }}">

                            @endif

                        </a>


                        {{-- INFORMAÇÕES --}}
                        <div class="landing-product-info">

                            <h3>
                                {{ $produto->nome }}
                            </h3>


                            <div class="landing-price">

                                <strong>
                                    R$
                                    {{ number_format($produto->preco, 2, ',', '.') }}
                                </strong>

                            </div>


                            <div class="landing-rating">

                                <span>
                                    ★
                                </span>

                                Disponível:
                                {{ $produto->quantidade }}

                            </div>

                        </div>

                    </article>

                    @empty

                    <p>
                        Nenhum produto encontrado.
                    </p>

                    @endforelse

                </div>


                <button
                    class="landing-arrow landing-arrow-right"
                    type="button">

                    ›

                </button>

            </div>

        </section>

    </main>


    {{-- =========================================
        FOOTER
    ========================================== --}}
    <x-footer />

</body>

</html>