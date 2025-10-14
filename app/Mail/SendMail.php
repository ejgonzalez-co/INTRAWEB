<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMail extends Mailable
{
    use Queueable, SerializesModels;

    public $view;
    public $data;
    public $custom;
    public $id;
    public $fromAddress;
    
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($view, $data, $custom = null, $id = null, $fromAddress = null)
    {
        $this->view   = $view;
        $this->data   = $data;
        $this->custom = $custom;
        $this->id = $id;
        $this->fromAddress  = $fromAddress;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->markdown($this->view)
            ->subject($this->custom->subject)
            ->replyTo(env('MAIL_REPLY'), env('NAME_REPLY'))
            ->with('data', $this->data)
            ->withSymfonyMessage(function ($message) {
                $message->getHeaders()->addTextHeader('X-Custom-ID', $this->id);
                $message->getHeaders()->addTextHeader('X-Endpoint', config('app.endpoint'));
            });

        // Agrega el remitente si fue especificado dinámicamente
        if ($this->fromAddress) {
            $email->from($this->fromAddress, env('MAIL_FROM_NAME'));
        }

        // Si hay BCC definido globalmente
        if (env('MAIL_BCC')) {
            $email->bcc(env('MAIL_BCC'));
        }

        return $email;
    }
}
