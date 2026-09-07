@php

    /*
    |--------------------------------------------------------------------------
    | RF013 - GRÁFICO DE PRODUTOS CADASTRADOS
    |--------------------------------------------------------------------------
    */

    $inicioGrafico = now()
        ->startOfMonth()
        ->subMonths(11);

    $fimGrafico = now()
        ->endOfMonth();


    /*
    |--------------------------------------------------------------------------
    | PRODUTOS AGRUPADOS POR MÊS
    |--------------------------------------------------------------------------
    */

    $produtosAgrupados = \App\Models\Produto::query()
        ->whereNotNull('created_at')
        ->whereBetween(
            'created_at',
            [
                $inicioGrafico,
                $fimGrafico
            ]
        )
        ->selectRaw("
            DATE_FORMAT(created_at, '%Y-%m') AS periodo,
            COUNT(*) AS total
        ")
        ->groupByRaw("
            DATE_FORMAT(created_at, '%Y-%m')
        ")
        ->orderByRaw("
            DATE_FORMAT(created_at, '%Y-%m')
        ")
        ->pluck(
            'total',
            'periodo'
        );


    /*
    |--------------------------------------------------------------------------
    | NOMES DOS MESES
    |--------------------------------------------------------------------------
    */

    $nomesMeses = [
        1 => 'Jan',
        2 => 'Fev',
        3 => 'Mar',
        4 => 'Abr',
        5 => 'Mai',
        6 => 'Jun',
        7 => 'Jul',
        8 => 'Ago',
        9 => 'Set',
        10 => 'Out',
        11 => 'Nov',
        12 => 'Dez'
    ];


    /*
    |--------------------------------------------------------------------------
    | MONTAR OS ÚLTIMOS 12 MESES
    |--------------------------------------------------------------------------
    */

    $dadosGraficoProdutos = [];

    for ($indice = 0; $indice < 12; $indice++) {

        $data = $inicioGrafico
            ->copy()
            ->addMonths($indice);

        $chave = $data->format('Y-m');

        $total = (int) (
            $produtosAgrupados[$chave]
            ?? 0
        );

        $dadosGraficoProdutos[] = [
            'periodo' => $chave,

            'mes' => $nomesMeses[
                $data->month
            ],

            'ano' => $data->format('Y'),

            'label' =>
                $nomesMeses[$data->month]
                .
                '/'
                .
                $data->format('y'),

            'total' => $total
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | MAIOR VALOR
    |--------------------------------------------------------------------------
    */

    $maiorValorGrafico = (int) collect(
        $dadosGraficoProdutos
    )->max('total');

    if ($maiorValorGrafico < 1) {
        $maiorValorGrafico = 1;
    }


    /*
    |--------------------------------------------------------------------------
    | TOTAL NO PERÍODO
    |--------------------------------------------------------------------------
    */

    $totalProdutosGrafico = (int) collect(
        $dadosGraficoProdutos
    )->sum('total');

@endphp


@if(isset($isAdmin) && $isAdmin)

    <section
        class="rf013-card"
        id="rf013-chart"
    >

        {{-- =====================================================
            CABEÇALHO
        ====================================================== --}}

        <div class="rf013-header">

            <div class="rf013-heading">

                <div class="rf013-heading-icon">

                    <svg
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.8"
                    >
                        <path d="M4 19V10"></path>
                        <path d="M10 19V5"></path>
                        <path d="M16 19V12"></path>
                        <path d="M22 19H2"></path>
                    </svg>

                </div>


                <div class="rf013-heading-text">

                    <span class="rf013-badge">
                        RF013
                    </span>

                    <h2>
                        Produtos cadastrados
                    </h2>

                    <p>
                        Evolução dos cadastros nos últimos 12 meses
                    </p>

                </div>

            </div>


            {{-- =================================================
                TOTAL
            ================================================== --}}

            <div class="rf013-total">

                <span>
                    Total no período
                </span>

                <div>

                    <strong>
                        {{ $totalProdutosGrafico }}
                    </strong>

                    <small>
                        {{ $totalProdutosGrafico === 1 ? 'produto' : 'produtos' }}
                    </small>

                </div>

            </div>

        </div>


        {{-- =====================================================
            GRÁFICO
        ====================================================== --}}

        <div class="rf013-scroll">

            <div class="rf013-chart-area">


                {{-- =================================================
                    LINHAS DE FUNDO
                ================================================== --}}

                <div class="rf013-grid">
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                    <span></span>
                </div>


                {{-- =================================================
                    BARRAS
                ================================================== --}}

                <div class="rf013-bars">

                    @foreach($dadosGraficoProdutos as $item)

                        @php

                            if ($item['total'] > 0) {

                                $alturaBarra =
                                    (
                                        $item['total']
                                        /
                                        $maiorValorGrafico
                                    )
                                    *
                                    100;

                                $alturaBarra = max(
                                    8,
                                    $alturaBarra
                                );

                            } else {

                                $alturaBarra = 0;

                            }

                        @endphp


                        <div class="rf013-column">


                            {{-- QUANTIDADE --}}

                            <span class="rf013-value">
                                {{ $item['total'] }}
                            </span>


                            {{-- BARRA --}}

                            <div class="rf013-bar-space">

                                <div
                                    class="rf013-bar {{ $item['total'] === 0 ? 'rf013-bar-zero' : '' }}"
                                    style="height: {{ number_format($alturaBarra, 2, '.', '') }}%;"
                                >

                                    @if($item['total'] > 0)

                                        <div class="rf013-tooltip">

                                            <strong>
                                                {{ $item['total'] }}
                                            </strong>

                                            <span>
                                                {{ $item['total'] === 1 ? 'produto' : 'produtos' }}
                                            </span>

                                            <small>
                                                {{ $item['mes'] }} de {{ $item['ano'] }}
                                            </small>

                                        </div>

                                    @endif

                                </div>

                            </div>


                            {{-- MÊS --}}

                            <span class="rf013-label">
                                {{ $item['label'] }}
                            </span>

                        </div>

                    @endforeach

                </div>

            </div>

        </div>


        {{-- =====================================================
            RODAPÉ
        ====================================================== --}}

        <footer class="rf013-footer">

            <div class="rf013-legend">

                <span class="rf013-legend-color"></span>

                <span>
                    Produtos cadastrados
                </span>

            </div>


            <div class="rf013-period">

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

                    <path d="M12 7v5l3 2"></path>
                </svg>

                <span>
                    Últimos 12 meses
                </span>

            </div>

        </footer>

    </section>


    <style>

        /* =========================================================
           RF013
        ========================================================= */

        .rf013-card {
            width: 100%;
            margin: 24px 0;
            padding: 24px;

            background: #ffffff;

            border:
                1px solid
                #eee8f3;

            border-radius: 16px;

            box-shadow:
                0 5px 20px
                rgba(58, 32, 76, 0.05);
        }


        /* =========================================================
           HEADER
        ========================================================= */

        .rf013-header {
            width: 100%;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;

            margin-bottom: 28px;
        }


        .rf013-heading {
            display: flex;
            align-items: center;

            gap: 14px;
        }


        .rf013-heading-icon {
            width: 44px;
            height: 44px;

            flex-shrink: 0;

            display: flex;
            align-items: center;
            justify-content: center;

            color: #ffffff;

            background:
                linear-gradient(
                    135deg,
                    #6e3cac,
                    #9d69d7
                );

            border-radius: 11px;

            box-shadow:
                0 5px 13px
                rgba(111, 61, 171, 0.20);
        }


        .rf013-heading-icon svg {
            width: 22px;
            height: 22px;
        }


        .rf013-heading-text {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }


        .rf013-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            margin-bottom: 5px;

            padding: 3px 8px;

            color: #7541ac;
            background: #f2ebf9;

            border-radius: 20px;

            font-size: 9px;
            font-weight: 800;
            letter-spacing: 0.8px;
        }


        .rf013-heading h2 {
            margin: 0;

            color: #312539;

            font-size: 19px;
            font-weight: 700;
        }


        .rf013-heading p {
            margin: 4px 0 0;

            color: #94899c;

            font-size: 12px;
        }


        /* =========================================================
           TOTAL
        ========================================================= */

        .rf013-total {
            min-width: 140px;

            padding: 11px 15px;

            text-align: right;

            background: #f8f5fb;

            border:
                1px solid
                #eee8f3;

            border-radius: 10px;
        }


        .rf013-total > span {
            display: block;

            margin-bottom: 3px;

            color: #94889c;

            font-size: 10px;
            font-weight: 600;
        }


        .rf013-total div {
            display: flex;
            align-items: baseline;
            justify-content: flex-end;

            gap: 4px;
        }


        .rf013-total strong {
            color: #7040aa;

            font-size: 26px;
            line-height: 1;
        }


        .rf013-total small {
            color: #94889c;

            font-size: 10px;
        }


        /* =========================================================
           SCROLL
        ========================================================= */

        .rf013-scroll {
            width: 100%;

            overflow-x: auto;
            overflow-y: hidden;

            padding-bottom: 3px;
        }


        /* =========================================================
           GRÁFICO
        ========================================================= */

        .rf013-chart-area {
            position: relative;

            width: 100%;
            min-width: 760px;
            height: 300px;
        }


        /* =========================================================
           GRID
        ========================================================= */

        .rf013-grid {
            position: absolute;

            z-index: 1;

            top: 28px;
            right: 0;
            bottom: 42px;
            left: 0;

            display: flex;
            flex-direction: column;
            justify-content: space-between;

            pointer-events: none;
        }


        .rf013-grid span {
            display: block;

            width: 100%;
            height: 1px;

            background: #eeeaf1;
        }


        /* =========================================================
           BARRAS
        ========================================================= */

        .rf013-bars {
            position: relative;

            z-index: 2;

            width: 100%;
            height: 100%;

            display: grid;

            grid-template-columns:
                repeat(
                    12,
                    minmax(44px, 1fr)
                );

            gap: 12px;
        }


        .rf013-column {
            min-width: 0;
            height: 100%;

            display: grid;

            grid-template-rows:
                28px
                1fr
                42px;

            align-items: end;

            text-align: center;
        }


        /* =========================================================
           VALOR
        ========================================================= */

        .rf013-value {
            align-self: center;

            color: #776d7e;

            font-size: 10px;
            font-weight: 700;
        }


        /* =========================================================
           ESPAÇO DA BARRA
        ========================================================= */

        .rf013-bar-space {
            width: 100%;
            height: 100%;

            display: flex;
            align-items: flex-end;
            justify-content: center;
        }


        /* =========================================================
           BARRA
        ========================================================= */

        .rf013-bar {
            position: relative;

            width: 36px;
            max-width: 75%;

            min-height: 0;

            background:
                linear-gradient(
                    180deg,
                    #a66ddb 0%,
                    #7542ad 100%
                );

            border-radius:
                8px
                8px
                3px
                3px;

            box-shadow:
                0 6px 14px
                rgba(116, 64, 170, 0.20);

            transition:
                transform 0.2s ease,
                filter 0.2s ease;
        }


        .rf013-bar:hover {
            transform: translateY(-3px);

            filter: brightness(1.05);
        }


        .rf013-bar-zero {
            height: 2px !important;

            background: #ddd7e2;

            box-shadow: none;
        }


        /* =========================================================
           TOOLTIP
        ========================================================= */

        .rf013-tooltip {
            position: absolute;

            z-index: 10;

            bottom: calc(100% + 8px);
            left: 50%;

            min-width: 90px;

            display: flex;
            flex-direction: column;
            align-items: center;

            padding: 7px 9px;

            background: #33253d;
            color: #ffffff;

            border-radius: 7px;

            box-shadow:
                0 5px 15px
                rgba(30, 20, 40, 0.18);

            transform: translateX(-50%);

            opacity: 0;
            visibility: hidden;

            pointer-events: none;

            transition:
                opacity 0.15s ease;
        }


        .rf013-tooltip::after {
            content: '';

            position: absolute;

            top: 100%;
            left: 50%;

            width: 0;
            height: 0;

            border-left:
                5px solid
                transparent;

            border-right:
                5px solid
                transparent;

            border-top:
                5px solid
                #33253d;

            transform: translateX(-50%);
        }


        .rf013-tooltip strong {
            font-size: 13px;
        }


        .rf013-tooltip span {
            font-size: 9px;
        }


        .rf013-tooltip small {
            margin-top: 2px;

            color: #cfc5d7;

            font-size: 8px;
        }


        .rf013-bar:hover .rf013-tooltip {
            opacity: 1;
            visibility: visible;
        }


        /* =========================================================
           LABEL
        ========================================================= */

        .rf013-label {
            align-self: center;

            color: #817589;

            font-size: 10px;
            font-weight: 600;

            white-space: nowrap;
        }


        /* =========================================================
           FOOTER
        ========================================================= */

        .rf013-footer {
            width: 100%;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 16px;

            margin-top: 15px;
            padding-top: 15px;

            border-top:
                1px solid
                #eeeaf1;

            color: #94899b;

            font-size: 10px;
        }


        .rf013-legend {
            display: flex;
            align-items: center;

            gap: 7px;
        }


        .rf013-legend-color {
            width: 10px;
            height: 10px;

            display: inline-block;

            flex-shrink: 0;

            background:
                linear-gradient(
                    180deg,
                    #a66ddb,
                    #7542ad
                );

            border-radius: 3px;
        }


        .rf013-period {
            display: flex;
            align-items: center;

            gap: 5px;
        }


        .rf013-period svg {
            width: 14px;
            height: 14px;
        }


        /* =========================================================
           SCROLLBAR
        ========================================================= */

        .rf013-scroll::-webkit-scrollbar {
            height: 6px;
        }


        .rf013-scroll::-webkit-scrollbar-track {
            background: #f4f1f6;

            border-radius: 20px;
        }


        .rf013-scroll::-webkit-scrollbar-thumb {
            background: #c5b1d6;

            border-radius: 20px;
        }


        /* =========================================================
           RESPONSIVO
        ========================================================= */

        @media (max-width: 768px) {

            .rf013-card {
                margin: 18px 0;

                padding: 18px;
            }


            .rf013-header {
                align-items: stretch;

                flex-direction: column;
            }


            .rf013-total {
                width: 100%;

                text-align: left;
            }


            .rf013-total div {
                justify-content: flex-start;
            }


            .rf013-footer {
                align-items: flex-start;

                flex-direction: column;

                gap: 8px;
            }

        }


        @media (max-width: 480px) {

            .rf013-heading {
                align-items: flex-start;
            }


            .rf013-heading-icon {
                width: 38px;
                height: 38px;
            }


            .rf013-heading h2 {
                font-size: 16px;
            }


            .rf013-heading p {
                font-size: 11px;
            }

        }

    </style>


    {{-- =========================================================
        POSICIONAR GRÁFICO DEPOIS DOS CARDS
    ========================================================== --}}

    <script>

        (function () {

            function posicionarGraficoRf013() {

                const grafico =
                    document.getElementById(
                        'rf013-chart'
                    );

                const estatisticas =
                    document.querySelector(
                        '.pm-stats'
                    );

                if (
                    !grafico
                    ||
                    !estatisticas
                ) {
                    return;
                }


                if (
                    estatisticas.nextElementSibling
                    ===
                    grafico
                ) {
                    return;
                }


                estatisticas.insertAdjacentElement(
                    'afterend',
                    grafico
                );

            }


            if (
                document.readyState
                ===
                'loading'
            ) {

                document.addEventListener(
                    'DOMContentLoaded',
                    posicionarGraficoRf013
                );

            } else {

                posicionarGraficoRf013();

            }

        })();

    </script>

@endif