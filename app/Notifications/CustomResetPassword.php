<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Clase modificada de envio de email de restablecimiento de contraseña
 */
class CustomResetPassword extends ResetPassword {

    /**
     * The password reset token.
     *
     * @var string
     */
    public $token;

    /**
     * The name user to reset password
     *
     * @var string
     */
    public $nameUser;

    /**
     * Create a notification instance.
     *
     * @param  string  $token
     * @return void
     */
    public function __construct($token, $nameUser)
    {
        $this->token = $token;
        $this->nameUser = $nameUser;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Aviso para restablecer contraseña')
            ->greeting('Hola estimado '.$this->nameUser)
            ->line('Está recibiendo éste email porque se ha solicitado un cambio de contraseña para su cuenta de Intraweb, por favor haga clic en el siguiente botón.')
            ->action('Reestablecer contraseña', url('password/reset', $this->token))
            ->line('Este enlace es para restablecer la contraseña y caduca en 60 minutos.')
            ->line('Si no ha solicitado un cambio de contraseña, puede ignorar o eliminar este e-mail.');
    }

}
