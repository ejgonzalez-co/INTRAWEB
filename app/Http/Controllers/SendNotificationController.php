<?php

namespace App\Http\Controllers;

use App\Http\Controllers\AppBaseController;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Str;
use Auth;
use DB;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;
use Modules\NotificacionesIntraweb\Models\ListadoCorreosCheckeos;
use Response;
use GuzzleHttp\Client;
use App\User;
use Egulias\EmailValidator\EmailValidator;
use Egulias\EmailValidator\Validation\RFCValidation;
use Egulias\EmailValidator\Validation\DNSCheckValidation;
use Egulias\EmailValidator\Validation\MultipleValidationWithAnd;
use Barryvdh\DomPDF\Facade\Pdf;


class SendNotificationController extends AppBaseController {

    /**
     * Funcion general para el envio de correos del aplicativo intraweb
     *
     * @author Johan David Velasco Rios - 29/05/2024
     * @version 1.0.0
     *
     * @param $plantilla = Ruta blade donde esta creada la plantilla del mail - String
     * @param $asunto = Asunto del correo electronico - Json
     * @param $datosEmail = Contiene los datos del registro de donde salio la correspondencia - Array
     * @param $destinatario = Contiene los correos electronicos de las personas la cuales se le enviara una notificacion - Array/string
     * @param $modulo = Modulo de donde proviene la notificación - String
     * @param $update = Valida si el registro se esta actualizando o creado - String
     * @param $idCorreo = Valida si el registro trae el id del correo - String
     * @param $mensajeNoValido = Valida si el registro trae el mensaje de noValido - String
     *
     */
    public static function SendNotification($plantilla, $asunto, $datosEmail, $destinatario,$modulo,$update = null, $idCorreo = null, $mensajeNoValido = null)
    {

        if (is_array($destinatario)) {
            foreach ($destinatario as $correo) {
                if (strpos($correo, '@random.com') !== false || strpos($correo, '@intranet') !== false) {
                    return;
                }
            }
        } elseif (is_string($destinatario)) {
            if (strpos($destinatario, '@random.com') !== false || strpos($destinatario, '@intranet') !== false) {
                return;
            }
        }
        //Validadores del correo electronico cuando no son de pago
        $validator = new EmailValidator();
        $response = "";
        $multipleValidations = new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation()
        ]);

        //Convierte el array $datosEmail en Json para poder ser almacenado en la tabla
        $jsonDatosEmail = json_encode($datosEmail);

        $user = Auth::user();
        try {

            //Valida el tipo de dato que es $destinatario
            if (gettype($destinatario) == "array") {

                //Recorreo cada correo para asignarle un id unico al correo y generar un registro en la tabla de notificaciones
                foreach ($destinatario as $correo) {

                    $leido = 'No aplica';

                    $datosEmail['mail'] = $correo;

                    // Crear una instancia del controlador
                    $instance = new self();
                    //Genera el id para el correo
                    $trackingId = $instance->generateTrackingId($correo);

                    $datosEmail['trackingId'] = $trackingId;

                    if ((isset($datosEmail['document_pdf']) && $datosEmail['document_pdf'] != null) || (isset($datosEmail['annexes_digital']) && $datosEmail['annexes_digital'] != null)) {
                        $leido = 'No';
                    }

                    //Llama la clase sendmail y la funcion build para construir toda la data que lleva el correo
                    $data = new SendMail($plantilla, $datosEmail, $asunto, $trackingId);
                    $data->build();

                    //Consulta si existe algun usuario con el correo ingresado
                    $user_destinatario = User::where('email',$correo)->get()->first();

                    $estado = "";
                    $mensajeServidor = "";
                    $primerMensajeNoValido = "";

                    if (empty($user_destinatario)) {

                        $checkMail = ListadoCorreosCheckeos::where('email',$correo)->get()->first();

                        if (!empty($checkMail)) {
                            if ($checkMail['estado'] == 'Valida') {

                                Mail::to($correo)->send($data);
                                $estado = "Enviado";
                                $mensajeServidor = "Notificación enviada con éxito.";
                            } else {
                                $estado = "No enviado";
                                $mensajeServidor = "Lamentamos informarle que no pudimos enviar la notificación debido a que la dirección de correo electrónico ingresada no se encuentra en nuestra base de datos. Esto puede deberse a un error de tipeo o a que el correo aún no ha sido validado. Le solicitamos se ponga en contacto con su administrador para solucionar este inconveniente.";

                            }

                        }else{
                            if ($validator->isValid($correo, $multipleValidations)) {
                                $curl = curl_init();

                                curl_setopt_array($curl, [
                                  CURLOPT_URL => "https://api.usebouncer.com/v1.1/email/verify?email=".$correo."",
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_ENCODING => "",
                                  CURLOPT_MAXREDIRS => 10,
                                  CURLOPT_TIMEOUT => 30,
                                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                  CURLOPT_CUSTOMREQUEST => "GET",
                                  CURLOPT_HTTPHEADER => [
                                    "x-api-key: ".env('BOUNCER_API_KEY').""
                                  ],
                                ]);

                                $response = curl_exec($curl);
                                $err = curl_error($curl);

                                if ($err) {
                                    throw new Exception("cURL Error #:" . $err, 1);
                                } else {

                                $resultMail = json_decode($response,true);

                                    if ($resultMail['status'] == "deliverable" || $resultMail['status'] == "unknown" || $resultMail['status'] == "risky") {

                                        Mail::to($correo)->send($data);
                                        $estado = "Enviado";
                                        $mensajeServidor = "Notificación enviada con éxito.";

                                        ListadoCorreosCheckeos::create([
                                            'email' => $correo,
                                            'estado' => 'Valida',
                                            'fecha_verificacion' => date('Y-m-d H:i:s'),
                                            'user_id' => Auth::check() ? Auth::user()->id : 0,
                                            'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                        ]);

                                    }else{

                                        $estado = "No enviado";
                                        $mensajeServidor = "La notificación no pudo ser enviada porque la dirección de correo ingresada no es válida o no es entregable. Por favor, verifique el correo y vuelva a intentarlo.";

                                        $primerMensajeNoValido = $mensajeServidor;

                                        ListadoCorreosCheckeos::create([
                                            'email' => $correo,
                                            'estado' => 'Inválido',
                                            'fecha_verificacion' => date('Y-m-d H:i:s'),
                                            'user_id' => Auth::check() ? Auth::user()->id : 0,
                                            'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                        ]);

                                    }
                                }

                            } else {
                                $estado = "No enviado";
                                $mensajeServidor = "La notificación no pudo ser enviada porque el dominio del correo electrónico no es válido.";
                                $primerMensajeNoValido = $mensajeServidor;

                                ListadoCorreosCheckeos::create([
                                    'email' => $correo,
                                    'estado' => 'Invalido',
                                    'fecha_verificacion' => date('Y-m-d H:i:s'),
                                    'user_id' => Auth::check() ? Auth::user()->id : 0,
                                    'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                ]);

                            }
                        }

                    } else {
                        if ($user_destinatario['sendEmail'] == 1 || $modulo == "Login") {

                            $checkMail = ListadoCorreosCheckeos::where('email',$correo)->get()->first();

                            if (!empty($checkMail)) {
                                if ($checkMail['estado'] == 'Valida') {

                                    Mail::to($correo)->send($data);
                                    $estado = "Enviado";
                                    $mensajeServidor = "Notificación enviada con éxito.";
                                } else {
                                    $estado = "No enviado";
                                    $mensajeServidor = "Lamentamos informarle que no pudimos enviar la notificación debido a que la dirección de correo electrónico ingresada no se encuentra en nuestra base de datos. Esto puede deberse a un error de tipeo o a que el correo aún no ha sido validado. Le solicitamos se ponga en contacto con su administrador para solucionar este inconveniente.";

                                }

                            }else{
                                if ($validator->isValid($correo, $multipleValidations)) {
                                    $curl = curl_init();

                                    curl_setopt_array($curl, [
                                      CURLOPT_URL => "https://api.usebouncer.com/v1.1/email/verify?email=".$correo."",
                                      CURLOPT_RETURNTRANSFER => true,
                                      CURLOPT_ENCODING => "",
                                      CURLOPT_MAXREDIRS => 10,
                                      CURLOPT_TIMEOUT => 30,
                                      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                      CURLOPT_CUSTOMREQUEST => "GET",
                                      CURLOPT_HTTPHEADER => [
                                        "x-api-key: ".env('BOUNCER_API_KEY').""
                                      ],
                                    ]);

                                    $response = curl_exec($curl);
                                    $err = curl_error($curl);

                                    if ($err) {
                                        throw new Exception("cURL Error #:" . $err, 1);
                                    } else {

                                    $resultMail = json_decode($response,true);

                                        if ($resultMail['status'] == "deliverable" || $resultMail['status'] == "unknown" || $resultMail['status'] == "risky") {

                                            Mail::to($correo)->send($data);
                                            $estado = "Enviado";
                                            $mensajeServidor = "Notificación enviada con éxito.";

                                            ListadoCorreosCheckeos::create([
                                                'email' => $correo,
                                                'estado' => 'Valida',
                                                'fecha_verificacion' => date('Y-m-d H:i:s'),
                                                'user_id' => Auth::check() ? Auth::user()->id : 0,
                                                'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                            ]);

                                        }else{

                                            $estado = "No enviado";
                                            $mensajeServidor = "La notificación no pudo ser enviada porque la dirección de correo ingresada no es válida o no es entregable. Por favor, verifique el correo y vuelva a intentarlo.";

                                            $primerMensajeNoValido = $mensajeServidor;

                                            ListadoCorreosCheckeos::create([
                                                'email' => $correo,
                                                'estado' => 'Invalido',
                                                'fecha_verificacion' => date('Y-m-d H:i:s'),
                                                'user_id' => Auth::check() ? Auth::user()->id : 0,
                                                'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                            ]);

                                        }
                                    }

                                } else {
                                    $estado = "No enviado";
                                    $mensajeServidor = "La notificación no pudo ser enviada porque el dominio del correo electrónico no es válido.";

                                    $primerMensajeNoValido = $mensajeServidor;

                                    ListadoCorreosCheckeos::create([
                                        'email' => $correo,
                                        'estado' => 'Invalido',
                                        'fecha_verificacion' => date('Y-m-d H:i:s'),
                                        'user_id' => Auth::check() ? Auth::user()->id : 0,
                                        'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                    ]);

                                }
                            }
                        }else{
                            $estado = "No enviado";
                            $mensajeServidor = "La notificación no fue enviada porque el ".$user_destinatario['user_type']." no tiene habilitada la opción de recibir notificaciones por correo electrónico.";

                        }
                    }



                    // Inicia la transacción
                    DB::beginTransaction();


                    //Genera un registro en la tabla de notificaciones
                    NotificacionesMailIntraweb::create([
                        'id_mail' => $trackingId,
                        'id_comunicacion_oficial' => $datosEmail['id'] ?? null,
                        'user_id' => Auth::check() ? Auth::user()->id : 0,
                        'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión',
                        'modulo' => $modulo,
                        'consecutivo' => $datosEmail['consecutive'] ?? $datosEmail['consecutivo'] ?? $datosEmail['pqr_id'] ?? null,
                        'estado_comunicacion' => $datosEmail['state'] ?? null,
                        'correo_destinatario' => $correo,
                        'datos_mail' => $jsonDatosEmail,
                        'leido' => $leido,
                        'estado_notificacion' => $estado,
                        'asunto_notificacion' => $asunto->subject,
                        'mensaje_notificacion' => $datosEmail['mensaje'] ?? null,
                        'plantilla_notificacion' => $plantilla,
                        'mensaje_cliente' => $mensajeServidor ?? null,
                        'primer_mensaje_novalido' => $primerMensajeNoValido ?? null
                    ]);


                    // Efectua los cambios realizados
                    DB::commit();
                }

            } else {

                $datosEmail['mail'] = $destinatario;

                $leido = 'No aplica';
                if ((isset($datosEmail['document_pdf']) && $datosEmail['document_pdf'] != null) ||
                (isset($datosEmail['documents']) && $datosEmail['documents'] != null) ||
                (isset($datosEmail['annexes_digital']) && $datosEmail['annexes_digital'] != null)) {
                $leido = 'No';
            }


                //Llama la clase sendmail y la funcion build para construir toda la data que lleva el correo
                if(empty($idCorreo)){
                    // Crear una instancia del controlador
                    $instance = new self();
                    //Genera el id para el correo
                    $trackingId = $instance->generateTrackingId($destinatario);

                    $datosEmail['trackingId'] = $trackingId;

                    $data = new SendMail($plantilla, $datosEmail, $asunto, $trackingId);

                }else{

                    $datosEmail['trackingId'] = $idCorreo;

                    $data = new SendMail($plantilla, $datosEmail, $asunto, $idCorreo);

                }
                $data->build();

                //Obtiene la información la cual indica si el correo es entregable o no
                $dataCorreo = json_decode($response, true);

                //Consulta si existe algun usuario con el correo ingresado
                $user_destinatario = User::where('email',$destinatario)->get()->first();

                $estado = "";
                $mensajeServidor = "";
                $primerMensajeNoValido = "";

                if (empty($user_destinatario)) {

                    $checkMail = ListadoCorreosCheckeos::where('email',$destinatario)->get()->first();

                    if (!empty($checkMail)) {
                        if ($checkMail['estado'] == 'Valida') {

                            Mail::to($destinatario)->send($data);
                            $estado = "Enviado";
                            $mensajeServidor = "Notificación enviada con éxito.";
                        } else {
                            $estado = "No enviado";
                            $mensajeServidor = "Lamentamos informarle que no pudimos enviar la notificación debido a que la dirección de correo electrónico ingresada no se encuentra en nuestra base de datos. Esto puede deberse a un error de tipeo o a que el correo aún no ha sido validado. Le solicitamos se ponga en contacto con su administrador para solucionar este inconveniente.";

                        }

                    }else{
                        if ($validator->isValid($destinatario, $multipleValidations)) {
                            $curl = curl_init();

                            curl_setopt_array($curl, [
                              CURLOPT_URL => "https://api.usebouncer.com/v1.1/email/verify?email=".$destinatario."",
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => "",
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 30,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => "GET",
                              CURLOPT_HTTPHEADER => [
                                "x-api-key: ".env('BOUNCER_API_KEY').""
                              ],
                            ]);

                            $response = curl_exec($curl);
                            $err = curl_error($curl);

                            if ($err) {
                                throw new Exception("cURL Error #:" . $err, 1);
                            } else {

                            $resultMail = json_decode($response,true);



                                if ($resultMail['status'] == "deliverable" || $resultMail['status'] == "unknown" || $resultMail['status'] == "risky") {

                                    Mail::to($destinatario)->send($data);
                                    $estado = "Enviado";
                                    $mensajeServidor = "Notificación enviada con éxito.";

                                    ListadoCorreosCheckeos::create([
                                        'email' => $destinatario,
                                        'estado' => 'Valida',
                                        'fecha_verificacion' => date('Y-m-d H:i:s'),
                                        'user_id' => Auth::check() ? Auth::user()->id : 0,
                                        'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                    ]);

                                }else{

                                    $estado = "No enviado";
                                    $mensajeServidor = "La notificación no pudo ser enviada porque la dirección de correo ingresada no es válida o no es entregable. Por favor, verifique el correo y vuelva a intentarlo.";

                                    $primerMensajeNoValido = $mensajeServidor;


                                    ListadoCorreosCheckeos::create([
                                        'email' => $destinatario,
                                        'estado' => 'Inválido',
                                        'fecha_verificacion' => date('Y-m-d H:i:s'),
                                        'user_id' => Auth::check() ? Auth::user()->id : 0,
                                        'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                    ]);

                                }
                            }

                        } else {
                            $estado = "No enviado";
                            $mensajeServidor = "La notificación no pudo ser enviada porque el dominio del correo electrónico no es válido.";

                            $primerMensajeNoValido = $mensajeServidor;

                            ListadoCorreosCheckeos::create([
                                'email' => $destinatario,
                                'estado' => 'Invalido',
                                'fecha_verificacion' => date('Y-m-d H:i:s'),
                                'user_id' => Auth::check() ? Auth::user()->id : 0,
                                'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                            ]);

                        }
                    }

                } else {
                    if ($user_destinatario['sendEmail'] == 1 || $modulo == "Login") {

                        $checkMail = ListadoCorreosCheckeos::where('email',$destinatario)->get()->first();

                        if (!empty($checkMail)) {
                            if ($checkMail['estado'] == 'Valida') {

                                Mail::to($destinatario)->send($data);
                                $estado = "Enviado";
                                $mensajeServidor = "Notificación enviada con éxito.";
                            } else {
                                $estado = "No enviado";
                                $mensajeServidor = "Lamentamos informarle que no pudimos enviar la notificación debido a que la dirección de correo electrónico ingresada no se encuentra en nuestra base de datos. Esto puede deberse a un error de tipeo o a que el correo aún no ha sido validado. Le solicitamos se ponga en contacto con su administrador para solucionar este inconveniente.";

                            }

                        }else{
                            if ($validator->isValid($destinatario, $multipleValidations)) {
                                $curl = curl_init();

                                curl_setopt_array($curl, [
                                  CURLOPT_URL => "https://api.usebouncer.com/v1.1/email/verify?email=".$destinatario."",
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_ENCODING => "",
                                  CURLOPT_MAXREDIRS => 10,
                                  CURLOPT_TIMEOUT => 30,
                                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                  CURLOPT_CUSTOMREQUEST => "GET",
                                  CURLOPT_HTTPHEADER => [
                                    "x-api-key: ".env('BOUNCER_API_KEY').""
                                  ],
                                ]);

                                $response = curl_exec($curl);
                                $err = curl_error($curl);



                                if ($err) {
                                    throw new Exception("cURL Error #:" . $err, 1);
                                } else {


                                $resultMail = json_decode($response,true);

                                    if ($resultMail['status'] == "deliverable" || $resultMail['status'] == "unknown" || $resultMail['status'] == "risky") {

                                        Mail::to($destinatario)->send($data);
                                        $estado = "Enviado";
                                        $mensajeServidor = "Notificación enviada con éxito.";

                                        ListadoCorreosCheckeos::create([
                                            'email' => $destinatario,
                                            'estado' => 'Valida',
                                            'fecha_verificacion' => date('Y-m-d H:i:s'),
                                            'user_id' => Auth::check() ? Auth::user()->id : 0,
                                            'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                        ]);

                                    }else{

                                        $estado = "No enviado";
                                        $mensajeServidor = "La notificación no pudo ser enviada porque la dirección de correo ingresada no es válida o no es entregable. Por favor, verifique el correo y vuelva a intentarlo.";

                                        $primerMensajeNoValido = $mensajeServidor;


                                        ListadoCorreosCheckeos::create([
                                            'email' => $destinatario,
                                            'estado' => 'Invalido',
                                            'fecha_verificacion' => date('Y-m-d H:i:s'),
                                            'user_id' => Auth::check() ? Auth::user()->id : 0,
                                            'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                        ]);

                                    }
                                }

                            } else {
                                $estado = "No enviado";
                                $mensajeServidor = "La notificación no pudo ser enviada porque el dominio del correo electrónico no es válido.";

                                $primerMensajeNoValido = $mensajeServidor;

                                ListadoCorreosCheckeos::create([
                                    'email' => $destinatario,
                                    'estado' => 'Invalido',
                                    'fecha_verificacion' => date('Y-m-d H:i:s'),
                                    'user_id' => Auth::check() ? Auth::user()->id : 0,
                                    'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                ]);

                            }
                        }
                    }else{
                        $estado = "No enviado";
                        $mensajeServidor = "La notificación no fue enviada porque el ".$user_destinatario['user_type']." no tiene habilitada la opción de recibir notificaciones por correo electrónico.";

                    }
                }

                // Inicia la transaccion
                DB::beginTransaction();

                //Valida si se requiere crear o actualizar el registro de la notificacion
                if (empty($update)) {
                    //Genera un registro en la tabla de notificaciones
                    NotificacionesMailIntraweb::create([
                        'id_mail' => $trackingId,
                        'id_comunicacion_oficial' => $datosEmail['id'] ?? null,
                        'user_id' => !empty($user) ? $user->id : null,
                        'user_name' => !empty($user) ? $user->name : null,
                        'modulo' => $modulo,
                        'consecutivo' => $datosEmail['consecutive'] ?? $datosEmail['consecutivo'] ?? $datosEmail['pqr_id'] ?? null,
                        'estado_comunicacion' => $datosEmail['state'] ?? null,
                        'correo_destinatario' => $destinatario,
                        'leido' => $leido,
                        'datos_mail' => $jsonDatosEmail,
                        'estado_notificacion' => $estado,
                        'asunto_notificacion' => $asunto->subject,
                        'mensaje_notificacion' => $datosEmail['mensaje'] ?? null,
                        'plantilla_notificacion' => $plantilla,
                        'mensaje_cliente' => $mensajeServidor ?? null,
                        'primer_mensaje_novalido' => $primerMensajeNoValido ?? null

                    ]);
                }else{

                    //Una vez se envia se actualiza el campo nuevamente
                    NotificacionesMailIntraweb::where('id_mail',$idCorreo)->update([
                        'estado_notificacion'=>$estado,
                        'correo_destinatario'=>$destinatario,
                        'respuesta_servidor_notificacion' => $mensajeServidor ?? null,
                        'primer_mensaje_novalido' => $mensajeNoValido ?? $primerMensajeNoValido
                    ]);

                }



                // Efectua los cambios realizados
                DB::commit();
            }

        } catch (\Illuminate\Database\QueryException $error) {

            // Inserta el error en el registro de log
            AppBaseController::generateSevenLog('SendNotificationController', 'App\Http\Controllers\SendNotificationController - '.(!empty($user) ? $user->name : null).' -  Error: '.$error->getMessage());

            if (gettype($destinatario) == "array") {
                //Recorreo cada correo para asignarle un id unico al correo y generar un registro en la tabla de notificaciones
                foreach ($destinatario as $correo) {
                        //Genera un registro en la tabla de notificaciones
                        NotificacionesMailIntraweb::create([
                            'id_mail' => $trackingId ? $idCorreo : null,
                            'id_comunicacion_oficial' => $datosEmail['id'] ?? null,
                            'user_id' => !empty($user) ? $user->id : null,
                            'user_name' => !empty($user) ? $user->name : null,
                            'modulo' => $modulo,
                            'consecutivo' => $datosEmail['consecutive'] ?? $datosEmail['consecutivo'] ?? $datosEmail['pqr_id'] ?? null,
                            'estado_comunicacion' => $datosEmail['state'] ?? null,
                            'correo_destinatario' => $correo,
                            'datos_mail' => $jsonDatosEmail,
                            'estado_notificacion' => 'Enviado',
                            'asunto_notificacion' => $asunto->subject,
                            'mensaje_notificacion' => $datosEmail['mensaje'] ?? null,
                            'plantilla_notificacion' => $plantilla
                        ]);
                }

            } else {
                //Genera un registro en la tabla de notificaciones
                NotificacionesMailIntraweb::create([
                    'id_mail' => $trackingId ? $idCorreo : null,
                    'id_comunicacion_oficial' => $datosEmail['id'] ?? null,
                    'user_id' => !empty($user) ? $user->id : null,
                    'user_name' => !empty($user) ? $user->name : null,
                    'modulo' => $modulo,
                    'consecutivo' => $datosEmail['consecutive'] ?? $datosEmail['consecutivo'] ?? $datosEmail['pqr_id'] ?? null,
                    'estado_comunicacion' => $datosEmail['state'] ?? null,
                    'correo_destinatario' => $destinatario,
                    'datos_mail' => $jsonDatosEmail,
                    'estado_notificacion' => 'Enviado',
                    'asunto_notificacion' => $asunto->subject,
                    'mensaje_notificacion' => $datosEmail['mensaje'] ?? null,
                    'plantilla_notificacion' => $plantilla
                ]);
            }
        } catch (\Exception $e) {

            // Inserta el error en el registro de log
            AppBaseController::generateSevenLog('SendNotificationController', 'App\Http\Controllers\SendNotificationController - '.(!empty($user) ? $user->name : null).' -  Error: '.$e->getMessage() .' -  linea: '.$e->getLine());

            if (gettype($destinatario) == "array") {
                //Recorreo cada correo para asignarle un id unico al correo y generar un registro en la tabla de notificaciones
                foreach ($destinatario as $correo) {
                        //Genera un registro en la tabla de notificaciones
                        NotificacionesMailIntraweb::create([
                            'id_mail' => $trackingId ? $idCorreo : null,
                            'id_comunicacion_oficial' => $datosEmail['id'] ?? null,
                            'user_id' => !empty($user) ? $user->id : null,
                            'user_name' => !empty($user) ? $user->name : null,
                            'modulo' => $modulo,
                            'consecutivo' => $datosEmail['consecutive'] ?? $datosEmail['consecutivo'] ?? $datosEmail['pqr_id'] ?? null,
                            'estado_comunicacion' => $datosEmail['state'] ?? null,
                            'correo_destinatario' => $correo,
                            'datos_mail' => $jsonDatosEmail,
                            'estado_notificacion' => 'No enviado',
                            'asunto_notificacion' => $asunto->subject,
                            'mensaje_notificacion' => $datosEmail['mensaje'] ?? null,
                            'plantilla_notificacion' => $plantilla,
                            'respuesta_servidor_notificacion' => $e->getMessage() ?? null
                        ]);
                }

            } else {
                //Genera un registro en la tabla de notificaciones
                NotificacionesMailIntraweb::create([
                    'id_mail' => $trackingId ? $idCorreo : null,
                    'id_comunicacion_oficial' => $datosEmail['id'] ?? null,
                    'user_id' => !empty($user) ? $user->id : null,
                    'user_name' => !empty($user) ? $user->name : null,
                    'modulo' => $modulo,
                    'consecutivo' => $datosEmail['consecutive'] ?? $datosEmail['consecutivo'] ?? $datosEmail['pqr_id'] ?? null,
                    'estado_comunicacion' => $datosEmail['state'] ?? null,
                    'correo_destinatario' => $destinatario,
                    'datos_mail' => $jsonDatosEmail,
                    'estado_notificacion' => 'No enviado',
                    'asunto_notificacion' => $asunto->subject,
                    'mensaje_notificacion' => $datosEmail['mensaje'] ?? null,
                    'plantilla_notificacion' => $plantilla,
                    'respuesta_servidor_notificacion' => $e->getMessage() ?? null
                ]);
            }
        }

    }

    public function forwardNotification() {

        //Validadores del correo electronico cuando no son de pago
        $validator = new EmailValidator();
        $response = "";
        $multipleValidations = new MultipleValidationWithAnd([
            new RFCValidation(),
            new DNSCheckValidation()
        ]);

        $mails = NotificacionesMailIntraweb::whereNotNull('id_comunicacion_oficial')
        ->whereIn('estado_notificacion', ['No enviado', 'Rebote'])
        ->whereNotNull('correo_destinatario')
        ->orderBy('updated_at', 'asc')
        ->limit(100)
        ->get()
        ->toArray();

        $resultadoMails = [];

        try {
            foreach ($mails as $key => $value) {

                $datosEmail = json_decode($value['datos_mail'],true);

                $asunto = json_decode('{"subject": "'.$value['asunto_notificacion'].'"}');

                $datosEmail['mail'] = $value['correo_destinatario'];

                $asunto = json_decode('{"subject": "'.$value['asunto_notificacion'].'"}');

                $datosEmail['trackingId'] = $value['id_mail'];

                //Llama la clase sendmail y la funcion build para construir toda la data que lleva el correo
                $data = new SendMail($value['plantilla_notificacion'], $datosEmail, $asunto, $value['id_mail']);
                $data->build();

                //Consulta si existe algun usuario con el correo ingresado
                $user_destinatario = User::where('email',$value['correo_destinatario'])->get()->first();

                $estado = "";
                $mensajeServidor = "";
                $primerMensajeNoValido = "";

                if (empty($user_destinatario)) {

                    $checkMail = ListadoCorreosCheckeos::where('email',$value['correo_destinatario'])->get()->first();

                    if (!empty($checkMail)) {
                        if ($checkMail['estado'] == 'Valida') {

                            Mail::to($value['correo_destinatario'])->send($data);
                            $estado = "Enviado";
                            $mensajeServidor = "Notificación enviada con éxito.";
                            $resultadoMails[$key]['estado'] = $estado;
                            $resultadoMails[$key]['correo'] = $value['correo_destinatario'];
                            $resultadoMails[$key]['mensaje'] = $mensajeServidor;
                        } else {
                            $estado = "No enviado";
                            $mensajeServidor = "Lamentamos informarle que no pudimos enviar la notificación debido a que la dirección de correo electrónico ingresada no se encuentra en nuestra base de datos. Esto puede deberse a un error de tipeo o a que el correo aún no ha sido validado. Le solicitamos se ponga en contacto con su administrador para solucionar este inconveniente.";
                            $resultadoMails[$key]['estado'] = $estado;
                            $resultadoMails[$key]['correo'] = $value['correo_destinatario'];
                            $resultadoMails[$key]['mensaje'] = $mensajeServidor;
                        }

                    }else{
                        if ($validator->isValid($value['correo_destinatario'], $multipleValidations)) {
                            $curl = curl_init();

                            curl_setopt_array($curl, [
                              CURLOPT_URL => "https://api.usebouncer.com/v1.1/email/verify?email=".$value['correo_destinatario']."",
                              CURLOPT_RETURNTRANSFER => true,
                              CURLOPT_ENCODING => "",
                              CURLOPT_MAXREDIRS => 10,
                              CURLOPT_TIMEOUT => 30,
                              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                              CURLOPT_CUSTOMREQUEST => "GET",
                              CURLOPT_HTTPHEADER => [
                                "x-api-key: ".env('BOUNCER_API_KEY').""
                              ],
                            ]);

                            $response = curl_exec($curl);
                            $err = curl_error($curl);

                            if ($err) {
                                throw new Exception("cURL Error #:" . $err, 1);
                            } else {

                            $resultMail = json_decode($response,true);

                                if ($resultMail['status'] == "deliverable" || $resultMail['status'] == "unknown" || $resultMail['status'] == "risky") {

                                    Mail::to($value['correo_destinatario'])->send($data);
                                    $estado = "Enviado";
                                    $mensajeServidor = "Notificación enviada con éxito.";
                                    $resultadoMails[$key]['estado'] = $estado;
                                    $resultadoMails[$key]['correo'] = $value['correo_destinatario'];
                                    $resultadoMails[$key]['mensaje'] = $mensajeServidor;

                                    ListadoCorreosCheckeos::create([
                                        'email' => $value['correo_destinatario'],
                                        'estado' => 'Valida',
                                        'fecha_verificacion' => date('Y-m-d H:i:s'),
                                        'user_id' => Auth::check() ? Auth::user()->id : 0,
                                        'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                    ]);

                                }else{

                                    $estado = "No enviado";
                                    $mensajeServidor = "La notificación no pudo ser enviada porque la dirección de correo ingresada no es válida o no es entregable. Por favor, verifique el correo y vuelva a intentarlo.";

                                    $primerMensajeNoValido = $mensajeServidor;
                                    $resultadoMails[$key]['estado'] = $estado;
                                    $resultadoMails[$key]['correo'] = $value['correo_destinatario'];
                                    $resultadoMails[$key]['mensaje'] = $mensajeServidor;


                                    ListadoCorreosCheckeos::create([
                                        'email' => $value['correo_destinatario'],
                                        'estado' => 'Inválido',
                                        'fecha_verificacion' => date('Y-m-d H:i:s'),
                                        'user_id' => Auth::check() ? Auth::user()->id : 0,
                                        'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                    ]);

                                }
                            }

                        } else {
                            $estado = "No enviado";
                            $mensajeServidor = "La notificación no pudo ser enviada porque el dominio del correo electrónico no es válido.";
                            $primerMensajeNoValido = $mensajeServidor;
                            $resultadoMails[$key]['estado'] = $estado;
                            $resultadoMails[$key]['correo'] = $value['correo_destinatario'];
                            $resultadoMails[$key]['mensaje'] = $mensajeServidor;

                            ListadoCorreosCheckeos::create([
                                'email' => $value['correo_destinatario'],
                                'estado' => 'Invalido',
                                'fecha_verificacion' => date('Y-m-d H:i:s'),
                                'user_id' => Auth::check() ? Auth::user()->id : 0,
                                'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                            ]);

                        }
                    }

                } else {
                    if ($user_destinatario['sendEmail'] == 1) {

                        $checkMail = ListadoCorreosCheckeos::where('email',$value['correo_destinatario'])->get()->first();

                        if (!empty($checkMail)) {
                            if ($checkMail['estado'] == 'Valida') {

                                Mail::to($value['correo_destinatario'])->send($data);
                                $estado = "Enviado";
                                $mensajeServidor = "Notificación enviada con éxito.";
                                $resultadoMails[$key]['estado'] = $estado;
                                $resultadoMails[$key]['correo'] = $value['correo_destinatario'];
                                $resultadoMails[$key]['mensaje'] = $mensajeServidor;
                            } else {
                                $estado = "No enviado";
                                $mensajeServidor = "Lamentamos informarle que no pudimos enviar la notificación debido a que la dirección de correo electrónico ingresada no se encuentra en nuestra base de datos. Esto puede deberse a un error de tipeo o a que el correo aún no ha sido validado. Le solicitamos se ponga en contacto con su administrador para solucionar este inconveniente.";
                                $resultadoMails[$key]['estado'] = $estado;
                                $resultadoMails[$key]['correo'] = $value['correo_destinatario'];
                                $resultadoMails[$key]['mensaje'] = $mensajeServidor;
                            }

                        }else{
                            if ($validator->isValid($value['correo_destinatario'], $multipleValidations)) {
                                $curl = curl_init();

                                curl_setopt_array($curl, [
                                  CURLOPT_URL => "https://api.usebouncer.com/v1.1/email/verify?email=".$value['correo_destinatario']."",
                                  CURLOPT_RETURNTRANSFER => true,
                                  CURLOPT_ENCODING => "",
                                  CURLOPT_MAXREDIRS => 10,
                                  CURLOPT_TIMEOUT => 30,
                                  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                                  CURLOPT_CUSTOMREQUEST => "GET",
                                  CURLOPT_HTTPHEADER => [
                                    "x-api-key: ".env('BOUNCER_API_KEY').""
                                  ],
                                ]);

                                $response = curl_exec($curl);
                                $err = curl_error($curl);

                                if ($err) {
                                    throw new Exception("cURL Error #:" . $err, 1);
                                } else {

                                $resultMail = json_decode($response,true);

                                    if ($resultMail['status'] == "deliverable" || $resultMail['status'] == "unknown" || $resultMail['status'] == "risky") {

                                        Mail::to($value['correo_destinatario'])->send($data);
                                        $estado = "Enviado";
                                        $mensajeServidor = "Notificación enviada con éxito.";
                                        $resultadoMails[$key]['estado'] = $estado;
                                        $resultadoMails[$key]['correo'] = $value['correo_destinatario'];
                                        $resultadoMails[$key]['mensaje'] = $mensajeServidor;

                                        ListadoCorreosCheckeos::create([
                                            'email' => $value['correo_destinatario'],
                                            'estado' => 'Valida',
                                            'fecha_verificacion' => date('Y-m-d H:i:s'),
                                            'user_id' => Auth::check() ? Auth::user()->id : 0,
                                            'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                        ]);

                                    }else{

                                        $estado = "No enviado";
                                        $mensajeServidor = "La notificación no pudo ser enviada porque la dirección de correo ingresada no es válida o no es entregable. Por favor, verifique el correo y vuelva a intentarlo.";

                                        $primerMensajeNoValido = $mensajeServidor;
                                        $resultadoMails[$key]['estado'] = $estado;
                                        $resultadoMails[$key]['correo'] = $value['correo_destinatario'];
                                        $resultadoMails[$key]['mensaje'] = $mensajeServidor;


                                        ListadoCorreosCheckeos::create([
                                            'email' => $value['correo_destinatario'],
                                            'estado' => 'Invalido',
                                            'fecha_verificacion' => date('Y-m-d H:i:s'),
                                            'user_id' => Auth::check() ? Auth::user()->id : 0,
                                            'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                        ]);

                                    }
                                }

                            } else {
                                $estado = "No enviado";
                                $mensajeServidor = "La notificación no pudo ser enviada porque el dominio del correo electrónico no es válido.";

                                $primerMensajeNoValido = $mensajeServidor;
                                $resultadoMails[$key]['estado'] = $estado;
                                $resultadoMails[$key]['correo'] = $value['correo_destinatario'];
                                $resultadoMails[$key]['mensaje'] = $mensajeServidor;

                                ListadoCorreosCheckeos::create([
                                    'email' => $value['correo_destinatario'],
                                    'estado' => 'Invalido',
                                    'fecha_verificacion' => date('Y-m-d H:i:s'),
                                    'user_id' => Auth::check() ? Auth::user()->id : 0,
                                    'user_name' => Auth::check() ? Auth::user()->name: 'Sin sesión'
                                ]);

                            }
                        }
                    }else{
                        $estado = "No enviado";
                        $mensajeServidor = "La notificación no fue enviada porque el ".$user_destinatario['user_type']." no tiene habilitada la opción de recibir notificaciones por correo electrónico.";
                        $resultadoMails[$key]['estado'] = $estado;
                        $resultadoMails[$key]['correo'] = $value['correo_destinatario'];
                        $resultadoMails[$key]['mensaje'] = $mensajeServidor;
                    }
                }

                //Una vez se envia se actualiza el campo nuevamente
                NotificacionesMailIntraweb::where('id',$value['id'])->update([
                    'estado_notificacion'=>$estado,
                    'correo_destinatario'=>$value['correo_destinatario'],
                    'respuesta_servidor_notificacion' => $mensajeServidor ?? null,
                ]);
            }


            $filePDF = PDF::loadView('notificacionesintraweb::notificaciones_mail_intrawebs.report_pdf', ['data' => $resultadoMails])->setPaper(([0, 0, 841.89, 594.29]));
            return $filePDF->download("reporte_correos_reenviados.pdf");



        } catch (\Illuminate\Database\QueryException $error) {

            // Inserta el error en el registro de log
            AppBaseController::generateSevenLog('SendNotificationController', 'App\Http\Controllers\SendNotificationController - '.(!empty($user) ? $user->name : null).' -  Error: '.$error->getMessage());

        } catch (\Exception $e) {

            // Inserta el error en el registro de log
            AppBaseController::generateSevenLog('SendNotificationController', 'App\Http\Controllers\SendNotificationController - '.(!empty($user) ? $user->name : null).' -  Error: '.$e->getMessage() .' -  linea: '.$e->getLine());

        }



    }

    // Generate a unique tracking ID
    public function generateTrackingId($id)
    {
        try {

            $randomString = Str::random();
            $time = time();
            return hash('sha256', $id . $randomString . $time);

        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            AppBaseController::generateSevenLog('SendNotificationController', 'App\Http\Controllers\SendNotificationController - '. (Auth::check() ? Auth::user()->name: 'Sin sesión').' -  Error: '.$e->getMessage());
        }

    }

    /**
     * Envia una notificación al correo recibido por parámetro indicando una novedad con correo integrado
     *
     * @author Seven Soluciones Informáticas - 04/04/2025
     * @version 1.0.0
     *
     * @param $plantilla = Ruta blade donde esta creada la plantilla del mail - String
     * @param $asunto = Asunto del correo electronico - Json
     * @param $datosEmail = Contiene los datos del registro de donde salio la correspondencia - Array
     * @param $destinatario = Contiene los correos electronicos de las personas la cuales se le enviara una notificacion - Array/string
     */
    public static function enviarNotificacionCorreoIntegrado($plantilla, $asunto, $datosEmail, $destinatario)
    {
        $user = Auth::user();
        try {
            // Crear una instancia del controlador
            $instance = new self();
            //Genera el id para el correo
            $trackingId = $instance->generateTrackingId($destinatario);
            $data = new SendMail($plantilla, $datosEmail, $asunto, $trackingId);

            $data->build();

            Mail::to($destinatario)->send($data);
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            AppBaseController::generateSevenLog('SendNotificationController', 'App\Http\Controllers\SendNotificationController - '.(!empty($user) ? $user->name : null).' -  Error: '.$e->getMessage() .' -  linea: '.$e->getLine());
        }
    }
}
