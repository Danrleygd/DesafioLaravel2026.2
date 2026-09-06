<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico de Compras - D-tech</title>

    @vite([
        'resources/css/app.css',
        'resources/css/userSidebar.css',
        'resources/css/compras.css',
        'resources/js/userSidebar.js'
    ])
</head>

<body class="user-area">

<x-user-sidebar />

<main class="compras-page">

    <section class="compras-header">
        <div>
            <span class="compras-eyebrow">MINHA CONTA</span>
            <h1>Histórico de Compras</h1>
            <p>Acompanhe todos os produtos que você já comprou na D-tech.</p>
        </div>

        <a href="{{ route('landing') }}" class="btn-voltar-loja">
            <i class="bi bi-arrow-left"></i>
            Voltar para a loja
        </a>
    </section>

    @if($errors->any())
        <div class="compras-alert">
            <i class="bi bi-exclamation-circle"></i>
            <div>
                @foreach($errors->all() as $erro)
                    <div>{{ $erro }}</div>
                @endforeach
            </div>
        </div>
    @endif

    <section class="compras-resumo">
        <article class="resumo-card">
            <div class="resumo-card-icon">
                <i class="bi bi-bag-check"></i>
            </div>
            <div>
                <span>Compras realizadas</span>
                <strong>{{ $totalCompras }}</strong>
            </div>
        </article>

        <article class="resumo-card">
            <div class="resumo-card-icon">
                <i class="bi bi-cash-stack"></i>
            </div>
            <div>
                <span>Total gasto</span>
                <strong>
                    R$ {{ number_format($totalGasto, 2, ',', '.') }}
                </strong>
            </div>
        </article>

        <article class="resumo-card">
            <div class="resumo-card-icon">
                <i class="bi bi-clock-history"></i>
            </div>
            <div>
                <span>Última compra</span>
                <strong class="ultima-compra">
                    @if($ultimoPedido)
                        {{ \Carbon\Carbon::parse($ultimoPedido)->format('d/m/Y') }}
                    @else
                        —
                    @endif
                </strong>
            </div>
        </article>
    </section>

    <section class="compras-filtros-card">

        <div class="filtros-titulo">
            <div class="filtros-icon">
                <i class="bi bi-calendar-range"></i>
            </div>
            <div>
                <h2>Filtrar por período</h2>
                <p>Escolha o período que deseja visualizar ou exportar.</p>
            </div>
        </div>

        <form method="GET"
              action="{{ route('compras.index') }}"
              class="compras-filtros">

            <div class="filtro-field">
                <label for="data_inicio">Data inicial</label>
                <input
                    type="date"
                    id="data_inicio"
                    name="data_inicio"
                    value="{{ $filtros['data_inicio'] ?? '' }}"
                >
            </div>

            <div class="filtro-field">
                <label for="data_fim">Data final</label>
                <input
                    type="date"
                    id="data_fim"
                    name="data_fim"
                    value="{{ $filtros['data_fim'] ?? '' }}"
                >
            </div>

            <div class="filtros-actions">

                <button type="submit" class="btn-filtrar">
                    <i class="bi bi-funnel"></i>
                    Filtrar
                </button>

                <a href="{{ route('compras.index') }}"
                   class="btn-limpar">
                    Limpar
                </a>

                <a
                    href="{{
                        route(
                            'compras.pdf',
                            array_filter([
                                'data_inicio' =>
                                    $filtros['data_inicio'] ?? null,
                                'data_fim' =>
                                    $filtros['data_fim'] ?? null,
                            ])
                        )
                    }}"
                    class="btn-pdf"
                >
                    <i class="bi bi-file-earmark-pdf"></i>
                    Gerar PDF
                </a>
            </div>
        </form>
    </section>

    <section class="compras-listagem-card">

        <div class="listagem-header">
            <div>
                <h2>Suas compras</h2>
                <p>Produtos das compras concluídas no período selecionado.</p>
            </div>

            <span class="listagem-count">
                {{ $compras->total() }}
                {{ $compras->total() === 1 ? 'item' : 'itens' }}
            </span>
        </div>

        @if($compras->isEmpty())

            <div class="compras-empty">
                <div class="compras-empty-icon">
                    <i class="bi bi-bag-x"></i>
                </div>

                <h3>Nenhuma compra encontrada</h3>
                <p>Não existem compras concluídas no período informado.</p>

                <a href="{{ route('landing') }}">
                    Explorar produtos
                </a>
            </div>

        @else

            <div class="compras-lista">

                @foreach($compras as $compra)

                    @php
                        $foto = $compra->produto_foto;

                        if (!$foto) {
                            $imagem = asset('images/sem-imagem.png');
                        } elseif (
                            str_starts_with($foto, 'http://')
                            || str_starts_with($foto, 'https://')
                        ) {
                            $imagem = $foto;
                        } elseif (str_starts_with($foto, '/')) {
                            $imagem = asset(ltrim($foto, '/'));
                        } else {
                            $imagem = asset(
                                'storage/' . ltrim($foto, '/')
                            );
                        }
                    @endphp

                    <article class="compra-item">

                        <a
                            href="{{ route('produto.show', $compra->produto_id) }}"
                            class="compra-foto"
                        >
                            <img
                                src="{{ $imagem }}"
                                alt="{{ $compra->produto_nome }}"
                            >
                        </a>

                        <div class="compra-conteudo">

                            <div class="compra-topo">
                                <div>
                                    <span class="compra-pedido">
                                        Pedido #{{ $compra->venda_id }}
                                    </span>

                                    <h3>
                                        {{ $compra->produto_nome }}
                                    </h3>
                                </div>

                                <span class="compra-status">
                                    <i class="bi bi-check-circle-fill"></i>
                                    Pago
                                </span>
                            </div>

                            <div class="compra-dados">

                                <div>
                                    <span>Data da compra</span>
                                    <strong>
                                        {{
                                            \Carbon\Carbon::parse(
                                                $compra->data_compra
                                            )->format('d/m/Y H:i')
                                        }}
                                    </strong>
                                </div>

                                <div>
                                    <span>Categoria</span>
                                    <strong>
                                        {{ $compra->categoria_nome }}
                                    </strong>
                                </div>

                                <div>
                                    <span>Quantidade</span>
                                    <strong>
                                        {{ $compra->quantidade }}
                                    </strong>
                                </div>

                                <div>
                                    <span>Vendedor</span>
                                    <strong>
                                        {{ $compra->vendedor_nome }}
                                    </strong>
                                </div>
                            </div>
                        </div>

                        <div class="compra-valor">
                            <span>Valor</span>

                            <strong>
                                R$
                                {{
                                    number_format(
                                        $compra->subtotal,
                                        2,
                                        ',',
                                        '.'
                                    )
                                }}
                            </strong>

                            <small>
                                {{ $compra->quantidade }}
                                x
                                R$
                                {{
                                    number_format(
                                        $compra->ValorUnitario,
                                        2,
                                        ',',
                                        '.'
                                    )
                                }}
                            </small>
                        </div>
                    </article>

                @endforeach
            </div>

            <div class="compras-pagination">
                {{ $compras->links() }}
            </div>

        @endif
    </section>
</main>

</body>
</html>
