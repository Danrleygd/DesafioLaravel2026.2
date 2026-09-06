<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DtechResetPasswordNotification extends Notification
{
    use Queueable;


    public function __construct(
        private readonly string $token
    ) {
    }


    public function via(
        object $notifiable
    ): array {
        return [
            'mail',
        ];
    }


    public function toMail(
        object $notifiable
    ): MailMessage {
        $email =
            method_exists(
                $notifiable,
                'getEmailForPasswordReset'
            )
                ? $notifiable
                    ->getEmailForPasswordReset()
                : $notifiable->email;


        $url = route(
            'password.reset',
            [
                'token' =>
                    $this->token,

                'email' =>
                    $email,
            ]
        );


        $expire = config(
            'auth.passwords.users.expire',
            60
        );


        return (new MailMessage)
            ->subject(
                'Redefinição de senha - D-tech'
            )
            ->greeting(
                'Olá, '
                .
                (
                    $notifiable->nome
                    ??
                    'usuário'
                )
                .
                '!'
            )
            ->line(
                'Recebemos uma solicitação para redefinir a senha da sua conta D-tech.'
            )
            ->action(
                'Redefinir minha senha',
                $url
            )
            ->line(
                'Este link expira em '
                .
                $expire
                .
                ' minutos.'
            )
            ->line(
                'Se você não solicitou a redefinição, ignore este e-mail.'
            )
            ->salutation(
                'Equipe D-tech'
            );
    }


    public function toArray(
        object $notifiable
    ): array {
        return [];
    }
}
