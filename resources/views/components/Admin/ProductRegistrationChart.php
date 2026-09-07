<?php

namespace App\View\Components\Admin;

use App\Models\Produto;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Component;

class ProductRegistrationChart extends Component
{
    public array $dadosGrafico;

    public int $totalPeriodo;

    public int $maiorValor;


    public function __construct()
    {
        /*
        |--------------------------------------------------------------------------
        | GARANTIR ADMINISTRADOR
        |--------------------------------------------------------------------------
        */

        abort_unless(
            Auth::check()
            &&
            Auth::user()->tipo === 'administrador',
            403,
            'Acesso não autorizado.'
        );


        /*
        |--------------------------------------------------------------------------
        | PERÍODO
        |--------------------------------------------------------------------------
        |
        | RF013:
        | últimos 12 meses incluindo o mês atual.
        |
        */

        $inicio =
            now()
                ->startOfMonth()
                ->subMonths(11);


        $fim =
            now()
                ->endOfMonth();


        /*
        |--------------------------------------------------------------------------
        | PRODUTOS CADASTRADOS POR MÊS
        |--------------------------------------------------------------------------
        |
        | Resultado esperado:
        |
        | 2025-10 => 3
        | 2025-11 => 7
        | 2025-12 => 2
        | ...
        |
        */

        $totaisPorMes =
            Produto::query()
                ->whereNotNull(
                    'created_at'
                )
                ->whereBetween(
                    'created_at',
                    [
                        $inicio,
                        $fim,
                    ]
                )
                ->selectRaw(
                    "
                    DATE_FORMAT(
                        created_at,
                        '%Y-%m'
                    ) as mes,
                    COUNT(*) as total
                    "
                )
                ->groupByRaw(
                    "
                    DATE_FORMAT(
                        created_at,
                        '%Y-%m'
                    )
                    "
                )
                ->orderByRaw(
                    "
                    DATE_FORMAT(
                        created_at,
                        '%Y-%m'
                    )
                    "
                )
                ->pluck(
                    'total',
                    'mes'
                );


        /*
        |--------------------------------------------------------------------------
        | NOMES DOS MESES
        |--------------------------------------------------------------------------
        */

        $nomesMeses = [
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
        ];


        /*
        |--------------------------------------------------------------------------
        | MAIOR VALOR
        |--------------------------------------------------------------------------
        |
        | Utilizado para calcular proporcionalmente
        | a altura das barras.
        |
        */

        $this->maiorValor =
            max(
                1,
                (int) (
                    $totaisPorMes->max()
                    ?? 0
                )
            );


        /*
        |--------------------------------------------------------------------------
        | MONTAR OS 12 MESES
        |--------------------------------------------------------------------------
        |
        | Mesmo meses que não possuem produtos
        | cadastrados aparecerão com valor zero.
        |
        */

        $dadosGrafico = [];


        for (
            $i = 0;
            $i < 12;
            $i++
        ) {

            $data =
                $inicio
                    ->copy()
                    ->addMonths(
                        $i
                    );


            $chave =
                $data->format(
                    'Y-m'
                );


            $total =
                (int) (
                    $totaisPorMes[
                        $chave
                    ]
                    ?? 0
                );


            /*
            |--------------------------------------------------------------------------
            | ALTURA DA BARRA
            |--------------------------------------------------------------------------
            */

            if ($total === 0) {

                $altura = 0;

            } else {

                $altura =
                    (
                        $total
                        /
                        $this->maiorValor
                    )
                    *
                    100;


                /*
                 * Garante que valores pequenos
                 * continuem visualmente perceptíveis.
                 */

                $altura =
                    max(
                        7,
                        $altura
                    );
            }


            $dadosGrafico[] = [

                'chave' =>
                    $chave,

                'mes' =>
                    $nomesMeses[
                        $data->month
                    ],

                'ano' =>
                    $data->format(
                        'Y'
                    ),

                'label' =>
                    $nomesMeses[
                        $data->month
                    ]
                    .
                    '/'
                    .
                    $data->format(
                        'y'
                    ),

                'total' =>
                    $total,

                'altura' =>
                    round(
                        $altura,
                        2
                    ),
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | DADOS DO COMPONENTE
        |--------------------------------------------------------------------------
        */

        $this->dadosGrafico =
            $dadosGrafico;


        $this->totalPeriodo =
            collect(
                $dadosGrafico
            )
                ->sum(
                    'total'
                );
    }


    /*
    |--------------------------------------------------------------------------
    | VIEW
    |--------------------------------------------------------------------------
    */

    public function render(): View
    {
        return view(
            'components.admin.product-registration-chart'
        );
    }
}