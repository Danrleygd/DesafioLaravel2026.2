<?php

namespace App\Http\Controllers;

use App\Models\Endereco;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Throwable;

class AdminUserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTAGEM
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = User::query()
            ->where('tipo', 'usuario')
            ->with('enderecos');

        /*
        |--------------------------------------------------------------------------
        | PESQUISA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('busca')) {

            $busca = trim($request->busca);

            $cpfBusca = preg_replace(
                '/\D/',
                '',
                $busca
            );

            $query->where(function ($query) use ($busca, $cpfBusca) {

                $query
                    ->where(
                        'nome',
                        'LIKE',
                        '%' . $busca . '%'
                    )
                    ->orWhere(
                        'email',
                        'LIKE',
                        '%' . $busca . '%'
                    );

                if (!empty($cpfBusca)) {

                    $query->orWhere(
                        'cpf',
                        'LIKE',
                        '%' . $cpfBusca . '%'
                    );
                }
            });
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINAÇÃO
        |--------------------------------------------------------------------------
        */

        $usuarios = $query
            ->orderByDesc('created_at')
            ->paginate(6)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | ESTATÍSTICAS
        |--------------------------------------------------------------------------
        */

        $totalUsuarios = User::where(
            'tipo',
            'usuario'
        )->count();


        $novosHoje = User::where(
            'tipo',
            'usuario'
        )
            ->whereDate(
                'created_at',
                today()
            )
            ->count();


        $usuariosComEndereco = User::where(
            'tipo',
            'usuario'
        )
            ->whereHas('enderecos')
            ->count();


        return view(
            'admin.User',
            compact(
                'usuarios',
                'totalUsuarios',
                'novosHoje',
                'usuariosComEndereco'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TELA DE CADASTRO
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'admin.users.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CADASTRAR USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $this->normalizarDados($request);

        $dados = $this->validar(
            $request
        );

        $foto = null;


        /*
        |--------------------------------------------------------------------------
        | FOTO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto')) {

            $foto = $request
                ->file('foto')
                ->store(
                    'usuarios',
                    'public'
                );
        }


        try {

            DB::transaction(
                function () use (
                    $dados,
                    $foto
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | USUÁRIO
                    |--------------------------------------------------------------------------
                    */

                    $usuario = User::create([
                        'nome' =>
                            $dados['nome'],

                        'email' =>
                            $dados['email'],

                        'senha' =>
                            Hash::make(
                                $dados['senha']
                            ),

                        /*
                         * Essa área é exclusivamente
                         * RF005 - usuários comuns.
                         */
                        'tipo' =>
                            'usuario',

                        'cpf' =>
                            $dados['cpf'],

                        'data_nascimento' =>
                            $dados['data_nascimento'],

                        'telefone' =>
                            $dados['telefone'],

                        'saldo' =>
                            $dados['saldo'] ?? 0,

                        'foto' =>
                            $foto,

                        'criador_id' =>
                            Auth::id(),
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | ENDEREÇO
                    |--------------------------------------------------------------------------
                    */

                    $endereco = Endereco::create(
                        $this->dadosEndereco(
                            $dados
                        )
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | USUÁRIO x ENDEREÇO
                    |--------------------------------------------------------------------------
                    */

                    $usuario
                        ->enderecos()
                        ->attach(
                            $endereco->id
                        );
                }
            );

        } catch (Throwable $exception) {

            /*
             * Se algo falhar no banco,
             * remove a imagem enviada.
             */

            if ($foto) {

                Storage::disk('public')
                    ->delete($foto);
            }

            throw $exception;
        }


        return redirect()
            ->route(
                'admin.usuarios.index'
            )
            ->with(
                'success',
                'Usuário cadastrado com sucesso.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VISUALIZAR USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function show(User $usuario)
    {
        $this->garantirUsuarioComum(
            $usuario
        );

        $usuario->load(
            'enderecos'
        );


        return view(
            'admin.users.show',
            compact('usuario')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | TELA DE EDIÇÃO
    |--------------------------------------------------------------------------
    */

    public function edit(User $usuario)
    {
        $this->garantirUsuarioComum(
            $usuario
        );

        $usuario->load(
            'enderecos'
        );


        return view(
            'admin.users.edit',
            compact('usuario')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        User $usuario
    ) {
        $this->garantirUsuarioComum(
            $usuario
        );

        $this->normalizarDados(
            $request
        );

        $dados = $this->validar(
            $request,
            $usuario
        );


        $fotoAntiga =
            $usuario->foto;

        $novaFoto = null;


        /*
        |--------------------------------------------------------------------------
        | NOVA FOTO
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto')) {

            $novaFoto = $request
                ->file('foto')
                ->store(
                    'usuarios',
                    'public'
                );
        }


        try {

            DB::transaction(
                function () use (
                    $usuario,
                    $dados,
                    $novaFoto
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | DADOS DO USUÁRIO
                    |--------------------------------------------------------------------------
                    */

                    $dadosUsuario = [
                        'nome' =>
                            $dados['nome'],

                        'email' =>
                            $dados['email'],

                        /*
                         * Continua obrigatoriamente
                         * sendo usuário comum.
                         */
                        'tipo' =>
                            'usuario',

                        'cpf' =>
                            $dados['cpf'],

                        'data_nascimento' =>
                            $dados['data_nascimento'],

                        'telefone' =>
                            $dados['telefone'],

                        'saldo' =>
                            $dados['saldo'] ?? 0,
                    ];


                    /*
                    |--------------------------------------------------------------------------
                    | SENHA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !empty(
                            $dados['senha'] ?? null
                        )
                    ) {

                        $dadosUsuario['senha'] =
                            Hash::make(
                                $dados['senha']
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | FOTO
                    |--------------------------------------------------------------------------
                    */

                    if ($novaFoto) {

                        $dadosUsuario['foto'] =
                            $novaFoto;
                    }


                    $usuario->update(
                        $dadosUsuario
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | ENDEREÇO
                    |--------------------------------------------------------------------------
                    */

                    $endereco = $usuario
                        ->enderecos()
                        ->first();


                    if ($endereco) {

                        $endereco->update(
                            $this->dadosEndereco(
                                $dados
                            )
                        );

                    } else {

                        $endereco = Endereco::create(
                            $this->dadosEndereco(
                                $dados
                            )
                        );

                        $usuario
                            ->enderecos()
                            ->attach(
                                $endereco->id
                            );
                    }
                }
            );


            /*
            |--------------------------------------------------------------------------
            | REMOVE FOTO ANTIGA
            |--------------------------------------------------------------------------
            */

            if (
                $novaFoto
                &&
                $fotoAntiga
                &&
                !str_starts_with(
                    $fotoAntiga,
                    'http://'
                )
                &&
                !str_starts_with(
                    $fotoAntiga,
                    'https://'
                )
            ) {

                Storage::disk('public')
                    ->delete(
                        $fotoAntiga
                    );
            }

        } catch (Throwable $exception) {

            /*
             * Remove a foto nova se a
             * atualização do banco falhar.
             */

            if ($novaFoto) {

                Storage::disk('public')
                    ->delete(
                        $novaFoto
                    );
            }

            throw $exception;
        }


        return redirect()
            ->route(
                'admin.usuarios.index'
            )
            ->with(
                'success',
                'Usuário atualizado com sucesso.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR
    |--------------------------------------------------------------------------
    */

    public function destroy(User $usuario)
    {
        $this->garantirUsuarioComum(
            $usuario
        );


        $foto =
            $usuario->foto;


        try {

            DB::transaction(
                function () use ($usuario) {

                    /*
                     * Guarda os endereços antes
                     * de remover o usuário.
                     */

                    $enderecos =
                        $usuario
                            ->enderecos()
                            ->get();


                    /*
                    |--------------------------------------------------------------------------
                    | EXCLUI USUÁRIO
                    |--------------------------------------------------------------------------
                    */

                    $usuario->delete();


                    /*
                    |--------------------------------------------------------------------------
                    | REMOVE ENDEREÇOS ÓRFÃOS
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $enderecos
                        as
                        $endereco
                    ) {

                        /*
                         * Só remove se nenhum outro usuário
                         * estiver utilizando o endereço.
                         */

                        if (
                            !$endereco
                                ->users()
                                ->exists()
                        ) {

                            $endereco->delete();
                        }
                    }
                }
            );


            /*
            |--------------------------------------------------------------------------
            | REMOVE FOTO
            |--------------------------------------------------------------------------
            */

            if (
                $foto
                &&
                !str_starts_with(
                    $foto,
                    'http://'
                )
                &&
                !str_starts_with(
                    $foto,
                    'https://'
                )
            ) {

                Storage::disk('public')
                    ->delete(
                        $foto
                    );
            }


            return redirect()
                ->route(
                    'admin.usuarios.index'
                )
                ->with(
                    'success',
                    'Usuário excluído com sucesso.'
                );

        } catch (QueryException $exception) {

            /*
             * Isso pode acontecer caso existam
             * produtos, vendas ou outros registros
             * ligados ao usuário.
             */

            return redirect()
                ->route(
                    'admin.usuarios.index'
                )
                ->with(
                    'error',
                    'Não foi possível excluir este usuário porque existem registros vinculados a ele.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAÇÃO
    |--------------------------------------------------------------------------
    */

    private function normalizarDados(
        Request $request
    ): void {

        $request->merge([
            'cpf' =>
                preg_replace(
                    '/\D/',
                    '',
                    $request->cpf ?? ''
                ),

            'telefone' =>
                preg_replace(
                    '/\D/',
                    '',
                    $request->telefone ?? ''
                ),

            'cep' =>
                preg_replace(
                    '/\D/',
                    '',
                    $request->cep ?? ''
                ),

            'estado' =>
                strtoupper(
                    trim(
                        $request->estado ?? ''
                    )
                ),
        ]);
    }


    /*
    |--------------------------------------------------------------------------
    | VALIDAÇÃO
    |--------------------------------------------------------------------------
    */

    private function validar(
        Request $request,
        ?User $usuario = null
    ): array {

        $tabelaUsuario =
            (new User())->getTable();


        $emailUnique =
            Rule::unique(
                $tabelaUsuario,
                'email'
            );


        $cpfUnique =
            Rule::unique(
                $tabelaUsuario,
                'cpf'
            );


        if ($usuario) {

            $emailUnique->ignore(
                $usuario->id
            );

            $cpfUnique->ignore(
                $usuario->id
            );
        }


        return $request->validate(
            [
                /*
                |--------------------------------------------------------------------------
                | USUÁRIO
                |--------------------------------------------------------------------------
                */

                'nome' => [
                    'required',
                    'string',
                    'max:150',
                ],

                'email' => [
                    'required',
                    'email',
                    'max:150',
                    $emailUnique,
                ],

                'cpf' => [
                    'required',
                    'digits:11',
                    $cpfUnique,
                ],

                'data_nascimento' => [
                    'required',
                    'date',
                    'before_or_equal:today',
                ],

                'telefone' => [
                    'required',
                    'string',
                    'min:10',
                    'max:11',
                ],

                'saldo' => [
                    'nullable',
                    'numeric',
                    'min:0',
                ],

                'foto' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048',
                ],


                /*
                |--------------------------------------------------------------------------
                | SENHA
                |--------------------------------------------------------------------------
                */

                'senha' => $usuario
                    ? [
                        'nullable',
                        'string',
                        'min:6',
                        'confirmed',
                    ]
                    : [
                        'required',
                        'string',
                        'min:6',
                        'confirmed',
                    ],


                /*
                |--------------------------------------------------------------------------
                | ENDEREÇO
                |--------------------------------------------------------------------------
                */

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

                'complemento' => [
                    'nullable',
                    'string',
                    'max:100',
                ],
            ],
            [
                'nome.required' =>
                    'Informe o nome.',

                'email.required' =>
                    'Informe o e-mail.',

                'email.email' =>
                    'Informe um e-mail válido.',

                'email.unique' =>
                    'Este e-mail já está cadastrado.',

                'cpf.required' =>
                    'Informe o CPF.',

                'cpf.digits' =>
                    'O CPF deve possuir 11 números.',

                'cpf.unique' =>
                    'Este CPF já está cadastrado.',

                'data_nascimento.required' =>
                    'Informe a data de nascimento.',

                'telefone.required' =>
                    'Informe o telefone.',

                'senha.required' =>
                    'Informe uma senha.',

                'senha.confirmed' =>
                    'As senhas não correspondem.',

                'cep.required' =>
                    'Informe o CEP.',

                'cep.digits' =>
                    'O CEP deve possuir 8 números.',

                'logradouro.required' =>
                    'Informe o logradouro.',

                'numero.required' =>
                    'Informe o número.',

                'bairro.required' =>
                    'Informe o bairro.',

                'cidade.required' =>
                    'Informe a cidade.',

                'estado.required' =>
                    'Informe o estado.',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DADOS DO ENDEREÇO
    |--------------------------------------------------------------------------
    */

    private function dadosEndereco(
        array $dados
    ): array {

        return [
            'cep' =>
                $dados['cep'],

            'logradouro' =>
                $dados['logradouro'],

            'numero' =>
                $dados['numero'],

            'bairro' =>
                $dados['bairro'],

            'cidade' =>
                $dados['cidade'],

            'estado' =>
                $dados['estado'],

            'complemento' =>
                $dados['complemento']
                ?? null,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | PROTEÇÃO RF005
    |--------------------------------------------------------------------------
    |
    | Usuários administrativos pertencem ao RF006.
    |
    */

    private function garantirUsuarioComum(
        User $usuario
    ): void {

        abort_unless(
            $usuario->tipo === 'usuario',
            404
        );
    }
}