<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use App\Models\ItemCarrinho;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CarrinhoController extends Controller
{
    /**
     * Exibe o carrinho do usuário autenticado.
     */
    public function index()
    {
        $carrinho = Carrinho::firstOrCreate([
            'UsuarioId' => Auth::id(),
        ]);

        $itens = $carrinho->itens()
            ->with([
                'produto.vendedor',
                'produto.fotos' => function ($query) {
                    $query
                        ->orderByDesc('principal')
                        ->orderBy('id');
                },
            ])
            ->orderByDesc('id')
            ->get();

        $total = $itens->sum(function ($item) {

            if (!$item->produto) {
                return 0;
            }

            return (float) $item->produto->preco * $item->quantidade;
        });

        return view('carrinho', compact(
            'carrinho',
            'itens',
            'total'
        ));
    }

    /**
     * Adiciona um produto ao carrinho.
     */
    public function adicionar(Request $request, Produto $produto)
    {
        $request->validate([
            'quantidade' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        if ($produto->quantidade <= 0) {

            return back()->with(
                'error',
                'Este produto está sem estoque.'
            );
        }

        $quantidadeSolicitada = (int) $request->quantidade;

        $carrinho = Carrinho::firstOrCreate([
            'UsuarioId' => Auth::id(),
        ]);

        $item = ItemCarrinho::where(
            'CarrinhoId',
            $carrinho->id
        )
            ->where(
                'ProdutoId',
                $produto->id
            )
            ->first();

        if ($item) {

            $novaQuantidade =
                $item->quantidade + $quantidadeSolicitada;

            if ($novaQuantidade > $produto->quantidade) {

                return back()->with(
                    'error',
                    'A quantidade solicitada ultrapassa o estoque disponível.'
                );
            }

            $item->quantidade = $novaQuantidade;
            $item->save();

        } else {

            if ($quantidadeSolicitada > $produto->quantidade) {

                return back()->with(
                    'error',
                    'A quantidade solicitada ultrapassa o estoque disponível.'
                );
            }

            ItemCarrinho::create([
                'CarrinhoId' => $carrinho->id,
                'ProdutoId' => $produto->id,
                'quantidade' => $quantidadeSolicitada,
            ]);
        }

        return redirect()
            ->route('carrinho.index')
            ->with(
                'success',
                'Produto adicionado ao carrinho.'
            );
    }

    /**
     * Atualiza a quantidade de um item.
     */
    public function atualizar(
        Request $request,
        ItemCarrinho $item
    ) {
        $this->verificarItemDoUsuario($item);

        $request->validate([
            'quantidade' => [
                'required',
                'integer',
                'min:1',
            ],
        ]);

        $produto = $item->produto;

        if (!$produto) {

            return response()->json([
                'success' => false,
                'message' => 'Produto não encontrado.',
            ], 404);
        }

        $quantidade = (int) $request->quantidade;

        if ($quantidade > $produto->quantidade) {

            return response()->json([
                'success' => false,
                'message' =>
                    'Quantidade maior que o estoque disponível.',
                'estoque' => $produto->quantidade,
            ], 422);
        }

        $item->quantidade = $quantidade;
        $item->save();

        return response()->json([
            'success' => true,
            'quantidade' => $item->quantidade,
            'preco' => (float) $produto->preco,
            'subtotal' =>
                (float) $produto->preco
                * $item->quantidade,
        ]);
    }

    /**
     * Remove um produto do carrinho.
     */
    public function remover(ItemCarrinho $item)
    {
        $this->verificarItemDoUsuario($item);

        $item->delete();

        return response()->json([
            'success' => true,
            'message' => 'Produto removido do carrinho.',
        ]);
    }

    /**
     * Remove todos os produtos do carrinho.
     */
    public function limpar()
    {
        $carrinho = Carrinho::where(
            'UsuarioId',
            Auth::id()
        )->first();

        if ($carrinho) {
            $carrinho->itens()->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Carrinho limpo.',
        ]);
    }

    /**
     * Garante que o item pertence ao usuário autenticado.
     */
    private function verificarItemDoUsuario(
        ItemCarrinho $item
    ): void {
        $item->loadMissing('carrinho');

        if (
            !$item->carrinho ||
            (int) $item->carrinho->UsuarioId !==
            (int) Auth::id()
        ) {
            abort(403);
        }
    }
}