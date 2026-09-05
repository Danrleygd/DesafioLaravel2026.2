<!DOCTYPE html>
<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>
        Relatório de Vendas
    </title>


    <style>

        * {
            box-sizing: border-box;
        }


        body {
            margin: 0;

            padding: 25px;

            font-family:
                DejaVu Sans,
                sans-serif;

            color:
                #2d2035;

            font-size:
                10px;
        }


        .header {
            width: 100%;

            margin-bottom:
                24px;

            padding-bottom:
                15px;

            border-bottom:
                2px solid
                #6d269e;
        }


        .header h1 {
            margin:
                0
                0
                5px;

            color:
                #5e1f89;

            font-size:
                22px;
        }


        .header p {
            margin:
                3px
                0;

            color:
                #6e6573;
        }


        .stats {
            width:
                100%;

            margin-bottom:
                20px;

            border-collapse:
                separate;

            border-spacing:
                8px;
        }


        .stats td {
            width:
                33.333%;

            padding:
                12px;

            background:
                #f3ebf7;

            border-radius:
                5px;
        }


        .stats span {
            display:
                block;

            margin-bottom:
                4px;

            color:
                #7f7186;
        }


        .stats strong {
            color:
                #522173;

            font-size:
                14px;
        }


        table.sales {
            width:
                100%;

            border-collapse:
                collapse;
        }


        table.sales th {
            padding:
                8px;

            background:
                #68279a;

            color:
                #ffffff;

            text-align:
                left;

            font-size:
                8px;
        }


        table.sales td {
            padding:
                8px;

            border-bottom:
                1px solid
                #e5dfe8;

            font-size:
                8px;

            vertical-align:
                top;
        }


        table.sales tr:nth-child(even) td {
            background:
                #faf8fb;
        }


        .money {
            font-weight:
                bold;

            white-space:
                nowrap;
        }


        .footer {
            margin-top:
                20px;

            padding-top:
                10px;

            border-top:
                1px solid
                #ded6e2;

            color:
                #8b818f;

            font-size:
                7px;
        }

    </style>

</head>


<body>


    <div class="header">

        <h1>
            D-tech - Relatório de Vendas
        </h1>


        <p>

            Período:

            {{ \Carbon\Carbon::parse(
                $dataInicio
            )->format('d/m/Y') }}

            até

            {{ \Carbon\Carbon::parse(
                $dataFim
            )->format('d/m/Y') }}

        </p>


        <p>

            Emitido em:

            {{ now()->format(
                'd/m/Y H:i'
            ) }}

        </p>


        @if(!$isAdmin)

            <p>
                Vendedor:
                {{ $usuario->nome }}
            </p>

        @else

            <p>
                Relatório administrativo - todas as vendas
            </p>

        @endif

    </div>


    <table class="stats">

        <tr>

            <td>

                <span>
                    Registros
                </span>

                <strong>
                    {{ $vendas->count() }}
                </strong>

            </td>


            <td>

                <span>
                    Itens vendidos
                </span>

                <strong>
                    {{ $quantidadeTotal }}
                </strong>

            </td>


            <td>

                <span>
                    Valor total
                </span>

                <strong>

                    R$
                    {{ number_format(
                        $valorTotal,
                        2,
                        ',',
                        '.'
                    ) }}

                </strong>

            </td>

        </tr>

    </table>


    <table class="sales">

        <thead>

            <tr>

                <th>
                    Data
                </th>

                <th>
                    Produto
                </th>

                <th>
                    Categoria
                </th>

                <th>
                    Qtd.
                </th>

                <th>
                    Valor
                </th>

                <th>
                    Comprador
                </th>

                <th>
                    Vendedor
                </th>

                <th>
                    Status
                </th>

            </tr>

        </thead>


        <tbody>

            @forelse($vendas as $venda)

                <tr>

                    <td>

                        {{ \Carbon\Carbon::parse(
                            $venda->data_compra
                        )->format(
                            'd/m/Y H:i'
                        ) }}

                    </td>


                    <td>
                        {{ $venda->produto_nome }}
                    </td>


                    <td>
                        {{ $venda->categoria_nome }}
                    </td>


                    <td>
                        {{ $venda->quantidade }}
                    </td>


                    <td class="money">

                        R$
                        {{ number_format(
                            $venda->subtotal,
                            2,
                            ',',
                            '.'
                        ) }}

                    </td>


                    <td>
                        {{ $venda->comprador_nome }}
                    </td>


                    <td>
                        {{ $venda->vendedor_nome }}
                    </td>


                    <td>
                        {{ ucfirst(
                            $venda->status_pagamento
                        ) }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td
                        colspan="8"
                        style="
                            text-align: center;
                            padding: 30px;
                        "
                    >
                        Nenhuma venda encontrada neste período.
                    </td>

                </tr>

            @endforelse

        </tbody>

    </table>


    <div class="footer">

        Documento gerado automaticamente pelo sistema D-tech.

    </div>

</body>

</html>