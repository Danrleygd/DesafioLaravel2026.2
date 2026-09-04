<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ViaCepController;

Route::get('/cep/{cep}', [ViaCepController::class, 'consultar'])
    ->name('api.cep');