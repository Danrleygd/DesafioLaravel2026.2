<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;

class ViaCepController extends Controller
{
    public function consultar(string $cep)
    {
        // Remove tudo que não for número
        $cep = preg_replace('/\D/', '', $cep);

        // Verifica se possui 8 dígitos
        if (strlen($cep) !== 8) {
            return response()->json([
                'erro' => 'CEP inválido. Informe um CEP com 8 dígitos.'
            ], 422);
        }

        try {

            $response = Http::timeout(10)
                ->get("https://viacep.com.br/ws/{$cep}/json/");

        } catch (\Exception $e) {

            return response()->json([
                'erro' => 'Erro ao conectar com o serviço ViaCEP.'
            ], 500);
        }

        if (!$response->successful()) {

            return response()->json([
                'erro' => 'Não foi possível consultar o CEP.'
            ], 500);
        }

        $dados = $response->json();

        if (isset($dados['erro']) && $dados['erro'] === true) {

            return response()->json([
                'erro' => 'CEP não encontrado.'
            ], 404);
        }

        return response()->json($dados);
    }
}