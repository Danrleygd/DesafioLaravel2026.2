<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Notifications\DtechResetPasswordNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    /**
     * Exibe a tela "Esqueci minha senha".
     */
    public function create(): View
    {
        return view(
            'auth.forgot-password'
        );
    }


    /**
     * Gera o token de recuperação e envia o e-mail.
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $request->validate(
            [
                'email' => [
                    'required',
                    'email',
                ],
            ],
            [
                'email.required' =>
                    'Informe seu e-mail.',

                'email.email' =>
                    'Informe um e-mail válido.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | USUÁRIO / ADMINISTRADOR
        |--------------------------------------------------------------------------
        |
        | Ambos estão armazenados na tabela Usuarios.
        |
        */

        $user = User::where(
            'email',
            $request->email
        )->first();


        /*
        |--------------------------------------------------------------------------
        | EVITA EXPOR SE O E-MAIL EXISTE OU NÃO
        |--------------------------------------------------------------------------
        */

        if ($user) {

            /*
            |--------------------------------------------------------------------------
            | TOKEN DO PASSWORD BROKER DO LARAVEL / BREEZE
            |--------------------------------------------------------------------------
            */

            $token = Password::broker()
                ->createToken(
                    $user
                );


            /*
            |--------------------------------------------------------------------------
            | ENVIA E-MAIL
            |--------------------------------------------------------------------------
            */

            $user->notify(
                new DtechResetPasswordNotification(
                    $token
                )
            );
        }


        return back()->with(
            'status',
            'Se o e-mail estiver cadastrado na D-tech, você receberá um link para redefinir sua senha.'
        );
    }
}
