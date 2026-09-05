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

class AdminAdministratorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTAR ADMINISTRADORES
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = User::query()
            ->where('tipo', 'administrador')
            ->with([
                'creator',
                'enderecos',
            ]);


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


            $query->where(
                function ($query) use (
                    $busca,
                    $cpfBusca
                ) {

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
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINAÇÃO
        |--------------------------------------------------------------------------
        */

        $administradores = $query
            ->orderByDesc('created_at')
            ->paginate(6)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | ESTATÍSTICAS
        |--------------------------------------------------------------------------
        */

        $totalAdministradores =
            User::where(
                'tipo',
                'administrador'
            )
            ->count();


        $criadosPorMim =
            User::where(
                'tipo',
                'administrador'
            )
            ->where(
                'criador_id',
                Auth::id()
            )
            ->count();


        $novosHoje =
            User::where(
                'tipo',
                'administrador'
            )
            ->whereDate(
                'created_at',
                today()
            )
            ->count();


        return view(
            'admin.Administrator',
            compact(
                'administradores',
                'totalAdministradores',
                'criadosPorMim',
                'novosHoje'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULÁRIO DE CADASTRO
    |--------------------------------------------------------------------------
    */

    public function create()
    {
        return view(
            'admin.administrators.create'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CADASTRAR ADMINISTRADOR
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        $this->normalizarDados(
            $request
        );


        $dados =
            $this->validar(
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
                    | ADMINISTRADOR
                    |--------------------------------------------------------------------------
                    */

                    $administrador =
                        User::create([
                            'nome' =>
                                $dados['nome'],

                            'email' =>
                                $dados['email'],

                            'senha' =>
                                Hash::make(
                                    $dados['senha']
                                ),

                            'tipo' =>
                                'administrador',

                            'cpf' =>
                                $dados['cpf'],

                            'data_nascimento' =>
                                $dados['data_nascimento'],

                            'telefone' =>
                                $dados['telefone'],

                            'saldo' =>
                                0,

                            'foto' =>
                                $foto,

                            /*
                             * Registra quem criou
                             * este administrador.
                             */
                            'criador_id' =>
                                Auth::id(),
                        ]);


                    /*
                    |--------------------------------------------------------------------------
                    | ENDEREÇO
                    |--------------------------------------------------------------------------
                    */

                    $endereco =
                        Endereco::create(
                            $this->dadosEndereco(
                                $dados
                            )
                        );


                    /*
                    |--------------------------------------------------------------------------
                    | ADMINISTRADOR x ENDEREÇO
                    |--------------------------------------------------------------------------
                    */

                    $administrador
                        ->enderecos()
                        ->attach(
                            $endereco->id
                        );
                }
            );

        } catch (Throwable $exception) {

            if ($foto) {

                Storage::disk('public')
                    ->delete(
                        $foto
                    );
            }


            throw $exception;
        }


        return redirect()
            ->route(
                'admin.administradores.index'
            )
            ->with(
                'success',
                'Administrador cadastrado com sucesso.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | VISUALIZAR
    |--------------------------------------------------------------------------
    */

    public function show(
        User $administrador
    ) {

        $this->garantirAdministrador(
            $administrador
        );


        $administrador->load([
            'creator',
            'enderecos',
        ]);


        return view(
            'admin.administrators.show',
            compact(
                'administrador'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | FORMULÁRIO DE EDIÇÃO
    |--------------------------------------------------------------------------
    */

    public function edit(
        User $administrador
    ) {

        $this->garantirAdministrador(
            $administrador
        );


        $this->autorizarGerenciamento(
            $administrador
        );


        $administrador->load(
            'enderecos'
        );


        return view(
            'admin.administrators.edit',
            compact(
                'administrador'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        User $administrador
    ) {

        $this->garantirAdministrador(
            $administrador
        );


        /*
         * RF006:
         * só pode editar a si mesmo
         * ou quem foi criado por ele.
         */
        $this->autorizarGerenciamento(
            $administrador
        );


        $this->normalizarDados(
            $request
        );


        $dados =
            $this->validar(
                $request,
                $administrador
            );


        $fotoAntiga =
            $administrador->foto;


        $novaFoto = null;


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
                    $administrador,
                    $dados,
                    $novaFoto
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | DADOS
                    |--------------------------------------------------------------------------
                    */

                    $dadosAdministrador = [
                        'nome' =>
                            $dados['nome'],

                        'email' =>
                            $dados['email'],

                        'cpf' =>
                            $dados['cpf'],

                        'data_nascimento' =>
                            $dados['data_nascimento'],

                        'telefone' =>
                            $dados['telefone'],

                        /*
                         * Nunca permitimos mudar
                         * esse registro para usuário.
                         */
                        'tipo' =>
                            'administrador',
                    ];


                    /*
                    |--------------------------------------------------------------------------
                    | NOVA SENHA
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !empty(
                            $dados['senha'] ?? null
                        )
                    ) {

                        $dadosAdministrador['senha'] =
                            Hash::make(
                                $dados['senha']
                            );
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | NOVA FOTO
                    |--------------------------------------------------------------------------
                    */

                    if ($novaFoto) {

                        $dadosAdministrador['foto'] =
                            $novaFoto;
                    }


                    $administrador->update(
                        $dadosAdministrador
                    );


                    /*
                    |--------------------------------------------------------------------------
                    | ENDEREÇO
                    |--------------------------------------------------------------------------
                    */

                    $endereco =
                        $administrador
                            ->enderecos()
                            ->first();


                    if ($endereco) {

                        $endereco->update(
                            $this->dadosEndereco(
                                $dados
                            )
                        );

                    } else {

                        $endereco =
                            Endereco::create(
                                $this->dadosEndereco(
                                    $dados
                                )
                            );


                        $administrador
                            ->enderecos()
                            ->attach(
                                $endereco->id
                            );
                    }
                }
            );


            /*
            |--------------------------------------------------------------------------
            | APAGA FOTO ANTIGA
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
                'admin.administradores.index'
            )
            ->with(
                'success',
                'Administrador atualizado com sucesso.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Request $request,
        User $administrador
    ) {

        $this->garantirAdministrador(
            $administrador
        );


        $this->autorizarGerenciamento(
            $administrador
        );


        $excluindoPropriaConta =
            Auth::id()
            ===
            $administrador->id;


        $foto =
            $administrador->foto;


        try {

            DB::transaction(
                function () use (
                    $administrador
                ) {

                    $enderecos =
                        $administrador
                            ->enderecos()
                            ->get();


                    /*
                    |--------------------------------------------------------------------------
                    | DESVINCULA ENDEREÇO
                    |--------------------------------------------------------------------------
                    */

                    $administrador
                        ->enderecos()
                        ->detach();


                    /*
                    |--------------------------------------------------------------------------
                    | EXCLUI ADMINISTRADOR
                    |--------------------------------------------------------------------------
                    */

                    $administrador->delete();


                    /*
                    |--------------------------------------------------------------------------
                    | ENDEREÇOS ÓRFÃOS
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $enderecos
                        as
                        $endereco
                    ) {

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
            | FOTO
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


            /*
            |--------------------------------------------------------------------------
            | EXCLUIU A PRÓPRIA CONTA
            |--------------------------------------------------------------------------
            */

            if ($excluindoPropriaConta) {

                Auth::logout();


                $request
                    ->session()
                    ->invalidate();


                $request
                    ->session()
                    ->regenerateToken();


                return redirect()
                    ->route('landing')
                    ->with(
                        'success',
                        'Sua conta de administrador foi excluída.'
                    );
            }


            return redirect()
                ->route(
                    'admin.administradores.index'
                )
                ->with(
                    'success',
                    'Administrador excluído com sucesso.'
                );

        } catch (QueryException $exception) {

            return redirect()
                ->route(
                    'admin.administradores.index'
                )
                ->with(
                    'error',
                    'Não foi possível excluir este administrador porque existem registros vinculados a ele.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | NORMALIZAR DADOS
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
    | VALIDAR
    |--------------------------------------------------------------------------
    */

    private function validar(
        Request $request,
        ?User $administrador = null
    ): array {

        $tabela =
            (new User())->getTable();


        $emailUnique =
            Rule::unique(
                $tabela,
                'email'
            );


        $cpfUnique =
            Rule::unique(
                $tabela,
                'cpf'
            );


        if ($administrador) {

            $emailUnique->ignore(
                $administrador->id
            );


            $cpfUnique->ignore(
                $administrador->id
            );
        }


        return $request->validate(
            [
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

                'foto' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:2048',
                ],

                'senha' =>
                    $administrador
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
    | ENDEREÇO
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


    private function garantirAdministrador(
        User $administrador
    ): void {

        abort_unless(
            $administrador->tipo
            ===
            'administrador',
            404
        );
    }



    private function autorizarGerenciamento(
        User $administrador
    ): void {

        $usuarioLogado =
            Auth::id();


        $propriaConta =
            $administrador->id
            ===
            $usuarioLogado;


        $foiCriadoPorMim =
            $administrador->criador_id
            ===
            $usuarioLogado;


        abort_unless(
            $propriaConta
            ||
            $foiCriadoPorMim,
            403,
            'Você não possui permissão para editar ou excluir este administrador.'
        );
    }
}