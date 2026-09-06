<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ViaCepController;
use App\Http\Controllers\PagBankWebhookController;


/*
|--------------------------------------------------------------------------
| RF012 - VIA CEP
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


/*
|--------------------------------------------------------------------------
| PAGBANK WEBHOOK
|--------------------------------------------------------------------------
*/

Route::post(
    '/pagbank/webhook',
    [
        PagBankWebhookController::class,
        'handle',
    ]
)->name(
    'api.pagbank.webhook'
);