<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Histórico de Compras - D-tech</title>

    <style>
        @page {
            margin: 26px 28px;
        }

        body {
            margin: 0;
            font-family: DejaVu Sans, sans-serif;
            color: #272336;
            font-size: 10px;
        }

        .header {
            padding-bottom: 16px;
            border-bottom: 2px solid #6420d4;
        }

        .brand {
            color: #6420d4;
            font-size: 24px;
            font-weight: 700;
        }

        .header h1 {
            margin: 5px 0 4px;
            font-size: 18px;
        }

        .header p {
            margin: 0;
            color: #6c6879;
        }

        .meta,
        .resumo,
        .compras {
            width: 100%;
        }

        .meta {
            margin-top: 15px;
        }

        .meta td {
            padding: 4px 0;
        }

        .meta-label {
            width: 130px;
            color: #757080;
        }

        .resumo {
            margin: 18px 0;
            border-collapse: collapse;
        }

        .resumo td {
            width: 33.33%;
            padding: 12px;
            border: 1px solid #e7e1f0;
        }

        .resumo span {
            display: block;
            margin-bottom: 4px;
            color: #756f80;
            font-size: 9px;
        }

        .resumo strong {
            color: #6420d4;
            font-size: 13px;
        }

        .compras {
            border-collapse: collapse;
        }

        .compras thead th {
            padding: 8px 6px;
            background: #6420d4;
            color: #fff;
            text-align: left;
            font-size: 8px;
        }

        .compras tbody td {
            padding: 8px 6px;
            border-bottom: 1px solid #e9e5ee;
            vertical-align: top;
        }

        .produto {
            font-weight: 700;
        }

        .muted {
            color: #777280;
            font-size: 8px;
        }

        .valor {
            white-space: nowrap;
            font-weight: 700;
        }

        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #e2dde8;
            color: #888391;
            text-align: center;
            font-size: 8px;
        }
    </style>
</head>

<body>

<div class="header">
    <div class="brand">D-tech</div>
    <h1>Relatório de Histórico de Compras</h1>
    <p>
        Documento gerado em {{ now()->format('d/m/Y H:i') }}
    </p>
</div>

<table class="meta">
    <tr>
        <td class="meta-label">Usuário comprador:</td>
        <td>
            <strong>{{ $usuario->nome }}</strong>
        </td>
    </tr>

    <tr>
        <td class="meta-label">Período:</td>
        <td>
            @if(
                !empty($filtros['data_inicio'])
                || !empty($filtros['data_fim'])
            )

                {{
                    !empty($filtros['data_inicio'])
                        ? \Carbon\Carbon::parse(
                            $filtros['data_inicio']
                        )->format('d/m/Y')
                        : 'Início'
                }}

                até

                {{
                    !empty($filtros['data_fim'])
                        ? \Carbon\Carbon::parse(
                            $filtros['data_fim']
                        )->format('d/m/Y')
                        : 'Hoje'
                }}

            @else
                Todo o histórico
            @endif
        </td>
    </tr>
</table>

<table class="resumo">
    <tr>
        <td>
            <span>Transações no relatório</span>
            <strong>{{ $compras->count() }}</strong>
        </td>

        <td>
            <span>Quantidade de produtos</span>
            <strong>{{ $quantidadeItens }}</strong>
        </td>

        <td>
            <span>Valor total</span>
            <strong>
                R$
                {{ number_format($totalPeriodo, 2, ',', '.') }}
            </strong>
        </td>
    </tr>
</table>

<table class="compras">

    <thead>
        <tr>
            <th>Data</th>
            <th>Produto</th>
            <th>Categoria</th>
            <th>Comprador</th>
            <th>Vendedor</th>
            <th>Qtd.</th>
            <th>Valor</th>
        </tr>
    </thead>

    <tbody>

        @forelse($compras as $compra)

            <tr>
                <td>
                    {{
                        \Carbon\Carbon::parse(
                            $compra->data_compra
                        )->format('d/m/Y H:i')
                    }}
                </td>

                <td>
                    <div class="produto">
                        {{ $compra->produto_nome }}
                    </div>

                    <div class="muted">
                        Pedido #{{ $compra->venda_id }}
                    </div>
                </td>

                <td>
                    {{ $compra->categoria_nome }}
                </td>

                <td>
                    {{ $compra->comprador_nome }}
                </td>

                <td>
                    {{ $compra->vendedor_nome }}
                </td>

                <td>
                    {{ $compra->quantidade }}
                </td>

                <td class="valor">
                    R$
                    {{
                        number_format(
                            $compra->subtotal,
                            2,
                            ',',
                            '.'
                        )
                    }}
                </td>
            </tr>

        @empty

            <tr>
                <td colspan="7" style="text-align:center;padding:25px;">
                    Nenhuma compra encontrada no período selecionado.
                </td>
            </tr>

        @endforelse
    </tbody>
</table>

<div class="footer">
    D-tech · Relatório de compras do usuário autenticado
</div>

</body>
</html>
