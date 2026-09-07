<?php

namespace Database\Seeders;

use App\Models\Produto;
use App\Models\User;
use App\Models\Venda;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VendaSeeder extends Seeder
{
    public function run(): void
    {

        $vendasFactory = DB::table(
            'Vendas'
        )
            ->where(
                'codigo_transacao',
                'LIKE',
                'FACT-%'
            )
            ->pluck('id');

        if ($vendasFactory->isNotEmpty()) {

            DB::table(
                'ItensVendas'
            )
                ->whereIn(
                    'VendasId',
                    $vendasFactory
                )
                ->delete();

            DB::table(
                'Vendas'
            )
                ->whereIn(
                    'id',
                    $vendasFactory
                )
                ->delete();
        }


        //usuario

        $usuarios = User::query()
            ->where(
                'tipo',
                'usuario'
            )
            ->get();

        if ($usuarios->count() < 2) {

            $this->command?->error(
                'É necessário existir pelo menos dois usuários comuns.'
            );

            return;
        }


        //caixa 2 existente --> vendedores com produtos cadastrados

        $idsUsuarios = $usuarios
            ->pluck('id');

        $produtos = Produto::query()
            ->whereIn(
                'UsuarioId',
                $idsUsuarios
            )
            ->get();

        if ($produtos->isEmpty()) {

            $this->command?->error(
                'Nenhum usuário comum possui produtos cadastrados.'
            );

            return;
        }


        //agrupa venda

        $produtosPorVendedor =
            $produtos
                ->groupBy(
                    'UsuarioId'
                );


       //venda por mes

        $quantidadesPorMes = [
            0 => 5,
            1 => 3,
            2 => 7,
            3 => 4,
            4 => 6,
            5 => 2,
            6 => 5,
            7 => 3,
            8 => 6,
            9 => 2,
            10 => 4,
            11 => 3,
        ];


        //caixa 2 para vendedor

        foreach (
            $produtosPorVendedor as
            $vendedorId =>
            $produtosDoVendedor
        ) {

            $usuario = User::find(
                $vendedorId
            );

            if (!$usuario) {
                continue;
            }

            $totalCriado =
                0;

            foreach (
                $quantidadesPorMes as
                $mesesAtras =>
                $quantidadeVendas
            ) {

                for (
                    $i = 0;
                    $i < $quantidadeVendas;
                    $i++
                ) {

                    $produto =
                        $produtosDoVendedor
                            ->random();

                    Venda::factory()
                        ->noMes(
                            $mesesAtras
                        )
                        ->paraProduto(
                            $produto
                        )
                        ->create();

                    $totalCriado++;
                }
            }

            $this->command?->info(
                "Vendedor {$usuario->nome} (ID {$usuario->id}): {$totalCriado} vendas criadas."
            );
        }


        

        $totalVendas = DB::table(
            'Vendas'
        )
            ->where(
                'codigo_transacao',
                'LIKE',
                'FACT-%'
            )
            ->count();

        $totalItens = DB::table(
            'ItensVendas as iv'
        )
            ->join(
                'Vendas as v',
                'v.id',
                '=',
                'iv.VendasId'
            )
            ->where(
                'v.codigo_transacao',
                'LIKE',
                'FACT-%'
            )
            ->count();

        $this->command?->newLine();

        $this->command?->info(
            "Total de vendas criadas: {$totalVendas}"
        );

        $this->command?->info(
            "Total de itens de vendas criados: {$totalItens}"
        );

        $this->command?->newLine();

        $this->command?->info(
            'VendaSeeder finalizado.'
        );
    }
}