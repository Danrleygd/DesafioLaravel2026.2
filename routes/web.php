<?php

use Illuminate\Support\Facades\Route;

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
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ComprasController;
use App\Http\Controllers\AdminEmailController;


/*
|--------------------------------------------------------------------------
| PÁGINA INICIAL
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [
        LandingController::class,
        'index',
    ]
)->name('landing');


/*
|--------------------------------------------------------------------------
| PRODUTO
|--------------------------------------------------------------------------
*/

Route::get(
    '/produto/{id}',
    [
        ProdutoController::class,
        'show',
    ]
)->name('produto.show');


/*
|--------------------------------------------------------------------------
| LISTAGEM DE PRODUTOS
|--------------------------------------------------------------------------
*/

Route::get(
    '/produtos',
    [
        ProdutoIndexController::class,
        'index',
    ]
)->name('produtos.index');


/*
|--------------------------------------------------------------------------
| POSTS
|--------------------------------------------------------------------------
*/

Route::get(
    '/posts',
    [
        LandingController::class,
        'index',
    ]
)->name('posts');


/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/

Route::get(
    '/dashboard',
    [
        DashboardController::class,
        'index',
    ]
)
    ->middleware([
        'auth',
        'verified',
    ])
    ->name('dashboard');


/*
|--------------------------------------------------------------------------
| VIA CEP
|--------------------------------------------------------------------------
*/

Route::get(
    '/cep/{cep}',
    [
        ViaCepController::class,
        'consultar',
    ]
)->name('api.cep');


/*
|--------------------------------------------------------------------------
| ÁREA ADMINISTRATIVA
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
        )->parameters([
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
        )->parameters([
            'administradores' => 'administrador',
        ]);


        /*
        |--------------------------------------------------------------------------
        | PRODUTOS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/produtos',
            [
                GerenciamentoProdutoController::class,
                'adminIndex',
            ]
        )->name('produtos.index');


        Route::put(
            '/produtos/{produto}',
            [
                GerenciamentoProdutoController::class,
                'adminUpdate',
            ]
        )->name('produtos.update');


        Route::delete(
            '/produtos/{produto}',
            [
                GerenciamentoProdutoController::class,
                'adminDestroy',
            ]
        )->name('produtos.destroy');


        /*
        |--------------------------------------------------------------------------
        | RF009 - HISTÓRICO DE VENDAS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/vendas',
            [
                VendasController::class,
                'adminIndex',
            ]
        )->name('vendas.index');


        Route::get(
            '/vendas/relatorio/pdf',
            [
                VendasController::class,
                'adminPdf',
            ]
        )->name(
            'vendas.relatorio.pdf'
        );


        Route::get(
            '/vendas/relatorio/xlsx',
            [
                VendasController::class,
                'adminXlsx',
            ]
        )->name(
            'vendas.relatorio.xlsx'
        );


        /*
        |--------------------------------------------------------------------------
        | RF011 - SISTEMA DE E-MAIL
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/emails',
            [
                AdminEmailController::class,
                'index',
            ]
        )->name(
            'emails.index'
        );


        Route::post(
            '/emails/enviar',
            [
                AdminEmailController::class,
                'send',
            ]
        )->name(
            'emails.send'
        );
    });


/*
|--------------------------------------------------------------------------
| MEUS PRODUTOS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | INDEX
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/meus-produtos',
            [
                GerenciamentoProdutoController::class,
                'index',
            ]
        )->name(
            'meus-produtos.index'
        );


        /*
        |--------------------------------------------------------------------------
        | CREATE
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/meus-produtos/create',
            [
                GerenciamentoProdutoController::class,
                'create',
            ]
        )->name(
            'meus-produtos.create'
        );


        /*
        |--------------------------------------------------------------------------
        | STORE
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/meus-produtos',
            [
                GerenciamentoProdutoController::class,
                'store',
            ]
        )->name(
            'meus-produtos.store'
        );


        /*
        |--------------------------------------------------------------------------
        | EDIT
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/meus-produtos/{produto}/edit',
            [
                GerenciamentoProdutoController::class,
                'edit',
            ]
        )->name(
            'meus-produtos.edit'
        );


        /*
        |--------------------------------------------------------------------------
        | UPDATE
        |--------------------------------------------------------------------------
        */

        Route::put(
            '/meus-produtos/{produto}',
            [
                GerenciamentoProdutoController::class,
                'update',
            ]
        )->name(
            'meus-produtos.update'
        );


        /*
        |--------------------------------------------------------------------------
        | DELETE
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/meus-produtos/{produto}',
            [
                GerenciamentoProdutoController::class,
                'destroy',
            ]
        )->name(
            'meus-produtos.destroy'
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
        | CARRINHO
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/carrinho',
            [
                CarrinhoController::class,
                'index',
            ]
        )->name(
            'carrinho.index'
        );


        /*
        |--------------------------------------------------------------------------
        | ADICIONAR
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/carrinho/adicionar/{produto}',
            [
                CarrinhoController::class,
                'adicionar',
            ]
        )->name(
            'carrinho.adicionar'
        );


        /*
        |--------------------------------------------------------------------------
        | ATUALIZAR
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/carrinho/item/{item}',
            [
                CarrinhoController::class,
                'atualizar',
            ]
        )->name(
            'carrinho.atualizar'
        );


        /*
        |--------------------------------------------------------------------------
        | REMOVER
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/carrinho/item/{item}',
            [
                CarrinhoController::class,
                'remover',
            ]
        )->name(
            'carrinho.remover'
        );


        /*
        |--------------------------------------------------------------------------
        | LIMPAR
        |--------------------------------------------------------------------------
        */

        Route::delete(
            '/carrinho',
            [
                CarrinhoController::class,
                'limpar',
            ]
        )->name(
            'carrinho.limpar'
        );
    });


/*
|--------------------------------------------------------------------------
| RF004 - CHECKOUT PAGBANK
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('checkout')
    ->name('checkout.')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | SELECIONAR PRODUTOS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/itens/selecionar',
            [
                CheckoutController::class,
                'selecionarItens',
            ]
        )->name(
            'itens.selecionar'
        );


        /*
        |--------------------------------------------------------------------------
        | ENDEREÇO
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/endereco',
            [
                CheckoutController::class,
                'endereco',
            ]
        )->name(
            'endereco'
        );


        /*
        |--------------------------------------------------------------------------
        | SELECIONAR ENDEREÇO
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/endereco/selecionar',
            [
                CheckoutController::class,
                'selecionarEndereco',
            ]
        )->name(
            'endereco.selecionar'
        );


        /*
        |--------------------------------------------------------------------------
        | NOVO ENDEREÇO
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/endereco/novo',
            [
                CheckoutController::class,
                'storeEndereco',
            ]
        )->name(
            'endereco.store'
        );


        /*
        |--------------------------------------------------------------------------
        | PAGAMENTO
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/pagamento',
            [
                CheckoutController::class,
                'pagamento',
            ]
        )->name(
            'pagamento'
        );


        /*
        |--------------------------------------------------------------------------
        | PAGBANK
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/pagamento/pagbank',
            [
                CheckoutController::class,
                'criarPagamentoPagBank',
            ]
        )->name(
            'pagamento.pagbank'
        );


        /*
        |--------------------------------------------------------------------------
        | RETORNO
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/retorno',
            [
                CheckoutController::class,
                'retorno',
            ]
        )->name(
            'retorno'
        );


        /*
        |--------------------------------------------------------------------------
        | VERIFICAR PAGAMENTO
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/retorno/verificar',
            [
                CheckoutController::class,
                'verificarPagamento',
            ]
        )->name(
            'retorno.verificar'
        );


        /*
        |--------------------------------------------------------------------------
        | CONFIRMAÇÃO
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/confirmacao/{venda}',
            [
                CheckoutController::class,
                'confirmacao',
            ]
        )
            ->whereNumber('venda')
            ->name(
                'confirmacao'
            );
    });


/*
|--------------------------------------------------------------------------
| RF008 - HISTÓRICO DE COMPRAS
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | COMPRAS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/compras',
            [
                ComprasController::class,
                'index',
            ]
        )->name(
            'compras.index'
        );


        /*
        |--------------------------------------------------------------------------
        | PDF DAS COMPRAS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/compras/relatorio/pdf',
            [
                ComprasController::class,
                'pdf',
            ]
        )->name(
            'compras.pdf'
        );
    });


/*
|--------------------------------------------------------------------------
| RF009 - HISTÓRICO DE VENDAS DO USUÁRIO
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {


        /*
        |--------------------------------------------------------------------------
        | VENDAS
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/vendas',
            [
                VendasController::class,
                'index',
            ]
        )->name(
            'vendas.index'
        );


        /*
        |--------------------------------------------------------------------------
        | PDF
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/vendas/relatorio/pdf',
            [
                VendasController::class,
                'pdf',
            ]
        )->name(
            'vendas.relatorio.pdf'
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
        | EDITAR PERFIL
        |--------------------------------------------------------------------------
        */

        Route::get(
            '/profile',
            [
                ProfileController::class,
                'edit',
            ]
        )->name(
            'profile.edit'
        );


        /*
        |--------------------------------------------------------------------------
        | ATUALIZAR PERFIL
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/profile',
            [
                ProfileController::class,
                'update',
            ]
        )->name(
            'profile.update'
        );


        /*
        |--------------------------------------------------------------------------
        | ENDEREÇO
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/profile/address',
            [
                ProfileController::class,
                'storeAddress',
            ]
        )->name(
            'profile.address.store'
        );


        /*
        |--------------------------------------------------------------------------
        | SENHA
        |--------------------------------------------------------------------------
        */

        Route::patch(
            '/profile/password',
            [
                ProfileController::class,
                'updatePassword',
            ]
        )->name(
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
                'destroy',
            ]
        )->name(
            'profile.destroy'
        );
    });


/*
|--------------------------------------------------------------------------
| BREEZE / RF010
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';