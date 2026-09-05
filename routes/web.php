<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\AdminUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AdminAdministratorController;
use App\Http\Controllers\GerenciamentoProdutoController;
use App\Http\Controllers\ViaCepController;
use App\Http\Controllers\ProdutoIndexController;
use App\Http\Controllers\VendasController;

use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| LANDING PAGE
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [
        LandingController::class,
        'index'
    ]
)
    ->name('landing');


/*
|--------------------------------------------------------------------------
| PÁGINA INDIVIDUAL DO PRODUTO
|--------------------------------------------------------------------------
*/

Route::get(
    '/produto/{id}',
    [
        ProdutoController::class,
        'show'
    ]
)
    ->name('produto.show');


/*
|--------------------------------------------------------------------------
| LISTAGEM DE PRODUTOS
|--------------------------------------------------------------------------
*/

Route::get(
    '/produtos',
    [
        ProdutoIndexController::class,
        'index'
    ]
)
    ->name('produtos.index');


/*
|--------------------------------------------------------------------------
| POSTS
|--------------------------------------------------------------------------
*/

Route::get(
    '/posts',
    [
        LandingController::class,
        'index'
    ]
)
    ->name('posts');


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    [
        DashboardController::class,
        'index'
    ]
)
    ->middleware([
        'auth',
        'verified'
    ])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| CEP
|--------------------------------------------------------------------------
*/

Route::get(
    '/cep/{cep}',
    [
        ViaCepController::class,
        'consultar'
    ]
)
    ->name('api.cep');


/*
|--------------------------------------------------------------------------
| ROTAS ADMINISTRATIVAS
|--------------------------------------------------------------------------
*/

Route::middleware([
    'auth',
    'admin',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | USUÁRIOS
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'usuarios',
            AdminUserController::class
        )
            ->parameters([
                'usuarios' => 'usuario',
            ]);


        /*
        |--------------------------------------------------------------------------
        | ADMINISTRADORES
        |--------------------------------------------------------------------------
        */

        Route::resource(
            'administradores',
            AdminAdministratorController::class
        )
            ->parameters([
                'administradores' => 'administrador',
            ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUTOS - ADMIN
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/produtos',
            [
                GerenciamentoProdutoController::class,
                'adminIndex'
            ]
        )
            ->name('produtos.index');


        Route::put(
            '/produtos/{produto}',
            [
                GerenciamentoProdutoController::class,
                'adminUpdate'
            ]
        )
            ->name('produtos.update');


        Route::delete(
            '/produtos/{produto}',
            [
                GerenciamentoProdutoController::class,
                'adminDestroy'
            ]
        )
            ->name('produtos.destroy');


        /*
        |--------------------------------------------------------------------------
        | RF009 - VENDAS - ADMIN
        |--------------------------------------------------------------------------
        */

        /*
         * Histórico de todas as vendas.
         *
         * URL:
         * /admin/vendas
         *
         * Nome:
         * admin.vendas.index
         */

        Route::get(
            '/vendas',
            [
                VendasController::class,
                'adminIndex'
            ]
        )
            ->name('vendas.index');


        /*
         * Relatório PDF.
         *
         * URL:
         * /admin/vendas/relatorio/pdf
         *
         * Nome:
         * admin.vendas.relatorio.pdf
         */

        Route::get(
            '/vendas/relatorio/pdf',
            [
                VendasController::class,
                'adminRelatorioPdf'
            ]
        )
            ->name('vendas.relatorio.pdf');


        /*
         * Relatório XLSX.
         *
         * Exclusivo do administrador.
         *
         * URL:
         * /admin/vendas/relatorio/xlsx
         *
         * Nome:
         * admin.vendas.relatorio.xlsx
         */

        Route::get(
            '/vendas/relatorio/xlsx',
            [
                VendasController::class,
                'adminRelatorioXlsx'
            ]
        )
            ->name('vendas.relatorio.xlsx');

    });


/*
|--------------------------------------------------------------------------
| GERENCIAMENTO DE PRODUTOS - USUÁRIO
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | LISTAR MEUS PRODUTOS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/meus-produtos',
            [
                GerenciamentoProdutoController::class,
                'index'
            ]
        )
            ->name(
                'meus-produtos.index'
            );


        /*
        |--------------------------------------------------------------------------
        | CADASTRAR PRODUTO
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/meus-produtos/create',
            [
                GerenciamentoProdutoController::class,
                'create'
            ]
        )
            ->name(
                'meus-produtos.create'
            );


        /*
        |--------------------------------------------------------------------------
        | SALVAR PRODUTO
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/meus-produtos',
            [
                GerenciamentoProdutoController::class,
                'store'
            ]
        )
            ->name(
                'meus-produtos.store'
            );


        /*
        |--------------------------------------------------------------------------
        | EDITAR PRODUTO
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/meus-produtos/{produto}/edit',
            [
                GerenciamentoProdutoController::class,
                'edit'
            ]
        )
            ->name(
                'meus-produtos.edit'
            );


        /*
        |--------------------------------------------------------------------------
        | ATUALIZAR PRODUTO
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/meus-produtos/{produto}',
            [
                GerenciamentoProdutoController::class,
                'update'
            ]
        )
            ->name(
                'meus-produtos.update'
            );


        /*
        |--------------------------------------------------------------------------
        | EXCLUIR PRODUTO
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/meus-produtos/{produto}',
            [
                GerenciamentoProdutoController::class,
                'destroy'
            ]
        )
            ->name(
                'meus-produtos.destroy'
            );

    });


/*
|--------------------------------------------------------------------------
| RF009 - VENDAS - USUÁRIO
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | HISTÓRICO DE VENDAS
        |--------------------------------------------------------------------------
        |
        | Usuário normal vê somente os produtos que ele vendeu.
        |
        | URL:
        | /vendas
        |
        | Nome:
        | vendas.index
        |
        */

        Route::get(
            '/vendas',
            [
                VendasController::class,
                'index'
            ]
        )
            ->name(
                'vendas.index'
            );


        /*
        |--------------------------------------------------------------------------
        | RELATÓRIO PDF
        |--------------------------------------------------------------------------
        |
        | URL:
        | /vendas/relatorio/pdf
        |
        | Nome:
        | vendas.relatorio.pdf
        |
        */

        Route::get(
            '/vendas/relatorio/pdf',
            [
                VendasController::class,
                'relatorioPdf'
            ]
        )
            ->name(
                'vendas.relatorio.pdf'
            );

    });


/*
|--------------------------------------------------------------------------
| CARRINHO
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | VISUALIZAR CARRINHO
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/carrinho',
            [
                CarrinhoController::class,
                'index'
            ]
        )
            ->name(
                'carrinho.index'
            );


        /*
        |--------------------------------------------------------------------------
        | ADICIONAR ITEM
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/carrinho/adicionar/{produto}',
            [
                CarrinhoController::class,
                'adicionar'
            ]
        )
            ->name(
                'carrinho.adicionar'
            );


        /*
        |--------------------------------------------------------------------------
        | ATUALIZAR QUANTIDADE
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/carrinho/item/{item}',
            [
                CarrinhoController::class,
                'atualizar'
            ]
        )
            ->name(
                'carrinho.atualizar'
            );


        /*
        |--------------------------------------------------------------------------
        | REMOVER ITEM
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/carrinho/item/{item}',
            [
                CarrinhoController::class,
                'remover'
            ]
        )
            ->name(
                'carrinho.remover'
            );


        /*
        |--------------------------------------------------------------------------
        | LIMPAR CARRINHO
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/carrinho',
            [
                CarrinhoController::class,
                'limpar'
            ]
        )
            ->name(
                'carrinho.limpar'
            );

    });


/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | PÁGINA DO PERFIL
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profile',
            [
                ProfileController::class,
                'edit'
            ]
        )
            ->name(
                'profile.edit'
            );


        /*
        |--------------------------------------------------------------------------
        | ATUALIZAR INFORMAÇÕES PESSOAIS
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/profile',
            [
                ProfileController::class,
                'update'
            ]
        )
            ->name(
                'profile.update'
            );


        /*
        |--------------------------------------------------------------------------
        | ADICIONAR ENDEREÇO
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/profile/address',
            [
                ProfileController::class,
                'storeAddress'
            ]
        )
            ->name(
                'profile.address.store'
            );


        /*
        |--------------------------------------------------------------------------
        | ALTERAR SENHA
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/profile/password',
            [
                ProfileController::class,
                'updatePassword'
            ]
        )
            ->name(
                'profile.password'
            );


        /*
        |--------------------------------------------------------------------------
        | EXCLUIR CONTA
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/profile',
            [
                ProfileController::class,
                'destroy'
            ]
        )
            ->name(
                'profile.destroy'
            );

    });


/*
|--------------------------------------------------------------------------
| ROTAS DO BREEZE
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';