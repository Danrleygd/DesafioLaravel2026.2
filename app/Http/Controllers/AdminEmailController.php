<?php

namespace App\Http\Controllers;

use App\Mail\AdminMessageMail;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class AdminEmailController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | PÁGINA DE ENVIO DE E-MAIL
    |--------------------------------------------------------------------------
    */

    public function index(): View
    {
        /*
        |--------------------------------------------------------------------------
        | BUSCA SOMENTE USUÁRIOS COMUNS
        |--------------------------------------------------------------------------
        */

        $usuarios = User::query()
            ->where(
                'tipo',
                'usuario'
            )
            ->orderBy(
                'nome'
            )
            ->get([
                'id',
                'nome',
                'email',
            ]);


        return view(
            'admin.emails.index',
            compact(
                'usuarios'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | ENVIAR E-MAIL
    |--------------------------------------------------------------------------
    */

    public function send(
        Request $request
    ): RedirectResponse {

        /*
        |--------------------------------------------------------------------------
        | VALIDAÇÃO
        |--------------------------------------------------------------------------
        */

        $dados = $request->validate(
            [
                'usuario_id' => [
                    'required',
                    'integer',
                ],

                'assunto' => [
                    'required',
                    'string',
                    'max:150',
                ],

                'conteudo' => [
                    'required',
                    'string',
                    'max:10000',
                ],
            ],
            [
                'usuario_id.required' =>
                    'Selecione o usuário que receberá o e-mail.',

                'usuario_id.integer' =>
                    'O usuário selecionado é inválido.',

                'assunto.required' =>
                    'Informe o assunto do e-mail.',

                'assunto.max' =>
                    'O assunto pode possuir no máximo 150 caracteres.',

                'conteudo.required' =>
                    'Digite o conteúdo do e-mail.',

                'conteudo.max' =>
                    'O conteúdo pode possuir no máximo 10.000 caracteres.',
            ]
        );


        /*
        |--------------------------------------------------------------------------
        | VERIFICA DESTINATÁRIO
        |--------------------------------------------------------------------------
        */

        $usuario = User::query()
            ->where(
                'id',
                $dados['usuario_id']
            )
            ->where(
                'tipo',
                'usuario'
            )
            ->first();


        if (!$usuario) {

            return back()
                ->withInput()
                ->withErrors([
                    'usuario_id' =>
                        'O destinatário selecionado é inválido.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | ENVIO
        |--------------------------------------------------------------------------
        */

        try {

            Mail::to(
                $usuario->email
            )->send(
                new AdminMessageMail(
                    destinatario: $usuario,
                    administrador: Auth::user(),
                    assunto: $dados['assunto'],
                    conteudo: $dados['conteudo'],
                )
            );

        } catch (Throwable $e) {

            report($e);


            return back()
                ->withInput()
                ->withErrors([
                    'email' =>
                        'Não foi possível enviar o e-mail. Verifique a configuração SMTP.',
                ]);
        }


        /*
        |--------------------------------------------------------------------------
        | SUCESSO
        |--------------------------------------------------------------------------
        */

        return redirect()
            ->route(
                'admin.emails.index'
            )
            ->with(
                'success',
                'E-mail enviado com sucesso para '
                . $usuario->nome
                . '.'
            );
    }
}