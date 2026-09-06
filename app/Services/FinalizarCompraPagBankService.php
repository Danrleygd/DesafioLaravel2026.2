<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class FinalizarCompraPagBankService
{
    /**
     * Finaliza uma compra somente a partir de um pedido
     * consultado diretamente na API oficial do PagBank.
     *
     * Retorna o ID da venda criada. Se a mesma cobrança
     * já tiver sido processada, retorna a venda existente.
     */
    public function finalizar(array $pedidoPagBank): int
    {
        $charge = collect(
            $pedidoPagBank['charges'] ?? []
        )->first(
            fn ($charge) =>
                ($charge['status'] ?? null) === 'PAID'
        );

        if (!$charge) {
            throw new RuntimeException(
                'O pagamento ainda não está com status PAID.'
            );
        }

        $chargeId =
            $charge['id'] ?? null;

        if (
            !$chargeId
            ||
            !str_starts_with(
                $chargeId,
                'CHAR_'
            )
        ) {
            throw new RuntimeException(
                'O PagBank não retornou um código de cobrança válido.'
            );
        }

        $referenceId =
            (string) (
                $pedidoPagBank['reference_id']
                ??
                ''
            );

        $compradorId =
            $this->extrairCompradorId(
                $referenceId
            );

        $itensPagBank =
            collect(
                $pedidoPagBank['items']
                ??
                []
            );

        if ($itensPagBank->isEmpty()) {
            throw new RuntimeException(
                'O pedido pago não possui produtos.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDA O VALOR PAGO
        |--------------------------------------------------------------------------
        */

        $totalItensCentavos =
            $itensPagBank->sum(
                function ($item) {

                    $quantidade =
                        (int) (
                            $item['quantity']
                            ??
                            0
                        );

                    $valorUnitario =
                        (int) (
                            $item['unit_amount']
                            ??
                            0
                        );

                    if (
                        $quantidade <= 0
                        ||
                        $valorUnitario < 0
                    ) {
                        throw new RuntimeException(
                            'O PagBank retornou um item inválido.'
                        );
                    }

                    return
                        $quantidade
                        *
                        $valorUnitario;
                }
            );

        $valorPagoCentavos =
            (int) (
                data_get(
                    $charge,
                    'amount.summary.paid'
                )
                ??
                data_get(
                    $charge,
                    'amount.value'
                )
                ??
                0
            );

        if (
            $valorPagoCentavos
            !==
            $totalItensCentavos
        ) {
            throw new RuntimeException(
                'O valor confirmado pelo PagBank não corresponde ao valor dos produtos.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | TRANSAÇÃO DO BANCO
        |--------------------------------------------------------------------------
        */

        try {
            return DB::transaction(
                function () use (
                    $charge,
                    $chargeId,
                    $compradorId,
                    $itensPagBank,
                    $totalItensCentavos
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | IDEMPOTÊNCIA
                    |--------------------------------------------------------------------------
                    */

                    $vendaExistente =
                        DB::table('Vendas')
                            ->where(
                                'codigo_transacao',
                                $chargeId
                            )
                            ->first();

                    if ($vendaExistente) {
                        return
                            (int)
                            $vendaExistente->id;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | COMPRADOR
                    |--------------------------------------------------------------------------
                    */

                    $comprador =
                        DB::table('Usuarios')
                            ->where(
                                'id',
                                $compradorId
                            )
                            ->lockForUpdate()
                            ->first();

                    if (!$comprador) {
                        throw new RuntimeException(
                            'O comprador associado ao pagamento não existe.'
                        );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | PREPARA PRODUTOS
                    |--------------------------------------------------------------------------
                    */

                    $produtosProcessados = [];
                    $creditoPorVendedor = [];

                    foreach (
                        $itensPagBank
                        as
                        $itemPagBank
                    ) {
                        $produtoId =
                            (int) (
                                $itemPagBank[
                                    'reference_id'
                                ]
                                ??
                                0
                            );

                        $quantidade =
                            (int) (
                                $itemPagBank[
                                    'quantity'
                                ]
                                ??
                                0
                            );

                        $valorCentavos =
                            (int) (
                                $itemPagBank[
                                    'unit_amount'
                                ]
                                ??
                                0
                            );

                        if (
                            $produtoId <= 0
                            ||
                            $quantidade <= 0
                        ) {
                            throw new RuntimeException(
                                'Um dos produtos pagos possui referência inválida.'
                            );
                        }

                        $produto =
                            DB::table('Produtos')
                                ->where(
                                    'id',
                                    $produtoId
                                )
                                ->lockForUpdate()
                                ->first();

                        if (!$produto) {
                            throw new RuntimeException(
                                'Um dos produtos pagos não existe mais no sistema.'
                            );
                        }

                        if (
                            (int)
                            $produto->quantidade
                            <
                            $quantidade
                        ) {
                            throw new RuntimeException(
                                'Não há estoque suficiente para finalizar um dos produtos pagos.'
                            );
                        }

                        $valorUnitario =
                            round(
                                $valorCentavos
                                /
                                100,
                                2
                            );

                        $subtotal =
                            round(
                                $valorUnitario
                                *
                                $quantidade,
                                2
                            );

                        $vendedorId =
                            (int)
                            $produto->UsuarioId;

                        $produtosProcessados[] = [
                            'ProdutoId' =>
                                $produtoId,

                            'VendedorId' =>
                                $vendedorId,

                            'quantidade' =>
                                $quantidade,

                            'ValorUnitario' =>
                                $valorUnitario,

                            'subtotal' =>
                                $subtotal,
                        ];

                        $creditoPorVendedor[
                            $vendedorId
                        ] =
                            (
                                $creditoPorVendedor[
                                    $vendedorId
                                ]
                                ??
                                0
                            )
                            +
                            $subtotal;
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | CRIA VENDA
                    |--------------------------------------------------------------------------
                    */

                    $dataCompra =
                        $this->dataPagamento(
                            $charge['paid_at']
                            ??
                            null
                        );

                    $vendaId =
                        DB::table('Vendas')
                            ->insertGetId([
                                'CompradorId' =>
                                    $compradorId,

                                'ValorTotal' =>
                                    round(
                                        $totalItensCentavos
                                        /
                                        100,
                                        2
                                    ),

                                'StatusPagamento' =>
                                    'pago',

                                'LocalPagamento' =>
                                    'pagseguro',

                                'codigo_transacao' =>
                                    $chargeId,

                                'data_compra' =>
                                    $dataCompra,
                            ]);

                    /*
                    |--------------------------------------------------------------------------
                    | ITENS + ESTOQUE
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $produtosProcessados
                        as
                        $produto
                    ) {
                        DB::table(
                            'ItensVendas'
                        )->insert([
                            'VendasId' =>
                                $vendaId,

                            'ProdutoId' =>
                                $produto[
                                    'ProdutoId'
                                ],

                            'VendedorId' =>
                                $produto[
                                    'VendedorId'
                                ],

                            'quantidade' =>
                                $produto[
                                    'quantidade'
                                ],

                            'ValorUnitario' =>
                                $produto[
                                    'ValorUnitario'
                                ],

                            'subtotal' =>
                                $produto[
                                    'subtotal'
                                ],
                        ]);

                        DB::table('Produtos')
                            ->where(
                                'id',
                                $produto[
                                    'ProdutoId'
                                ]
                            )
                            ->decrement(
                                'quantidade',
                                $produto[
                                    'quantidade'
                                ]
                            );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | SALDO DOS VENDEDORES
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $creditoPorVendedor
                        as
                        $vendedorId =>
                        $valor
                    ) {
                        DB::table('Usuarios')
                            ->where(
                                'id',
                                $vendedorId
                            )
                            ->increment(
                                'saldo',
                                round(
                                    $valor,
                                    2
                                )
                            );
                    }

                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE/REDUZ ITENS DO CARRINHO
                    |--------------------------------------------------------------------------
                    */

                    $carrinho =
                        DB::table(
                            'Carrinhos'
                        )
                            ->where(
                                'UsuarioId',
                                $compradorId
                            )
                            ->lockForUpdate()
                            ->first();

                    if ($carrinho) {
                        foreach (
                            $produtosProcessados
                            as
                            $produto
                        ) {
                            $restante =
                                $produto[
                                    'quantidade'
                                ];

                            $itensCarrinho =
                                DB::table(
                                    'ItensCarrinho'
                                )
                                    ->where(
                                        'CarrinhoId',
                                        $carrinho->id
                                    )
                                    ->where(
                                        'ProdutoId',
                                        $produto[
                                            'ProdutoId'
                                        ]
                                    )
                                    ->lockForUpdate()
                                    ->orderBy('id')
                                    ->get();

                            foreach (
                                $itensCarrinho
                                as
                                $itemCarrinho
                            ) {
                                if ($restante <= 0) {
                                    break;
                                }

                                $quantidadeCarrinho =
                                    (int)
                                    $itemCarrinho
                                        ->quantidade;

                                if (
                                    $quantidadeCarrinho
                                    >
                                    $restante
                                ) {
                                    DB::table(
                                        'ItensCarrinho'
                                    )
                                        ->where(
                                            'id',
                                            $itemCarrinho->id
                                        )
                                        ->update([
                                            'quantidade' =>
                                                $quantidadeCarrinho
                                                -
                                                $restante,
                                        ]);

                                    $restante = 0;

                                    break;
                                }

                                DB::table(
                                    'ItensCarrinho'
                                )
                                    ->where(
                                        'id',
                                        $itemCarrinho->id
                                    )
                                    ->delete();

                                $restante -=
                                    $quantidadeCarrinho;
                            }
                        }
                    }

                    return (int) $vendaId;
                },
                5
            );

        } catch (QueryException $exception) {

            /*
            |--------------------------------------------------------------------------
            | PROTEÇÃO EXTRA CONTRA WEBHOOK DUPLICADO
            |--------------------------------------------------------------------------
            |
            | Com o índice UNIQUE em codigo_transacao, duas requisições
            | simultâneas não conseguem duplicar a mesma compra.
            |
            */

            $vendaExistente =
                DB::table('Vendas')
                    ->where(
                        'codigo_transacao',
                        $chargeId
                    )
                    ->first();

            if ($vendaExistente) {
                return
                    (int)
                    $vendaExistente->id;
            }

            throw $exception;
        }
    }


    private function extrairCompradorId(
        string $referenceId
    ): int {
        /*
        |--------------------------------------------------------------------------
        | SUPORTA AS DUAS REFERÊNCIAS USADAS DURANTE O DESENVOLVIMENTO
        |--------------------------------------------------------------------------
        |
        | DTECH-15-20260906...
        | DTECH-U15-20260906...
        |
        */

        if (
            !preg_match(
                '/^DTECH-(?:U)?(\d+)-/',
                $referenceId,
                $matches
            )
        ) {
            throw new RuntimeException(
                'A referência do pagamento não pertence à D-tech.'
            );
        }

        $compradorId =
            (int)
            $matches[1];

        if ($compradorId <= 0) {
            throw new RuntimeException(
                'A referência do comprador é inválida.'
            );
        }

        return $compradorId;
    }


    private function dataPagamento(
        ?string $paidAt
    ): string {
        if (!$paidAt) {
            return now()
                ->format(
                    'Y-m-d H:i:s'
                );
        }

        try {
            return Carbon::parse(
                $paidAt
            )
                ->setTimezone(
                    config(
                        'app.timezone'
                    )
                )
                ->format(
                    'Y-m-d H:i:s'
                );

        } catch (\Throwable) {
            return now()
                ->format(
                    'Y-m-d H:i:s'
                );
        }
    }
}
