<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\ProdutoController;
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

Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

require __DIR__ . '/auth.php';
