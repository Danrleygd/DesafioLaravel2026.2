<?php

namespace App\Exports;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class VendasExport implements
    FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithColumnFormatting
{
    private string $dataInicio;

    private string $dataFim;


    public function __construct(
        string $dataInicio,
        string $dataFim
    ) {
        $this->dataInicio =
            $dataInicio;

        $this->dataFim =
            $dataFim;
    }


    /*
    |--------------------------------------------------------------------------
    | DADOS
    |--------------------------------------------------------------------------
    */

    public function collection(): Collection
    {
        return DB::table(
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
            )

            ->whereDate(
                'v.created_at',
                '>=',
                $this->dataInicio
            )

            ->whereDate(
                'v.created_at',
                '<=',
                $this->dataFim
            )

            ->orderByDesc(
                'v.created_at'
            )

            ->select([
                'v.id as venda_id',
                'v.created_at as data_compra',

                'p.nome as produto',

                'c.nome as categoria',

                'iv.quantidade',

                'iv.ValorUnitario as valor_unitario',

                'iv.subtotal',

                'comprador.nome as comprador',

                'vendedor.nome as vendedor',

                'v.StatusPagamento as status',

                'v.LocalPagamento as pagamento',

                'v.codigo_transacao as transacao',
            ])

            ->get()

            ->map(
                function ($venda) {
                    return [
                        $venda->venda_id,

                        Carbon::parse(
                            $venda->data_compra
                        )->format(
                            'd/m/Y H:i'
                        ),

                        $venda->produto,

                        $venda->categoria,

                        $venda->quantidade,

                        (float)
                        $venda->valor_unitario,

                        (float)
                        $venda->subtotal,

                        $venda->comprador,

                        $venda->vendedor,

                        ucfirst(
                            $venda->status
                        ),

                        ucfirst(
                            $venda->pagamento
                        ),

                        $venda->transacao,
                    ];
                }
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CABEÇALHOS
    |--------------------------------------------------------------------------
    */

    public function headings(): array
    {
        return [
            'Venda',
            'Data',
            'Produto',
            'Categoria',
            'Quantidade',
            'Valor Unitário',
            'Valor Total',
            'Comprador',
            'Vendedor',
            'Status',
            'Pagamento',
            'Transação',
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | FORMATAÇÃO
    |--------------------------------------------------------------------------
    */

    public function columnFormats(): array
    {
        return [
            'F' =>
                '"R$" #,##0.00',

            'G' =>
                '"R$" #,##0.00',
        ];
    }
}