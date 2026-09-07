<?php

namespace Database\Factories;

use App\Models\Produto;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use RuntimeException;


class VendaFactory extends Factory
{
    protected $model = Venda::class;

    public function definition(): array
    {
        $compradorId = User::query()
            ->where('tipo', 'usuario')
            ->inRandomOrder()
            ->value('id');

        if (!$compradorId) {
            throw new RuntimeException(
                'É necessário existir pelo menos um usuário comum para criar vendas.'
            );
        }

        return [
            'CompradorId' => $compradorId,

            'ValorTotal' => 0,

            'StatusPagamento' => 'pago',

            'LocalPagamento' => fake()->randomElement([
                'mercadopago',
                'pagseguro',
            ]),

            'codigo_transacao' =>
                'FACT-' . Str::uuid(),

            'created_at' => now(),

            'updated_at' => now(),
        ];
    }

    //quando a venda foi realizada 

    public function noMes(
        int $mesesAtras
    ): static {
        return $this->state(
            function () use ($mesesAtras) {

                $inicio = now()
                    ->copy()
                    ->startOfMonth()
                    ->subMonths($mesesAtras);

                $fim = $inicio
                    ->copy()
                    ->endOfMonth();

                if ($fim->greaterThan(now())) {
                    $fim = now();
                }

                $dataVenda = fake()
                    ->dateTimeBetween(
                        $inicio,
                        $fim
                    );

                return [
                    'created_at' => $dataVenda,
                    'updated_at' => $dataVenda,
                ];
            }
        );
    }


    public function paraProduto(
        Produto $produto
    ): static {
        return $this
            ->state(
                function () use ($produto) {

                    $vendedorId =
                        (int) $produto->UsuarioId;

                    /*
                     * Comprador precisa ser diferente
                     * do vendedor.
                     */

                    $compradorId = User::query()
                        ->where(
                            'tipo',
                            'usuario'
                        )
                        ->where(
                            'id',
                            '!=',
                            $vendedorId
                        )
                        ->inRandomOrder()
                        ->value('id');

                    if (!$compradorId) {
                        throw new RuntimeException(
                            "Não existe outro usuário para comprar o produto {$produto->id}."
                        );
                    }

                    return [
                        'CompradorId' =>
                            $compradorId,
                    ];
                }
            )
            ->afterCreating(
                function (
                    Venda $venda
                ) use (
                    $produto
                ) {

                    $vendedorId =
                        (int) $produto->UsuarioId;

                    $quantidade =
                        fake()
                            ->numberBetween(
                                1,
                                3
                            );

                    $valorUnitario =
                        (float) $produto->preco;

                    $subtotal =
                        round(
                            $valorUnitario
                            *
                            $quantidade,
                            2
                        );

                    // Cria o item de venda para o produto

                    DB::table(
                        'ItensVendas'
                    )->insert([
                        'VendasId' =>
                            $venda->id,

                        'ProdutoId' =>
                            $produto->id,

                        'VendedorId' =>
                            $vendedorId,

                        'quantidade' =>
                            $quantidade,

                        'ValorUnitario' =>
                            $valorUnitario,

                        'subtotal' =>
                            $subtotal,

                        'created_at' =>
                            $venda->created_at,

                        'updated_at' =>
                            $venda->created_at,
                    ]);

                    //atualiza o valor total da venda

                    DB::table(
                        'Vendas'
                    )
                        ->where(
                            'id',
                            $venda->id
                        )
                        ->update([
                            'ValorTotal' =>
                                $subtotal,
                        ]);
                }
            );
    }
}