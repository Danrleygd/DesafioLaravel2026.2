<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    /**
     * Exibe o perfil.
     */
    public function edit()
    {
        $user = Auth::user();

        $enderecos = $user->enderecos()
            ->orderBy('id')
            ->get();

        $cartoes = $user->cartoes()
            ->orderBy('id')
            ->get();

        $totalPedidos = $user->vendasComoComprador()->count();

        return view('profile.edit', compact(
            'user',
            'enderecos',
            'cartoes',
            'totalPedidos'
        ));
    }

    /**
     * Atualiza os dados do perfil.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nome' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:Usuarios,email,' . $user->id,
            ],

            'cpf' => [
                'nullable',
                'string',
                'max:14',
            ],

            'data_nascimento' => [
                'nullable',
                'date',
            ],

            'telefone' => [
                'nullable',
                'string',
                'max:20',
            ],

            'foto' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,webp',
                'max:2048',
            ],
        ]);

        /*
        |--------------------------------------------------------------------------
        | FOTO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto')) {

            if ($user->foto) {

                $fotoAnterior = ltrim($user->foto, '/');

                if (Storage::disk('public')->exists($fotoAnterior)) {
                    Storage::disk('public')->delete($fotoAnterior);
                }
            }

            $caminhoFoto = $request->file('foto')->store(
                'usuarios',
                'public'
            );

            $user->foto = $caminhoFoto;
        }

        /*
        |--------------------------------------------------------------------------
        | E-MAIL
        |--------------------------------------------------------------------------
        */

        if ($user->email !== $validated['email']) {
            $user->email_verified_at = null;
        }

        /*
        |--------------------------------------------------------------------------
        | DADOS
        |--------------------------------------------------------------------------
        */

        $user->nome = $validated['nome'];
        $user->email = $validated['email'];
        $user->cpf = $validated['cpf'] ?? null;
        $user->data_nascimento = $validated['data_nascimento'] ?? null;
        $user->telefone = $validated['telefone'] ?? null;

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Perfil atualizado com sucesso!');
    }

    /**
     * Atualiza a senha.
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'senha_atual' => [
                'required',
                'string',
            ],

            'nova_senha' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->senha_atual, $user->senha)) {

            return back()
                ->withErrors([
                    'senha_atual' => 'A senha atual está incorreta.',
                ])
                ->withInput();
        }

        $user->senha = $request->nova_senha;

        $user->save();

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Senha alterada com sucesso!');
    }

    /**
     * Cadastra um novo endereço.
     */
    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'cep' => [
                'required',
                'digits:8',
            ],

            'logradouro' => [
                'required',
                'string',
                'max:150',
            ],

            'numero' => [
                'required',
                'string',
                'max:10',
            ],

            'complemento' => [
                'nullable',
                'string',
                'max:100',
            ],

            'bairro' => [
                'required',
                'string',
                'max:100',
            ],

            'cidade' => [
                'required',
                'string',
                'max:100',
            ],

            'estado' => [
                'required',
                'string',
                'size:2',
            ],
        ]);

        // Garante que o estado seja salvo em letras maiúsculas
        $validated['estado'] = strtoupper($validated['estado']);

        // Cria o endereço
        $endereco = Endereco::create($validated);

        // Relaciona o endereço ao usuário logado
        Auth::user()
            ->enderecos()
            ->attach($endereco->id);

        return redirect()
            ->route('profile.edit')
            ->with('success', 'Endereço cadastrado com sucesso!');
    }

    /**
     * Exclui a conta.
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'senha' => [
                'required',
                'string',
            ],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->senha, $user->senha)) {

            return back()
                ->withErrors([
                    'senha' => 'A senha está incorreta.',
                ]);
        }

        if ($user->foto) {

            $foto = ltrim($user->foto, '/');

            if (Storage::disk('public')->exists($foto)) {
                Storage::disk('public')->delete($foto);
            }
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('success', 'Conta excluída com sucesso.');
    }
}