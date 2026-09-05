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
    'resources/js/app.js',
    'resources/js/landingBanners.js',
    'resources/js/landingProducts.js',
    ])
</head>

<body class="landing-body">

    <x-nav-landing />

    <main class="landing-content">

        {{-- =========================================
BANNERS
========================================== --}}

        <section class="landing-banners">

            {{-- =========================================
    BANNER PRINCIPAL - CARROSSEL
    ========================================== --}}

            <div class="landing-banner-main">

                @foreach ($bannersPrincipais as $index => $banner)

                <div
                    class="landing-main-slide {{ $index === 0 ? 'active' : '' }}"
                    data-slide="{{ $index }}">

                    <img
                        src="{{ asset('assets/images/' . $banner['imagem']) }}"
                        alt="{{ $banner['alt'] }}">

                    <div class="landing-banner-overlay">

                        @foreach ($banner['titulo'] as $linha)

                        <span>
                            {{ $linha }}
                        </span>

                        @endforeach

                        <a href="{{ $banner['link'] }}">
                            Compre Agora
                        </a>

                    </div>

                </div>

                @endforeach


                {{-- =========================================
        INDICADORES DO CARROSSEL
        ========================================== --}}

                @if (count($bannersPrincipais) > 1)

                <div class="landing-slider-dots">

                    @foreach ($bannersPrincipais as $index => $banner)

                    <button
                        type="button"
                        class="{{ $index === 0 ? 'active' : '' }}"
                        data-slide-to="{{ $index }}"
                        aria-label="Banner {{ $index + 1 }}"></button>

                    @endforeach

                </div>

                @endif

            </div>


            {{-- =========================================
    PRODUTOS LATERAIS
    ========================================== --}}

            <div class="landing-banner-side">

                @foreach ($produtosLaterais as $produto)

                <a
                    href="{{ route('produto.show', $produto->id) }}"
                    class="landing-side-banner">

                    {{-- IMAGEM DO PRODUTO --}}

                    @if ($produto->foto)

                    <img
                        src="{{
                            str_starts_with($produto->foto, 'http://')
                            || str_starts_with($produto->foto, 'https://')

                                ? $produto->foto

                                : asset(
                                    'storage/' .
                                    ltrim($produto->foto, '/')
                                )
                        }}"
                        alt="{{ $produto->nome }}">

                    @else

                    <img
                        src="{{ asset('assets/images/foneCase.png') }}"
                        alt="{{ $produto->nome }}">

                    @endif


                    {{-- NOME DO PRODUTO --}}

                    <div class="landing-side-product-info">

                        <h2>
                            {{ $produto->nome }}
                        </h2>

                        <span>
                            Comprar agora
                        </span>

                    </div>

                </a>

                @endforeach

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
                href="{{ route('produtos.index', [
                        'categoria' => $categoria->id
                    ]) }}"
                class="landing-category">

                <div class="landing-category-image">

                    <img
                        src="{{ asset(
                                'assets/images/' .
                                ($imagensCategorias[$categoria->nome] ?? 'fone.png')
                            ) }}"
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

                <a href="{{ route('produtos.index') }}">
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

                        <div class="landing-discount">
                            OFERTA
                        </div>

                        <button
                            class="landing-favorite"
                            type="button">
                            ♡
                        </button>

                        <a
                            href="{{ route('produto.show', $produto->id) }}"
                            class="landing-product-image">

                            @if ($produto->foto)

                            <img
                                src="{{ str_starts_with($produto->foto, 'http://') ||
                                            str_starts_with($produto->foto, 'https://')
                                                ? $produto->foto
                                                : asset('storage/' . ltrim($produto->foto, '/')) }}"
                                alt="{{ $produto->nome }}">

                            @else

                            <img
                                src="{{ asset('assets/images/foneCase.png') }}"
                                alt="{{ $produto->nome }}">

                            @endif

                        </a>

                        <div class="landing-product-info">

                            <h3>
                                {{ $produto->nome }}
                            </h3>

                            <div class="landing-price">

                                <strong>
                                    R$ {{ number_format(
                                            $produto->preco,
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                </strong>

                            </div>

                            <div class="landing-rating">

                                <span>★</span>

                                Disponível:
                                {{ $produto->quantidade }}

                            </div>

                        </div>

                    </article>

                    @empty

                    <p>Nenhum produto encontrado.</p>

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

                <a
                    href="{{ route('produtos.index', [
                        'secao' => 'upgrade'
                    ]) }}">
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

                        <div class="landing-discount">
                            OFERTA
                        </div>

                        <button
                            class="landing-favorite"
                            type="button">
                            ♡
                        </button>

                        <a
                            href="{{ route('produto.show', $produto->id) }}"
                            class="landing-product-image">

                            @if ($produto->foto)

                            <img
                                src="{{ str_starts_with($produto->foto, 'http://') ||
                                            str_starts_with($produto->foto, 'https://')
                                                ? $produto->foto
                                                : asset('storage/' . ltrim($produto->foto, '/')) }}"
                                alt="{{ $produto->nome }}">

                            @else

                            <img
                                src="{{ asset('assets/images/foneCase.png') }}"
                                alt="{{ $produto->nome }}">

                            @endif

                        </a>

                        <div class="landing-product-info">

                            <h3>
                                {{ $produto->nome }}
                            </h3>

                            <div class="landing-price">

                                <strong>
                                    R$ {{ number_format(
                                            $produto->preco,
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                </strong>

                            </div>

                            <div class="landing-rating">

                                <span>★</span>

                                Disponível:
                                {{ $produto->quantidade }}

                            </div>

                        </div>

                    </article>

                    @empty

                    <p>Nenhum produto encontrado.</p>

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

                <a
                    href="{{ route('produtos.index', [
                        'secao' => 'casa'
                    ]) }}">
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

                        <div class="landing-discount">
                            OFERTA
                        </div>

                        <button
                            class="landing-favorite"
                            type="button">
                            ♡
                        </button>

                        <a
                            href="{{ route('produto.show', $produto->id) }}"
                            class="landing-product-image">

                            @if ($produto->foto)

                            <img
                                src="{{ str_starts_with($produto->foto, 'http://') ||
                                            str_starts_with($produto->foto, 'https://')
                                                ? $produto->foto
                                                : asset('storage/' . ltrim($produto->foto, '/')) }}"
                                alt="{{ $produto->nome }}">

                            @else

                            <img
                                src="{{ asset('assets/images/foneCase.png') }}"
                                alt="{{ $produto->nome }}">

                            @endif

                        </a>

                        <div class="landing-product-info">

                            <h3>
                                {{ $produto->nome }}
                            </h3>

                            <div class="landing-price">

                                <strong>
                                    R$ {{ number_format(
                                            $produto->preco,
                                            2,
                                            ',',
                                            '.'
                                        ) }}
                                </strong>

                            </div>

                            <div class="landing-rating">

                                <span>★</span>

                                Disponível:
                                {{ $produto->quantidade }}

                            </div>

                        </div>

                    </article>

                    @empty

                    <p>Nenhum produto encontrado.</p>

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

    <x-footer />

</body>

</html>