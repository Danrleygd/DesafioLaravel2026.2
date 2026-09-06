<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PagBankWebhookController;
use App\Http\Controllers\ViaCepController;


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
)->name(
    'api.cep.json'
);



Route::post(
    '/pagbank/webhook',
    [
        PagBankWebhookController::class,
        'handle',
    ]
)->name(
    'api.pagbank.webhook'
);
