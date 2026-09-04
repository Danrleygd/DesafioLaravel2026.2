<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\CarrinhoController;
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// rota api

Route::get('/cep/{cep}', [ViaCepController::class, 'consultar'])
    ->name('api.cep');

/*
    |--------------------------------------------------------------------------
    | CARRINHO
    |--------------------------------------------------------------------------
    */

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
