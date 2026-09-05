<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Produto;
use App\Models\ProdutoFoto;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class GerenciamentoProdutoController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTAR PRODUTOS DO USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        if (
            Auth::user()->tipo === 'administrador'
        ) {
            return redirect()
                ->route('admin.produtos.index');
        }


        $query = Produto::query()
            ->with([
                'categoria',
                'vendedor',
                'fotos',
            ])
            ->where(
                'UsuarioId',
                Auth::id()
            );


        /*
        |--------------------------------------------------------------------------
        | PESQUISA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('busca')) {

            $busca = trim(
                $request->busca
            );


            $query->where(
                function ($query) use ($busca) {

                    $query
                        ->where(
                            'nome',
                            'LIKE',
                            '%' . $busca . '%'
                        )
                        ->orWhere(
                            'descricao',
                            'LIKE',
                            '%' . $busca . '%'
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORIA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('categoria')) {

            $query->where(
                'categoria_id',
                $request->categoria
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINAÇÃO
        |--------------------------------------------------------------------------
        */

        $produtos = $query
            ->orderByDesc('id')
            ->paginate(8)
            ->withQueryString();


        /*
        |--------------------------------------------------------------------------
        | CATEGORIAS
        |--------------------------------------------------------------------------
        */

        $categorias = Categoria::orderBy(
            'nome'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | ESTATÍSTICAS
        |--------------------------------------------------------------------------
        */

        $totalProdutos = Produto::where(
            'UsuarioId',
            Auth::id()
        )->count();


        $produtosDisponiveis = Produto::where(
            'UsuarioId',
            Auth::id()
        )
            ->where(
                'quantidade',
                '>',
                0
            )
            ->count();


        $produtosSemEstoque = Produto::where(
            'UsuarioId',
            Auth::id()
        )
            ->where(
                'quantidade',
                '<=',
                0
            )
            ->count();


        $isAdmin = false;


        return view(
            'products.manage.index',
            compact(
                'produtos',
                'categorias',
                'totalProdutos',
                'produtosDisponiveis',
                'produtosSemEstoque',
                'isAdmin'
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
        /*
         * RF007:
         * administrador não pode criar produto.
         */

        abort_if(
            Auth::user()->tipo === 'administrador',
            403,
            'Administradores não podem cadastrar produtos.'
        );


        $categorias = Categoria::orderBy(
            'nome'
        )->get();


        return view(
            'products.manage.create',
            compact('categorias')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | CADASTRAR PRODUTO
    |--------------------------------------------------------------------------
    */

    public function store(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | BLOQUEAR ADMIN
        |--------------------------------------------------------------------------
        */

        abort_if(
            Auth::user()->tipo === 'administrador',
            403,
            'Administradores não podem cadastrar produtos.'
        );


        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        $categoriaTable =
            (new Categoria())->getTable();


        $dados = $request->validate(
            [
                'nome' => [
                    'required',
                    'string',
                    'max:150',
                ],

                'descricao' => [
                    'required',
                    'string',
                    'max:2000',
                ],

                'preco' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'quantidade' => [
                    'required',
                    'integer',
                    'min:0',
                ],

                'categoria_id' => [
                    'required',
                    Rule::exists(
                        $categoriaTable,
                        'id'
                    ),
                ],

                /*
                 * Cada slot envia:
                 *
                 * imagens[0]
                 * imagens[1]
                 * imagens[2]
                 * imagens[3]
                 * imagens[4]
                 */
                'imagens' => [
                    'required',
                    'array',
                    'min:1',
                    'max:5',
                ],

                'imagens.*' => [
                    'required',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:4096',
                ],

                'principal_index' => [
                    'required',
                    'integer',
                    'between:0,4',
                ],
            ],
            [
                'nome.required' =>
                    'Informe o nome do produto.',

                'descricao.required' =>
                    'Informe a descrição do produto.',

                'preco.required' =>
                    'Informe o preço do produto.',

                'preco.numeric' =>
                    'O preço informado é inválido.',

                'preco.min' =>
                    'O preço deve ser maior que zero.',

                'quantidade.required' =>
                    'Informe a quantidade disponível.',

                'quantidade.integer' =>
                    'A quantidade deve ser um número inteiro.',

                'quantidade.min' =>
                    'A quantidade não pode ser negativa.',

                'categoria_id.required' =>
                    'Selecione uma categoria.',

                'categoria_id.exists' =>
                    'A categoria selecionada não existe.',

                'imagens.required' =>
                    'Adicione pelo menos uma imagem.',

                'imagens.array' =>
                    'As imagens enviadas são inválidas.',

                'imagens.min' =>
                    'Adicione pelo menos uma imagem.',

                'imagens.max' =>
                    'Você pode adicionar no máximo 5 imagens.',

                'imagens.*.image' =>
                    'Todos os arquivos enviados devem ser imagens.',

                'imagens.*.mimes' =>
                    'As imagens devem ser JPG, JPEG, PNG ou WEBP.',

                'imagens.*.max' =>
                    'Cada imagem pode possuir no máximo 4 MB.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | PEGAR ARQUIVOS
        |--------------------------------------------------------------------------
        */

        $arquivos = $request->file(
            'imagens',
            []
        );


        /*
         * Remove posições vazias,
         * mantendo os índices.
         */
        $arquivos = array_filter(
            $arquivos,
            function ($arquivo) {
                return $arquivo !== null;
            }
        );


        /*
        |--------------------------------------------------------------------------
        | GARANTIR PELO MENOS UMA
        |--------------------------------------------------------------------------
        */

        if (count($arquivos) === 0) {

            throw ValidationException::withMessages([
                'imagens' =>
                    'Adicione pelo menos uma imagem ao produto.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | MÁXIMO DE CINCO
        |--------------------------------------------------------------------------
        */

        if (count($arquivos) > 5) {

            throw ValidationException::withMessages([
                'imagens' =>
                    'O produto pode possuir no máximo 5 imagens.',
            ]);
        }


        /*
        |--------------------------------------------------------------------------
        | PRINCIPAL
        |--------------------------------------------------------------------------
        */

        $principalIndex =
            (int) $dados['principal_index'];


        /*
         * Se o radio selecionado estiver em um slot
         * sem imagem, usamos a primeira imagem enviada.
         */
        if (
            !array_key_exists(
                $principalIndex,
                $arquivos
            )
        ) {
            $principalIndex =
                array_key_first(
                    $arquivos
                );
        }


        /*
        |--------------------------------------------------------------------------
        | SALVAR ARQUIVOS
        |--------------------------------------------------------------------------
        */

        $caminhos = [];


        try {

            foreach (
                $arquivos as $index => $arquivo
            ) {

                $caminhos[$index] =
                    $arquivo->store(
                        'produtos',
                        'public'
                    );
            }


            /*
            |--------------------------------------------------------------------------
            | BANCO
            |--------------------------------------------------------------------------
            */

            DB::transaction(
                function () use (
                    $dados,
                    $caminhos,
                    $principalIndex
                ) {

                    /*
                    |--------------------------------------------------------------------------
                    | PRODUTO
                    |--------------------------------------------------------------------------
                    */

                    $produto = Produto::create([
                        'nome' =>
                            $dados['nome'],

                        'descricao' =>
                            $dados['descricao'],

                        /*
                         * A foto principal permanece também
                         * na tabela Produtos.
                         */
                        'foto' =>
                            $caminhos[
                                $principalIndex
                            ],

                        'preco' =>
                            $dados['preco'],

                        'quantidade' =>
                            $dados['quantidade'],

                        /*
                         * O vendedor é sempre
                         * o usuário autenticado.
                         */
                        'UsuarioId' =>
                            Auth::id(),

                        'categoria_id' =>
                            $dados['categoria_id'],
                    ]);


                    /*
                    |--------------------------------------------------------------------------
                    | TODAS AS IMAGENS
                    |--------------------------------------------------------------------------
                    */

                    foreach (
                        $caminhos as $index => $caminho
                    ) {

                        ProdutoFoto::create([
                            'ProdutoId' =>
                                $produto->id,

                            'foto' =>
                                $caminho,

                            'principal' =>
                                (int) $index ===
                                (int) $principalIndex,
                        ]);
                    }
                }
            );

        } catch (Throwable $exception) {

            /*
             * Se ocorrer erro no banco,
             * exclui as imagens já salvas.
             */

            foreach (
                $caminhos as $caminho
            ) {

                Storage::disk('public')
                    ->delete(
                        $caminho
                    );
            }


            throw $exception;
        }


        return redirect()
            ->route(
                'meus-produtos.index'
            )
            ->with(
                'success',
                'Produto cadastrado com sucesso.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | TELA DE EDIÇÃO
    |--------------------------------------------------------------------------
    */

    public function edit(
        Produto $produto
    ) {
        $this->garantirDono(
            $produto
        );


        $produto->load([
            'categoria',
            'fotos',
        ]);


        $categorias = Categoria::orderBy(
            'nome'
        )->get();


        return view(
            'products.manage.edit',
            compact(
                'produto',
                'categorias'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAR PRODUTO DO USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function update(
        Request $request,
        Produto $produto
    ) {
        $this->garantirDono(
            $produto
        );


        return $this->atualizarProduto(
            $request,
            $produto,
            'meus-produtos.index'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUIR PRODUTO DO USUÁRIO
    |--------------------------------------------------------------------------
    */

    public function destroy(
        Produto $produto
    ) {
        $this->garantirDono(
            $produto
        );


        return $this->excluirProduto(
            $produto,
            'meus-produtos.index'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - LISTAR PRODUTOS
    |--------------------------------------------------------------------------
    */

    public function adminIndex(
        Request $request
    ) {
        $this->garantirAdministrador();


        $query = Produto::query()
            ->with([
                'categoria',
                'vendedor',
                'fotos',
            ]);


        /*
        |--------------------------------------------------------------------------
        | PESQUISA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('busca')) {

            $busca = trim(
                $request->busca
            );


            $query->where(
                function ($query) use ($busca) {

                    $query
                        ->where(
                            'nome',
                            'LIKE',
                            '%' . $busca . '%'
                        )
                        ->orWhere(
                            'descricao',
                            'LIKE',
                            '%' . $busca . '%'
                        )
                        ->orWhereHas(
                            'vendedor',
                            function ($query) use ($busca) {

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
                            }
                        );
                }
            );
        }


        /*
        |--------------------------------------------------------------------------
        | CATEGORIA
        |--------------------------------------------------------------------------
        */

        if ($request->filled('categoria')) {

            $query->where(
                'categoria_id',
                $request->categoria
            );
        }


        /*
        |--------------------------------------------------------------------------
        | PAGINAÇÃO
        |--------------------------------------------------------------------------
        */

        $produtos = $query
            ->orderByDesc('id')
            ->paginate(8)
            ->withQueryString();


        $categorias = Categoria::orderBy(
            'nome'
        )->get();


        /*
        |--------------------------------------------------------------------------
        | ESTATÍSTICAS
        |--------------------------------------------------------------------------
        */

        $totalProdutos =
            Produto::count();


        $produtosDisponiveis =
            Produto::where(
                'quantidade',
                '>',
                0
            )->count();


        $produtosSemEstoque =
            Produto::where(
                'quantidade',
                '<=',
                0
            )->count();


        $totalVendedores =
            Produto::query()
                ->distinct()
                ->count(
                    'UsuarioId'
                );


        $isAdmin = true;


        return view(
            'admin.products.index',
            compact(
                'produtos',
                'categorias',
                'totalProdutos',
                'produtosDisponiveis',
                'produtosSemEstoque',
                'totalVendedores',
                'isAdmin'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - ATUALIZAR
    |--------------------------------------------------------------------------
    */

    public function adminUpdate(
        Request $request,
        Produto $produto
    ) {
        $this->garantirAdministrador();


        return $this->atualizarProduto(
            $request,
            $produto,
            'admin.produtos.index'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ADMIN - EXCLUIR
    |--------------------------------------------------------------------------
    */

    public function adminDestroy(
        Produto $produto
    ) {
        $this->garantirAdministrador();


        return $this->excluirProduto(
            $produto,
            'admin.produtos.index'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ATUALIZAÇÃO COMPARTILHADA
    |--------------------------------------------------------------------------
    */

    private function atualizarProduto(
        Request $request,
        Produto $produto,
        string $redirectRoute
    ) {
        $categoriaTable =
            (new Categoria())->getTable();


        $dados = $request->validate(
            [
                'nome' => [
                    'required',
                    'string',
                    'max:150',
                ],

                'descricao' => [
                    'required',
                    'string',
                    'max:2000',
                ],

                'preco' => [
                    'required',
                    'numeric',
                    'min:0.01',
                ],

                'quantidade' => [
                    'required',
                    'integer',
                    'min:0',
                ],

                'categoria_id' => [
                    'required',
                    Rule::exists(
                        $categoriaTable,
                        'id'
                    ),
                ],

                /*
                 * Mantém compatibilidade
                 * com o formulário antigo de edição.
                 */
                'foto' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:4096',
                ],

                /*
                 * Permite que o edit posteriormente
                 * envie novas imagens também.
                 */
                'novas_imagens' => [
                    'nullable',
                    'array',
                    'max:5',
                ],

                'novas_imagens.*' => [
                    'nullable',
                    'image',
                    'mimes:jpg,jpeg,png,webp',
                    'max:4096',
                ],
            ]
        );


        $produto->load(
            'fotos'
        );


        /*
        |--------------------------------------------------------------------------
        | DADOS BÁSICOS
        |--------------------------------------------------------------------------
        */

        $produto->update([
            'nome' =>
                $dados['nome'],

            'descricao' =>
                $dados['descricao'],

            'preco' =>
                $dados['preco'],

            'quantidade' =>
                $dados['quantidade'],

            'categoria_id' =>
                $dados['categoria_id'],
        ]);


        /*
        |--------------------------------------------------------------------------
        | SUBSTITUIR FOTO PRINCIPAL
        |--------------------------------------------------------------------------
        */

        if ($request->hasFile('foto')) {

            $novaFoto = $request
                ->file('foto')
                ->store(
                    'produtos',
                    'public'
                );


            $fotoAntiga =
                $produto->foto;


            try {

                DB::transaction(
                    function () use (
                        $produto,
                        $novaFoto
                    ) {

                        /*
                         * Nenhuma das antigas permanece principal.
                         */
                        $produto
                            ->fotos()
                            ->update([
                                'principal' => false,
                            ]);


                        /*
                         * Cria nova principal.
                         */
                        ProdutoFoto::create([
                            'ProdutoId' =>
                                $produto->id,

                            'foto' =>
                                $novaFoto,

                            'principal' =>
                                true,
                        ]);


                        /*
                         * Compatibilidade com landing.
                         */
                        $produto->update([
                            'foto' =>
                                $novaFoto,
                        ]);
                    }
                );


                /*
                 * Não apagamos automaticamente uma imagem antiga
                 * da galeria, pois ela pode continuar sendo uma
                 * imagem secundária do produto.
                 */

            } catch (Throwable $exception) {

                Storage::disk('public')
                    ->delete(
                        $novaFoto
                    );


                throw $exception;
            }
        }


        /*
        |--------------------------------------------------------------------------
        | ADICIONAR NOVAS IMAGENS
        |--------------------------------------------------------------------------
        */

        $novasImagens =
            $request->file(
                'novas_imagens',
                []
            );


        $novasImagens =
            array_filter(
                $novasImagens
            );


        if (count($novasImagens) > 0) {

            $produto->load(
                'fotos'
            );


            $totalAtual =
                $produto
                    ->fotos
                    ->count();


            if (
                $totalAtual +
                count($novasImagens)
                >
                5
            ) {

                throw ValidationException::withMessages([
                    'novas_imagens' =>
                        'O produto pode possuir no máximo 5 imagens.',
                ]);
            }


            $novosCaminhos =
                [];


            try {

                foreach (
                    $novasImagens as $arquivo
                ) {

                    $novosCaminhos[] =
                        $arquivo->store(
                            'produtos',
                            'public'
                        );
                }


                DB::transaction(
                    function () use (
                        $produto,
                        $novosCaminhos
                    ) {

                        foreach (
                            $novosCaminhos as $caminho
                        ) {

                            ProdutoFoto::create([
                                'ProdutoId' =>
                                    $produto->id,

                                'foto' =>
                                    $caminho,

                                'principal' =>
                                    false,
                            ]);
                        }
                    }
                );

            } catch (Throwable $exception) {

                foreach (
                    $novosCaminhos as $caminho
                ) {

                    Storage::disk('public')
                        ->delete(
                            $caminho
                        );
                }


                throw $exception;
            }
        }


        return redirect()
            ->route(
                $redirectRoute
            )
            ->with(
                'success',
                'Produto atualizado com sucesso.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | EXCLUSÃO COMPARTILHADA
    |--------------------------------------------------------------------------
    */

    private function excluirProduto(
        Produto $produto,
        string $redirectRoute
    ) {
        $produto->load(
            'fotos'
        );


        /*
         * Guarda todos os caminhos antes
         * da exclusão.
         */

        $arquivos = collect([
            $produto->foto,
        ])
            ->merge(
                $produto
                    ->fotos
                    ->pluck('foto')
            )
            ->filter()
            ->unique();


        try {

            DB::transaction(
                function () use ($produto) {

                    /*
                     * Remove registros da galeria.
                     */
                    $produto
                        ->fotos()
                        ->delete();


                    /*
                     * Remove produto.
                     */
                    $produto->delete();
                }
            );


            /*
             * Só remove arquivos se a
             * exclusão do banco der certo.
             */

            foreach (
                $arquivos as $arquivo
            ) {

                if (
                    !$this->imagemExterna(
                        $arquivo
                    )
                ) {

                    Storage::disk('public')
                        ->delete(
                            $arquivo
                        );
                }
            }


            return redirect()
                ->route(
                    $redirectRoute
                )
                ->with(
                    'success',
                    'Produto excluído com sucesso.'
                );

        } catch (QueryException $exception) {

            return redirect()
                ->route(
                    $redirectRoute
                )
                ->with(
                    'error',
                    'Não foi possível excluir este produto porque existem registros vinculados a ele.'
                );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | GARANTIR DONO
    |--------------------------------------------------------------------------
    */

    private function garantirDono(
        Produto $produto
    ): void {

        abort_if(
            Auth::user()->tipo ===
                'administrador',
            403
        );


        abort_unless(
            (int) $produto->UsuarioId
            ===
            (int) Auth::id(),
            403,
            'Você não possui permissão para gerenciar este produto.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | GARANTIR ADMIN
    |--------------------------------------------------------------------------
    */

    private function garantirAdministrador(): void
    {
        abort_unless(
            Auth::check()
            &&
            Auth::user()->tipo ===
                'administrador',
            403,
            'Acesso não autorizado.'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | IMAGEM EXTERNA
    |--------------------------------------------------------------------------
    */

    private function imagemExterna(
        ?string $foto
    ): bool {

        if (!$foto) {
            return false;
        }


        return
            str_starts_with(
                $foto,
                'http://'
            )
            ||
            str_starts_with(
                $foto,
                'https://'
            );
    }
}