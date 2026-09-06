<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComprasController extends Controller
{
    public function index(Request $request)
    {
        $filtros = $this->validarPeriodo($request);

        $compras = $this->queryCompras($filtros)
            ->orderByDesc('v.data_compra')
            ->orderByDesc('iv.id')
            ->paginate(10)
            ->withQueryString();

        $resumoQuery = DB::table('Vendas as v')
            ->where('v.CompradorId', Auth::id())
            ->where('v.StatusPagamento', 'pago');

        if (!empty($filtros['data_inicio'])) {
            $resumoQuery->whereDate(
                'v.data_compra',
                '>=',
                $filtros['data_inicio']
            );
        }

        if (!empty($filtros['data_fim'])) {
            $resumoQuery->whereDate(
                'v.data_compra',
                '<=',
                $filtros['data_fim']
            );
        }

        $totalCompras = (clone $resumoQuery)->count();
        $totalGasto = (clone $resumoQuery)->sum('v.ValorTotal');

        $ultimoPedido = (clone $resumoQuery)
            ->orderByDesc('v.data_compra')
            ->value('v.data_compra');

        return view('compras.index', compact(
            'compras',
            'filtros',
            'totalCompras',
            'totalGasto',
            'ultimoPedido'
        ));
    }

    public function pdf(Request $request)
    {
        $filtros = $this->validarPeriodo($request);

        $compras = $this->queryCompras($filtros)
            ->orderByDesc('v.data_compra')
            ->orderByDesc('iv.id')
            ->get();

        $totalPeriodo = $compras->sum('subtotal');
        $quantidadeItens = $compras->sum('quantidade');
        $usuario = Auth::user();

        $pdf = Pdf::loadView('compras.pdf', compact(
            'compras',
            'filtros',
            'totalPeriodo',
            'quantidadeItens',
            'usuario'
        ))->setPaper('a4', 'portrait');

        return $pdf->download(
            'historico-compras-' .
            now()->format('Y-m-d-His') .
            '.pdf'
        );
    }

    private function queryCompras(array $filtros)
    {
        $query = DB::table('ItensVendas as iv')
            ->join(
                'Vendas as v',
                'v.id',
                '=',
                'iv.VendasId'
            )
            ->join(
                'Produtos as p',
                'p.id',
                '=',
                'iv.ProdutoId'
            )
            ->join(
                'Categorias as c',
                'c.id',
                '=',
                'p.categoria_id'
            )
            ->join(
                'Usuarios as comprador',
                'comprador.id',
                '=',
                'v.CompradorId'
            )
            ->join(
                'Usuarios as vendedor',
                'vendedor.id',
                '=',
                'iv.VendedorId'
            )
            ->where(
                'v.CompradorId',
                Auth::id()
            )
            ->where(
                'v.StatusPagamento',
                'pago'
            )
            ->select([
                'iv.id as item_venda_id',
                'iv.VendasId as venda_id',
                'iv.ProdutoId as produto_id',
                'iv.VendedorId as vendedor_id',
                'iv.quantidade',
                'iv.ValorUnitario',
                'iv.subtotal',
                'v.ValorTotal as valor_total_venda',
                'v.StatusPagamento',
                'v.LocalPagamento',
                'v.codigo_transacao',
                'v.data_compra',
                'p.nome as produto_nome',
                'p.foto as produto_foto',
                'c.nome as categoria_nome',
                'comprador.nome as comprador_nome',
                'vendedor.nome as vendedor_nome',
            ]);

        if (!empty($filtros['data_inicio'])) {
            $query->whereDate(
                'v.data_compra',
                '>=',
                $filtros['data_inicio']
            );
        }

        if (!empty($filtros['data_fim'])) {
            $query->whereDate(
                'v.data_compra',
                '<=',
                $filtros['data_fim']
            );
        }

        return $query;
    }

    private function validarPeriodo(Request $request): array
    {
        return $request->validate(
            [
                'data_inicio' => [
                    'nullable',
                    'date',
                ],
                'data_fim' => [
                    'nullable',
                    'date',
                    'after_or_equal:data_inicio',
                ],
            ],
            [
                'data_inicio.date' =>
                    'A data inicial é inválida.',
                'data_fim.date' =>
                    'A data final é inválida.',
                'data_fim.after_or_equal' =>
                    'A data final deve ser igual ou posterior à data inicial.',
            ]
        );
    }
}
