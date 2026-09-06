<?php

namespace App\Http\Controllers;

use App\Services\FinalizarCompraPagBankService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class PagBankWebhookController extends Controller
{
    public function handle(
        Request $request,
        FinalizarCompraPagBankService $finalizarCompra
    ) {
        /*
        |--------------------------------------------------------------------------
        | O WEBHOOK DE PAGAMENTO DO CHECKOUT RETORNA UM ORDER (ORDE_)
        |--------------------------------------------------------------------------
        */

        $orderId =
            $this->localizarId(
                $request->all(),
                'ORDE_'
            );

        if (!$orderId) {
            $headerId =
                $request->header(
                    'x-product-id'
                );

            if (
                is_string($headerId)
                &&
                str_starts_with(
                    $headerId,
                    'ORDE_'
                )
            ) {
                $orderId = $headerId;
            }
        }

        /*
        |--------------------------------------------------------------------------
        | EVENTOS DE CHECKOUT, COMO EXPIRED, NÃO GERAM VENDA
        |--------------------------------------------------------------------------
        */

        if (!$orderId) {
            return response()->json([
                'received' => true,
                'processed' => false,
                'message' =>
                    'Evento recebido, mas não corresponde a um pedido pago.',
            ]);
        }

        try {
            $pedido =
                $this->consultarPedido(
                    $orderId
                );

            $status =
                collect(
                    $pedido['charges']
                    ??
                    []
                )
                    ->pluck('status')
                    ->first();

            if ($status !== 'PAID') {
                return response()->json([
                    'received' => true,
                    'processed' => false,
                    'status' => $status,
                ]);
            }

            $vendaId =
                $finalizarCompra
                    ->finalizar(
                        $pedido
                    );

            return response()->json([
                'received' => true,
                'processed' => true,
                'venda_id' => $vendaId,
            ]);

        } catch (Throwable $exception) {
            Log::error(
                'Falha ao processar webhook PagBank.',
                [
                    'order_id' =>
                        $orderId,

                    'message' =>
                        $exception
                            ->getMessage(),
                ]
            );

            /*
            |--------------------------------------------------------------------------
            | 500 FAZ O PROVEDOR SABER QUE O EVENTO NÃO FOI PROCESSADO
            |--------------------------------------------------------------------------
            */

            return response()->json([
                'received' => true,
                'processed' => false,
                'message' =>
                    'Não foi possível processar o pagamento.',
            ], 500);
        }
    }


    private function consultarPedido(
        string $orderId
    ): array {
        $token =
            config('pagbank.token');

        if (!$token) {
            throw new RuntimeException(
                'Token PagBank não configurado.'
            );
        }

        $baseUrl =
            config(
                'pagbank.environment'
            )
            ===
            'production'
                ? config(
                    'pagbank.production_url'
                )
                : config(
                    'pagbank.sandbox_url'
                );

        $response =
            Http::withToken(
                $token
            )
                ->acceptJson()
                ->retry(
                    2,
                    500
                )
                ->timeout(20)
                ->get(
                    $baseUrl
                    .
                    '/orders/'
                    .
                    $orderId
                );

        if (!$response->successful()) {
            throw new RuntimeException(
                'Não foi possível validar o pedido diretamente no PagBank.'
            );
        }

        return
            $response->json();
    }


    private function localizarId(
        mixed $valor,
        string $prefixo
    ): ?string {
        if (
            is_string($valor)
            &&
            str_starts_with(
                $valor,
                $prefixo
            )
        ) {
            return $valor;
        }

        if (!is_array($valor)) {
            return null;
        }

        foreach ($valor as $item) {
            $encontrado =
                $this->localizarId(
                    $item,
                    $prefixo
                );

            if ($encontrado) {
                return $encontrado;
            }
        }

        return null;
    }
}
