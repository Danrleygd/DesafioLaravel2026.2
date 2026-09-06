<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Compra Confirmada - D-tech
    </title>

    @vite([
        'resources/css/app.css',
        'resources/css/navLanding.css',
        'resources/css/checkout.css',
        'resources/css/checkoutPagamento.css'
    ])

</head>

<body class="checkout-body">

    <x-nav-landing />


    <main class="checkout-confirmacao-page">

        <section class="checkout-card checkout-confirmacao-card">

            <div class="checkout-confirmacao-icon">

                <i class="bi bi-check-lg"></i>

            </div>


            <span class="checkout-confirmacao-label">
                PAGAMENTO APROVADO
            </span>


            <h1>
                Compra realizada com sucesso!
            </h1>


            <p class="checkout-confirmacao-texto">
                O pagamento foi confirmado pelo PagBank
                e sua compra já foi registrada na D-tech.
            </p>


            <div class="checkout-confirmacao-resumo">

                <div>

                    <span>
                        Pedido
                    </span>

                    <strong>
                        #{{ $venda->id }}
                    </strong>

                </div>


                <div>

                    <span>
                        Data
                    </span>

                    <strong>
                        {{
                            \Carbon\Carbon::parse(
                                $venda->data_compra
                            )->format('d/m/Y H:i')
                        }}
                    </strong>

                </div>


                <div>

                    <span>
                        Total
                    </span>

                    <strong class="confirmacao-total">
                        R$
                        {{
                            number_format(
                                $venda->ValorTotal,
                                2,
                                ',',
                                '.'
                            )
                        }}
                    </strong>

                </div>

            </div>


            <div class="checkout-confirmacao-itens">

                <h2>
                    Produtos
                </h2>


                @foreach($itens as $item)

                    <div class="checkout-confirmacao-item">

                        <div>

                            <strong>
                                {{ $item->nome }}
                            </strong>

                            <span>
                                {{ $item->quantidade }}
                                x
                                R$
                                {{
                                    number_format(
                                        $item->ValorUnitario,
                                        2,
                                        ',',
                                        '.'
                                    )
                                }}
                            </span>

                        </div>


                        <strong>
                            R$
                            {{
                                number_format(
                                    $item->subtotal,
                                    2,
                                    ',',
                                    '.'
                                )
                            }}
                        </strong>

                    </div>

                @endforeach

            </div>


            <div class="checkout-confirmacao-status">

                <div>

                    <i class="bi bi-database-check"></i>

                    <span>
                        Venda registrada
                    </span>

                </div>


                <div>

                    <i class="bi bi-box-seam"></i>

                    <span>
                        Estoque atualizado
                    </span>

                </div>


                <div>

                    <i class="bi bi-wallet2"></i>

                    <span>
                        Saldo do vendedor atualizado
                    </span>

                </div>

            </div>


            <div class="checkout-confirmacao-actions">

                <a
                    href="{{ route('landing') }}"
                    class="btn-return-primary"
                >
                    Continuar comprando
                </a>


                <a
                    href="{{ route('dashboard') }}"
                    class="btn-return-secondary"
                >
                    Ir para o dashboard
                </a>

            </div>

        </section>

    </main>

</body>

</html>
