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


/*
|--------------------------------------------------------------------------
| LOJA
|--------------------------------------------------------------------------
*/

Route::get(
    '/',
    [
        LandingController::class,
        'index',
    ]
)->name('landing');


Route::get(
    '/produto/{id}',
    [
        ProdutoController::class,
        'show',
    ]
)->name('produto.show');


Route::get(
    '/produtos',
    [
        ProdutoIndexController::class,
        'index',
    ]
)->name('produtos.index');


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
|
| Mantida esta rota para não quebrar partes antigas do projeto.
| A rota oficial /api/cep/{cep} também ficará em routes/api.php.
|
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
| ADMIN
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
        | HISTÓRICO DE VENDAS - ADMIN - RF009
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
        )->name('vendas.relatorio.pdf');


        Route::get(
            '/vendas/relatorio/xlsx',
            [
                VendasController::class,
                'adminXlsx',
            ]
        )->name('vendas.relatorio.xlsx');
    });


/*
|--------------------------------------------------------------------------
| PRODUTOS DO USUÁRIO
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        Route::get(
            '/meus-produtos',
            [
                GerenciamentoProdutoController::class,
                'index',
            ]
        )->name('meus-produtos.index');


        Route::get(
            '/meus-produtos/create',
            [
                GerenciamentoProdutoController::class,
                'create',
            ]
        )->name('meus-produtos.create');


        Route::post(
            '/meus-produtos',
            [
                GerenciamentoProdutoController::class,
                'store',
            ]
        )->name('meus-produtos.store');


        Route::get(
            '/meus-produtos/{produto}/edit',
            [
                GerenciamentoProdutoController::class,
                'edit',
            ]
        )->name('meus-produtos.edit');


        Route::put(
            '/meus-produtos/{produto}',
            [
                GerenciamentoProdutoController::class,
                'update',
            ]
        )->name('meus-produtos.update');


        Route::delete(
            '/meus-produtos/{produto}',
            [
                GerenciamentoProdutoController::class,
                'destroy',
            ]
        )->name('meus-produtos.destroy');
    });


/*
|--------------------------------------------------------------------------
| CARRINHO
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        Route::get(
            '/carrinho',
            [
                CarrinhoController::class,
                'index',
            ]
        )->name('carrinho.index');


        Route::post(
            '/carrinho/adicionar/{produto}',
            [
                CarrinhoController::class,
                'adicionar',
            ]
        )->name('carrinho.adicionar');


        Route::patch(
            '/carrinho/item/{item}',
            [
                CarrinhoController::class,
                'atualizar',
            ]
        )->name('carrinho.atualizar');


        Route::delete(
            '/carrinho/item/{item}',
            [
                CarrinhoController::class,
                'remover',
            ]
        )->name('carrinho.remover');


        Route::delete(
            '/carrinho',
            [
                CarrinhoController::class,
                'limpar',
            ]
        )->name('carrinho.limpar');
    });


/*
|--------------------------------------------------------------------------
| CHECKOUT PAGBANK - RF004
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->prefix('checkout')
    ->name('checkout.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | SELEÇÃO DOS ITENS
        |--------------------------------------------------------------------------
        */

        Route::post(
            '/itens/selecionar',
            [
                CheckoutController::class,
                'selecionarItens',
            ]
        )->name('itens.selecionar');


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
        )->name('endereco');


        Route::post(
            '/endereco/selecionar',
            [
                CheckoutController::class,
                'selecionarEndereco',
            ]
        )->name('endereco.selecionar');


        Route::post(
            '/endereco/novo',
            [
                CheckoutController::class,
                'storeEndereco',
            ]
        )->name('endereco.store');


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
        )->name('pagamento');


        Route::post(
            '/pagamento/pagbank',
            [
                CheckoutController::class,
                'criarPagamentoPagBank',
            ]
        )->name('pagamento.pagbank');


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
        )->name('retorno');


        Route::get(
            '/retorno/verificar',
            [
                CheckoutController::class,
                'verificarPagamento',
            ]
        )->name('retorno.verificar');


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
            ->name('confirmacao');
    });


/*
|--------------------------------------------------------------------------
| HISTÓRICO DE COMPRAS - RF008
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        Route::get(
            '/compras',
            [
                ComprasController::class,
                'index',
            ]
        )->name('compras.index');


        Route::get(
            '/compras/relatorio/pdf',
            [
                ComprasController::class,
                'pdf',
            ]
        )->name('compras.pdf');
    });


/*
|--------------------------------------------------------------------------
| HISTÓRICO DE VENDAS - USUÁRIO - RF009
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        Route::get(
            '/vendas',
            [
                VendasController::class,
                'index',
            ]
        )->name('vendas.index');


        Route::get(
            '/vendas/relatorio/pdf',
            [
                VendasController::class,
                'pdf',
            ]
        )->name('vendas.relatorio.pdf');
    });


/*
|--------------------------------------------------------------------------
| PERFIL
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        Route::get(
            '/profile',
            [
                ProfileController::class,
                'edit',
            ]
        )->name('profile.edit');


        Route::patch(
            '/profile',
            [
                ProfileController::class,
                'update',
            ]
        )->name('profile.update');


        Route::post(
            '/profile/address',
            [
                ProfileController::class,
                'storeAddress',
            ]
        )->name('profile.address.store');


        Route::patch(
            '/profile/password',
            [
                ProfileController::class,
                'updatePassword',
            ]
        )->name('profile.password');


        Route::delete(
            '/profile',
            [
                ProfileController::class,
                'destroy',
            ]
        )->name('profile.destroy');
    });


/*
|--------------------------------------------------------------------------
| AUTENTICAÇÃO / BREEZE
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';