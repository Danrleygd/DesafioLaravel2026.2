<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
        |--------------------------------------------------------------------------
        | USUÁRIO NÃO LOGADO
        |--------------------------------------------------------------------------
        */

        if (!auth()->check()) {

            return redirect()
                ->route('login');
        }


        /*
        |--------------------------------------------------------------------------
        | USUÁRIO NÃO É ADMINISTRADOR
        |--------------------------------------------------------------------------
        */

        if (
            auth()->user()->tipo
            !==
            'administrador'
        ) {

            abort(
                403,
                'Você não possui permissão para acessar esta página.'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ADMINISTRADOR
        |--------------------------------------------------------------------------
        */

        return $next($request);
    }
}