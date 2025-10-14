<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreateCorreoIntegradoSpamRequest;
use Modules\Correspondence\Http\Requests\UpdateCorreoIntegradoSpamRequest;
use Modules\Correspondence\Repositories\CorreoIntegradoSpamRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Correspondence\Models\CorreoIntegradoSpam;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Correspondence\Models\CorreoIntegradoConfiguracion;
use DateTime;

use PhpImap\Exceptions\ConnectionException;
use PhpImap\Mailbox;
/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class CorreoIntegradoSpamController extends AppBaseController {

    /** @var  CorreoIntegradoSpamRepository */
    private $correoIntegradoSpamRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(CorreoIntegradoSpamRepository $correoIntegradoSpamRepo) {
        $this->correoIntegradoSpamRepository = $correoIntegradoSpamRepo;
    }

    /**
     * Muestra la vista para el CRUD de CorreoIntegradoSpam.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        return view('correspondence::correo_integrado_spams.index');

    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */       

    public function all(Request $request) {
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
            $correos =CorreoIntegradoSpam::whereRaw(base64_decode($request["f"]))->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get();
            $count =CorreoIntegradoSpam::whereRaw(base64_decode($request["f"]))->count();
        }else if(isset($request["cp"]) && isset($request["pi"])) {
    
            $correos = CorreoIntegradoSpam::latest()
            ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
            ->take(base64_decode($request["pi"]))
            ->get();
            $count =CorreoIntegradoSpam::count();
        }
        return $this->sendResponseAvanzado($correos, trans('data_obtained_successfully'), null, ["total_registros" => $count]);
    
        }
    

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateCorreoIntegradoSpamRequest $request
     *
     * @return Response
     */
    public function store(CreateCorreoIntegradoSpamRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $correoIntegradoSpam = $this->correoIntegradoSpamRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($correoIntegradoSpam->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoSpamController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoSpamController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateCorreoIntegradoSpamRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCorreoIntegradoSpamRequest $request) {

        $input = $request->all();

        /** @var CorreoIntegradoSpam $correoIntegradoSpam */
        $correoIntegradoSpam = $this->correoIntegradoSpamRepository->find($id);

        if (empty($correoIntegradoSpam)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $correoIntegradoSpam = $this->correoIntegradoSpamRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($correoIntegradoSpam->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoSpamController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoSpamController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un CorreoIntegradoSpam del almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var CorreoIntegradoSpam $correoIntegradoSpam */
        $correoIntegradoSpam = $this->correoIntegradoSpamRepository->find($id);

        if (empty($correoIntegradoSpam)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $correoIntegradoSpam->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoSpamController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoSpamController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('correo_integrado_spams').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName);
        }
    }

    
    public function obtenerCorreosIntegradosSpam_phpimap() {
        try {
            // Consulta el último registro de configuración de correo integrado
            $correo_oficiales = CorreoIntegradoConfiguracion::latest()->get();
            // Se crea la carpeta según la vigencia actual, donde se almacenan los adjuntos de los correos
            @mkdir(storage_path("app/public/container/correo_integrado_".date("Y")."/"), 0755, true);
            foreach ($correo_oficiales as $key => $correo_oficial) {
                
                // Se obtienen los valores de la configuración IMAP, si no esta configurado, se asignan los siguientes valores por defecto
                $servidor = $correo_oficial["servidor"] ?? "imap.gmail.com";
                $puerto = $correo_oficial["puerto"] ?? 993;
                $seguridad = $correo_oficial["seguridad"] ?? "SSL";
                $obtener_desde = $correo_oficial["obtener_desde"] ??  date("Y-m-d"); // Fecha de ayer
    
                // Cree instancia de PhpImap\Mailbox para todas las acciones adicionales
                $mailbox = new Mailbox(
                    '{' . $servidor . ':' . $puerto . '/imap/' . strtolower($seguridad) . '}[Gmail]/Spam', // Nombre correcto para Gmail
                    $correo_oficial["correo_comunicaciones"], // Usuario
                    $correo_oficial["correo_communicaciones_clave"] // Contraseña
                );
                
                // Establezca algunos argumentos de conexión (si corresponde)
                $mailbox->setConnectionArgs(
                    CL_EXPUNGE // Borrar los correos eliminados al cerrar el buzón
                );
    
                // Obtiene la fecha actual del sistema
                $fecha_actual = date("Y-m-d");
                // Almacena el número total de comunicaciones descargadas del buzón de correos
                $comunicaciones_descargadas = 0;
    
                // Si la fecha de obtener los correos es menor o igual a la fecha actual, ingresa al ciclo
                while($obtener_desde <= $fecha_actual) {
                    try {
                        // Criterios de selección de correos
                        $mail_ids = $mailbox->searchMailbox('ON "'.$obtener_desde.'"');
                    } catch (ConnectionException $ex) {
                        exit('IMAP connection failed: '.$ex->getMessage());
                    } catch (Exception $ex) {
                        exit('An error occured: '.$ex->getMessage());
                    }
    
                    // Obtiene el número total de comunicaciones de la vigencia actual
                    // $total_comunicaciones = CorreoIntegrado::where("vigencia", date("Y"))->count();
                    // $total_comunicaciones == 0 ? $total_comunicaciones = 1 : $total_comunicaciones += 1;
                    // Recorre los correos obtenidos según el criterio aplicado anteriormente
                    foreach ($mail_ids as $mailId) {
                        $email = $mailbox->getMail(
                            $mailId, // ID of the email, you want to get
                            false // Do NOT mark emails as seen (optional)
                        );
                     
                        // Consulta para determinar si existe un registro de una comunicacion
                        $correo_existente = CorreoIntegradoSpam::where("uid", explode("@", $email->messageId)[0])->count();
                        if($correo_existente == 0 && $email->fromAddress) {
                            $messageId = $email->messageId ?? "Sin Message-ID";
                            $fecha = $email->date ?? "Fecha no disponible";

                            if (preg_match('/\s*\(-\d{2}\)$/', $fecha)) {
                                $fecha = preg_replace('/\s*\(-\d{2}\)$/', '', $fecha);
                            }
                            
                            $fecha = new DateTime($fecha);
                            $fecha = $fecha->format('Y-m-d H:i:s');
                            $remitente = $email->fromAddress ?? "Remitente desconocido";
                            $asunto = $email->subject ?? "Sin asunto";
                            echo "Message-ID: " . htmlspecialchars($messageId) . "<br>";
                            echo "Fecha: $fecha ". "<br>";
                            echo "Remitente: $remitente ". "<br>";
                            echo "----------------------------". "<br>";
                            $correo_integrado = CorreoIntegradoSpam::create([
                                "uid" => explode("@", $email->messageId)[0],
                                "correo_remitente" => $remitente,
                                "fecha" => $fecha,
                                "asunto" => $asunto,
                            ]);
                            $comunicaciones_descargadas += 1;
                        }
                    }


                    // Incrementa en un día la fecha de la variable obtener_desde
                    $timestamp = strtotime($obtener_desde); // Convertir a timestamp
                    $timestamp += 86400; // Sumar un día
                    $obtener_desde = date("Y-m-d", $timestamp); // Convertir a formato de fecha
                }
    
                // Desconecta la sesión con el servidor de correos
                $mailbox->disconnect();
    
                echo "<br /><strong>Total comunicaciones descargadas: $comunicaciones_descargadas</strong><br />";
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\ExternalController - Error: '.$e->getMessage().' - Linea: '.$e->getLine());
            // Retorna mensaje de error de base de datos
            dd($e->getMessage(), $e->getLine());
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\ExternalController - Error: '.$e->getMessage().' - Linea: '.$e->getLine());
            // Retorna error de tipo logico
            dd($e->getMessage(), $e->getLine());
        }
    }
}
