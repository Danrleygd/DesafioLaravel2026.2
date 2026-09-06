<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use App\Models\Endereco;
use App\Services\FinalizarCompraPagBankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class CheckoutController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SELEÇÃO DOS ITENS
    |--------------------------------------------------------------------------
    */

    public function selecionarItens(
        Request $request
    ) {
        $dados =
            $request->validate([
                'itens' => [
                    'required',
                    'array',
                    'min:1',
                ],

                'itens.*' => [
                    'required',
                    'integer',
                    'distinct',
                ],
            ]);

        $user = Auth::user();

        $carrinho =
            Carrinho::where(
                'UsuarioId',
                $user->id
            )->first();

        if (!$carrinho) {
            return response()->json([
                'message' =>
                    'Seu carrinho está vazio.',
            ], 422);
        }

        $idsSolicitados =
            collect(
                $dados['itens']
            )
                ->map(
                    fn ($id) =>
                        (int) $id
                )
                ->unique()
                ->values();

        $idsValidos =
            $carrinho
                ->itens()
                ->whereIn(
                    'id',
                    $idsSolicitados
                )
                ->pluck('id')
                ->map(
                    fn ($id) =>
                        (int) $id
                )
                ->values();

        if (
            $idsValidos->count()
            !==
            $idsSolicitados->count()
        ) {
            return response()->json([
                'message' =>
                    'Um ou mais itens selecionados não pertencem ao seu carrinho.',
            ], 422);
        }

        session([
            'checkout_itens' =>
                $idsValidos->all(),
        ]);

        session()->forget([
            'checkout_endereco_id',
            'checkout_pagbank_id',
            'checkout_pagbank_reference',
            'checkout_pagbank_pay_url',
            'checkout_total',
            'checkout_payment_method',
        ]);

        return response()->json([
            'success' => true,

            'redirect' =>
                route(
                    'checkout.endereco'
                ),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | ENDEREÇO
    |--------------------------------------------------------------------------
    */

    public function endereco()
    {
        try {
            [
                'itens' => $itens,
                'total' => $total,
            ] =
                $this
                    ->carregarItensCheckout();

        } catch (
            RuntimeException
            $exception
        ) {
            return redirect()
                ->route(
                    'carrinho.index'
                )
                ->with(
                    'error',
                    $exception
                        ->getMessage()
                );
        }

        $user = Auth::user();

        $enderecos =
            $user
                ->enderecos()
                ->get();

        $enderecoSelecionadoId =
            session(
                'checkout_endereco_id'
            );

        if (
            !$enderecoSelecionadoId
            ||
            !$enderecos->contains(
                'id',
                $enderecoSelecionadoId
            )
        ) {
            $enderecoSelecionadoId =
                $enderecos
                    ->first()
                    ?->id;
        }

        return view(
            'checkout.endereco',
            compact(
                'user',
                'itens',
                'total',
                'enderecos',
                'enderecoSelecionadoId'
            )
        );
    }


    public function selecionarEndereco(
        Request $request
    ) {
        $request->validate([
            'endereco_id' => [
                'required',
                'integer',
            ],
        ]);

        try {
            $this
                ->carregarItensCheckout();

        } catch (
            RuntimeException
            $exception
        ) {
            return redirect()
                ->route(
                    'carrinho.index'
                )
                ->with(
                    'error',
                    $exception
                        ->getMessage()
                );
        }

        $endereco =
            Auth::user()
                ->enderecos()
                ->where(
                    'Enderecos.id',
                    $request
                        ->endereco_id
                )
                ->first();

        if (!$endereco) {
            return back()
                ->with(
                    'error',
                    'O endereço selecionado não pertence ao usuário.'
                );
        }

        session([
            'checkout_endereco_id'
                =>
                $endereco->id,
        ]);

        return redirect()
            ->route(
                'checkout.pagamento'
            );
    }


    public function storeEndereco(
        Request $request
    ) {
        $dados =
            $request->validate([
                'cep' => [
                    'required',
                    'string',
                    'max:9',
                ],

                'logradouro' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'numero' => [
                    'required',
                    'string',
                    'max:20',
                ],

                'complemento' => [
                    'nullable',
                    'string',
                    'max:255',
                ],

                'bairro' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'cidade' => [
                    'required',
                    'string',
                    'max:255',
                ],

                'estado' => [
                    'required',
                    'string',
                    'size:2',
                ],
            ]);

        try {
            $this
                ->carregarItensCheckout();

        } catch (
            RuntimeException
            $exception
        ) {
            return redirect()
                ->route(
                    'carrinho.index'
                )
                ->with(
                    'error',
                    $exception
                        ->getMessage()
                );
        }

        $user =
            Auth::user();

        $dados['cep'] =
            preg_replace(
                '/\D/',
                '',
                $dados['cep']
            );

        $dados['estado'] =
            strtoupper(
                $dados['estado']
            );

        DB::transaction(
            function () use (
                $user,
                $dados
            ) {
                $endereco =
                    Endereco::create(
                        $dados
                    );

                $user
                    ->enderecos()
                    ->syncWithoutDetaching([
                        $endereco->id,
                    ]);

                session([
                    'checkout_endereco_id'
                        =>
                        $endereco->id,
                ]);
            }
        );

        return redirect()
            ->route(
                'checkout.pagamento'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | PAGAMENTO
    |--------------------------------------------------------------------------
    */

    public function pagamento()
    {
        try {
            [
                'itens' => $itens,
                'total' => $total,
            ] =
                $this
                    ->carregarItensCheckout();

        } catch (
            RuntimeException
            $exception
        ) {
            return redirect()
                ->route(
                    'carrinho.index'
                )
                ->with(
                    'error',
                    $exception
                        ->getMessage()
                );
        }

        $endereco =
            $this
                ->carregarEnderecoSelecionado();

        if (!$endereco) {
            return redirect()
                ->route(
                    'checkout.endereco'
                )
                ->with(
                    'error',
                    'Selecione um endereço de entrega para continuar.'
                );
        }

        return view(
            'checkout.pagamento',
            compact(
                'itens',
                'total',
                'endereco'
            )
        );
    }


    public function criarPagamentoPagBank(
        Request $request
    ) {
        $dados =
            $request->validate([
                'metodo_pagamento' => [
                    'required',
                    'string',
                    'in:PIX,CREDIT_CARD,BOLETO',
                ],
            ], [
                'metodo_pagamento.required' =>
                    'Selecione uma forma de pagamento.',

                'metodo_pagamento.in' =>
                    'A forma de pagamento selecionada é inválida.',
            ]);

        $metodoPagamento =
            $dados[
                'metodo_pagamento'
            ];

        /*
        |--------------------------------------------------------------------------
        | MANTÉM A OPÇÃO SELECIONADA
        |--------------------------------------------------------------------------
        |
        | Se a API recusar a criação do checkout e o usuário voltar para a tela,
        | a opção escolhida continuará marcada.
        |
        */

        session([
            'checkout_payment_method'
                =>
                $metodoPagamento,
        ]);

        try {
            [
                'itens' => $itens,
                'total' => $total,
            ] =
                $this
                    ->carregarItensCheckout();

        } catch (
            RuntimeException
            $exception
        ) {
            return redirect()
                ->route(
                    'carrinho.index'
                )
                ->with(
                    'error',
                    $exception
                        ->getMessage()
                );
        }

        $endereco =
            $this
                ->carregarEnderecoSelecionado();

        if (!$endereco) {
            return redirect()
                ->route(
                    'checkout.endereco'
                )
                ->with(
                    'error',
                    'Selecione um endereço de entrega para continuar.'
                );
        }

        $token =
            config(
                'pagbank.token'
            );

        if (!$token) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'O token do PagBank ainda não foi configurado no arquivo .env.'
                );
        }

        $baseUrl =
            $this
                ->pagBankBaseUrl();

        $referenceId =
            'DTECH-U'
            .
            Auth::id()
            .
            '-'
            .
            now()->format(
                'YmdHis'
            )
            .
            '-'
            .
            Str::upper(
                Str::random(6)
            );

        $returnUrl =
            config(
                'pagbank.return_url'
            )
            ?:
            route(
                'checkout.retorno'
            );

        $payload = [
            'reference_id' =>
                $referenceId,

            'customer_modifiable' =>
                true,

            'items' =>
                $itens
                    ->map(
                        function (
                            $item
                        ) {
                            return [
                                'reference_id' =>
                                    (string)
                                    $item
                                        ->produto
                                        ->id,

                                'name' =>
                                    Str::limit(
                                        $item
                                            ->produto
                                            ->nome,
                                        100,
                                        ''
                                    ),

                                'quantity' =>
                                    (int)
                                    $item
                                        ->quantidade,

                                'unit_amount' =>
                                    (int)
                                    round(
                                        (float)
                                        $item
                                            ->produto
                                            ->preco
                                        *
                                        100
                                    ),
                            ];
                        }
                    )
                    ->values()
                    ->all(),

            'shipping' => [
                'type' =>
                    'FREE',

                'address' => [
                    'country' =>
                        'BRA',

                    'region_code' =>
                        strtoupper(
                            $endereco
                                ->estado
                        ),

                    'city' =>
                        Str::limit(
                            (string)
                            $endereco
                                ->cidade,
                            90,
                            ''
                        ),

                    'postal_code' =>
                        preg_replace(
                            '/\D/',
                            '',
                            $endereco
                                ->cep
                        ),

                    'street' =>
                        Str::limit(
                            (string)
                            $endereco
                                ->logradouro,
                            160,
                            ''
                        ),

                    'number' =>
                        Str::limit(
                            (string)
                            $endereco
                                ->numero,
                            20,
                            ''
                        ),

                    'locality' =>
                        Str::limit(
                            (string)
                            $endereco
                                ->bairro,
                            60,
                            ''
                        ),

                ],

                'address_modifiable' =>
                    false,
            ],

            /*
            |--------------------------------------------------------------------------
            | SOMENTE O MÉTODO ESCOLHIDO
            |--------------------------------------------------------------------------
            |
            | Isso faz o Checkout hospedado do PagBank abrir oferecendo apenas
            | Pix, Cartão ou Boleto, conforme a escolha feita na D-tech.
            |
            */

            'payment_methods' => [
                [
                    'type' =>
                        $metodoPagamento,
                ],
            ],

            'return_url' =>
                $returnUrl,

            'redirect_url' =>
                $returnUrl,

            'redirect_waiting_time' =>
                5,
        ];


        /*
        |--------------------------------------------------------------------------
        | CAMPOS OPCIONAIS DO ENDEREÇO
        |--------------------------------------------------------------------------
        |
        | O PagBank valida complemento como string de 1 a 40 caracteres.
        | Por isso, quando estiver vazio, o campo não deve ser enviado como null.
        |
        */

        if (
            filled(
                $endereco
                    ->complemento
            )
        ) {
            $payload[
                'shipping'
            ][
                'address'
            ][
                'complement'
            ] =
                Str::limit(
                    (string)
                    $endereco
                        ->complemento,
                    40,
                    ''
                );
        }


        /*
        |--------------------------------------------------------------------------
        | URLS LOCAIS
        |--------------------------------------------------------------------------
        |
        | Em localhost a API pode rejeitar URLs de retorno dependendo da
        | validação do ambiente. Para o sandbox local, removemos as URLs
        | de retorno quando o host for localhost/127.0.0.1.
        |
        | Com ngrok ou domínio HTTPS público, elas continuam sendo enviadas.
        |
        */

        $hostRetorno =
            parse_url(
                $returnUrl,
                PHP_URL_HOST
            );

        $urlLocal =
            in_array(
                $hostRetorno,
                [
                    'localhost',
                    '127.0.0.1',
                ],
                true
            );

        if ($urlLocal) {
            unset(
                $payload[
                    'return_url'
                ],
                $payload[
                    'redirect_url'
                ],
                $payload[
                    'redirect_waiting_time'
                ]
            );
        }


        $notificationUrl =
            config(
                'pagbank.notification_url'
            );

        if ($notificationUrl) {
            $payload[
                'notification_urls'
            ] = [
                $notificationUrl,
            ];

            $payload[
                'payment_notification_urls'
            ] = [
                $notificationUrl,
            ];
        }

        try {
            $response =
                Http::withToken(
                    $token
                )
                    ->acceptJson()
                    ->asJson()
                    ->retry(
                        2,
                        500,
                        throw: false
                    )
                    ->timeout(30)
                    ->post(
                        $baseUrl
                        .
                        '/checkouts',
                        $payload
                    );

        } catch (
            Throwable
            $exception
        ) {
            report($exception);

            return back()
                ->withInput()
                ->with(
                    'error',
                    'Não foi possível conectar ao PagBank. Tente novamente.'
                );
        }

        if (
            !$response
                ->successful()
        ) {
            report(
                new RuntimeException(
                    'Erro PagBank: '
                    .
                    $response
                        ->body()
                )
            );

            $jsonErro =
                $response
                    ->json();

            $errosPagBank =
                collect(
                    data_get(
                        $jsonErro,
                        'error_messages',
                        []
                    )
                );

            $mensagem =
                $errosPagBank
                    ->map(
                        function (
                            $erro
                        ) {
                            $descricao =
                                $erro[
                                    'description'
                                ]
                                ??
                                'Erro na requisição.';

                            $campo =
                                $erro[
                                    'parameter_name'
                                ]
                                ??
                                null;

                            return $campo
                                ?
                                $descricao
                                .
                                ' Campo: '
                                .
                                $campo
                                :
                                $descricao;
                        }
                    )
                    ->filter()
                    ->implode(' | ');

            if (!$mensagem) {
                $mensagem =
                    data_get(
                        $jsonErro,
                        'message'
                    )
                    ??
                    'O PagBank recusou a criação do checkout.';
            }

            return back()
                ->withInput()
                ->with(
                    'error',
                    $mensagem
                );
        }

        $dadosPagBank =
            $response
                ->json();

        $payLink =
            collect(
                data_get(
                    $dadosPagBank,
                    'links',
                    []
                )
            )
                ->first(
                    fn ($link) =>
                        (
                            $link['rel']
                            ??
                            null
                        )
                        ===
                        'PAY'
                );

        $payUrl =
            $payLink[
                'href'
            ]
            ??
            null;

        if (!$payUrl) {
            return back()
                ->withInput()
                ->with(
                    'error',
                    'O PagBank criou o checkout, mas não retornou o link de pagamento.'
                );
        }

        session([
            'checkout_pagbank_id' =>
                data_get(
                    $dadosPagBank,
                    'id'
                ),

            'checkout_pagbank_reference'
                =>
                $referenceId,

            'checkout_pagbank_pay_url'
                =>
                $payUrl,

            'checkout_total' =>
                $total,

            'checkout_payment_method'
                =>
                $metodoPagamento,
        ]);

        return redirect()
            ->away(
                $payUrl
            );
    }


    /*
    |--------------------------------------------------------------------------
    | RETORNO / SINCRONIZAÇÃO
    |--------------------------------------------------------------------------
    */

    public function retorno(
        FinalizarCompraPagBankService
        $finalizarCompra
    ) {
        return
            $this
                ->sincronizarRetorno(
                    $finalizarCompra
                );
    }


    public function verificarPagamento(
        FinalizarCompraPagBankService
        $finalizarCompra
    ) {
        return
            $this
                ->sincronizarRetorno(
                    $finalizarCompra
                );
    }


    private function sincronizarRetorno(
        FinalizarCompraPagBankService
        $finalizarCompra
    ) {
        $checkoutId =
            session(
                'checkout_pagbank_id'
            );

        $referenceId =
            session(
                'checkout_pagbank_reference'
            );

        $status =
            'PROCESSING';

        $statusTitulo =
            'Pagamento em processamento';

        $statusMensagem =
            'Estamos aguardando a confirmação do PagBank.';

        if (!$checkoutId) {
            return view(
                'checkout.retorno',
                compact(
                    'checkoutId',
                    'referenceId',
                    'status',
                    'statusTitulo',
                    'statusMensagem'
                )
            );
        }

        try {
            $checkout =
                $this
                    ->consultarCheckoutPagBank(
                        $checkoutId
                    );

            $orderId =
                $this->localizarId(
                    $checkout,
                    'ORDE_'
                );

            /*
            |--------------------------------------------------------------------------
            | FALLBACK: LOCALIZA COBRANÇA E DEPOIS O PEDIDO
            |--------------------------------------------------------------------------
            */

            if (!$orderId) {
                $chargeId =
                    $this->localizarId(
                        $checkout,
                        'CHAR_'
                    );

                if ($chargeId) {
                    $orderId =
                        $this
                            ->localizarPedidoPorCharge(
                                $chargeId
                            );
                }
            }

            if ($orderId) {
                $pedido =
                    $this
                        ->consultarPedidoPagBank(
                            $orderId
                        );

                $chargeStatus =
                    collect(
                        $pedido['charges']
                        ??
                        []
                    )
                        ->pluck('status')
                        ->first();

                $status =
                    $chargeStatus
                    ??
                    'PROCESSING';

                if (
                    $chargeStatus
                    ===
                    'PAID'
                ) {
                    $vendaId =
                        $finalizarCompra
                            ->finalizar(
                                $pedido
                            );

                    return redirect()
                        ->route(
                            'checkout.confirmacao',
                            $vendaId
                        );
                }

                if (
                    in_array(
                        $chargeStatus,
                        [
                            'WAITING',
                            'IN_ANALYSIS',
                            'AUTHORIZED',
                        ],
                        true
                    )
                ) {
                    $statusTitulo =
                        'Pagamento aguardando confirmação';

                    $statusMensagem =
                        'O PagBank ainda está processando o pagamento. Você pode verificar novamente em alguns instantes.';
                }

                if (
                    in_array(
                        $chargeStatus,
                        [
                            'DECLINED',
                            'CANCELED',
                        ],
                        true
                    )
                ) {
                    $statusTitulo =
                        'Pagamento não concluído';

                    $statusMensagem =
                        'O pagamento foi recusado ou cancelado. Seus produtos continuam no carrinho.';
                }
            }

        } catch (
            Throwable
            $exception
        ) {
            report($exception);

            $statusTitulo =
                'Aguardando confirmação';

            $statusMensagem =
                'Não foi possível consultar o PagBank neste momento. Nenhuma cobrança será registrada na D-tech sem confirmação.';
        }

        return view(
            'checkout.retorno',
            compact(
                'checkoutId',
                'referenceId',
                'status',
                'statusTitulo',
                'statusMensagem'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CONFIRMAÇÃO
    |--------------------------------------------------------------------------
    */

    public function confirmacao(
        int $venda
    ) {
        $vendaDados =
            DB::table(
                'Vendas'
            )
                ->where(
                    'id',
                    $venda
                )
                ->where(
                    'CompradorId',
                    Auth::id()
                )
                ->first();

        abort_unless(
            $vendaDados,
            404
        );

        $itens =
            DB::table(
                'ItensVendas as iv'
            )
                ->join(
                    'Produtos as p',
                    'p.id',
                    '=',
                    'iv.ProdutoId'
                )
                ->where(
                    'iv.VendasId',
                    $vendaDados->id
                )
                ->select([
                    'iv.*',
                    'p.nome',
                    'p.foto',
                ])
                ->get();

        session()->forget([
            'checkout_itens',
            'checkout_endereco_id',
            'checkout_pagbank_id',
            'checkout_pagbank_reference',
            'checkout_pagbank_pay_url',
            'checkout_total',
            'checkout_payment_method',
        ]);

        return view(
            'checkout.confirmacao',
            [
                'venda' =>
                    $vendaDados,

                'itens' =>
                    $itens,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | HELPERS DO CHECKOUT
    |--------------------------------------------------------------------------
    */

    private function carregarItensCheckout(): array
    {
        $user =
            Auth::user();

        $carrinho =
            Carrinho::where(
                'UsuarioId',
                $user->id
            )->first();

        if (!$carrinho) {
            throw new RuntimeException(
                'Seu carrinho está vazio.'
            );
        }

        $idsSelecionados =
            collect(
                session(
                    'checkout_itens',
                    []
                )
            )
                ->map(
                    fn ($id) =>
                        (int) $id
                )
                ->unique()
                ->values();

        if (
            $idsSelecionados
                ->isEmpty()
        ) {
            throw new RuntimeException(
                'Selecione pelo menos um produto para continuar.'
            );
        }

        $itens =
            $carrinho
                ->itens()
                ->whereIn(
                    'id',
                    $idsSelecionados
                )
                ->with([
                    'produto.categoria',
                    'produto.vendedor',
                    'produto.fotos',
                ])
                ->get();

        if (
            $itens->count()
            !==
            $idsSelecionados->count()
        ) {
            session()->forget(
                'checkout_itens'
            );

            throw new RuntimeException(
                'Os produtos selecionados foram alterados. Selecione novamente os itens do carrinho.'
            );
        }

        foreach (
            $itens
            as
            $item
        ) {
            if (!$item->produto) {
                throw new RuntimeException(
                    'Um dos produtos selecionados não está mais disponível.'
                );
            }

            if (
                (int)
                $item->quantidade
                >
                (int)
                $item
                    ->produto
                    ->quantidade
            ) {
                throw new RuntimeException(
                    'A quantidade de um dos produtos selecionados ultrapassa o estoque disponível.'
                );
            }

            if (
                (int)
                $item
                    ->produto
                    ->quantidade
                <=
                0
            ) {
                throw new RuntimeException(
                    'Um dos produtos selecionados está sem estoque.'
                );
            }
        }

        $total =
            $itens->sum(
                function (
                    $item
                ) {
                    return
                        (float)
                        $item
                            ->produto
                            ->preco
                        *
                        (int)
                        $item
                            ->quantidade;
                }
            );

        return [
            'itens' => $itens,
            'total' => $total,
        ];
    }


    private function carregarEnderecoSelecionado()
    {
        $enderecoId =
            session(
                'checkout_endereco_id'
            );

        if (!$enderecoId) {
            return null;
        }

        return
            Auth::user()
                ->enderecos()
                ->where(
                    'Enderecos.id',
                    $enderecoId
                )
                ->first();
    }


    private function consultarCheckoutPagBank(
        string $checkoutId
    ): array {
        $response =
            $this
                ->pagBankClient()
                ->get(
                    $this
                        ->pagBankBaseUrl()
                    .
                    '/checkouts/'
                    .
                    $checkoutId
                );

        if (
            !$response
                ->successful()
        ) {
            throw new RuntimeException(
                'Não foi possível consultar o checkout no PagBank.'
            );
        }

        return
            $response->json();
    }


    private function consultarPedidoPagBank(
        string $orderId
    ): array {
        $response =
            $this
                ->pagBankClient()
                ->get(
                    $this
                        ->pagBankBaseUrl()
                    .
                    '/orders/'
                    .
                    $orderId
                );

        if (
            !$response
                ->successful()
        ) {
            throw new RuntimeException(
                'Não foi possível consultar o pedido no PagBank.'
            );
        }

        return
            $response->json();
    }


    private function localizarPedidoPorCharge(
        string $chargeId
    ): ?string {
        $response =
            $this
                ->pagBankClient()
                ->get(
                    $this
                        ->pagBankBaseUrl()
                    .
                    '/orders',
                    [
                        'charge_id' =>
                            $chargeId,
                    ]
                );

        if (
            !$response
                ->successful()
        ) {
            return null;
        }

        return
            $this->localizarId(
                $response->json(),
                'ORDE_'
            );
    }


    private function pagBankClient()
    {
        $token =
            config(
                'pagbank.token'
            );

        if (!$token) {
            throw new RuntimeException(
                'Token PagBank não configurado.'
            );
        }

        return
            Http::withToken(
                $token
            )
                ->acceptJson()
                ->retry(
                    2,
                    500,
                    throw: false
                )
                ->timeout(20);
    }


    private function pagBankBaseUrl(): string
    {
        return
            config(
                'pagbank.environment'
            )
            ===
            'production'
                ?
                config(
                    'pagbank.production_url'
                )
                :
                config(
                    'pagbank.sandbox_url'
                );
    }


    private function localizarId(
        mixed $valor,
        string $prefixo
    ): ?string {
        if (
            is_string($valor)
            &&
            str_starts_with(
                $valor,
                $prefixo
            )
        ) {
            return $valor;
        }

        if (!is_array($valor)) {
            return null;
        }

        foreach (
            $valor
            as
            $item
        ) {
            $encontrado =
                $this->localizarId(
                    $item,
                    $prefixo
                );

            if ($encontrado) {
                return $encontrado;
            }
        }

        return null;
    }
}
