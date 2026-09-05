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
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])
    ->name('landing');
Route::get('/produto/{id}', [ProdutoController::class, 'show'])
    ->name('produto.show');

Route::get('/produtos', [ProdutoIndexController::class, 'index'])
    ->name('produtos.index');

Route::get('/posts', [LandingController::class, 'index'])
    ->name('posts');

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

// rota api

Route::get('/cep/{cep}', [ViaCepController::class, 'consultar'])
    ->name('api.cep');

Route::middleware([
    'auth',
    'admin',
])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Usuario
        Route::resource(
            'usuarios',
            AdminUserController::class
        )
            ->parameters([
                'usuarios' => 'usuario',
            ]);


        // Administrador

        Route::resource(
            'administradores',
            AdminAdministratorController::class
        )
            ->parameters([
                'administradores' => 'administrador',
            ]);

        Route::get(
            '/produtos',
            [GerenciamentoProdutoController::class, 'adminIndex']
        )->name('produtos.index');

        Route::put(
            '/produtos/{produto}',
            [GerenciamentoProdutoController::class, 'adminUpdate']
        )->name('produtos.update');

        Route::delete(
            '/produtos/{produto}',
            [GerenciamentoProdutoController::class, 'adminDestroy']
        )->name('produtos.destroy');
    });

//Produto Gerenciamento
Route::middleware('auth')
    ->group(function () {

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
    | CARRINHO
    |--------------------------------------------------------------------------
    */

Route::middleware('auth')->group(function () {
    Route::get(
        '/carrinho',
        [CarrinhoController::class, 'index']
    )->name('carrinho.index');

    Route::post(
        '/carrinho/adicionar/{produto}',
        [CarrinhoController::class, 'adicionar']
    )->name('carrinho.adicionar');

    Route::patch(
        '/carrinho/item/{item}',
        [CarrinhoController::class, 'atualizar']
    )->name('carrinho.atualizar');

    Route::delete(
        '/carrinho/item/{item}',
        [CarrinhoController::class, 'remover']
    )->name('carrinho.remover');

    Route::delete(
        '/carrinho',
        [CarrinhoController::class, 'limpar']
    )->name('carrinho.limpar');
});


Route::middleware('auth')->group(function () {

    // Página do perfil
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    // Atualizar informações pessoais
    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    // Adicionar endereço
    Route::post('/profile/address', [ProfileController::class, 'storeAddress'])
        ->name('profile.address.store');

    // Alterar senha
    Route::patch('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');

    // Excluir conta
    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});


require __DIR__ . '/auth.php';
