<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use App\Models\Categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LandingPageController extends Controller
{
    public function index(Request $request)
    {
        $busca = $request->input('busca');
        $categoria = $request->input('categoria');

        $query = Produto::with('categoria')
            ->where('quantidade', '>', 0);

        if (Auth::check()) {
            $query->where('UsuarioId', '!=', Auth::id());
        }


        if (!empty($busca)) {
            $query->where('nome', 'LIKE', '%' . $busca . '%');
        }

        if (!empty($categoria)) {
            $query->where('categoria_id', $categoria);
        }



        $produtos = $query
            ->orderBy('id', 'DESC')
            ->paginate(10)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Categorias
        |--------------------------------------------------------------------------
        */

        $categorias = Categoria::orderBy('nome')->get();

        return view('landing', compact(
            'produtos',
            'categorias',
            'busca',
            'categoria'
        ));
    }
}