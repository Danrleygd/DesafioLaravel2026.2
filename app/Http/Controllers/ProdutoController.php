<?php

namespace App\Http\Controllers;

use App\Models\Produto;
use Illuminate\Http\Request;

class ProdutoController extends Controller
{
    public function show($id)
    {
        $produto = Produto::with([
            'categoria',
            'fotos' => function ($query) {
                $query->orderByDesc('principal')
                    ->orderBy('id');
            }
        ])->findOrFail($id);

        $produtosRelacionados = Produto::with([
            'fotos' => function ($query) {
                $query->orderByDesc('principal')
                    ->orderBy('id');
            }
        ])
            ->where('categoria_id', $produto->categoria_id)
            ->where('id', '!=', $produto->id)
            ->where('quantidade', '>', 0)
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('produto', compact(
            'produto',
            'produtosRelacionados'
        ));
    }
}