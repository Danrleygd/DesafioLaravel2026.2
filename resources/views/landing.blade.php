<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>D-tech</title>

    @vite([
    'resources/css/app.css',
    'resources/css/landing.css',
    'resources/css/navLanding.css',
    'resources/js/app.js'
    ])
</head>

<body>

    {{-- =========================================
        NAVBAR
    ========================================== --}}

    <x-nav-landing/>

        


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

                <a href="#" class="landing-side-banner">
                    <img
                        src="{{ asset('assets/images/DeadpoolControl.jpeg') }}"
                        alt="Cheeky Controller">
                </a>

                <a href="#" class="landing-side-banner">
                    <img
                        src="{{ asset('assets/images/wolverineAlexa.webp') }}"
                        alt="Alexarine">
                </a>

            </div>

        </section>


        {{-- =========================================
             CATEGORIAS
        ========================================== --}}

        <section
            id="categorias"
            class="landing-categories">

            @php
            $categorias = [
            ['nome' => 'Smartphones', 'imagem' => 'Celular.png'],
            ['nome' => 'Tablets', 'imagem' => 'tablet.png'],
            ['nome' => 'Computadores', 'imagem' => 'Pc.png'],
            ['nome' => 'Controles', 'imagem' => 'controleSwt.png'],
            ['nome' => 'Consoles', 'imagem' => 'play5.png'],
            ['nome' => 'Áudio', 'imagem' => 'fone.png'],
            ['nome' => 'Acessórios', 'imagem' => 'carregador.png'],
            ['nome' => 'Eletrodomésticos', 'imagem' => 'geladeira.png'],
            ];
            @endphp


            @foreach ($categorias as $categoria)

            <a
                href="#"
                class="landing-category">

                <div class="landing-category-image">

                    <img
                        src="{{ asset('assets/images/' . $categoria['imagem']) }}"
                        alt="{{ $categoria['nome'] }}">

                </div>

                <span>
                    {{ $categoria['nome'] }}
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

                    @for ($i = 1; $i <= 5; $i++)

                        <article class="landing-product-card">

                        <div class="landing-discount">
                            -20%
                        </div>


                        <button
                            class="landing-favorite"
                            type="button">
                            ♡
                        </button>


                        <div class="landing-product-image">

                            <img
                                src="{{ asset('assets/images/foneCase.png') }}"
                                alt="Fone de Ouvido">

                        </div>


                        <div class="landing-product-info">

                            <h3>
                                Fone de Ouvido
                            </h3>

                            <div class="landing-price">

                                <strong>
                                    R$ 50,00
                                </strong>

                                <del>
                                    R$ 60,00
                                </del>

                            </div>

                            <div class="landing-rating">

                                <span>★</span>

                                4.7 (218)

                            </div>

                        </div>

                        </article>

                        @endfor

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

                    @for ($i = 1; $i <= 5; $i++)

                        <article class="landing-product-card">

                        <div class="landing-discount">
                            -20%
                        </div>


                        <button
                            class="landing-favorite"
                            type="button">
                            ♡
                        </button>


                        <div class="landing-product-image">

                            <img
                                src="{{ asset('assets/images/foneCase.png') }}"
                                alt="Fone de Ouvido">

                        </div>


                        <div class="landing-product-info">

                            <h3>
                                Fone de Ouvido
                            </h3>

                            <div class="landing-price">

                                <strong>
                                    R$ 50,00
                                </strong>

                                <del>
                                    R$ 60,00
                                </del>

                            </div>

                            <div class="landing-rating">

                                <span>★</span>

                                4.7 (218)

                            </div>

                        </div>

                        </article>

                        @endfor

                </div>


                <button
                    class="landing-arrow landing-arrow-right"
                    type="button">
                    ›
                </button>

            </div>

        </section>

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

                    @for ($i = 1; $i <= 5; $i++)

                        <article class="landing-product-card">

                        <div class="landing-discount">
                            -20%
                        </div>


                        <button
                            class="landing-favorite"
                            type="button">
                            ♡
                        </button>


                        <div class="landing-product-image">

                            <img
                                src="{{ asset('assets/images/foneCase.png') }}"
                                alt="Fone de Ouvido">

                        </div>


                        <div class="landing-product-info">

                            <h3>
                                Fone de Ouvido
                            </h3>

                            <div class="landing-price">

                                <strong>
                                    R$ 50,00
                                </strong>

                                <del>
                                    R$ 60,00
                                </del>

                            </div>

                            <div class="landing-rating">

                                <span>★</span>

                                4.7 (218)

                            </div>

                        </div>

                        </article>

                        @endfor

                </div>


                <button
                    class="landing-arrow landing-arrow-right"
                    type="button">
                    ›
                </button>

            </div>

        </section>


    </main>

</body>

</html>