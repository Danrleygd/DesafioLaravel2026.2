<?php

namespace App\Http\Controllers;

use App\Models\Carrinho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use RuntimeException;
use Throwable;

class MercadoPagoCheckoutController extends Controller
{
    /**
     * RF015
     *
     * Recebe os itens selecionados no carrinho,
     * valida tudo novamente no servidor,
     * cria uma preferência no Mercado Pago
     * e devolve a URL do Checkout Pro.
     */
    public function checkout(Request $request)
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
        ], [
            'itens.required' =>
                'Selecione pelo menos um produto.',

            'itens.min' =>
                'Selecione pelo menos um produto.',
        ]);

        $user = Auth::user();

        if (
            isset($user->tipo)
            &&
            $user->tipo === 'administrador'
        ) {
            return response()->json([
                'message' =>
                    'Administradores não podem realizar compras.',
            ], 403);
        }

        $carrinho = Carrinho::where(
            'UsuarioId',
            $user->id
        )->first();

        if (!$carrinho) {
            return response()->json([
                'message' =>
                    'Seu carrinho está vazio.',
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

        /*
        |--------------------------------------------------------------------------
        | BUSCA SOMENTE ITENS DO CARRINHO DO USUÁRIO
        |--------------------------------------------------------------------------
        */

        $itens = $carrinho
            ->itens()
            ->whereIn(
                'id',
                $idsSolicitados
            )
            ->with([
                'produto',
            ])
            ->get();

        if (
            $itens->count()
            !==
            $idsSolicitados->count()
        ) {
            return response()->json([
                'message' =>
                    'Um ou mais itens selecionados não pertencem ao seu carrinho.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | VALIDA PRODUTOS E ESTOQUE
        |--------------------------------------------------------------------------
        */

        foreach ($itens as $item) {

            if (!$item->produto) {
                return response()->json([
                    'message' =>
                        'Um dos produtos selecionados não está mais disponível.',
                ], 422);
            }

            if (
                (int) $item->quantidade
                <=
                0
            ) {
                return response()->json([
                    'message' =>
                        'Existe um item com quantidade inválida no carrinho.',
                ], 422);
            }

            if (
                (int) $item->quantidade
                >
                (int) $item->produto->quantidade
            ) {
                return response()->json([
                    'message' =>
                        'A quantidade de um dos produtos ultrapassa o estoque disponível.',
                ], 422);
            }

            if (
                (int) $item->produto->quantidade
                <=
                0
            ) {
                return response()->json([
                    'message' =>
                        'Um dos produtos selecionados está sem estoque.',
                ], 422);
            }

            if (
                (int) $item->produto->UsuarioId
                ===
                (int) $user->id
            ) {
                return response()->json([
                    'message' =>
                        'Você não pode comprar um produto anunciado por você mesmo.',
                ], 422);
            }
        }

        $accessToken = config(
            'mercadopago.access_token'
        );

        if (!$accessToken) {
            return response()->json([
                'message' =>
                    'O token do Mercado Pago ainda não foi configurado no arquivo .env.',
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | REFERÊNCIA DA COMPRA
        |--------------------------------------------------------------------------
        */

        $externalReference =
            'DTECH-CART-U'
            .
            $user->id
            .
            '-'
            .
            now()->format(
                'YmdHis'
            )
            .
            '-'
            .
            Str::upper(
                Str::random(6)
            );

        /*
        |--------------------------------------------------------------------------
        | ITENS DA PREFERÊNCIA
        |--------------------------------------------------------------------------
        |
        | O preço sempre vem do banco de dados.
        | Nenhum valor recebido do navegador é utilizado.
        |
        */

        $itemsMercadoPago = $itens
            ->map(
                function ($item) {

                    return [
                        'id' =>
                            (string)
                            $item->produto->id,

                        'title' =>
                            Str::limit(
                                (string)
                                $item->produto->nome,
                                120,
                                ''
                            ),

                        'currency_id' =>
                            'BRL',

                        'quantity' =>
                            (int)
                            $item->quantidade,

                        'unit_price' =>
                            round(
                                (float)
                                $item->produto->preco,
                                2
                            ),
                    ];
                }
            )
            ->values()
            ->all();

        $payload = [
            'items' =>
                $itemsMercadoPago,

            'external_reference' =>
                $externalReference,
        ];

        /*
        |--------------------------------------------------------------------------
        | URLS DE RETORNO
        |--------------------------------------------------------------------------
        |
        | Mercado Pago não recomenda localhost/127.0.0.1 em back_urls.
        | Portanto, só enviamos URLs quando forem públicas.
        |
        */

        $successUrl = config(
            'mercadopago.success_url'
        );

        $failureUrl = config(
            'mercadopago.failure_url'
        );

        $pendingUrl = config(
            'mercadopago.pending_url'
        );

        if (
            $this->urlPublica($successUrl)
            &&
            $this->urlPublica($failureUrl)
            &&
            $this->urlPublica($pendingUrl)
        ) {
            $payload['back_urls'] = [
                'success' =>
                    $successUrl,

                'failure' =>
                    $failureUrl,

                'pending' =>
                    $pendingUrl,
            ];

            $payload['auto_return'] =
                'approved';
        }

        $notificationUrl = config(
            'mercadopago.notification_url'
        );

        if (
            $this->urlPublica(
                $notificationUrl
            )
            &&
            str_starts_with(
                $notificationUrl,
                'https://'
            )
        ) {
            $payload['notification_url'] =
                $notificationUrl;
        }

        /*
        |--------------------------------------------------------------------------
        | CRIA PREFERÊNCIA
        |--------------------------------------------------------------------------
        */

        try {

            $response = Http::withToken(
                $accessToken
            )
                ->acceptJson()
                ->asJson()
                ->retry(
                    2,
                    500,
                    throw: false
                )
                ->timeout(30)
                ->post(
                    rtrim(
                        config(
                            'mercadopago.base_url'
                        ),
                        '/'
                    )
                    .
                    '/checkout/preferences',
                    $payload
                );

        } catch (Throwable $exception) {

            report($exception);

            return response()->json([
                'message' =>
                    'Não foi possível conectar ao Mercado Pago.',
            ], 500);
        }

        if (!$response->successful()) {

            report(
                new RuntimeException(
                    'Erro Mercado Pago: '
                    .
                    $response->body()
                )
            );

            $json = $response->json();

            $mensagem =
                data_get(
                    $json,
                    'message'
                )
                ??
                data_get(
                    $json,
                    'error'
                )
                ??
                'O Mercado Pago recusou a criação do checkout.';

            $causa = collect(
                data_get(
                    $json,
                    'cause',
                    []
                )
            )
                ->map(
                    function ($item) {

                        return
                            $item['description']
                            ??
                            $item['code']
                            ??
                            null;
                    }
                )
                ->filter()
                ->implode(' | ');

            if ($causa) {
                $mensagem .=
                    ' '
                    .
                    $causa;
            }

            return response()->json([
                'message' =>
                    $mensagem,
            ], $response->status());
        }

        $preferencia =
            $response->json();

        /*
        |--------------------------------------------------------------------------
        | URL DO CHECKOUT PRO
        |--------------------------------------------------------------------------
        */

        $environment = config(
            'mercadopago.environment'
        );

        if (
            $environment === 'sandbox'
            &&
            filled(
                data_get(
                    $preferencia,
                    'sandbox_init_point'
                )
            )
        ) {
            $checkoutUrl =
                data_get(
                    $preferencia,
                    'sandbox_init_point'
                );
        } else {
            $checkoutUrl =
                data_get(
                    $preferencia,
                    'init_point'
                );
        }

        if (!$checkoutUrl) {
            return response()->json([
                'message' =>
                    'O Mercado Pago criou a preferência, mas não retornou a URL do checkout.',
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | SALVA REFERÊNCIA NA SESSÃO
        |--------------------------------------------------------------------------
        */

        session([
            'mercadopago_preference_id' =>
                data_get(
                    $preferencia,
                    'id'
                ),

            'mercadopago_external_reference'
                =>
                $externalReference,

            'mercadopago_cart_item_ids'
                =>
                $idsSolicitados->all(),
        ]);

        return response()->json([
            'success' => true,

            'redirect' =>
                $checkoutUrl,
        ]);
    }


    /**
     * Retorna true somente para URLs externas.
     */
    private function urlPublica(
        ?string $url
    ): bool {
        if (!$url) {
            return false;
        }

        if (
            !filter_var(
                $url,
                FILTER_VALIDATE_URL
            )
        ) {
            return false;
        }

        $host = parse_url(
            $url,
            PHP_URL_HOST
        );

        if (
            !$host
            ||
            in_array(
                $host,
                [
                    'localhost',
                    '127.0.0.1',
                    '::1',
                ],
                true
            )
        ) {
            return false;
        }

        return true;
    }
}
