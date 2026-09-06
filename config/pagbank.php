<?php

return [

    /*
    |--------------------------------------------------------------------------
    | AMBIENTE
    |--------------------------------------------------------------------------
    |
    | sandbox    = ambiente de testes
    | production = ambiente real
    |
    */

    'environment' =>
        env(
            'PAGBANK_ENVIRONMENT',
            'sandbox'
        ),


    /*
    |--------------------------------------------------------------------------
    | TOKEN
    |--------------------------------------------------------------------------
    */

    'token' =>
        env(
            'PAGBANK_TOKEN'
        ),


    /*
    |--------------------------------------------------------------------------
    | URLs DA API
    |--------------------------------------------------------------------------
    */

    'sandbox_url' =>
        'https://sandbox.api.pagseguro.com',

    'production_url' =>
        'https://api.pagseguro.com',


    /*
    |--------------------------------------------------------------------------
    | RETORNO
    |--------------------------------------------------------------------------
    |
    | Pode ficar vazio durante o desenvolvimento.
    | Nesse caso será utilizada a rota checkout.retorno.
    |
    */

    'return_url' =>
        env(
            'PAGBANK_RETURN_URL'
        ),


    /*
    |--------------------------------------------------------------------------
    | WEBHOOK
    |--------------------------------------------------------------------------
    |
    | Será utilizado no próximo passo.
    | Para funcionar, precisa ser uma URL pública.
    |
    */

    'notification_url' =>
        env(
            'PAGBANK_NOTIFICATION_URL'
        ),

];
