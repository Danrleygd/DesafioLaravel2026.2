<?php

return [

    /*
    |--------------------------------------------------------------------------
    | MERCADO PAGO - RF015
    |--------------------------------------------------------------------------
    */

    'environment' =>
        env(
            'MERCADOPAGO_ENVIRONMENT',
            'sandbox'
        ),


    'access_token' =>
        env(
            'MERCADOPAGO_ACCESS_TOKEN'
        ),


    'base_url' =>
        env(
            'MERCADOPAGO_BASE_URL',
            'https://api.mercadopago.com'
        ),


    /*
    |--------------------------------------------------------------------------
    | URLS PÚBLICAS
    |--------------------------------------------------------------------------
    |
    | Em localhost deixe vazias.
    | Se usar ngrok/domínio público, configure as três back_urls.
    |
    */

    'success_url' =>
        env(
            'MERCADOPAGO_SUCCESS_URL'
        ),


    'failure_url' =>
        env(
            'MERCADOPAGO_FAILURE_URL'
        ),


    'pending_url' =>
        env(
            'MERCADOPAGO_PENDING_URL'
        ),


    /*
    |--------------------------------------------------------------------------
    | WEBHOOK
    |--------------------------------------------------------------------------
    |
    | Opcional para o RF015.
    | Precisa ser HTTPS e público.
    |
    */

    'notification_url' =>
        env(
            'MERCADOPAGO_NOTIFICATION_URL'
        ),

];
