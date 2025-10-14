<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendErrorEmail extends Command
{
    protected $signature = 'send:error-email {message}';
    protected $description = 'Envía un correo cuando ocurre un error en el script de despliegue';

    public function handle()
    {
        $message = $this->argument('message');

        Mail::raw("Mensaje de despliegue:\n\n" . $message, function ($mail) {
            $mail->to('mmarin@seven.com.co')
                 ->subject('Notificacion de despliegue');
        });

        $this->info('Correo de error enviado.');
    }
}
