<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>
        Status do Pagamento - D-tech
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


    <main class="checkout-return-page">

        <section class="checkout-card checkout-return-card">

            <div
                class="
                    checkout-return-icon
                    {{
                        in_array(
                            $status,
                            ['DECLINED', 'CANCELED'],
                            true
                        )
                            ? 'error'
                            : ''
                    }}
                "
            >

                @if(
                    in_array(
                        $status,
                        ['DECLINED', 'CANCELED'],
                        true
                    )
                )

                    <i class="bi bi-x-lg"></i>

                @else

                    <i class="bi bi-hourglass-split"></i>

                @endif

            </div>


            <h1>
                {{ $statusTitulo }}
            </h1>


            <p>
                {{ $statusMensagem }}
            </p>


            @if($referenceId)

                <div class="checkout-return-reference">

                    <span>
                        Referência
                    </span>

                    <strong>
                        {{ $referenceId }}
                    </strong>

                </div>

            @endif


            <div class="checkout-return-actions">

                @if(
                    !in_array(
                        $status,
                        ['DECLINED', 'CANCELED'],
                        true
                    )
                )

                    <a
                        href="{{ route('checkout.retorno.verificar') }}"
                        class="btn-return-primary"
                    >
                        <i class="bi bi-arrow-clockwise"></i>

                        Verificar pagamento
                    </a>

                @else

                    <a
                        href="{{ route('checkout.pagamento') }}"
                        class="btn-return-primary"
                    >
                        Tentar novamente
                    </a>

                @endif


                <a
                    href="{{ route('carrinho.index') }}"
                    class="btn-return-secondary"
                >
                    Voltar para o carrinho
                </a>

            </div>


            <div class="checkout-return-note">

                <i class="bi bi-shield-check"></i>

                Estoque, saldo do vendedor e histórico só são
                atualizados depois que o PagBank confirma o status
                <strong>PAID</strong>.

            </div>

        </section>

    </main>

</body>

</html>
