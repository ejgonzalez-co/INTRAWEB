<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreateCorreoIntegradoRequest;
use Modules\Correspondence\Http\Requests\UpdateCorreoIntegradoRequest;
use Modules\Correspondence\Repositories\CorreoIntegradoRepository;
use App\Http\Controllers\AppBaseController;
use App\Mail\SendMail;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use GuzzleHttp\Cookie\CookieJarInterface;
use Illuminate\Support\Facades\Mail;
use Modules\Correspondence\Models\CorreoIntegrado;
use Modules\Correspondence\Models\CorreoIntegradoHistorial;
use Modules\Correspondence\Models\CorreoIntegradoAdjunto;
use Modules\Configuracion\Models\Variables;
use Modules\Correspondence\Http\Controllers\ExternalReceivedController;
use Modules\Correspondence\Http\Requests\CreateExternalReceivedRequest;
use Modules\Correspondence\Models\CorreoIntegradoConfiguracion;
use App\Http\Controllers\SendNotificationController;

use Webklex\IMAP\Client;
use Zend\Mail\Storage\Imap;
use App\Http\Controllers\JwtController;
use Modules\Tenants\Models\Tenant;
use Aws\S3\S3Client;
use Carbon\Carbon;
use DateTime;

// declare(strict_types=1);

require_once __DIR__.'/../../../../vendor/autoload.php';

use PhpImap\Exceptions\ConnectionException;
use PhpImap\Mailbox;
/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class CorreoIntegradoController extends AppBaseController {

    /** @var  CorreoIntegradoRepository */
    private $correoIntegradoRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(CorreoIntegradoRepository $correoIntegradoRepo) {
        $this->correoIntegradoRepository = $correoIntegradoRepo;
    }

    /**
     * Muestra la vista para el CRUD de CorreoIntegrado.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        // if(Auth::user()->hasRole(["Administrador Correspondencia","Correspondencia Recibida Admin"])){

        if(!Auth::user()->hasRole(["Ciudadano"])){
            $clasificacion = Variables::where('name', 'clasificacion_documental_recibida')->pluck('value')->first() ?? 'no';
            return view('correspondence::correo_integrados.index',compact(['clasificacion']));
        }
        return view("auth.forbidden");

    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 2.0.0
     *
     * @return Response
     */
    public function all(Request $request) {

        if(Auth::user()->hasRole(["Administrador Correspondencia","Correspondencia Recibida Admin"])){
            // Variable para contar el número total de registros de la consulta realizada
            $count_comunicaciones = 0;
            // Reemplaza los espacios en blanco por + en la cadena de filtros codificada
            $request["f"] = str_replace(" ", "+", $request["f"]);
            // Decodifica los campos filtrados
            $filtros = base64_decode($request["f"]);
            $filtro_tablero_correspondencia_pqrsd = null;
            // Valida si en los filtros realizados viene el filtro de filtro_tablero_correspondencia
            if(stripos($filtros, "filtro_tablero_correspondencia") !== false) {
                // Se separan los filtros por el operador AND, obteniendo un array
                $filtro = explode(" AND ", $filtros);
                // Se obtiene la posición del filtro de filtro_tablero_correspondencia en el array de filtros
                $posicion = array_keys(array_filter($filtro, function($value) {
                    return stripos($value, 'filtro_tablero_correspondencia') !== false;
                }))[0];
                // Se elimina el filtro de filtro_tablero_correspondencia del array de filtro
                unset($filtro[$posicion]);
                // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
                $filtros = implode(" AND ", $filtro);
                $filtro_tablero_correspondencia_pqrsd = 0;
            } else if(stripos($filtros, "filtro_tablero_pqrsd") !== false) {
                // Se separan los filtros por el operador AND, obteniendo un array
                $filtro = explode(" AND ", $filtros);
                // Se obtiene la posición del filtro de filtro_tablero_pqrsd en el array de filtros
                $posicion = array_keys(array_filter($filtro, function($value) {
                    return stripos($value, 'filtro_tablero_pqrsd') !== false;
                }))[0];
                // Se elimina el filtro de filtro_tablero_pqrsd del array de filtro
                unset($filtro[$posicion]);
                // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
                $filtros = implode(" AND ", $filtro);
                $filtro_tablero_correspondencia_pqrsd = 1;
            }
            // * cp: currentPage
            // * pi: pageItems
            // * f: filtros
            // Valida si existen las variables del paginado y si esta filtrando
            if(!empty($request["page"])) {

                $comunicacionesConsulta = CorreoIntegrado::select(
                    'id',
                    'nombre_remitente',
                    'consecutivo',
                    'asunto',
                    'correo_remitente',
                    'fecha',
                    'estado',
                    'clasificacion'
                )
                ->whereNull("deleted_at")
                ->when($filtros || $filtro_tablero_correspondencia_pqrsd !== null, function($query) use($filtros, $filtro_tablero_correspondencia_pqrsd) {
                    if($filtro_tablero_correspondencia_pqrsd === null) {
                        $query->whereRaw($filtros);
                    } else if($filtro_tablero_correspondencia_pqrsd==0){
                        // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                        $query->whereRelation("externalReceived", "npqr", null);
                    } else {
                        // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                        $query->whereRelation("externalReceived", "npqr", $filtro_tablero_correspondencia_pqrsd);
                    }
                })
                ->orderBy('fecha','desc')
                ->paginate(base64_decode($request["pi"])); // Aquí se define el número de registros por página;

                $count_comunicaciones = $comunicacionesConsulta->total(); // Total de documentos encontrados
                $comunicaciones = $comunicacionesConsulta->toArray()["data"]; // Consulta los documentos según los filtros
            } else {
                // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                $comunicaciones = CorreoIntegrado::whereNull("deleted_at")->orderBy('fecha','desc')->get()->toArray();
                // Contar el número total de registros de la consulta realizada según el paginado seleccionado
                $count_comunicaciones = CorreoIntegrado::whereNull("deleted_at")->count();
            }
            // dd($comunicaciones, $filtro_tablero_correspondencia_pqrsd);
            return $this->sendResponseAvanzado($comunicaciones, trans('data_obtained_successfully'), null,
            ["total_registros" => $count_comunicaciones]);

        }else{

            return $this->sendResponseAvanzado([], trans('data_obtained_successfully'), null,["total_registros" => 0]);

        }
    }

    /**
     * Obtiene los valores del tablero principal
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function consultaTablero() {
        try {
            // Almacena los valores del estado de las comunicaciones para mostrar en el tablero del usuario administrador
            $comunicaciones_state = [];
            // Vigencia de consulta por defecto para el tablero
            $vigencia = date("Y");
            // Obtiene la cantidad total de comunicaciones según la vigencia
            $total = CorreoIntegrado::whereNull("deleted_at")->count();
            // Obtiene la cantidad de todas las comunicaciones en estado Sin clasificar según la vigencia
            $sin_clasificar = CorreoIntegrado::where("estado", "Sin clasificar")->whereNull("deleted_at")->count();
            // Obtiene la cantidad de todas las comunicaciones donde la clasificación sea correspondencia, que no tenga pqr asociado y según la vigencia
            $clasificado_correspondencia = CorreoIntegrado::where("clasificacion", "Correspondencia")->whereNull("deleted_at")->whereRelation("externalReceived", "npqr", null)->count();
            // Obtiene la cantidad de todas las comunicaciones donde la clasificación sea correspondencia, que tenga pqr asociado y según la vigencia
            $clasificado_pqrsd = CorreoIntegrado::where("clasificacion", "Correspondencia")->whereNull("deleted_at")->whereRelation("externalReceived", "npqr", 1)->count();
            // Obtiene la cantidad de todas las comunicaciones donde la clasificación sea Comunicación no oficial y según la vigencia
            $comunicacion_no_oficial = CorreoIntegrado::where("clasificacion", "Comunicación no oficial")->whereNull("deleted_at")->count();
            // Se crea el array con las variables del consolidado creadas anteriormente
            $comunicaciones_state = compact("total", "sin_clasificar", "clasificado_correspondencia", "clasificado_pqrsd", "comunicacion_no_oficial");

            return $this->sendResponse($comunicaciones_state, trans('data_obtained_successfully'));
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('CorreoIntegrado','Modules\Correspondence\Http\Controllers\CorreoIntegradoController - ' .(Auth::user()->name ?? 'Usuario Desconocido') .' -  Error: ' . $e->getMessage() .' -  Linea: ' . $e->getLine());            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateCorreoIntegradoRequest $request
     *
     * @return Response
     */
    public function store(CreateCorreoIntegradoRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $correoIntegrado = $this->correoIntegradoRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($correoIntegrado->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$e->getMessage());
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
     * @param CreateExternalReceivedRequest $request El tipo de esta variable fue modificado, ya que se requiere para guardar la correspondencia recibida
     *
     * @return Response
     */
    public function update($id, CreateExternalReceivedRequest $request) {
        // Elimina el id del request, para que no entre en conflicto con la correspondencia
        unset($request["id"]);
        $input = $request->all();
        // Asigna el id y nombre del usuario que esta clasificando la comunicación
        $input["users_id"] = Auth::user()->id;
        $input["nombre_usuario"] = Auth::user()->name;
        $input["estado"] = "Clasificado";
        $input["correo_remitente"] = $input["citizen_email"];
        $input["nombre_remitente"] = $input["citizen_name"];
        /** @var CorreoIntegrado $correoIntegrado */
        $correoIntegrado = $this->correoIntegradoRepository->find($id);

        if (empty($correoIntegrado)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $correoIntegradoConfiguracion = CorreoIntegradoConfiguracion::latest()->first();
            // Valida si la clasificación de la comunicación fue una correspondencia
            if($input["clasificacion"] == "Correspondencia") {

                if ( !empty($input["functionary_name"]) && empty($input["functionary_id"])) {
                    return $this->sendSuccess('<strong>El funcionario ingresado no existe.</strong>'. '<br>' . "Por favor seleecione un funcionario valido.", 'warning');
                }
                // Se hace la solicitud a la creación de la correspondencia recibida
                $externa = app(ExternalReceivedController::class)->store($request);
                $externa= JwtController::decodeToken($externa["data"]);
                $externa = array('data' => (array) $externa );

                // Se asocia el id de la correspodencia recibida creada anteriormente
                $input["external_received_id"] = $externa["data"]["id"];
                // Mensaje que va dirijido al ciudadano, informándole qué clasificación se le dió al correo enviado por el
                $notificacion["mensaje"] = $correoIntegradoConfiguracion["notificacion_correspondencia"]." ".$externa["data"]["consecutive"];

                // Valida si la clasificación de la comunicación fue clasificada como PQR
                if(isset($externa["pqr"])) {
                    // Mensaje que va dirijido al ciudadano, informándole qué clasificación se le dió al correo enviado por el
                    $notificacion["mensaje"] = $correoIntegradoConfiguracion["notificacion_pqr"]." ".$externa["data"]["pqr"];
                }
                // Asunto del email
                // $asunto = json_decode('{"subject": "Notificación de la comunicación '.$correoIntegrado["consecutivo"].'"}');
                // // Envia notificacion al funcionario asignado
                // SendNotificationController::SendNotification('correspondence::correo_integrados.email.notificacion_ciudadano',$asunto,notificacion,$externa["data"]["citizen_email"],'Comunicaciones por correo');

                $notificacion['consecutive'] = $correoIntegrado["consecutivo"];
                $notificacion["state"] = "Clasificado";
                $notificacion["id"] = $id;

                // if(!empty($externa["data"]["citizen_email"]))
                //     // Envia notificacion al funcionario asignado
                //     SendNotificationController::SendNotification('correspondence::correo_integrados.email.notificacion_ciudadano',$asunto,$notificacion,$externa["data"]["citizen_email"],'Comunicaciones por correo');

            } else if($input["clasificacion"] == "Comunicación no oficial") {
                // Mensaje que va dirijido al ciudadano, informándole qué clasificación se le dió al correo enviado por el
                $notificacion["mensaje"] = $correoIntegradoConfiguracion["notificacion_no_oficial"];
            }
            // Actualiza el registro
            $correoIntegrado = $this->correoIntegradoRepository->update($input, $id);
            $correoIntegrado["comunicaciones_por_correo_id"] = $correoIntegrado->id;
            // Se crea el registro historial de la comunicación
            CorreoIntegradoHistorial::create($correoIntegrado->toArray());
            $correoIntegrado->correoIntegradoHistorial;
            $correoIntegrado->adjuntosCorreo;
            $correoIntegrado->externalReceived;
            // $correoIntegrado["id"] = isset($externa) ?  $externa["data"]['id'] : NULL;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($correoIntegrado->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {

            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name?? 'Usuario Desconocido ').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '.(Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine(). ' Consecutivo: '. ($correoIntegrado['consecutivo'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un CorreoIntegrado del almacenamiento
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
        /** @var CorreoIntegrado $correoIntegrado */
        $correoIntegrado = $this->correoIntegradoRepository->find($id);
        if (empty($correoIntegrado)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $correoIntegrado->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$e->getMessage() . ' Linea: '. $e->getLine(). ' Consecutivo: '. ($correoIntegrado['consecutivo'] ?? 'Desconocido') );
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
        $fileName = time().'-'.trans('correo_integrados').'.'.$fileType;

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

    public function guardarConfiguracionCorreo(Request $request) {
        $input = $request->all();
        // ID de la configuración en caso de que ya haya una
        $id = $input["configuracion_id"] ?? null;
        // Asigna el id y nombre del usuario que esta clasificando la comunicación
        $input["users_id"] = Auth::user()->id;
        /** @var CorreoIntegrado $correoIntegrado */
        $correoIntegradoConfiguracion = CorreoIntegradoConfiguracion::find($id);

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si la clasificación de la comunicación fue una correspondencia
            if(empty($correoIntegradoConfiguracion)) {
                // Actualiza el registro
                $correoIntegradoConfiguracion = CorreoIntegradoConfiguracion::create($input);
            } else {
                // Actualiza el registro
                $correoIntegradoConfiguracion = $correoIntegradoConfiguracion->update($input);
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse(null, trans('Configuración actualizada correctamente'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * https://github.com/Webklex/php-imap
     *
     * Tíldes en título
     * A veces no tiene HTML toca texto plano
     * @param Request $request
     * @return void
     */
    public function obtenerCorreosIntegrados_Webklex(Request $request) {
        /* Alternative by using the Facade*/
        $oClient = \Webklex\IMAP\Facades\Client::account('default');

        //Connect to the IMAP Server
        $oClient->connect();

        //Get all Mailboxes
        /** @var \Webklex\IMAP\Support\FolderCollection $aFolder */
        $oFolder = $oClient->getFolder('INBOX');

        $aMessage = $oFolder->query()->since('13.06.2023')->all()->get();
        /** @var \Webklex\PHPIMAP\Message $message */
        foreach($aMessage as $message){


            echo $message->getSubject()." - ".$message->getDate().'<br />';
            echo 'Attachments: '.$message->getAttachments()->count().'<br />';
            echo iconv_mime_decode($message->getRawBody());
            // echo $message->getHTMLBody();
        }
    }

    /**
     * https://docs.laminas.dev/laminas-mail/
     *
     * Los mensajes se obtienen mal codificados
     * No veo función para obtener los adjuntos
     * @return void
     */
    public function obtenerCorreosIntegrados_laminas() {

        // Connecting with Imap:
        $mail = new Imap([
            'host'     => 'smtp.gmail.com',
            'user'     => 'ggonzalez@seven.com.co',
            'password' => 'uphhgijbrazhblop',
            'port'     => '993',
            'ssl'      => 'SSL',
        ]);

        $message = $mail->getMessage(1);

        printf("Mail from '%s': %s\n", $message->from, $message->subject);

        $part = $message;
        while ($part->isMultipart()) {
            $part = $message->getPart(1);
        }
        echo 'Type of this part is ' . strtok($part->contentType, ';') . "\n";
        echo "Content:\n";
        echo $part->getContent();

        // echo $mail->countMessages() . " messages found\n";
        // foreach ($mail as $message) {
        //     printf("Mail from '%s': %s\n", $message->from, $message->subject);
        // }
    }

    function extractUniqueFilename($fileName) {

        $dotExtensionInicial = explode(".",$fileName);
        $dotExtension = end($dotExtensionInicial);

        $filenameWithoutExtension = strstr($fileName, $dotExtension, true);
        $fileNameFinal = $filenameWithoutExtension . $dotExtension;
        return $this->cleanFileName($fileNameFinal);
    }

    /**
     * Última librería consultada para obtener los correos
     * https://github.com/barbushin/php-imap
     *
     */
    public function obtenerCorreosIntegrados_phpimap_old() {
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
                $obtener_desde = $correo_oficial["obtener_desde"] ?? date("Y-m-d");
                // Cree instancia de PhpImap\Mailbox para todas las acciones adicionales
                $mailbox = new Mailbox(
                    '{'.$servidor.':'.$puerto.'/imap/'.$seguridad.'}INBOX', // Carpeta de servidor IMAP y buzón
                    $correo_oficial["correo_comunicaciones"], // Nombre de usuario para el buzón configurado antes
                    $correo_oficial["correo_communicaciones_clave"], // Contraseña para el nombre de usuario configurado antes
                );

                // Establezca algunos argumentos de conexión (si corresponde)
                $mailbox->setConnectionArgs(
                    CL_EXPUNGE // Borrar los correos eliminados al cerrar el buzón
                );
                // Obtiene la fecha actual del sistema
                // $fecha_actual = date("Y-m-d");
                $fecha_actual = '2024-10-24';
                // Almacena el número total de comunicaciones descargadas del buzón de correos
                $comunicaciones_descargadas = 0;
                // Si la fecha de obtener los correos es menor o igual a la fecha actual, ingresa al ciclo
                while($obtener_desde <= $fecha_actual) {
                    try {
                        // Criterios de selección de correos
                        // $obtener_desde = date("Y-m-d");
                        // $obtener_desde = '2023-06-01';
                        // $mail_ids = $mailbox->searchMailbox('FROM "informacionruntot@runt.com.co" ON "04 Jun 2024"');
                        $mail_ids = $mailbox->searchMailbox('ON "'.$obtener_desde.'"');
                        // $mail_ids = $mailbox->searchMailbox('ALL');
                    } catch (ConnectionException $ex) {
                        exit('IMAP connection failed: '.$ex->getMessage());
                    } catch (Exception $ex) {
                        exit('An error occured: '.$ex->getMessage());
                    }
                    // Obtiene el número total de comunicaciones de la vigencia actual
                    $total_comunicaciones = CorreoIntegrado::where("vigencia", date("Y"))->count();
                    $total_comunicaciones == 0 ? $total_comunicaciones = 1 : $total_comunicaciones += 1;
                    // Recorre los correos obtenidos según el criterio aplicado anteriormente
                    foreach ($mail_ids as $mail_id) {
                        // Obtiene el correo uno a uno según el id
                        $email = $mailbox->getMail(
                            $mail_id, // ID of the email, you want to get
                            false // Do NOT mark emails as seen (optional)
                        );

                        // Obtener el cuerpo del mensaje
                        $body = $email->textPlain; // o $mail->textHtml si es HTML
                        // Consulta para determinar si existe un registro de una comunicacion
                        // $correo_existente = CorreoIntegrado::where("uid", $mail_id)->orWhere(function($q) use($email) {
                        //     $q->where("fecha", $email->date);
                        //     $q->where("correo_remitente", $email->fromAddress);
                        // })->count();

                        // Consulta para determinar si existe un registro de una comunicacion
                        $correo_existente = CorreoIntegrado::where("uid", explode("@", $email->messageId)[0])->count();
                        // Si la comunicación (correo) no existe, empieza el proceso de descarga
                        if($correo_existente == 0 && $email->fromAddress) {
                            echo "+------ P A R S I N G ------+<br />";
                            // Almacena la información del correo para posteriormente guardarla en la BD
                            $correo_integrado = [];

                            // Busca el nombre del remitente original (último "De:" en caso de reenvíos)
                            if (preg_match_all('/(?:De|From):\s*(.*?)\s*<?([^>\s]+)>?/', $body, $matches, PREG_SET_ORDER)) {
                                // Obtiene el último remitente (el original)
                                if (!empty($matches)) {
                                    $lastSender = end($matches);
                                    $namesOriginal = trim($lastSender[1]) ?: null; // Nombre del remitente original
                                    $resentEmail = trim($lastSender[2]) ?: null;  // Correo del remitente original
                                }

                            } else {
                                $namesOriginal = null;
                                $resentEmail = null;
                            }

                            // Asigna el consecutivo del registro según la vigencia del año actual y la cantidad de comunicaciones
                            $correo_integrado["consecutivo"] = 'CRCE-'.date("Y")."-".$total_comunicaciones;
                            $correo_integrado["asunto"] = $email->subject;
                            // Almacena la ruta de los adjuntos del contenido del correo, con el id que no se visualizan desde el detalle de la comunicación
                            $id_adjuntos = [];
                            // Almacena la ruta de los adjuntos del correo en el servidor, una vez descargados
                            $ruta_adjuntos = [];
                            // Obtiene la información de los adjuntos del correo si los posee
                            $attachments = $email->getAttachments();
                            // Valida si tiene adjuntos
                            if (!$mailbox->getAttachmentsIgnore() && count($attachments)) {
                                // Recorre los archivos adjuntos uno por uno
                                foreach ($attachments as $key => $attachment) {
                                    // Generar un número aleatorio entre 1 y 99
                                    $number = rand(1, 99);
                                    // Generar una letra aleatoria entre A y Z
                                    $letter = chr(rand(65, 90)); // 65 es el código ASCII para 'A' y 90 para 'Z'
                                    // Se le asigna como prefijo el número incremental de la comunicación, una letra y un número al nombre del adjunto, para evitar duplicados
                                    $attachmentNameUnique = $total_comunicaciones.$letter.$number.$this->extractUniqueFilename($attachment->name);

                                    $attachments[$key]->name = trim($attachmentNameUnique);
                                    $attachments[$key]->setFilePath(storage_path("app/public/container/correo_integrado_".date("Y")."/".trim($attachmentNameUnique)));
                                    // Almacena la ruta de los adjuntos con el id del mismo, este id es generado por el correo
                                    $id_adjuntos[] = "cid:".$attachment->contentId;
                                    // Almacena la ruta de los adjuntos en el servidor, una vez descargados del correo
                                    $ruta_adjuntos[] = "\storage\container\correo_integrado_".date("Y")."\\".$attachmentNameUnique;
                                }

                            }
                            // Valida si el correo tiene el contenido del mismo en html, de lo contrario lo obtiene en texto plano
                            if ($email->textHtml) {
                                /**
                                 * Se elimina la información del texto plano del calendario en el contenido del correo, en caso tal de que lo posea,
                                 * también, se reemplazan las rutas de los adjuntos del contenido del correo, por las rutas de los mismos en el servidor
                                 */
                                $correo_integrado["contenido"] = str_replace($id_adjuntos, $ruta_adjuntos, preg_replace("/BEGIN:VCALENDAR.*.END:VCALENDAR/s", "", $email->textHtml));
                            } else {
                                /**
                                 * Se elimina la información del texto plano del calendario en el contenido del correo, en caso tal de que lo posea,
                                 * también, se reemplazan las rutas de los adjuntos del contenido del correo, por las rutas de los mismos en el servidor
                                 */
                                $correo_integrado["contenido"] = str_replace($id_adjuntos, $ruta_adjuntos, preg_replace("/BEGIN:VCALENDAR.*.END:VCALENDAR/s", "", $email->textPlain));
                            }
                            // Asigna la información del correo al arreglo para posteriormente guardarla
                            $correo_integrado["correo_remitente"] = $resentEmail ?? $email->fromAddress;
                            $correo_integrado["nombre_remitente"] = $namesOriginal ?? (string) ($email->fromName ?? $email->fromAddress);

                            $correo_integrado["correo_configurado"] = $correo_oficial["correo_comunicaciones"] ?? '';

                            if (strpos($email->date, ',') !== false) {
                                $date_string = $email->date;

                                // Eliminar la parte entre paréntesis
                                $date_string = preg_replace('/\s*\(.*?\)\s*$/', '', $date_string);

                                try {
                                    $date = new DateTime($date_string);
                                    $date = $date->format('Y-m-d H:i:s');
                                } catch (Exception $e) {
                                    dd('Error: ' . $e->getMessage());
                                    // echo 'Error: ' . $e->getMessage();
                                }
                            } else {
                                $date = $email->date;
                            }
                            $correo_integrado["fecha"] = $date;
                            $correo_integrado["estado"] = "Sin clasificar";
                            $correo_integrado["uid"] = explode("@", $email->messageId)[0];
                            $correo_integrado["vigencia"] = date("Y", strtotime($date));
                            // Mensajes de impresión para ver el progreso de la descarga de correos
                            echo 'from-name: '.(string) ($email->fromName ?? $email->fromAddress)."<br />";
                            echo 'from-email: '.(string) $email->fromAddress."<br />";
                            echo 'to: '.(string) $email->toString."<br />";
                            echo 'subject: '.(string) $email->subject."<br />";
                            echo 'message_id: '.(string) $email->messageId."<br />";

                            echo 'mail has attachments? ';
                            if ($email->hasAttachments()) {
                                echo "Yes<br />";
                            } else {
                                echo "No<br />";
                            }

                            if (!empty($email->getAttachments())) {
                                echo \count($email->getAttachments())." attachements<br />";
                            }
                            // Crea el registro de la comunicación del correo descargasdo
                            $correo_integrado = CorreoIntegrado::create($correo_integrado);
                            // Obtiene el id de la comunicación creada anteriormente
                            $correo_integrado["comunicaciones_por_correo_id"] = $correo_integrado->id;
                            // Crea el registro historial de la comunicación
                            $correo_integrado_historial = CorreoIntegradoHistorial::create($correo_integrado->toArray());
                            // Incrementa el consecutivo de las comunicaciones
                            $total_comunicaciones += 1;
                            // Valida si tiene adjuntos
                            if (!$mailbox->getAttachmentsIgnore() && count($attachments)) {

                                $adjuntos_correo = [];
                                // Recorre los archivos adjuntos uno por uno y los almacena en la tabla de adjuntos, la ruta y nombre
                                foreach ($attachments as $attachment) {
                                    echo '--> Guardando adjunto '.(string) $attachment->name.'...<br />';
                                    // Set individually filePath for each single attachment
                                    $ruta_documento = "container/correo_integrado_".date("Y")."/".$attachment->name;
                                    // Lista de MIME types que no se deben de guardar en AWS
                                    $validMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                                    // Extraer el MIME type principal sin el charset
                                    $mimeParts = explode(';', $attachment->mime);
                                    $mainMimeType = trim($mimeParts[0]);
                                    // Valida el tipo de almacenamiento general, si es AWS, obtiene el nombre del bucket para guardar el adjunto
                                    if(env("TIPO_ALMACENAMIENTO_GENERAL") == "AWS" && !in_array($mainMimeType, $validMimeTypes)) {
                                        $this->guardarDocumento($ruta_documento, $attachment->getContents(), $attachment->mime);
                                    } else {
                                        if ($attachment->saveToDisk()) {
                                            echo " OK, saved!<br />";
                                        } else {
                                            echo " ERROR, could not save!<br />";
                                        }
                                    }

                                    CorreoIntegradoAdjunto::create(["adjunto" => $ruta_documento, "comunicaciones_por_correo_id" => $correo_integrado->id]);
                                }

                            }

                            if ($email->textHtml) {
                                echo "Message HTML:<br />".$email->textHtml."<br />";
                            } else {
                                echo "Message Plain:<br />".$email->textPlain."<br /><br />";
                            }

                            if (!empty($email->autoSubmitted)) {
                                // Marque el correo electrónico como "leido"/"visto"
                                $mailbox->markMailAsRead($mail_id);
                                echo "+------ IGNORING: Auto-Reply ------+<br />";
                            }

                            if (!empty($email_content->precedence)) {
                                // Marque el correo electrónico como "leido"/"visto"
                                $mailbox->markMailAsRead($mail_id);
                                echo "+------ IGNORING: Non-Delivery Report/Receipt ------+<br />";
                            }
                            // Incrementa el total de comunicaciones descargadas en la ejecución del presente script
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
            $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController -   Error: '.$e->getMessage().' - Linea: '.$e->getLine());
            // Retorna mensaje de error de base de datos
            dd($e->getMessage(),$e->getLine());
        } catch (\Exception $e) {

            // Inserta el error en el registro de log
            $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController  -  Error: '.$e->getMessage().' - Linea: '.$e->getLine());
            // Retorna error de tipo logico
            dd($e->getMessage(),$e->getLine());
        }
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author Seven Soluciones Informáticas S.A.S. - Ene. 17 - 2024
     * @version 1.0.0
     *
     * @param int $id del registro procediente de las entradas recientes del dashboard
     */
    public function showFromDashboard($id)
    {
        $correoIntegrado = CorreoIntegrado::where("consecutivo", $id)->first();
        if (empty($correoIntegrado)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Relaciones
        $correoIntegrado->correoIntegradoHistorial;
        $correoIntegrado->adjuntosCorreo;
        $correoIntegrado->externalReceived;
        return $this->sendResponse($correoIntegrado->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author Erika Johana Gonzalez
     * @version 1.0.0
     *
     * @param int $id
     */
    public function show($id)
    {

        // with(["correoIntegradoHistorial", "adjuntosCorreo", "externalReceived"])


        $correoIntegrado = $this->correoIntegradoRepository->find($id);

        if (empty($correoIntegrado)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $correoIntegrado->correoIntegradoHistorial;
        $correoIntegrado->adjuntosCorreo;
        $correoIntegrado->externalReceived;

        return $this->sendResponse($correoIntegrado->toArray(), trans('data_obtained_successfully'));

    }
    /**
     * Almacena el documento recibido por parámetro en un bucket S3 de AWS
     *
     * @author Seven Soluciones Informáticas S.A.S. - May. 27 - 2024
     * @version 1.0.0
     *
     * @param string $ruta_documento - Ruta donde se va a guardar el archivo
     * @param string $contenido_documento - Cuerpo del documento
     * @param string $mime_type - Tipo de contenido del archivo
     *
     * @return string $ruta_documento - Ruta final del documento almacenado
     */
    public function guardarDocumento($ruta_documento, $contenido_documento, $mime_type) {
        // Consulta el nombre del bucket configurado en las variables de entorno .env
        $nombreBucket = env("AWS_BUCKET");
        if(!$nombreBucket) {
            return $this->sendResponse(null, "Se requiere configurar el nombre del contenedor. Para recibir asistencia, contacte al equipo de soporte técnico de Intraweb", "warning");
        }
        // Verifica si la variable 'TENANCY_HABILITADO' del .env es = true, significa que es un sitio multitenancy, adicionalmente se verifica que sea un inquilino
        if(env("TENANCY_HABILITADO") && Tenant::current()) {
            // Obtiene información del tenant actual
            $tenant = app('currentTenant');
            // Crea la ruta del adjunto con el prefijo del tenant, validando si tiene la palabra "public" para ser eliminada de la ruta
            $ruta_documento = "tenant_".$tenant["id"]."/".$ruta_documento;
        }

        // Crea un nuevo cliente para comunicarse con el bucket S3 de AWS
        $s3 = new S3Client([
            'version' => 'latest',
            'region'  => env('AWS_DEFAULT_REGION'),
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        // Se guarda el adjunto en el bucket S3 de AWS, según el nombre del bucket y ruta indicada anteriormente
        $file = $s3->putObject([
            'Bucket'     => $nombreBucket,
            'Key'        => $ruta_documento,
            'Body'       => $contenido_documento,
            'StorageClass' => 'GLACIER_IR',
            'ContentType' => $mime_type,
        ]);
        // Retorna la ruta del documento final ya almacenado
        return $ruta_documento;
    }

    /**
     * Obtiene los correos integrados desde una cuenta de correo según las fechas especificadas (si se provee) utilizando la librería PHP-IMAP.
     * 
     * @param string|null $fecha_inicial Fecha inicial para filtrar correos, si es null, no se aplica filtro de fecha inicial.
     * @param string|null $fecha_final   Fecha final para filtrar correos, si es null, no se aplica filtro de fecha final.
     * 
     * @return mixed Devuelve la lista de correos recuperados o el resultado del procesamiento de los mismos.
     * 
     * @throws \Exception Si ocurre un error en la conexión con el servidor o durante la recuperación de correos.
     */
    public function obtenerCorreosIntegrados_phpimap($fecha_inicial = null, $fecha_final = null) {
        // 1. Verificar si ya hay un proceso corriendo
        $proceso = DB::table('procesos_en_ejecucion')->where('proceso', 'obtener_correos')->first();

        // Si existe un registro del proceso en ejecución
        if ($proceso && !$fecha_inicial && !$fecha_final) {
            // Se convierte el valor de 'en_ejecucion' a booleano para evaluar si está activo
            $enEjecucion = (bool) $proceso->en_ejecucion;
            // Se obtiene la fecha de la última actualización del proceso y se convierte a un objeto Carbon
            $ultimaActualizacion = \Carbon\Carbon::parse($proceso->updated_at);
            // Si el proceso está en ejecución y fue actualizado hace 30 minutos o menos
            if ($enEjecucion && now()->diffInMinutes($ultimaActualizacion) <= 30) {
                // Se registra en el log que el proceso sigue en ejecución y se aborta la nueva ejecución
                $this->generateSevenLog(
                    'correo_integrado',
                    'Modules\Correspondence\Http\Controllers\CorreoIntegradoController  -  El proceso de obtener correos sigue en ejecución (menos de 30 minutos). Abortando nueva ejecución.'
                );
                return; // Se detiene la ejecución actual
            }
            // Si el proceso está en ejecución pero lleva más de 30 minutos sin actualizarse
            elseif ($enEjecucion && now()->diffInMinutes($ultimaActualizacion) > 30) {
                // Se registra en el log que el proceso parece estar atascado y que se forzará su liberación
                $this->generateSevenLog(
                    'correo_integrado',
                    'Modules\Correspondence\Http\Controllers\CorreoIntegradoController  -  Se detectó proceso atascado. Forzando liberación para obtener correos.'
                );
            }
        }

        // Decodifica el JSON almacenado en la propiedad 'informacion_adicional' del objeto $proceso a un objeto PHP
        $informacion_adicional = json_decode($proceso->informacion_adicional); 
        // Fecha anterior a la actual
        $fecha_ayer = Carbon::yesterday()->toDateString();
        // Verifica si informacion_adicional es nula o si la propiedad 'ultima_fecha_verificada' es falsa o no existe para ejecutar el bloque una sola vez
        if ($informacion_adicional === null || empty($informacion_adicional->ultima_fecha_verificada) || $informacion_adicional->ultima_fecha_verificada != $fecha_ayer) {
            // Si informacion_adicional es nula, se declara como un objeto
            $informacion_adicional = $informacion_adicional ?? new \stdClass();

            // Marca la fecha que se ha verificado (anterior a la fecha actual) para no repetir el proceso
            $informacion_adicional->ultima_fecha_verificada = $fecha_ayer; 
            
            // Codifica de nuevo el objeto actualizado a JSON para guardar en la base de datos
            $informacion_adicional_actualizada = json_encode($informacion_adicional); 
            
            // Actualiza o inserta el registro 'obtener_correos' en la tabla 'procesos_en_ejecucion' con la nueva información y la fecha de actualización (con una hora añadida)
            DB::table('procesos_en_ejecucion')->updateOrInsert(
                ['proceso' => 'obtener_correos'],
                [
                    'informacion_adicional' => $informacion_adicional_actualizada,
                    'en_ejecucion' => 1,
                    'updated_at' => now()->addHour()
                ]
            );

            // Obtiene la fecha del día anterior en formato 'YYYY-MM-DD'
            $dia_anterior = Carbon::yesterday()->toDateString(); 
            
            // Llama a la función para procesar correos específicamente del día anterior
            $this->obtenerCorreosIntegrados_phpimap($dia_anterior, $dia_anterior); 
            
            return;
        } else if (!$fecha_inicial && !$fecha_final) { // Si ambas variables están vacias, no está verificando el día anterior y debe ingresar al if
            // 2. Marcar como en ejecución
            DB::table('procesos_en_ejecucion')->updateOrInsert(
                ['proceso' => 'obtener_correos'],
                ['en_ejecucion' => 1, 'updated_at' => now()]
            );
        }

        try {
            $correo_configuraciones = CorreoIntegradoConfiguracion::select([
                'servidor', 'puerto', 'seguridad', 'obtener_desde', 'correo_comunicaciones', 'correo_communicaciones_clave'
            ])->latest()->get();

            $vigencia = date("Y");
            $carpeta_almacenamiento = storage_path("app/public/container/correo_integrado_{$vigencia}/");
            if (!is_dir($carpeta_almacenamiento)) {
                mkdir($carpeta_almacenamiento, 0755, true);
            }

            foreach ($correo_configuraciones as $correo_config) {
                // Se guarda el tiempo de inicio del proceso para medir duración posteriormente
                $inicio = microtime(true);
                // Se establece el servidor IMAP, puerto y seguridad, si no están configurados, se asignan valores por defecto
                $servidor = $correo_config->servidor ?? "imap.gmail.com";
                $puerto = $correo_config->puerto ?? 993;
                $seguridad = $correo_config->seguridad ?? "SSL";

                // Se establece la fecha desde la cual se deben consultar los correos (por defecto hoy)
                $obtener_desde = Carbon::parse($fecha_inicial ?? $correo_config->obtener_desde ?? now()->toDateString());
                
                // Para pruebas con una fecha actual fija
                // $obtener_desde = Carbon::parse('2025-05-22');
                
                // Se establece la fecha actual para comparar en el ciclo de descarga
                $fecha_actual = $fecha_final ? Carbon::parse($fecha_final) : Carbon::today();

                // Para pruebas con una fecha actual fija
                // $fecha_actual = Carbon::parse('2025-05-22');

                // Inicializa el contador de comunicaciones descargadas en cero
                $comunicaciones_descargadas = 0;

                // Se obtienen los UIDs de correos ya descargados para evitar duplicados. 
                $uids_existentes = CorreoIntegrado::where('vigencia', $vigencia)->pluck('uid')->flip()->toArray();

                // Se instancia la conexión al servidor IMAP con los datos del correo
                $mailbox = new Mailbox(
                    '{'.$servidor.':'.$puerto.'/imap/'.$seguridad.'}', // Ruta de conexión IMAP con servidor, puerto y seguridad
                    $correo_config->correo_comunicaciones,             // Correo de acceso al buzón
                    $correo_config->correo_communicaciones_clave       // Contraseña del buzón
                );

                // Se obtienen todas las carpetas de la cuenta de correo asociada
                $carpetas = $mailbox->getMailboxes();

                // Mientras la fecha de consulta sea menor o igual a la fecha actual, se continúa iterando por días
                while ($obtener_desde->lte($fecha_actual)) {
                    // Se prepara el filtro de búsqueda por fecha en formato requerido por IMAP (ej: ON "16 Dec 2025")
                    $fechaConsulta = 'ON "' . $obtener_desde->format('d M Y') . '"';
                    // Itera sobre cada carpeta del buzón para consultar correos por día
                    foreach ($carpetas as $carpeta) {
                        // Se obtiene el nombre completo de la carpeta (path)
                        $nombreCarpeta = $carpeta['fullpath'];

                        // Convertimos a minúscula para evitar problemas de mayúsculas/minúsculas
                        $nombreNormalizado = strtolower($nombreCarpeta);

                        // Palabras clave de carpetas que queremos excluir
                        $carpetasExcluidas = ['sent', 'enviados', 'trash', 'papelera', 'spam', 'junk', 'drafts', 'borradores', 'all mail', 'todos'];

                        // Si el nombre de la carpeta (del correo) contiene alguna palabra excluida, lo saltamos
                        if (collect($carpetasExcluidas)->contains(fn($excluir) => str_contains($nombreNormalizado, $excluir))) {
                            continue;
                        }

                        // Cambia la carpeta actual del buzón IMAP a la que se está iterando en ese momento (por ejemplo: INBOX, etc.)
                        $mailbox->switchMailbox($nombreCarpeta);

                        // Realiza una búsqueda de correos en la carpeta actual usando la fecha especificada (por ejemplo: ON "16 Dec 2025")
                        $mail_ids = $mailbox->searchMailbox($fechaConsulta);

                        if (!empty($mail_ids)) {
                            foreach ($mail_ids as $mail_id) {
                                // Se inicializan las variables del asunto, remitente y fecha del correo como nulas
                                $asunto_correo = null;
                                $correo_remitente = null;
                                $fecha_correo = null;

                                try {
                                    // Se obtiene el correo completo desde el buzón IMAP usando el ID del mensaje (sin cuerpo completo si el segundo parámetro es false)
                                    $email = $mailbox->getMail($mail_id, false);

                                    // Se extrae el UID a partir del messageId (usando la parte antes del "@"), si existe
                                    $uid = $email->messageId ? trim(explode('@', $email->messageId)[0]) : null;

                                    // Se verifica si no hay UID, si el UID ya existe en el sistema (para evitar duplicados), o si no hay dirección del remitente
                                    if (!$uid || isset($uids_existentes[$uid]) || !$email->fromAddress) {
                                        continue; // Si se cumple alguna condición, se salta este correo y pasa al siguiente
                                    }
                                    // Correo y nombre del remitente actual del correo
                                    $correo_remitente = $email->fromAddress;
                                    $nombre_remitente = (string)($email->fromName ?? $email->fromAddress);
                                    $asunto_correo = $email->subject;
                                    // Obtener el cuerpo del mensaje
                                    // $body = $email->textPlain; // o $mail->textHtml si es HTML

                                    // Busca el nombre del remitente original (último "De:" en caso de reenvíos)
                                    // if (preg_match_all('/(?:De|From):\s*(.?)\s<([\w\.-]+@[\w\.-]+\.\w+)>/i', $plainText, $matches, PREG_SET_ORDER)) {
                                    // if (preg_match_all('/(?:De|From):\s*(.?)\s<?([^>\s]+)>?/', $body, $matches, PREG_SET_ORDER)) {

                                    //     // Obtiene el último remitente (el original)
                                    //     if (!empty($matches)) {
                                    //         $lastSender = end($matches);
                                    //         $nombre_remitente = trim($lastSender[1]) ?: null; // Nombre del remitente original
                                    //         $correo_remitente = trim($lastSender[2]) ?: null;  // Correo del remitente original
                                    //     }
                                    // }

                                    // 1. Buscar la línea de la fecha recibido real
                                    if (preg_match('/(?:Received|X-Received):.?;\s(.+?)(?:\r?\n|$)/is', $email->headersRaw, $matches)) {
                                        $fechaRaw = trim($matches[1]);

                                        // Convertir la fecha encontrada a la zona horaria deseada
                                        $fecha_correo = Carbon::parse($fechaRaw)
                                            ->setTimezone('America/Bogota')
                                            ->format('Y-m-d H:i:s');
                                    } else {
                                        $fecha_correo = $this->parseEmailDate($email->date);
                                    }

                                    // Procesa el contenido del correo (HTML o texto plano) mediante un método interno
                                    $contenido = $this->processEmailContent($email);

                                    // Inicia una transacción en la base de datos para asegurar que todos los cambios se completen correctamente
                                    DB::beginTransaction();

                                    // Verificar si el correo ya fue procesado
                                    if (CorreoIntegrado::where('uid', $uid)->exists()) {
                                        DB::rollBack();
                                        continue;
                                    }

                                    // Se obtiene el valor máximo del consecutivo numérico, usando lockForUpdate() para bloquear la fila durante la transacción y evitar condiciones de carrera.
                                    $total_comunicaciones = DB::table('comunicaciones_por_correo')
                                        ->where("vigencia", $vigencia)
                                        ->lockForUpdate()
                                        ->selectRaw("MAX(CAST(SUBSTRING_INDEX(consecutivo, '-', -1) AS UNSIGNED)) as max_consec")
                                        ->value('max_consec');

                                    // Si no hay consecutivo previo, se inicia en 1. De lo contrario, se incrementa en 1.
                                    $total_comunicaciones = ($total_comunicaciones ?? 0) + 1;

                                    // Genera el número consecutivo del correo con el formato VIGENCIA-NÚMERO (por ejemplo: 2025-103)
                                    $consecutivo = $vigencia . "-" . $total_comunicaciones;

                                    // Crea un nuevo registro en la tabla 'correo_integrado' con la información extraída del correo

                                    $correo_integrado = CorreoIntegrado::create([
                                        "consecutivo" => "CRCE-".$consecutivo,
                                        "asunto" => $asunto_correo,
                                        "contenido" => $contenido,
                                        "correo_remitente" => $correo_remitente,
                                        "nombre_remitente" => $nombre_remitente,
                                        "correo_configurado" => $correo_config->correo_comunicaciones ?? '',
                                        "fecha" => $fecha_correo,
                                        "estado" => "Sin clasificar",
                                        "uid" => $uid,
                                        "vigencia" => $vigencia,
                                    ]);
                                    
                                    // Guarda los archivos adjuntos del correo en el sistema, asociándolos con el correo recién creado
                                    $this->saveEmailAttachments($email, $correo_integrado->id, $carpeta_almacenamiento, $correo_integrado->contenido);

                                    // Crea una entrada en el historial del correo con la misma información, para mantener trazabilidad
                                    CorreoIntegradoHistorial::create($correo_integrado->toArray());

                                    // Confirma (hace commit) de la transacción en la base de datos
                                    DB::commit();

                                    // Agrega el UID del correo a la lista de correos procesados para evitar duplicados
                                    $uids_existentes[$uid] = true;
                                    // Incrementa el contador específico de comunicaciones descargadas exitosamente
                                    $comunicaciones_descargadas++;
                                } catch (\Throwable $e) {
                                    DB::rollback();
                                    // Loguear error específico de este correo
                                    $this->generateSevenLog('correo_integrado', 'Error al descargar el correo con ID ' . $mail_id . ': ' . $e->getMessage().' - Linea: '.$e->getLine());
                                    // Envía notificación de advertencia de conexión con la cuenta de correo
                                    $asunto = json_decode('{"subject": "Error al descargar correo en cuenta de correo integrado - '.config('app.name').'"}');
                                    $notificacion = [
                                        "mensaje" => "Error al descargar correo de ".$correo_config->correo_comunicaciones." en ".config('app.url')." con ID: <strong>$mail_id</strong>",
                                        "observacion" => $e->getMessage()
                                    ];
                                    // Se valida si existe el asunto del correo para agregarlo a la notificación
                                    if ($asunto_correo) $notificacion["mensaje"] .= ", asunto: <strong>$asunto_correo</strong>";
                                    // Se valida si existe el remitente del correo para agregarlo a la notificación
                                    if ($correo_remitente) $notificacion["mensaje"] .= ", remitente: <strong>$correo_remitente</strong>";
                                    // Se valida si existe la fecha de envio del correo para agregarlo a la notificación
                                    if ($fecha_correo) $notificacion["mensaje"] .= ", fecha del correo: <strong>$fecha_correo</strong>";

                                    if (strpos($e->getMessage(), 'imap_fetchheader(): UID does not exist') === false) {
                                        SendNotificationController::enviarNotificacionCorreoIntegrado('correspondence::correo_integrados.email.notificacion_advertencia', $asunto, $notificacion, config('app.notificaciones_error_correo_integrado'));
                                    }

                                    continue; // Saltamos al siguiente correo
                                }
                            }
                        }
                    }

                    $obtener_desde->addDay();
                }

                // Cierra la conexión con el servidor IMAP para liberar recursos
                $mailbox->disconnect();

                // Captura el tiempo de finalización del proceso
                $fin = microtime(true);

                // Calcula la duración total en segundos restando el tiempo de inicio al tiempo de finalización
                $duracionSegundos = $fin - $inicio;

                // Convierte los segundos en minutos y segundos para una lectura más amigable
                $minutos = floor($duracionSegundos / 60);
                $segundos = round($duracionSegundos % 60);

                // Construye el mensaje que muestra cuántas comunicaciones se descargaron y cuánto tiempo tomó
                $mensaje = "<br /><strong>Total de comunicaciones descargadas de la cuenta " . $correo_config->correo_comunicaciones . ": $comunicaciones_descargadas</strong>, con tiempo de ejecución: ";

                // Si hubo al menos un minuto, lo agrega al mensaje con pluralización correcta
                if ($minutos > 0) {
                    $mensaje .= "{$minutos} minuto" . ($minutos > 1 ? "s" : "") . " y ";
                }

                // Agrega los segundos al mensaje, también con pluralización correcta
                $mensaje .= "{$segundos} segundo" . ($segundos != 1 ? "s" : "");

                // Imprime el mensaje final en pantalla
                echo $mensaje;

            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController -   Error: '.$e->getMessage().' - Linea: '.$e->getLine());
        } catch (ConnectionException $ex) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController  -  IMAP connection failed: '.$ex->getMessage().' - Linea: '.$ex->getLine());
            // Envía notificación de advertencia de conexión con la cuenta de correo
            if (isset($correo_config)) {
                $asunto = json_decode('{"subject": "Error de conexión en cuenta de correo integrado - '.config('app.name').'"}');
                $notificacion = [];
                $notificacion["mensaje"] = "La cuenta de correo integrado ".$correo_config->correo_comunicaciones." ha generado un error de conexión en el sitio <strong>".config('app.name')."</strong> (".config('app.url').")";
                $notificacion["observacion"] = $ex->getMessage();
                SendNotificationController::enviarNotificacionCorreoIntegrado('correspondence::correo_integrados.email.notificacion_advertencia', $asunto, $notificacion, config('app.notificaciones_error_correo_integrado'));
            }
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController  -  Error: '.$e->getMessage().' - Linea: '.$e->getLine());
        } finally {
            // 3. Siempre liberar el bloqueo aunque haya errores
            DB::table('procesos_en_ejecucion')->where('proceso', 'obtener_correos')->update([
                'en_ejecucion' => 0,
                'updated_at' => now()
            ]);
        }
    }

    /**
     * Parsea la fecha del correo evitando errores.
     */
    private function parseEmailDate($dateString)
    {
        // Elimina zonas horarias entre paréntesis
        $dateString = preg_replace('/\s\([^)]*\)/', '', $dateString);

        // Elimina el nombre del día si viene seguido de coma y espacio
        $dateString = preg_replace('/^[^\d,]+,\s*/', '', $dateString);
        try {
            return (new DateTime($dateString))->format('Y-m-d H:i:s');
        } catch (Exception $e) {
            return now()->toDateTimeString(); // Devuelve la fecha actual en caso de error
        }
    }

    /**
     * Procesa el contenido del correo y reemplaza rutas de adjuntos.
     */
    private function processEmailContent($email)
    {
        $contenido = $email->textHtml ?: $email->textPlain;
        return preg_replace("/BEGIN:VCALENDAR.*.END:VCALENDAR/s", "", $contenido);
    }

    /**
     * Guarda los adjuntos del correo.
     */
    private function saveEmailAttachments($email, $correoId, $storagePath, $contenido)
    {
        if ($email->hasAttachments()) {
            // Almacena la ruta de los adjuntos del contenido del correo, con el id que no se visualizan desde el detalle de la comunicación
            $id_adjuntos = [];
            // Almacena la ruta de los adjuntos del correo en el servidor, una vez descargados
            $ruta_adjuntos = [];
            foreach ($email->getAttachments() as $attachment) {
                $uniqueName = uniqid() . "_" . $this->extractUniqueFilename($attachment->name);
                $filePath = $storagePath . trim($uniqueName);

                // Guardar en AWS si aplica
                if (env("TIPO_ALMACENAMIENTO_GENERAL") == "AWS" && !in_array($attachment->mime, ['image/jpeg', 'image/png', 'image/gif'])) {
                    $this->guardarDocumento("container/correo_integrado_" . date("Y") . "/" . $uniqueName, $attachment->getContents(), $attachment->mime);
                } else {
                    $attachment->setFilePath($filePath);
                    $attachment->saveToDisk();
                }

                CorreoIntegradoAdjunto::create([
                    "adjunto" => "container/correo_integrado_" . date("Y") . "/" . $uniqueName,
                    "comunicaciones_por_correo_id" => $correoId
                ]);
                // Almacena la ruta de los adjuntos con el id del mismo, este id es generado por el correo
                $id_adjuntos[] = "cid:".$attachment->contentId;
                // Almacena la ruta de los adjuntos en el servidor, una vez descargados del correo
                $ruta_adjuntos[] = "\storage\container\correo_integrado_".date("Y")."\\" . trim($uniqueName);
            }
            // Se reemplazan las rutas de los adjuntos del contenido del correo, por las rutas de los mismos en el servidor
            $contenido = str_replace($id_adjuntos, $ruta_adjuntos, $contenido);

            CorreoIntegrado::where('id', $correoId)->update(['contenido' => $contenido]);
        }
    }

    /**
     * Verifica la cantidad de correos descargados en un día específico, comparando la cantidad de correos recibidos en las cuentas de correo integrado de ese mismo día
     *
     * @author Seven Soluciones Informáticas S.A.S. - Abr. 09 - 2025
     * @version 1.0.0
     *
     * @param Date $fecha_verificacion - Si no se especifíca una fecha, se asigna la fecha del día anterior a la ejecución del script
     */
    public function verificarCorreosDescargados($fecha_verificacion = null) {
        $fecha_verificacion = Carbon::parse($fecha_verificacion ?? Carbon::yesterday())->format('Y-m-d');

        // 1. Verificar si ya hay un proceso corriendo
        $proceso = DB::table('procesos_en_ejecucion')->where('proceso', 'verificar_correos_descargados')->first();

        if ($proceso) {
            $enEjecucion = (bool) $proceso->en_ejecucion;
            $ultimaActualizacion = \Carbon\Carbon::parse($proceso->updated_at);

            // Si está en ejecución, pero han pasado más de 10 minutos, liberamos
            if ($enEjecucion && now()->diffInMinutes($ultimaActualizacion) <= 10) {
                $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController  -  El proceso de verificar correos descargados sigue en ejecución (menos de 10 minutos). Abortando nueva ejecución.');
                return;
            } elseif ($enEjecucion && now()->diffInMinutes($ultimaActualizacion) > 10) {
                $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController  -  Se detectó proceso atascado. Forzando liberación para verificar correos descargados.');
            }
        }

        // 2. Marcar como en ejecución el proceso de verificar_correos_descargados
        DB::table('procesos_en_ejecucion')->updateOrInsert(
            ['proceso' => 'verificar_correos_descargados'],
            ['en_ejecucion' => 1, 'updated_at' => now()]
        );

        try {
            $correo_configuraciones = CorreoIntegradoConfiguracion::select([
                'servidor', 'puerto', 'seguridad', 'obtener_desde', 'correo_comunicaciones', 'correo_communicaciones_clave'
            ])->latest()->get();

            $vigencia = date("Y");
            $carpeta_almacenamiento = storage_path("app/public/container/correo_integrado_{$vigencia}/");
            if (!is_dir($carpeta_almacenamiento)) {
                mkdir($carpeta_almacenamiento, 0755, true);
            }

            foreach ($correo_configuraciones as $correo_config) {
                $servidor = $correo_config->servidor ?? "imap.gmail.com";
                $puerto = $correo_config->puerto ?? 993;
                $seguridad = $correo_config->seguridad ?? "SSL";

                $mailbox = new Mailbox(
                    '{'.$servidor.':'.$puerto.'/imap/'.$seguridad.'}INBOX', // Carpeta de servidor IMAP y buzón
                    $correo_config->correo_comunicaciones,
                    $correo_config->correo_communicaciones_clave
                );
                $mailbox->setConnectionArgs(CL_EXPUNGE);

                $mail_ids = $mailbox->searchMailbox('ON "' . $fecha_verificacion . '"');
                // Obtiene los uids de todos los correos descargados, según el correo configurado y la fecha de verificación
                $uids_comunicaciones_descargadas_cuenta = CorreoIntegrado::whereDate('fecha', $fecha_verificacion)->where('correo_configurado', $correo_config->correo_comunicaciones)->pluck('uid')->toArray();
                // Obtiene los uids de todos los correos descargados, según la fecha de verificación
                $uids_comunicaciones_descargadas = CorreoIntegrado::whereDate('fecha', $fecha_verificacion)->pluck('uid')->toArray();
                // Indica si se genera una notificación email. 0 = No, 1 = Si
                $alerta_generada = 0;
                // Almacena la información de los correos no descargados según la fecha de verificación
                $correos_no_descargados = "";
                // Valida la diferencia entre los correos descargados y los recibidos en el correo integrado, si son diferentes, hubieron correos que no se descargaron
                if (count($uids_comunicaciones_descargadas_cuenta) < count($mail_ids)) {

                    foreach ($mail_ids as $mail_id) {
                        $email = $mailbox->getMail($mail_id, false);

                        $uid = $email->messageId ? trim(explode('@', $email->messageId)[0]) : null;
                        // Si el correo no tiene uid o el uid ya existe en las comunicaciones descargadas, de inmediato continua con la siguiente iteración
                        if (!$uid || in_array($uid, $uids_comunicaciones_descargadas)) {
                            continue;
                        }
                        // Correo y nombre del remitente actual del correo
                        $correos_no_descargados .= "\n\n* Asunto: ".$email->subject.". Correo remitente: ".$email->fromAddress;
                    }
                    if(!empty($correos_no_descargados)){

                        $alerta_generada = 1;

                        // Obtener el cuerpo del mensaje
                        $asunto = json_decode('{"subject": "Aviso de correos no descargados - '.config('app.name').'"}');
                        $notificacion = [];
                        $notificacion["mensaje"] = "La cuenta de correo integrado ".$correo_config->correo_comunicaciones.", del sitio (".config('app.url')."), ha generado una alerta de que el día <strong>".$fecha_verificacion."</strong> se descargaron <strong>".count($uids_comunicaciones_descargadas_cuenta)." correos</strong> localmente en el sitio, sin embargo se reportan <strong>".count($mail_ids)."</strong> correos recibidos en la cuenta de correo indicada anteriormente.";
                        $notificacion["observacion"] = "\nCorreos que no se descargaron (comunicaciones por correo): ".$correos_no_descargados ?? "Sin observacines";
                        // Envía la notificación de advertencia de los correos no descargados de la cuenta de correo indicada
                        SendNotificationController::enviarNotificacionCorreoIntegrado('correspondence::correo_integrados.email.notificacion_advertencia', $asunto, $notificacion, config('app.notificaciones_error_correo_integrado'));
                    }
                }
                // Arreglo para almacenar la información de la consulta de los correos descargados, vs con los recibidos en el correo integrado, según la fecha de verificación
                $informacion = [];
                $informacion["cuenta_correo"] = $correo_config->correo_comunicaciones;
                $informacion["fecha_verificacion"] = $fecha_verificacion;
                $informacion["no_correos_servidor"] = count($mail_ids);
                $informacion["no_correos_descargados"] = count($uids_comunicaciones_descargadas);
                $informacion["alerta_generada"] = $alerta_generada;
                $informacion["descripcion"] = $correos_no_descargados;

                // Registra los resultados de la consulta de los correos descargados, junto con los recibidos en el correo integrado, según la fecha de verificación
                DB::table('comunicaciones_por_correo_descarga_logs')->insert($informacion);

                $mailbox->disconnect();
                echo "<br />Total de comunicaciones descargadas de la cuenta <strong>".$correo_config->correo_comunicaciones."</strong> el día <strong>".$fecha_verificacion."</strong>: <strong>".count($uids_comunicaciones_descargadas)."</strong><br />";
                echo "Total de comunicaciones recibidas en la cuenta <strong>".$correo_config->correo_comunicaciones."</strong> el día <strong>".$fecha_verificacion."</strong>: <strong>".count($mail_ids)."</strong><br />";
            }
        } catch (\Illuminate\Database\QueryException $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController -   Error: '.$e->getMessage().' - Linea: '.$e->getLine());
        } catch (ConnectionException $ex) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController  -  IMAP connection failed: '.$ex->getMessage().' - Linea: '.$ex->getLine());
            // Envía notificación de advertencia de conexión con la cuenta de correo
            $asunto = json_decode('{"subject": "Error de conexión en cuenta de correo integrado (para la verificación de comunicaciones descargadas) - '.config('app.name').'"}');
            $notificacion = [];
            $notificacion["mensaje"] = "La cuenta de correo integrado ".$correo_config->correo_comunicaciones." ha generado un error de conexión en el sitio <strong>".config('app.name')."</strong> (".config('app.url').") para la verificación de comunicaciones descargadas del día <strong>".$fecha_verificacion."</strong>";
            $notificacion["observacion"] = $ex->getMessage();
            SendNotificationController::enviarNotificacionCorreoIntegrado('correspondence::correo_integrados.email.notificacion_advertencia', $asunto, $notificacion, config('app.notificaciones_error_correo_integrado'));
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('correo_integrado', 'Modules\Correspondence\Http\Controllers\CorreoIntegradoController  -  Error: '.$e->getMessage().' - Linea: '.$e->getLine());
        } finally {
            // 3. Siempre liberar el bloqueo aunque haya errores
            DB::table('procesos_en_ejecucion')->where('proceso', 'verificar_correos_descargados')->update([
                'en_ejecucion' => 0,
                'updated_at' => now()
            ]);
        }
    }

}
