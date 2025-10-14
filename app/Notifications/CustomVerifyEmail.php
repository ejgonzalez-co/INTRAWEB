<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

/**
 * Clase modificada de envio de email de restablecimiento de contraseña
 */
class CustomVerifyEmail extends VerifyEmail {

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
    public function __construct($nameUser)
    {
        $this->nameUser = $nameUser;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable) {
        $verificationUrl = $this->verificationUrl($notifiable);

        if (static::$toMailCallback) {
            return call_user_func(static::$toMailCallback, $notifiable, $verificationUrl);
        }

        return (new MailMessage)
            ->subject(Lang::get('Verify Email Address'))
            ->greeting('Hola estimado '.$this->nameUser)
            ->line(Lang::get('Please click the button below to verify your email address.'))
            ->action(Lang::get('Verify Email And Active Account'), $verificationUrl)
            ->line(Lang::get('If you did not create an account, no further action is required.'));
    }

}
