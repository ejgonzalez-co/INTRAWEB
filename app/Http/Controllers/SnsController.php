<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use DB;
use App\Http\Controllers\AppBaseController;
use Exception;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;

class SnsController extends AppBaseController
{
    public function handle(Request $request)
    {

        $messageType = $request->header('x-amz-sns-message-type');
        $message = json_decode($request->getContent(), true);

        if ($messageType === 'SubscriptionConfirmation') {
            $this->confirmSubscription($message);
        } elseif ($messageType === 'Notification') {
            $this->processNotification($message);
        }

        return response()->json(['message' => 'Processed successfully'], 200);
    }

    protected function confirmSubscription($message)
    {
        $client = new Client();
        $response = $client->get($message['SubscribeURL']);

        if ($response->getStatusCode() == 200) {
            Log::info('SNS subscription confirmed.');
        } else {
            Log::error('Failed to confirm SNS subscription.');
        }
    }

     /**
     * Función para actualizar el estado del correo a rebotado
     *
     * @author Johan David Velasco Rios - 04/06/2024
     * @version 1.0.0
     *
     * @param $message = Trae toda la información del rebote - array
     *
     */
    protected function processNotification(Request $request)
    {
        $message = $request->all();
        //Accede a la data del rebote
        $mainMessage = $message["Message"];

        try {
            //Convierne del json la data
            $varPositions = json_decode($mainMessage, true);

            //obtiene el mensaje del porque se retorno el correo
            $message_englis = (json_encode($varPositions["bounce"]["bounceType"]) !== "Transient" && isset($varPositions["bounce"]["bouncedRecipients"][0]["diagnosticCode"]))
                ? json_encode($varPositions["bounce"]["bouncedRecipients"][0]["diagnosticCode"], true)
                : "message_transient";

            // Expresión regular para encontrar el primer mensaje después de "550-5.1.1"
            $pattern = '/550-5\.1\.1 ([^.]*\.)/';

            // Aplicar la expresión regular
            if (preg_match($pattern, $message_englis, $matches)) {
                // Extraer el mensaje encontrado
                $message = $matches[1];
            } else {
                $message = $message_englis;
            }

            // Variable para almacenar el valor de X-Endpoint
            $custom_id_value = null;

            //Recorre la data obtenida del robete de AWS y obtiene el X-Custom-ID
            foreach ($varPositions["mail"]["headers"] as $item) {
                if ($item['name'] === 'X-Custom-ID') {
                    $custom_id_value = $item['value'];
                    break;
                }
            }

            $mensajeCliente = "";

            switch (true) {
                case strpos($message, 'Amazon SES has suppressed') !== false:
                    // Acción para 'Amazon SES has suppressed'
                    $mensajeCliente = "Nuestro sistema de envío de correos ha detectado que este correo electrónico ha presentado recientes rebotes, lo cual indica que la dirección no es válida.";
                    break;

                case strpos($message, 'smtp;550 5.7.1 TRANSPORT.RULES.RejectMessage') !== false:
                    // Acción para 'smtp;550 5.7.1 TRANSPORT.RULES.RejectMessage'
                    $mensajeCliente = "La notificación fue rechazada debido a las políticas de la organización del destinatario. Por favor, póngase en contacto con el destinatario y solicítele que agregue la dirección 'no-reply@intraweb.com.co' a su lista de remitentes confiables.";
                    break;

                case strpos($message, 'The email account that you tried to reach does not exist') !== false:
                    // Acción para 'The email account that you tried to reach does not exist'
                    $mensajeCliente = "La cuenta de correo electrónico a la que intento enviar la notificación no existe";
                    break;

                case strpos($message, 'smtp; 554 4.4.7 Message expired') !== false:
                    // Acción para 'No such user here'
                    $mensajeCliente = "La notificación no fue entregada ya que supero el limite de tiempo.";
                    break;

                case strpos($message, 'No such user here') !== false:
                    // Acción para 'No such user here'
                    $mensajeCliente = "La cuenta de correo electrónico a la que intento enviar la notificación no existe";
                    break;

                case strpos($message, 'mailbox full') !== false:
                    // Acción para 'mailbox full'
                    $mensajeCliente = "La notificación no pudo ser entregada porque el destinatario tiene su bandeja de entrada llena.";
                    break;

                case strpos($message, 'message_transient') !== false:
                    // Acción para 'mailbox full'
                    $mensajeCliente = "La bandeja de entrada está llena o hay problemas temporales con el servidor receptor.";
                    break;

                default:
                    // Acción por defecto si no se encuentra ninguna coincidencia
                    $mensajeCliente = "La notificación no pudo ser entregada. Por favor, comuníquese con el administrador para verificar la causa del problema.";
                    break;
            }

            //Valida que la variable $custom_id_value sea diferente de vacio
            if (!empty($custom_id_value)) {
                // Se inicializa la variable de estado como "Rebote"
                $estado_notificacion = "Rebote";
                // Se verifica que la configuración smtp_alterno exista, además se verifica si el mensaje de error contiene alguna de las cadenas que indican supresión o rechazo por políticas del destinatario
                if (config('mail.mailers.smtp_alterno.username') && strpos($message, 'Amazon SES has suppressed') !== false || 
                    strpos($message, 'smtp;550 5.7.1 TRANSPORT.RULES.RejectMessage') !== false || 
                    strpos($message, 'RESOLVER.RST.RestrictedToRecipientsPermission') !== false || 
                    strpos($message, 'email address for typos or unnecessary spaces') !== false) {
                    // Se consulta la base de datos para obtener la información del correo que falló, usando el ID personalizado
                    $informacion_notificacion = NotificacionesMailIntraweb::where("id_mail", $custom_id_value)->get()->first()->toArray();
                    // Asunto de la notificación
                    $asunto = json_decode('{"subject": "'.$informacion_notificacion["asunto_notificacion"].'"}');
                    // Se decodifica el JSON almacenado en la columna 'datos_mail' y se convierte en un array asociativo de PHP
                    $datosEmail = json_decode($informacion_notificacion["datos_mail"], true);
                    // Se agrega al array de datos del correo un identificador de seguimiento ('trackingId') usando el ID del mail
                    $datosEmail["trackingId"] = $informacion_notificacion["id_mail"];
                    // Se agrega al array de datosEmail el correo del destinatario, ya que se usa en algunas plantillas de correo
                    $datosEmail["mail"] = $informacion_notificacion["correo_destinatario"];
                    // Se instancia la clase de correo con los datos obtenidos: plantilla, datos, asunto e ID
                    $data = new SendMail(
                        $informacion_notificacion["plantilla_notificacion"],
                        $datosEmail,
                        $asunto,
                        $informacion_notificacion["id_mail"],
                        config('mail.mailers.smtp_alterno.username')
                    );
                    // Se construye el correo (esto suele preparar el contenido HTML y otros elementos antes de enviarlo)
                    $data->build();
                    // $informacion_notificacion["correo_destinatario"] = "ggonzalez@seven.com.co";
                    // Se reenvía el correo usando el mailer alternativo configurado en Laravel
                    Mail::mailer('smtp_alterno')->to($informacion_notificacion["correo_destinatario"])->send($data);
                    // Se actualiza el estado a "Enviado" porque el reenvío fue exitoso
                    $estado_notificacion = "Enviado por el correo institucional";
                    // Se limpian las variables de mensaje de error
                    $message = $mensajeCliente = NULL;
                    // Se registra en el log personalizado que el correo fue reenviado con SMTP alternativo
                    $this->generateSevenLog(
                        'SnSController',
                        'app\Http\Controllers\SnSController - Fallo el envío por SES, se reenvió con SMTP alternativo - JsonData: ' . $mainMessage
                    );
                }
                //busca el correo por medio del id unico y actualiza el estado
                DB::table('notificaciones_mail_intraweb')->where('id_mail',$custom_id_value)->update([
                    "estado_notificacion" => $estado_notificacion,
                    "respuesta_servidor_notificacion" => $message,
                    "mensaje_cliente" => $mensajeCliente
                ]);

                Log::info("Bounce received for email: " . json_encode($varPositions["bounce"],true));

            }else{
                throw new Exception("No se encontró el encabezado 'X-Custom-ID' en los headers del correo.", 1);
            }

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'app\Http\Controllers\SnSController -  Error: '.$error->getMessage() . '. JsonData: ' . $mainMessage);
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('SnSController', 'app\Http\Controllers\SnSController -  Error: ' . $e->getMessage() . '. JsonData: ' . $mainMessage);

        }

    }

}
