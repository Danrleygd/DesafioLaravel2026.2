<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    /**
     * Exibe o formulário para cadastrar
     * a nova senha.
     */
    public function create(
        Request $request
    ): View {
        return view(
            'auth.reset-password',
            [
                'request' =>
                    $request,
            ]
        );
    }


    /**
     * Redefine a senha.
     *
     * IMPORTANTE:
     * O projeto D-tech utiliza a coluna "senha"
     * em Usuarios, e não a coluna padrão "password".
     */
    public function store(
        Request $request
    ): RedirectResponse {
        $request->validate(
            [
                'token' => [
                    'required',
                ],

                'email' => [
                    'required',
                    'email',
                ],

                'password' => [
                    'required',
                    'confirmed',
                    Rules\Password::defaults(),
                ],
            ],
            [
                'token.required' =>
                    'O token de recuperação é obrigatório.',

                'email.required' =>
                    'Informe seu e-mail.',

                'email.email' =>
                    'Informe um e-mail válido.',

                'password.required' =>
                    'Informe a nova senha.',

                'password.confirmed' =>
                    'A confirmação da senha não corresponde.',
            ]
        );


        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function (
                $user,
                string $password
            ) {

                /*
                |--------------------------------------------------------------------------
                | COLUNA PERSONALIZADA DO PROJETO: senha
                |--------------------------------------------------------------------------
                */

                $user->forceFill([
                    'senha' =>
                        Hash::make(
                            $password
                        ),
                ]);


                /*
                |--------------------------------------------------------------------------
                | INVALIDA SESSÕES ANTIGAS BASEADAS EM REMEMBER TOKEN
                |--------------------------------------------------------------------------
                */

                $user->setRememberToken(
                    Str::random(60)
                );


                $user->save();


                event(
                    new PasswordReset(
                        $user
                    )
                );
            }
        );


        if (
            $status
            ===
            Password::PASSWORD_RESET
        ) {

            return redirect()
                ->route('login')
                ->with(
                    'status',
                    'Senha redefinida com sucesso. Agora você já pode entrar com a nova senha.'
                );
        }


        $mensagem = match ($status) {

            Password::INVALID_TOKEN =>
                'O link de recuperação é inválido ou expirou.',

            Password::INVALID_USER =>
                'Não foi possível localizar uma conta para este e-mail.',

            default =>
                'Não foi possível redefinir a senha. Solicite um novo link.',
        };


        return back()
            ->withInput(
                $request->only(
                    'email'
                )
            )
            ->withErrors([
                'email' =>
                    $mensagem,
            ]);
    }
}
