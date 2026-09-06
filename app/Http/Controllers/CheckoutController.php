<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Recebe do carrinho somente os itens selecionados
     * e guarda os IDs na sessão do checkout.
     */
    public function selecionarItens(Request $request)
    {
        $dados = $request->validate([
            'itens' => [
                'required',
                'array',
                'min:1',
            ],

            'itens.*' => [
                'required',
                'integer',
                'distinct',
            ],
        ]);

        $user = Auth::user();

        $carrinho = Carrinho::where(
            'UsuarioId',
            $user->id
        )->first();

        if (!$carrinho) {
            return response()->json([
                'message' => 'Seu carrinho está vazio.',
            ], 422);
        }

        $idsSolicitados = collect(
            $dados['itens']
        )
            ->map(
                fn ($id) => (int) $id
            )
            ->unique()
            ->values();

        $idsValidos = $carrinho
            ->itens()
            ->whereIn(
                'id',
                $idsSolicitados
            )
            ->pluck('id')
            ->map(
                fn ($id) => (int) $id
            )
            ->values();

        if (
            $idsValidos->count()
            !==
            $idsSolicitados->count()
        ) {
            return response()->json([
                'message' =>
                    'Um ou mais itens selecionados não pertencem ao seu carrinho.',
            ], 422);
        }

        session([
            'checkout_itens' =>
                $idsValidos->all(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | LIMPA DADOS DE UMA TENTATIVA ANTERIOR
        |--------------------------------------------------------------------------
        |
        | O endereço pode continuar salvo porque ainda pertence ao usuário.
        | Dados de pagamento serão limpos quando forem implementados.
        |
        */

        session()->forget([
            'checkout_pagbank_id',
            'checkout_pagbank_reference',
        ]);

        return response()->json([
            'success' => true,

            'redirect' =>
                route('checkout.endereco'),
        ]);
    }


    /**
     * Exibe a etapa de endereço do checkout
     * usando somente os itens selecionados no carrinho.
     */
    public function endereco()
    {
        $user = Auth::user();

        $carrinho = Carrinho::where(
            'UsuarioId',
            $user->id
        )->first();

        if (!$carrinho) {
            return redirect()
                ->route('carrinho.index')
                ->with(
                    'error',
                    'Seu carrinho está vazio.'
                );
        }

        $idsSelecionados = collect(
            session(
                'checkout_itens',
                []
            )
        )
            ->map(
                fn ($id) => (int) $id
            )
            ->unique()
            ->values();

        if ($idsSelecionados->isEmpty()) {
            return redirect()
                ->route('carrinho.index')
                ->with(
                    'error',
                    'Selecione pelo menos um produto para continuar.'
                );
        }

        $itens = $carrinho
            ->itens()
            ->whereIn(
                'id',
                $idsSelecionados
            )
            ->with([
                'produto.categoria',
                'produto.vendedor',
                'produto.fotos',
            ])
            ->get();

        if (
            $itens->isEmpty()
            ||
            $itens->count()
            !==
            $idsSelecionados->count()
        ) {
            session()->forget(
                'checkout_itens'
            );

            return redirect()
                ->route('carrinho.index')
                ->with(
                    'error',
                    'Os produtos selecionados foram alterados. Selecione novamente os itens do carrinho.'
                );
        }

        foreach ($itens as $item) {

            if (!$item->produto) {
                session()->forget(
                    'checkout_itens'
                );

                return redirect()
                    ->route('carrinho.index')
                    ->with(
                        'error',
                        'Um dos produtos selecionados não está mais disponível.'
                    );
            }

            if (
                (int) $item->quantidade
                >
                (int) $item->produto->quantidade
            ) {
                return redirect()
                    ->route('carrinho.index')
                    ->with(
                        'error',
                        'A quantidade de um dos produtos selecionados ultrapassa o estoque disponível.'
                    );
            }
        }

        $total = $itens->sum(
            function ($item) {

                return
                    (float) $item->produto->preco
                    *
                    (int) $item->quantidade;
            }
        );

        $enderecos = $user
            ->enderecos()
            ->get();

        $enderecoSelecionadoId =
            session(
                'checkout_endereco_id'
            );

        if (
            !$enderecoSelecionadoId
            ||
            !$enderecos->contains(
                'id',
                $enderecoSelecionadoId
            )
        ) {
            $enderecoSelecionadoId =
                $enderecos->first()?->id;
        }

        return view(
            'checkout.endereco',
            compact(
                'user',
                'itens',
                'total',
                'enderecos',
                'enderecoSelecionadoId'
            )
        );
    }


    /**
     * Seleciona um endereço já cadastrado.
     */
    public function selecionarEndereco(
        Request $request
    ) {
        $request->validate([
            'endereco_id' => [
                'required',
                'integer',
            ],
        ]);

        if (
            !session()->has(
                'checkout_itens'
            )
        ) {
            return redirect()
                ->route('carrinho.index')
                ->with(
                    'error',
                    'Selecione os produtos do carrinho novamente.'
                );
        }

        $user = Auth::user();

        $endereco = $user
            ->enderecos()
            ->where(
                'Enderecos.id',
                $request->endereco_id
            )
            ->first();

        if (!$endereco) {
            return back()
                ->with(
                    'error',
                    'O endereço selecionado não pertence ao usuário.'
                );
        }

        session([
            'checkout_endereco_id' =>
                $endereco->id,
        ]);

        /*
        |--------------------------------------------------------------------------
        | PRÓXIMA ETAPA
        |--------------------------------------------------------------------------
        |
        | No próximo passo este método passará a redirecionar para:
        |
        | return redirect()->route('checkout.pagamento');
        |
        */

        return back()
            ->with(
                'success',
                'Endereço selecionado. O checkout está pronto para seguir para o pagamento.'
            );
    }


    /**
     * Cadastra um novo endereço durante o checkout.
     */
    public function storeEndereco(
        Request $request
    ) {
        $dados = $request->validate([
            'cep' => [
                'required',
                'string',
                'max:9',
            ],

            'logradouro' => [
                'required',
                'string',
                'max:255',
            ],

            'numero' => [
                'required',
                'string',
                'max:20',
            ],

            'complemento' => [
                'nullable',
                'string',
                'max:255',
            ],

            'bairro' => [
                'required',
                'string',
                'max:255',
            ],

            'cidade' => [
                'required',
                'string',
                'max:255',
            ],

            'estado' => [
                'required',
                'string',
                'size:2',
            ],
        ]);

        $user = Auth::user();

        $dados['cep'] =
            preg_replace(
                '/\D/',
                '',
                $dados['cep']
            );

        $dados['estado'] =
            strtoupper(
                $dados['estado']
            );

        DB::transaction(
            function () use (
                $user,
                $dados
            ) {

                $endereco =
                    Endereco::create(
                        $dados
                    );

                $user
                    ->enderecos()
                    ->syncWithoutDetaching([
                        $endereco->id,
                    ]);

                session([
                    'checkout_endereco_id'
                        =>
                        $endereco->id,
                ]);
            }
        );

        return redirect()
            ->route('checkout.endereco')
            ->with(
                'success',
                'Endereço cadastrado e selecionado.'
            );
    }
}
