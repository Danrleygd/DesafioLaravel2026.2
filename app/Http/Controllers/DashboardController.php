<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | USUÁRIO LOGADO
        |--------------------------------------------------------------------------
        */

        $user = Auth::user();

        $userId = $user->id;


        /*
        |--------------------------------------------------------------------------
        | SE FOR ADMINISTRADOR
        |--------------------------------------------------------------------------
        |
        | Caso sua dashboard administrativa tenha esta rota,
        | o administrador será enviado para ela.
        |
        */

        if (
            $user->tipo === 'administrador'
            &&
            Route::has('admin.dashboard')
        ) {
            return redirect()
                ->route('admin.dashboard');
        }


        /*
        |--------------------------------------------------------------------------
        | SALDO
        |--------------------------------------------------------------------------
        */

        $saldo = (float) (
            $user->saldo ?? 0
        );


        /*
        |--------------------------------------------------------------------------
        | PRODUTOS DO USUÁRIO
        |--------------------------------------------------------------------------
        */

        $totalProdutos = Produto::where(
            'UsuarioId',
            $userId
        )->count();


        $produtosDisponiveis = Produto::where(
            'UsuarioId',
            $userId
        )
            ->where(
                'quantidade',
                '>',
                0
            )
            ->count();


        $produtosSemEstoque = Produto::where(
            'UsuarioId',
            $userId
        )
            ->where(
                'quantidade',
                '<=',
                0
            )
            ->count();


        /*
        |--------------------------------------------------------------------------
        | COMPRAS
        |--------------------------------------------------------------------------
        */

        $totalCompras = DB::table(
            'Vendas'
        )
            ->where(
                'CompradorId',
                $userId
            )
            ->count();


        $comprasPagas = DB::table(
            'Vendas'
        )
            ->where(
                'CompradorId',
                $userId
            )
            ->where(
                'StatusPagamento',
                'pago'
            )
            ->count();


        $valorTotalCompras = (float) DB::table(
            'Vendas'
        )
            ->where(
                'CompradorId',
                $userId
            )
            ->where(
                'StatusPagamento',
                'pago'
            )
            ->sum(
                'ValorTotal'
            );


        /*
        |--------------------------------------------------------------------------
        | VENDAS
        |--------------------------------------------------------------------------
        |
        | Uma venda pode possuir vários itens.
        | Por isso usamos distinct em VendasId.
        |
        */

        $totalVendas = DB::table(
            'ItensVendas'
        )
            ->where(
                'VendedorId',
                $userId
            )
            ->distinct()
            ->count(
                'VendasId'
            );


        /*
        |--------------------------------------------------------------------------
        | RECEITA DE VENDAS PAGAS
        |--------------------------------------------------------------------------
        */

        $receitaTotal = (float) DB::table(
            'ItensVendas'
        )
            ->join(
                'Vendas',
                'ItensVendas.VendasId',
                '=',
                'Vendas.id'
            )
            ->where(
                'ItensVendas.VendedorId',
                $userId
            )
            ->where(
                'Vendas.StatusPagamento',
                'pago'
            )
            ->sum(
                'ItensVendas.subtotal'
            );


        /*
        |--------------------------------------------------------------------------
        | PRODUTOS VENDIDOS
        |--------------------------------------------------------------------------
        */

        $quantidadeProdutosVendidos = (int) DB::table(
            'ItensVendas'
        )
            ->join(
                'Vendas',
                'ItensVendas.VendasId',
                '=',
                'Vendas.id'
            )
            ->where(
                'ItensVendas.VendedorId',
                $userId
            )
            ->where(
                'Vendas.StatusPagamento',
                'pago'
            )
            ->sum(
                'ItensVendas.quantidade'
            );


        /*
        |--------------------------------------------------------------------------
        | CARRINHO
        |--------------------------------------------------------------------------
        */

        $itensCarrinho = (int) DB::table(
            'Carrinhos'
        )
            ->leftJoin(
                'ItensCarrinho',
                'Carrinhos.id',
                '=',
                'ItensCarrinho.CarrinhoId'
            )
            ->where(
                'Carrinhos.UsuarioId',
                $userId
            )
            ->sum(
                'ItensCarrinho.quantidade'
            );


        /*
        |--------------------------------------------------------------------------
        | ÚLTIMOS PRODUTOS ANUNCIADOS
        |--------------------------------------------------------------------------
        */

        $ultimosProdutos = Produto::with([
            'categoria',
            'fotos',
        ])
            ->where(
                'UsuarioId',
                $userId
            )
            ->orderByDesc(
                'created_at'
            )
            ->take(4)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ÚLTIMAS COMPRAS
        |--------------------------------------------------------------------------
        */

        $ultimasCompras = DB::table(
            'Vendas'
        )
            ->where(
                'CompradorId',
                $userId
            )
            ->orderByDesc(
                'created_at'
            )
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | ÚLTIMAS VENDAS
        |--------------------------------------------------------------------------
        */

        $ultimasVendas = DB::table(
            'ItensVendas'
        )
            ->join(
                'Vendas',
                'ItensVendas.VendasId',
                '=',
                'Vendas.id'
            )
            ->join(
                'Produtos',
                'ItensVendas.ProdutoId',
                '=',
                'Produtos.id'
            )
            ->where(
                'ItensVendas.VendedorId',
                $userId
            )
            ->select([
                'ItensVendas.id',
                'ItensVendas.VendasId',
                'ItensVendas.quantidade',
                'ItensVendas.ValorUnitario',
                'ItensVendas.subtotal',
                'Produtos.id as produto_id',
                'Produtos.nome as produto_nome',
                'Produtos.foto as produto_foto',
                'Vendas.StatusPagamento',
                'Vendas.created_at as data_compra',
            ])
            ->orderByDesc(
                'Vendas.created_at'
            )
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | PRODUTO MAIS VENDIDO
        |--------------------------------------------------------------------------
        */

        $produtoMaisVendido = DB::table(
            'ItensVendas'
        )
            ->join(
                'Vendas',
                'ItensVendas.VendasId',
                '=',
                'Vendas.id'
            )
            ->join(
                'Produtos',
                'ItensVendas.ProdutoId',
                '=',
                'Produtos.id'
            )
            ->where(
                'ItensVendas.VendedorId',
                $userId
            )
            ->where(
                'Vendas.StatusPagamento',
                'pago'
            )
            ->select(
                'Produtos.id',
                'Produtos.nome',
                'Produtos.foto',
                DB::raw(
                    'SUM(ItensVendas.quantidade) as quantidade_vendida'
                ),
                DB::raw(
                    'SUM(ItensVendas.subtotal) as valor_vendido'
                )
            )
            ->groupBy(
                'Produtos.id',
                'Produtos.nome',
                'Produtos.foto'
            )
            ->orderByDesc(
                'quantidade_vendida'
            )
            ->first();


        /*
        |--------------------------------------------------------------------------
        | VENDAS DOS ÚLTIMOS 6 MESES
        |--------------------------------------------------------------------------
        */

        $inicioGrafico = now()
            ->copy()
            ->subMonths(5)
            ->startOfMonth();


        $dadosVendasBanco = DB::table(
            'ItensVendas'
        )
            ->join(
                'Vendas',
                'ItensVendas.VendasId',
                '=',
                'Vendas.id'
            )
            ->where(
                'ItensVendas.VendedorId',
                $userId
            )
            ->where(
                'Vendas.StatusPagamento',
                'pago'
            )
            ->where(
                'Vendas.created_at',
                '>=',
                $inicioGrafico
            )
            ->selectRaw(
                "
                DATE_FORMAT(
                    Vendas.created_at,
                    '%Y-%m'
                ) AS mes,
                SUM(
                    ItensVendas.subtotal
                ) AS total
                "
            )
            ->groupBy(
                'mes'
            )
            ->pluck(
                'total',
                'mes'
            );


        /*
        |--------------------------------------------------------------------------
        | MONTA OS 6 MESES
        |--------------------------------------------------------------------------
        */

        $mesesGrafico = [];

        $valoresGrafico = [];


        for (
            $i = 5;
            $i >= 0;
            $i--
        ) {
            $data = now()
                ->copy()
                ->subMonths($i);


            $chave = $data->format(
                'Y-m'
            );


            $mesesGrafico[] =
                $this->nomeMes(
                    (int) $data->format('m')
                );


            $valoresGrafico[] =
                (float) (
                    $dadosVendasBanco[
                        $chave
                    ]
                    ?? 0
                );
        }


        /*
        |--------------------------------------------------------------------------
        | MAIOR VALOR DO GRÁFICO
        |--------------------------------------------------------------------------
        */

        $maiorValorGrafico =
            max(
                array_merge(
                    $valoresGrafico,
                    [1]
                )
            );


        /*
        |--------------------------------------------------------------------------
        | RETORNO
        |--------------------------------------------------------------------------
        */

        return view(
            'dashboard',
            compact(
                'user',
                'saldo',
                'totalProdutos',
                'produtosDisponiveis',
                'produtosSemEstoque',
                'totalCompras',
                'comprasPagas',
                'valorTotalCompras',
                'totalVendas',
                'receitaTotal',
                'quantidadeProdutosVendidos',
                'itensCarrinho',
                'ultimosProdutos',
                'ultimasCompras',
                'ultimasVendas',
                'produtoMaisVendido',
                'mesesGrafico',
                'valoresGrafico',
                'maiorValorGrafico'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NOME DO MÊS
    |--------------------------------------------------------------------------
    */

    private function nomeMes(
        int $mes
    ): string {
        return match ($mes) {
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
            12 => 'Dez',
            default => '',
        };
    }
}