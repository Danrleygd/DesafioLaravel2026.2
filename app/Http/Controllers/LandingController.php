<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->input('busca');
        $categoria = $request->input('categoria');

        /*
        |--------------------------------------------------------------------------
        | PRODUTOS PRINCIPAIS
        |--------------------------------------------------------------------------
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
        | PRODUTOS DA SEÇÃO "HORA DO UPGRADE"
        |--------------------------------------------------------------------------
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
        | PRODUTOS DA SEÇÃO "O QUE FALTA NA SUA CASA"
        |--------------------------------------------------------------------------
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
        | PRODUTOS DOS BANNERS LATERAIS
        |--------------------------------------------------------------------------
        |
        | Aqui pegamos somente 2 produtos disponíveis.
        | Eles NÃO são carrossel.
        |
        */

        $produtosLaterais = Produto::where('quantidade', '>', 0)
            ->whereNotNull('foto')
            ->where('foto', '!=', '')
            ->orderBy('id', 'DESC')
            ->take(2)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | BANNERS PRINCIPAIS
        |--------------------------------------------------------------------------
        |
        | O carrossel possui no máximo 3 itens.
        |
        */

        $bannersPrincipais = [
            [
                'imagem' => 'razer-kraken-kitty-v2-gengar_inner-details_desktop-1920x700.webp',
                'alt' => 'Razer Kraken Kitty V2 Gengar',
                'titulo' => [
                    'RAZER KRAKEN',
                    'KITTY V2',
                    'EDIÇÃO GENGAR'
                ],
                'link' => '#'
            ],

            [
                'imagem' => 'DeadpoolControl.jpeg',
                'alt' => 'Controle Deadpool',
                'titulo' => [
                    'CHEEKY CONTROLLER',
                    'BY DEADPOOL'
                ],
                'link' => '#'
            ],

            [
                'imagem' => 'wolverineAlexa.webp',
                'alt' => 'Alexarine',
                'titulo' => [
                    'ALEXARINE'
                ],
                'link' => '#'
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | GARANTE NO MÁXIMO 3 BANNERS
        |--------------------------------------------------------------------------
        */

        $bannersPrincipais = array_slice(
            $bannersPrincipais,
            0,
            3
        );

        /*
        |--------------------------------------------------------------------------
        | CATEGORIAS
        |--------------------------------------------------------------------------
        */

        $categorias = Categoria::orderBy('nome')->get();

        /*
        |--------------------------------------------------------------------------
        | RETORNO
        |--------------------------------------------------------------------------
        */

        return view('landing', compact(
            'produtos',
            'produtosUpgrade',
            'produtosCasa',
            'produtosLaterais',
            'bannersPrincipais',
            'categorias',
            'busca',
            'categoria'
        ));
    }
}