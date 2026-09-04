<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class ProdutoIndexController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | PARÂMETROS
        |--------------------------------------------------------------------------
        */

        $categoria = $request->input('categoria');

        $secao = $request->input('secao');

        $busca = $request->input('busca');

        $precoMin = $request->input('preco_min');

        $precoMax = $request->input('preco_max');

        $ordenar = $request->input(
            'ordenar',
            'relevancia'
        );


        /*
        |--------------------------------------------------------------------------
        | QUERY
        |--------------------------------------------------------------------------
        */

        $query = Produto::with('categoria')
            ->where('quantidade', '>', 0);


        /*
        |--------------------------------------------------------------------------
        | CATEGORIA INDIVIDUAL
        |--------------------------------------------------------------------------
        */

        if (!empty($categoria)) {
            if (is_numeric($categoria)) {
                $query->where('categoria_id', $categoria);
            } else {
                $query->whereHas('categoria', function ($categoriaQuery) use ($categoria) {
                    $categoriaQuery->where('nome', $categoria);
                });
            }
        }


        /*
        |--------------------------------------------------------------------------
        | SEÇÃO: HORA DO UPGRADE
        |--------------------------------------------------------------------------
        */

        elseif ($secao === 'upgrade') {

            $query->whereHas(
                'categoria',
                function ($query) {

                    $query->whereIn(
                        'nome',
                        [
                            'Smartphones',
                            'Tablets',
                            'Computadores',
                            'Consoles',
                            'Controles',
                            'Audio',
                        ]
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | SEÇÃO: CASA
        |--------------------------------------------------------------------------
        */

        elseif ($secao === 'casa') {

            $query->whereHas(
                'categoria',
                function ($query) {

                    $query->whereIn(
                        'nome',
                        [
                            'Eletrodomesticos',
                            'Acessorios',
                        ]
                    );

                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | BUSCA
        |--------------------------------------------------------------------------
        */

        if (!empty($busca)) {

            $query->where(
                'nome',
                'LIKE',
                '%' . $busca . '%'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PREÇO MÍNIMO
        |--------------------------------------------------------------------------
        */

        if (
            $precoMin !== null &&
            $precoMin !== ''
        ) {

            $query->where(
                'preco',
                '>=',
                $precoMin
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PREÇO MÁXIMO
        |--------------------------------------------------------------------------
        */

        if (
            $precoMax !== null &&
            $precoMax !== ''
        ) {

            $query->where(
                'preco',
                '<=',
                $precoMax
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ORDENAÇÃO
        |--------------------------------------------------------------------------
        */

        switch ($ordenar) {

            case 'menor_preco':

                $query->orderBy(
                    'preco',
                    'ASC'
                );

                break;


            case 'maior_preco':

                $query->orderBy(
                    'preco',
                    'DESC'
                );

                break;


            case 'mais_recentes':

                $query->orderBy(
                    'id',
                    'DESC'
                );

                break;


            default:

                $query->orderBy(
                    'id',
                    'DESC'
                );

                break;
        }


        /*
        |--------------------------------------------------------------------------
        | PRODUTOS
        |--------------------------------------------------------------------------
        */

        $produtos = $query
            ->paginate(36)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | CATEGORIAS
        |--------------------------------------------------------------------------
        */

        $categorias = Categoria::orderBy(
            'nome'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | CONTAGEM DE PRODUTOS
        |--------------------------------------------------------------------------
        */

        $quantidadesCategorias = Produto::where(
                'quantidade',
                '>',
                0
            )
            ->selectRaw(
                'categoria_id, COUNT(*) as total'
            )
            ->groupBy(
                'categoria_id'
            )
            ->pluck(
                'total',
                'categoria_id'
            );


        /*
        |--------------------------------------------------------------------------
        | CATEGORIA SELECIONADA
        |--------------------------------------------------------------------------
        */

        $categoriaSelecionada = null;

        if (!empty($categoria)) {

            $categoriaSelecionada = Categoria::find(
                $categoria
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TÍTULO
        |--------------------------------------------------------------------------
        */

        if ($secao === 'upgrade') {

            $titulo = 'Hora do Upgrade';

        } elseif ($secao === 'casa') {

            $titulo = 'O Que falta na sua Casa';

        } elseif ($categoriaSelecionada) {

            $titulo = $categoriaSelecionada->nome;

        } else {

            $titulo = 'Todos os Produtos';
        }


        return view(
            'produtoIndex',
            compact(
                'produtos',
                'categorias',
                'quantidadesCategorias',
                'categoriaSelecionada',
                'titulo',
                'secao'
            )
        );
    }
}