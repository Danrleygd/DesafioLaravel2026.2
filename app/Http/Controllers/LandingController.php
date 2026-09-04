<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | BUSCA E FILTRO
        |--------------------------------------------------------------------------
        */

        $busca = $request->input('busca');
        $categoria = $request->input('categoria');


        /*
        |--------------------------------------------------------------------------
        | PRINCIPAIS PROMOÇÕES
        |--------------------------------------------------------------------------
        |
        | Mantém a busca e o filtro por categoria.
        | Mostra somente 7 produtos.
        |
        */

        $query = Produto::with('categoria')
            ->where('quantidade', '>', 0);

        if (!empty($busca)) {
            $query->where(
                'nome',
                'LIKE',
                '%' . $busca . '%'
            );
        }

        if (!empty($categoria)) {
            $query->where(
                'categoria_id',
                $categoria
            );
        }

        $produtos = $query
            ->orderBy('id', 'DESC')
            ->take(7)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | HORA DO UPGRADE
        |--------------------------------------------------------------------------
        |
        | Produtos relacionados a tecnologia:
        | Smartphones, Tablets, Computadores,
        | Consoles, Controles e Audio.
        |
        */

        $produtosUpgrade = Produto::with('categoria')
            ->where('quantidade', '>', 0)
            ->whereHas('categoria', function ($query) {

                $query->whereIn('nome', [
                    'Smartphones',
                    'Tablets',
                    'Computadores',
                    'Consoles',
                    'Controles',
                    'Audio',
                ]);

            })
            ->orderBy('id', 'DESC')
            ->take(7)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | O QUE FALTA NA SUA CASA
        |--------------------------------------------------------------------------
        |
        | Produtos relacionados à casa:
        | Eletrodomesticos e Acessorios.
        |
        */

        $produtosCasa = Produto::with('categoria')
            ->where('quantidade', '>', 0)
            ->whereHas('categoria', function ($query) {

                $query->whereIn('nome', [
                    'Eletrodomesticos',
                    'Acessorios',
                ]);

            })
            ->orderBy('id', 'DESC')
            ->take(7)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | CATEGORIAS
        |--------------------------------------------------------------------------
        */

        $categorias = Categoria::orderBy('nome')->get();


        /*
        |--------------------------------------------------------------------------
        | RETORNO PARA A LANDING
        |--------------------------------------------------------------------------
        */

        return view('landing', compact(
            'produtos',
            'produtosUpgrade',
            'produtosCasa',
            'categorias',
            'busca',
            'categoria'
        ));
    }
}