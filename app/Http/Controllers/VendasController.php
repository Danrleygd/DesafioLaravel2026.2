<?php

namespace App\Http\Controllers;

use App\Exports\VendasExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class VendasController extends Controller
{
    //vendas usuario

    public function index(Request $request)
    {
        if (
            Auth::user()->tipo === 'administrador'
        ) {
            return redirect()
                ->route('admin.vendas.index');
        }

        return $this->carregarPagina(
            $request,
            false
        );
    }


    //vendas admin

    public function adminIndex(Request $request)
    {
        $this->garantirAdministrador();

        return $this->carregarPagina(
            $request,
            true
        );
    }


    //vendas usuario e admin

    private function carregarPagina(
        Request $request,
        bool $isAdmin
    ) {
        

        $query =
            $this->queryBase(
                $isAdmin
            );


    

        $this->aplicarFiltros(
            $query,
            $request,
            $isAdmin
        );


    //estatisticas

        $totalVendas =
            (clone $query)
                ->distinct()
                ->count('v.id');


        $itensVendidos =
            (int) (
                (clone $query)
                    ->sum(
                        'iv.quantidade'
                    )
            );


        $valorTotalVendido =
            (float) (
                (clone $query)
                    ->sum(
                        'iv.subtotal'
                    )
            );


        $totalCompradores =
            (clone $query)
                ->distinct()
                ->count(
                    'v.CompradorId'
                );


        //listagem

        $vendas =
            (clone $query)
                ->select([
                    'iv.id as item_venda_id',
                    'iv.VendasId as venda_id',
                    'iv.ProdutoId as produto_id',

                    'p.nome as produto_nome',
                    'p.foto as produto_foto',

                    'c.id as categoria_id',
                    'c.nome as categoria_nome',

                    'iv.quantidade',
                    'iv.ValorUnitario as valor_unitario',
                    'iv.subtotal',

                    'v.created_at as data_compra',
                    'v.StatusPagamento as status_pagamento',
                    'v.LocalPagamento as local_pagamento',
                    'v.codigo_transacao',

                    'comprador.id as comprador_id',
                    'comprador.nome as comprador_nome',
                    'comprador.email as comprador_email',

                    'vendedor.id as vendedor_id',
                    'vendedor.nome as vendedor_nome',
                    'vendedor.email as vendedor_email',
                ])
                ->orderByDesc(
                    'v.created_at'
                )
                ->paginate(10)
                ->withQueryString();


        // categorias
        $categorias =
            DB::table('Categorias')
                ->orderBy('nome')
                ->get();


       //chamando a view correta dependendo se é admin ou usuário

        $view =
            $isAdmin
                ? 'admin.vendas.index'
                : 'vendas.index';


        return view(
            $view,
            compact(
                'vendas',
                'categorias',
                'totalVendas',
                'itensVendidos',
                'valorTotalVendido',
                'totalCompradores',
                'isAdmin'
            )
        );
    }




    private function queryBase(
        bool $isAdmin
    ): Builder {
        $query =
            DB::table(
                'ItensVendas as iv'
            )
                ->join(
                    'Vendas as v',
                    'iv.VendasId',
                    '=',
                    'v.id'
                )

                ->join(
                    'Produtos as p',
                    'iv.ProdutoId',
                    '=',
                    'p.id'
                )

                ->join(
                    'Categorias as c',
                    'p.categoria_id',
                    '=',
                    'c.id'
                )

                ->join(
                    'Usuarios as comprador',
                    'v.CompradorId',
                    '=',
                    'comprador.id'
                )

                ->join(
                    'Usuarios as vendedor',
                    'iv.VendedorId',
                    '=',
                    'vendedor.id'
                );


        /*
         * Usuário comum vê somente as vendas
         * em que ele é o vendedor.
         */

        if (!$isAdmin) {
            $query->where(
                'iv.VendedorId',
                Auth::id()
            );
        }


        return $query;
    }


    //filtros

    private function aplicarFiltros(
        Builder $query,
        Request $request,
        bool $isAdmin
    ): void {
        /*
        |--------------------------------------------------------------------------
        | PESQUISA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('busca')) {
            $busca =
                trim(
                    $request->busca
                );


            $query->where(
                function ($query) use (
                    $busca,
                    $isAdmin
                ) {
                    $query
                        ->where(
                            'p.nome',
                            'LIKE',
                            '%' . $busca . '%'
                        )

                        ->orWhere(
                            'comprador.nome',
                            'LIKE',
                            '%' . $busca . '%'
                        )

                        ->orWhere(
                            'v.codigo_transacao',
                            'LIKE',
                            '%' . $busca . '%'
                        );


                    if ($isAdmin) {
                        $query->orWhere(
                            'vendedor.nome',
                            'LIKE',
                            '%' . $busca . '%'
                        );
                    }
                }
            );
        }


        //filtro por categoria

        if (
            $request->filled(
                'categoria'
            )
        ) {
            $query->where(
                'p.categoria_id',
                $request->categoria
            );
        }


        

        if (
            $request->filled(
                'status'
            )
        ) {
            $query->where(
                'v.StatusPagamento',
                $request->status
            );
        }


      //data

        if (
            $request->filled(
                'data_inicio'
            )
        ) {
            $query->whereDate(
                'v.created_at',
                '>=',
                $request->data_inicio
            );
        }


        if (
            $request->filled(
                'data_fim'
            )
        ) {
            $query->whereDate(
                'v.created_at',
                '<=',
                $request->data_fim
            );
        }
    }




    public function pdf(
        Request $request
    ) {
        return $this->relatorioPdf(
            $request
        );
    }


   //pdf

    public function relatorioPdf(
        Request $request
    ) {
        abort_if(
            Auth::user()->tipo ===
                'administrador',
            403
        );


        return $this->gerarPdf(
            $request,
            false
        );
    }



    public function adminPdf(
        Request $request
    ) {
        return $this->adminRelatorioPdf(
            $request
        );
    }


    //pdf admin

    public function adminRelatorioPdf(
        Request $request
    ) {
        $this->garantirAdministrador();


        return $this->gerarPdf(
            $request,
            true
        );
    }


  //gerar pdf

    private function gerarPdf(
        Request $request,
        bool $isAdmin
    ) {
        

        $dados =
            $request->validate(
                [
                    'data_inicio' => [
                        'required',
                        'date',
                    ],

                    'data_fim' => [
                        'required',
                        'date',
                        'after_or_equal:data_inicio',
                    ],
                ],
                [
                    'data_inicio.required' =>
                        'Informe a data inicial.',

                    'data_fim.required' =>
                        'Informe a data final.',

                    'data_fim.after_or_equal' =>
                        'A data final deve ser igual ou posterior à data inicial.',
                ]
            );


       

        $query =
            $this->queryBase(
                $isAdmin
            );


        $query
            ->whereDate(
                'v.created_at',
                '>=',
                $dados['data_inicio']
            )
            ->whereDate(
                'v.created_at',
                '<=',
                $dados['data_fim']
            );


        $vendas =
            $query
                ->select([
                    'iv.id as item_venda_id',
                    'iv.VendasId as venda_id',

                    'p.nome as produto_nome',

                    'c.nome as categoria_nome',

                    'iv.quantidade',
                    'iv.ValorUnitario as valor_unitario',
                    'iv.subtotal',

                    'v.created_at as data_compra',
                    'v.StatusPagamento as status_pagamento',
                    'v.LocalPagamento as local_pagamento',
                    'v.codigo_transacao',

                    'comprador.nome as comprador_nome',
                    'comprador.email as comprador_email',

                    'vendedor.nome as vendedor_nome',
                    'vendedor.email as vendedor_email',
                ])
                ->orderByDesc(
                    'v.created_at'
                )
                ->get();


        

        $valorTotal =
            (float) $vendas->sum(
                'subtotal'
            );


        $quantidadeTotal =
            (int) $vendas->sum(
                'quantidade'
            );


        

        $pdf =
            Pdf::loadView(
                'vendas.relatorio-pdf',
                [
                    'vendas' =>
                        $vendas,

                    'dataInicio' =>
                        $dados['data_inicio'],

                    'dataFim' =>
                        $dados['data_fim'],

                    'valorTotal' =>
                        $valorTotal,

                    'quantidadeTotal' =>
                        $quantidadeTotal,

                    'isAdmin' =>
                        $isAdmin,

                    'usuario' =>
                        Auth::user(),
                ]
            )
                ->setPaper(
                    'a4',
                    'landscape'
                );


        return $pdf->stream(
            'relatorio-vendas-' .
            $dados['data_inicio'] .
            '-' .
            $dados['data_fim'] .
            '.pdf'
        );
    }


    // planilha xlsx 

    public function adminXlsx(
        Request $request
    ) {
        return $this->adminRelatorioXlsx(
            $request
        );
    }


    // planilha xlsx admin

    public function adminRelatorioXlsx(
        Request $request
    ) {
        $this->garantirAdministrador();


        $dados =
            $request->validate(
                [
                    'data_inicio' => [
                        'required',
                        'date',
                    ],

                    'data_fim' => [
                        'required',
                        'date',
                        'after_or_equal:data_inicio',
                    ],
                ]
            );


        return Excel::download(
            new VendasExport(
                $dados['data_inicio'],
                $dados['data_fim']
            ),
            'relatorio-vendas-' .
            $dados['data_inicio'] .
            '-' .
            $dados['data_fim'] .
            '.xlsx'
        );
    }


    //só adm

    private function garantirAdministrador(): void
    {
        abort_unless(
            Auth::check()
            &&
            Auth::user()->tipo ===
                'administrador',
            403,
            'Acesso não autorizado.'
        );
    }
}