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
        Minhas Vendas - D-tech
    </title>


    @vite([
        'resources/css/app.css',
        'resources/css/vendas.css',
        'resources/css/userSidebar.css',
        'resources/js/userSidebar.js'
    ])


    <style>

        /* =========================================================
           RF014 - GRÁFICO DE VENDAS
        ========================================================= */

        .rf014-card {
            width: 100%;

            margin:
                0
                0
                18px;

            padding: 22px;

            border:
                1px solid
                rgba(85, 51, 103, .08);

            border-radius: 14px;

            background: #ffffff;

            box-shadow:
                0 6px 22px
                rgba(48, 27, 59, .04);
        }


        /* HEADER */

        .rf014-header {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 20px;

            margin-bottom: 18px;
        }


        .rf014-heading {
            display: flex;

            align-items: center;

            gap: 12px;
        }


        .rf014-icon {
            width: 44px;
            height: 44px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 11px;

            background:
                linear-gradient(
                    135deg,
                    #7939b2,
                    #5c208b
                );

            color: #ffffff;
        }


        .rf014-icon svg {
            width: 22px;
            height: 22px;
        }


        .rf014-badge {
            display: inline-flex;

            margin-bottom: 4px;

            padding:
                3px
                8px;

            border-radius: 999px;

            background: #f0e5f7;

            color: #6d319e;

            font-size: 8px;

            font-weight: 800;
        }


        .rf014-heading h2 {
            margin: 0;

            color: #3d2947;

            font-size: 15px;

            font-weight: 800;
        }


        .rf014-heading p {
            margin:
                4px
                0
                0;

            color: #8c8091;

            font-size: 9px;
        }


        /* TOTAL */

        .rf014-total {
            min-width: 140px;

            padding:
                11px
                14px;

            border-radius: 10px;

            background: #f8f4fb;

            text-align: right;
        }


        .rf014-total > span {
            display: block;

            margin-bottom: 3px;

            color: #8c8091;

            font-size: 8px;
        }


        .rf014-total div {
            display: flex;

            align-items: baseline;

            justify-content: flex-end;

            gap: 4px;
        }


        .rf014-total strong {
            color: #6d2b9e;

            font-size: 24px;

            line-height: 1;
        }


        .rf014-total small {
            color: #8c8091;

            font-size: 8px;
        }


        /* RESUMO */

        .rf014-summary {
            display: grid;

            grid-template-columns:
                repeat(
                    3,
                    minmax(0, 1fr)
                );

            gap: 10px;

            margin-bottom: 18px;
        }


        .rf014-summary article {
            padding:
                11px
                13px;

            border:
                1px solid
                #eee7f2;

            border-radius: 9px;

            background: #fbf9fd;
        }


        .rf014-summary article span {
            display: block;

            margin-bottom: 3px;

            color: #998fa0;

            font-size: 7px;

            font-weight: 700;

            text-transform: uppercase;
        }


        .rf014-summary article strong {
            display: block;

            color: #44314e;

            font-size: 14px;
        }


        .rf014-summary article small {
            display: block;

            margin-top: 2px;

            color: #9d93a1;

            font-size: 7px;
        }


        /* GRÁFICO */

        .rf014-scroll {
            width: 100%;

            overflow-x: auto;

            overflow-y: hidden;
        }


        .rf014-chart {
            width: 100%;

            min-width: 760px;
        }


        .rf014-svg {
            display: block;

            width: 100%;

            height: auto;
        }


        .rf014-grid-line {
            stroke: #eee9f2;

            stroke-width: 1;
        }


        .rf014-axis {
            stroke: #ddd4e4;

            stroke-width: 1.2;
        }


        .rf014-y-label {
            fill: #9d92a4;

            font-size: 10px;
        }


        .rf014-x-label {
            fill: #817589;

            font-size: 10px;

            font-weight: 600;
        }


        .rf014-line {
            fill: none;

            stroke: #743cab;

            stroke-width: 4;

            stroke-linecap: round;

            stroke-linejoin: round;

            filter:
                drop-shadow(
                    0 4px 5px
                    rgba(111, 61, 170, .15)
                );
        }


        .rf014-hit {
            fill: transparent;
        }


        .rf014-point {
            fill: #ffffff;

            stroke: #743cab;

            stroke-width: 4;

            transition:
                r .2s ease,
                fill .2s ease;
        }


        .rf014-point-group:hover
        .rf014-point {
            r: 7;

            fill: #743cab;
        }


        /* FOOTER */

        .rf014-footer {
            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 15px;

            margin-top: 12px;

            padding-top: 13px;

            border-top:
                1px solid
                #eee9f2;

            color: #95899d;

            font-size: 8px;
        }


        .rf014-footer div {
            display: flex;

            align-items: center;

            gap: 8px;
        }


        .rf014-legend {
            width: 26px;
            height: 3px;

            display: inline-block;

            border-radius: 999px;

            background:
                linear-gradient(
                    90deg,
                    #a56cdb,
                    #66309d
                );
        }


        /* RESPONSIVO */

        @media (max-width: 750px) {

            .rf014-card {
                padding: 17px;
            }


            .rf014-header {
                align-items: stretch;

                flex-direction: column;
            }


            .rf014-total {
                width: 100%;

                text-align: left;
            }


            .rf014-total div {
                justify-content: flex-start;
            }


            .rf014-summary {
                grid-template-columns: 1fr;
            }


            .rf014-footer {
                align-items: flex-start;

                flex-direction: column;
            }

        }

    </style>

</head>


<body class="sales-public-body">

    <x-user-sidebar />


    <main class="sales-public-main">


        {{-- =========================================================
            RF014 - DADOS
        ========================================================== --}}

        @php

            /*
            |--------------------------------------------------------------------------
            | PERÍODO
            |--------------------------------------------------------------------------
            */

            $inicioRf014 = now()
                ->copy()
                ->startOfMonth()
                ->subMonths(11);

            $fimRf014 = now()
                ->copy()
                ->endOfMonth();


            /*
            |--------------------------------------------------------------------------
            | CONSULTA
            |--------------------------------------------------------------------------
            */

            $vendasRf014 =
                \Illuminate\Support\Facades\DB::table(
                    'ItensVendas as iv'
                )
                    ->join(
                        'Vendas as v',
                        'iv.VendasId',
                        '=',
                        'v.id'
                    )
                    ->where(
                        'iv.VendedorId',
                        \Illuminate\Support\Facades\Auth::id()
                    )
                    ->whereBetween(
                        'v.created_at',
                        [
                            $inicioRf014,
                            $fimRf014
                        ]
                    )
                    ->selectRaw(
                        "
                        DATE_FORMAT(
                            v.created_at,
                            '%Y-%m'
                        ) AS periodo,

                        COUNT(
                            DISTINCT v.id
                        ) AS total
                        "
                    )
                    ->groupByRaw(
                        "
                        DATE_FORMAT(
                            v.created_at,
                            '%Y-%m'
                        )
                        "
                    )
                    ->orderByRaw(
                        "
                        DATE_FORMAT(
                            v.created_at,
                            '%Y-%m'
                        )
                        "
                    )
                    ->pluck(
                        'total',
                        'periodo'
                    );


            /*
            |--------------------------------------------------------------------------
            | MESES
            |--------------------------------------------------------------------------
            */

            $mesesRf014 = [
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
            | DADOS
            |--------------------------------------------------------------------------
            */

            $dadosRf014 = [];


            for ($i = 0; $i < 12; $i++) {

                $data =
                    $inicioRf014
                        ->copy()
                        ->addMonths($i);


                $chave =
                    $data->format(
                        'Y-m'
                    );


                $dadosRf014[] = [

                    'mes' =>
                        $mesesRf014[
                            $data->month
                        ],

                    'ano' =>
                        $data->format(
                            'Y'
                        ),

                    'label' =>
                        $mesesRf014[
                            $data->month
                        ]
                        .
                        '/'
                        .
                        $data->format(
                            'y'
                        ),

                    'total' =>
                        (int) (
                            $vendasRf014[
                                $chave
                            ]
                            ?? 0
                        )

                ];
            }


            /*
            |--------------------------------------------------------------------------
            | TOTAL
            |--------------------------------------------------------------------------
            */

            $totalRf014 =
                (int) collect(
                    $dadosRf014
                )
                    ->sum(
                        'total'
                    );


            /*
            |--------------------------------------------------------------------------
            | MAIOR
            |--------------------------------------------------------------------------
            */

            $maiorRf014 =
                (int) collect(
                    $dadosRf014
                )
                    ->max(
                        'total'
                    );


            if ($maiorRf014 < 1) {
                $maiorRf014 = 1;
            }


            /*
            |--------------------------------------------------------------------------
            | MELHOR MÊS
            |--------------------------------------------------------------------------
            */

            $melhorRf014 =
                collect(
                    $dadosRf014
                )
                    ->sortByDesc(
                        'total'
                    )
                    ->first();


            /*
            |--------------------------------------------------------------------------
            | MÉDIA
            |--------------------------------------------------------------------------
            */

            $mediaRf014 =
                round(
                    $totalRf014
                    /
                    12,
                    1
                );


            /*
            |--------------------------------------------------------------------------
            | SVG
            |--------------------------------------------------------------------------
            */

            $svgWidth = 1000;

            $svgHeight = 320;

            $left = 55;

            $right = 25;

            $top = 30;

            $bottom = 60;


            $chartWidth =
                $svgWidth
                -
                $left
                -
                $right;


            $chartHeight =
                $svgHeight
                -
                $top
                -
                $bottom;


            $baseY =
                $top
                +
                $chartHeight;


            /*
            |--------------------------------------------------------------------------
            | PONTOS
            |--------------------------------------------------------------------------
            */

            $pontosRf014 = [];


            foreach ($dadosRf014 as $indice => $item) {

                $x =
                    $left
                    +
                    (
                        $indice
                        *
                        (
                            $chartWidth
                            /
                            11
                        )
                    );


                $y =
                    $baseY
                    -
                    (
                        (
                            $item['total']
                            /
                            $maiorRf014
                        )
                        *
                        $chartHeight
                    );


                $pontosRf014[] = [

                    'x' =>
                        round(
                            $x,
                            2
                        ),

                    'y' =>
                        round(
                            $y,
                            2
                        ),

                    'label' =>
                        $item['label'],

                    'mes' =>
                        $item['mes'],

                    'ano' =>
                        $item['ano'],

                    'total' =>
                        $item['total']

                ];
            }


            /*
            |--------------------------------------------------------------------------
            | LINHA
            |--------------------------------------------------------------------------
            */

            $linhaRf014 =
                collect(
                    $pontosRf014
                )
                    ->map(
                        function ($ponto) {

                            return
                                $ponto['x']
                                .
                                ','
                                .
                                $ponto['y'];

                        }
                    )
                    ->implode(
                        ' '
                    );


            /*
            |--------------------------------------------------------------------------
            | ÁREA
            |--------------------------------------------------------------------------
            */

            $primeiroX =
                $pontosRf014[0]['x']
                ??
                $left;


            $ultimoX =
                $pontosRf014[
                    count($pontosRf014)
                    -
                    1
                ]['x']
                ??
                (
                    $svgWidth
                    -
                    $right
                );


            $areaRf014 =
                $primeiroX
                .
                ','
                .
                $baseY
                .
                ' '
                .
                $linhaRf014
                .
                ' '
                .
                $ultimoX
                .
                ','
                .
                $baseY;


            /*
            |--------------------------------------------------------------------------
            | GRID
            |--------------------------------------------------------------------------
            */

            $gridRf014 = [];


            for ($i = 0; $i <= 4; $i++) {

                $percentual =
                    $i
                    /
                    4;


                $y =
                    $top
                    +
                    (
                        $chartHeight
                        *
                        $percentual
                    );


                $valor =
                    (int) round(
                        $maiorRf014
                        *
                        (
                            1
                            -
                            $percentual
                        )
                    );


                $gridRf014[] = [

                    'y' =>
                        round(
                            $y,
                            2
                        ),

                    'valor' =>
                        $valor

                ];
            }

        @endphp


        {{-- =========================================================
            RF014 - GRÁFICO
        ========================================================== --}}

        <section
            class="rf014-card"
            id="rf014-chart"
        >

            <header class="rf014-header">

                <div class="rf014-heading">

                    <div class="rf014-icon">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                            stroke-width="1.8"
                        >

                            <path
                                d="M3 18 8 13l4 3 8-9"
                            ></path>

                            <path
                                d="M15 7h5v5"
                            ></path>

                        </svg>

                    </div>


                    <div>

                        <span class="rf014-badge">
                            RF014
                        </span>

                        <h2>
                            Desempenho de vendas
                        </h2>

                        <p>
                            Quantidade de vendas realizadas nos últimos 12 meses
                        </p>

                    </div>

                </div>


                <div class="rf014-total">

                    <span>
                        Total no período
                    </span>

                    <div>

                        <strong>
                            {{ $totalRf014 }}
                        </strong>

                        <small>
                            {{ $totalRf014 === 1 ? 'venda' : 'vendas' }}
                        </small>

                    </div>

                </div>

            </header>


            <div class="rf014-summary">

                <article>

                    <span>
                        Melhor mês
                    </span>

                    <strong>
                        {{ $melhorRf014['label'] ?? '-' }}
                    </strong>

                    <small>
                        {{ $melhorRf014['total'] ?? 0 }}
                        {{ ($melhorRf014['total'] ?? 0) === 1 ? 'venda' : 'vendas' }}
                    </small>

                </article>


                <article>

                    <span>
                        Média mensal
                    </span>

                    <strong>
                        {{ number_format($mediaRf014, 1, ',', '.') }}
                    </strong>

                    <small>
                        vendas por mês
                    </small>

                </article>


                <article>

                    <span>
                        Período
                    </span>

                    <strong>
                        12
                    </strong>

                    <small>
                        meses
                    </small>

                </article>

            </div>


            <div class="rf014-scroll">

                <div class="rf014-chart">

                    <svg
                        viewBox="0 0 {{ $svgWidth }} {{ $svgHeight }}"
                        xmlns="http://www.w3.org/2000/svg"
                        class="rf014-svg"
                    >

                        <defs>

                            <linearGradient
                                id="rf014Area"
                                x1="0"
                                y1="0"
                                x2="0"
                                y2="1"
                            >

                                <stop
                                    offset="0%"
                                    stop-color="#7939b2"
                                    stop-opacity=".22"
                                ></stop>

                                <stop
                                    offset="100%"
                                    stop-color="#7939b2"
                                    stop-opacity=".01"
                                ></stop>

                            </linearGradient>

                        </defs>


                        @foreach($gridRf014 as $linhaGrid)

                            <line
                                x1="{{ $left }}"
                                y1="{{ $linhaGrid['y'] }}"
                                x2="{{ $svgWidth - $right }}"
                                y2="{{ $linhaGrid['y'] }}"
                                class="rf014-grid-line"
                            ></line>


                            <text
                                x="{{ $left - 12 }}"
                                y="{{ $linhaGrid['y'] + 4 }}"
                                text-anchor="end"
                                class="rf014-y-label"
                            >
                                {{ $linhaGrid['valor'] }}
                            </text>

                        @endforeach


                        <line
                            x1="{{ $left }}"
                            y1="{{ $baseY }}"
                            x2="{{ $svgWidth - $right }}"
                            y2="{{ $baseY }}"
                            class="rf014-axis"
                        ></line>


                        <polygon
                            points="{{ $areaRf014 }}"
                            fill="url(#rf014Area)"
                        ></polygon>


                        <polyline
                            points="{{ $linhaRf014 }}"
                            class="rf014-line"
                        ></polyline>


                        @foreach($pontosRf014 as $ponto)

                            <g class="rf014-point-group">

                                <circle
                                    cx="{{ $ponto['x'] }}"
                                    cy="{{ $ponto['y'] }}"
                                    r="12"
                                    class="rf014-hit"
                                ></circle>


                                <circle
                                    cx="{{ $ponto['x'] }}"
                                    cy="{{ $ponto['y'] }}"
                                    r="5"
                                    class="rf014-point"
                                >

                                    <title>
                                        {{ $ponto['mes'] }}
                                        de
                                        {{ $ponto['ano'] }}:
                                        {{ $ponto['total'] }}
                                        {{ $ponto['total'] === 1 ? 'venda' : 'vendas' }}
                                    </title>

                                </circle>


                                <text
                                    x="{{ $ponto['x'] }}"
                                    y="{{ $baseY + 28 }}"
                                    text-anchor="middle"
                                    class="rf014-x-label"
                                >
                                    {{ $ponto['label'] }}
                                </text>

                            </g>

                        @endforeach

                    </svg>

                </div>

            </div>


            <footer class="rf014-footer">

                <div>

                    <span class="rf014-legend"></span>

                    Vendas realizadas

                </div>

                <span>
                    Últimos 12 meses
                </span>

            </footer>

        </section>


        {{-- =========================================================
            CONTEÚDO ORIGINAL
        ========================================================== --}}

        @include('vendas._content')

    </main>


    {{-- =========================================================
        MOVER O RF014 PARA DEPOIS DOS CARDS
    ========================================================== --}}

    <script>

        (function () {

            function moverGraficoRf014() {

                const grafico =
                    document.getElementById(
                        'rf014-chart'
                    );


                const estatisticas =
                    document.querySelector(
                        '.sales-stats'
                    );


                if (
                    !grafico
                    ||
                    !estatisticas
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
                    moverGraficoRf014
                );

            } else {

                moverGraficoRf014();

            }

        })();

    </script>

</body>

</html>