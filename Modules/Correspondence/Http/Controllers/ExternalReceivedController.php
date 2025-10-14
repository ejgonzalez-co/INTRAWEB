<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\correspondence\GenericExport;
use App\Exports\correspondence\RequestExport;
use App\Exports\correspondence\RequestExportCorrespondence;
use Modules\Correspondence\Http\Requests\CreateExternalReceivedRequest;
use Modules\Correspondence\Http\Requests\UpdateExternalReceivedRequest;
use Modules\Correspondence\Repositories\ExternalReceivedRepository;
use Modules\Correspondence\Models\ExternalReceived;
use Modules\Correspondence\Models\ReceivedHistory;
use Modules\Correspondence\Models\ExternalReceivedCopyShare;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\AntiXSSController;
use Modules\Intranet\Models\Citizen;
use App\User;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\PQRS\Models\PQR;
use Modules\PQRS\Models\PQRHistorial;
use Modules\Intranet\Models\Dependency;
use Modules\Correspondence\Models\ExternalReceivedRead;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
use Modules\Correspondence\Models\TypeDocumentary;
use \stdClass;
use \DateTime;
use Modules\PQRS\Models\WorkingHours;
use Modules\PQRS\Models\HolidayCalendar;
use Modules\PQRS\Models\PQREjeTematico;
use Illuminate\Support\Arr;
use Modules\Configuracion\Models\Variables;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Correspondence\Models\ReceivedAnnotation;
use App\Http\Controllers\JwtController;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use Cloudinary\Cloudinary;
use Cloudinary\Transformation;
use App\Http\Controllers\SendNotificationController;
use Illuminate\Support\Facades\Crypt;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;


/**
 * Descripcion de la clase
 *
 * @author Carlos Moises Garcia T. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ExternalReceivedController extends AppBaseController {

    /** @var  ExternalReceivedRepository */
    private $externalReceivedRepository;

    /**
     * Constructor de la clase
     *
     * @author Carlos Moises Garcia T. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ExternalReceivedRepository $externalReceivedRepo) {
        $this->externalReceivedRepository = $externalReceivedRepo;
    }

    /**
     * Muestra la vista para el CRUD de ExternalReceived.
     *
     * @author Carlos Moises Garcia T. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(!Auth::user()->hasRole(["Ciudadano"])){
            $clasificacion = Variables::where('name', 'clasificacion_documental_recibida')->pluck('value')->first() ?? 'no';
            return view('correspondence::external_receiveds.index',compact(['clasificacion']));
        }
        return view("auth.forbidden");
    }


    public function indexEmail(Request $request)
    {
        $code = $request["c"];
        //id es id de la tabla de_documento_firmar
        return view('correspondence::external_receiveds.index_viewer',compact('code'));
    }
    /**
     * Muestra la vista de ExternalReceived repositorio del sitio anterior de la entidad.
     *
     * @author Desarrollador Seven - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexRepositorio(Request $request) {
        return view('correspondence::external_receiveds.index_repositorio')->with("vigencia",  $request['vigencia']);
    }


    public function all(Request $request)
    {
        $query = $request->input('query');

        // Verificar si se realiza una búsqueda por query
        if ($query) {
            return $this->buscarQuery($query);
        }

        $tablero = $request->input('tablero');  // o $request->query('tablero')

        $cantidadPaginado = base64_decode($request["pi"]);
        // Preparar filtros
        $filtros = $this->prepararFiltros($request);

        $filtrosRolConsulta = $this->prepararFiltros($request);

        if (!Auth::user()->hasRole('Consulta correspondencias')) {
            // Verificar el rol del usuario y manejar la lógica de acuerdo con su rol
            if (Auth::user()->hasRole('Correspondencia Recibida Admin')) {
                return $this->consultasAdmin($tablero, $filtros, $cantidadPaginado);
            }

            return $this->consultasFuncionario($tablero, $filtros, $cantidadPaginado);
        }else{

            $filtros = $this->limpiarConsulta($filtros);

            // Verificar el rol del usuario y manejar la lógica de acuerdo con su rol
            if (preg_match("/typeConsult LIKE '%CONSULTA DE CORRESPONDENCIA%'/", $filtrosRolConsulta)) {
                return $this->consultasAdmin($tablero, $filtros, $cantidadPaginado);
            }

            return $this->consultasFuncionario($tablero, $filtros, $cantidadPaginado);
        }
    }

    // Maneja la búsqueda por query
    private function limpiarConsulta($query) {
        // Expresiones regulares para encontrar y eliminar las condiciones específicas
        $pattern1 = "/\b(typeConsult\s+LIKE\s+'%CORRESPONDENCIA PROPIA%')\s*(AND\s*)?/i";
        $pattern2 = "/\b(typeConsult\s+LIKE\s+'%CONSULTA DE CORRESPONDENCIA%')\s*(AND\s*)?/i";

        // Reemplaza las condiciones encontradas por una cadena vacía
        $cleanedQuery = preg_replace([$pattern1, $pattern2], '', $query);

        // Elimina cualquier "AND" colgante al final de la consulta
        $cleanedQuery = preg_replace("/\s+AND\s*$/i", '', $cleanedQuery);

        // Si la consulta está vacía después de limpiar, devuelve "1=1"
        if (empty(trim($cleanedQuery))) {
            $cleanedQuery = "1=1";
        }

        return $cleanedQuery;
    }

    // Maneja la búsqueda por query
    private function buscarQuery($query)
    {
        $externals = DB::table('external_received')
            ->where('consecutive', 'like', '%'.$query.'%')
            ->where('state', 'like', 'Público')
            ->get();

        return $this->sendResponse($externals->toArray(), trans('data_obtained_successfully'));
    }

    // Prepara los filtros, decodificando y reemplazando los valores necesarios
    private function prepararFiltros(Request $request)
    {
        // Reemplazar los espacios en blanco por + en la cadena de filtros codificada
        $request["f"] = str_replace(" ", "+", $request["f"]);
        // Decodificar filtros si existen
        if (isset($request["f"]) && !empty($request["f"])) {
            $filtros = base64_decode($request["f"]);
                // Escapar la palabra reservada 'from' con comillas invertidas
                $filtros = str_replace("from", "`from`", $filtros);
            return $filtros;
        }
        return "";
    }

    // Maneja la lógica para los usuarios con el rol de 'Correspondencia Recibida Admin'
    private function consultasAdmin($tablero, $filtros, $cantidadPaginado)
    {
        if ($tablero) {
            return $this->tableroAdmin();
        }

        // Maneja la consulta de solicitudes internas, con los filtros aplicados
        $externalsQuery = ExternalReceived::with(['externalAnnotations','externalShares'])->when($filtros, function ($queryFiltros) use ($filtros) {
            if ($filtros == "state LIKE '%COPIAS%'") {
                $queryFiltros->whereRelation('externalCopyShares', 'users_id', Auth::user()->id);
            }else {
                $queryFiltros->whereRaw($filtros);
            }
        })->orderBy('created_at', 'DESC');

        $externals = $externalsQuery->latest("external_received.updated_at")
            ->paginate($cantidadPaginado);


        $count_externals = $externals->total();
        $externals = $externals->toArray()["data"];
        // dd($externals);

        return $this->sendResponseAvanzado($externals, trans('data_obtained_successfully'), null, ["total_registros" => $count_externals]);
    }

    // Maneja la lógica cuando se pasa el parámetro 'tablero' para usuarios administradores
    private function tableroAdmin(){

        $estados_originales = ["1", "3"];
        $estados_reemplazar = ["devuelta", "publica"];

        // Base de la consulta con relaciones necesarias
        $externalsQuery = ExternalReceived::with(['externalCopyShares'])
        ->select(
            'state',
            DB::raw('COUNT(*) as total')
        )
        ->groupBy('state');

        // Ejecutar consulta optimizada
        $externals = $externalsQuery->get();

        // Contar las copias compartidas con el usuario actual
        // $count_compartida = $externals->where('externalCopyShares.users_id', Auth::id())->count();
        $count_compartida = ExternalReceived::with('externalCopyShares')->whereRelation('externalCopyShares', 'users_id', Auth::user()->id)->count();

        // Mapeo de estados
        $state_totales = $externals->pluck('total', 'state')
            ->mapWithKeys(function ($total, $state) use ($estados_originales, $estados_reemplazar) {
                return [str_replace($estados_originales, $estados_reemplazar, $state) => $total];
            });

        // Calcular el total de registros
        $totalSuma = $externals->sum('total');


        // dd($state_totales);
        // Devolvemos la respuesta
        return $this->sendResponseAvanzado([], trans('data_obtained_successfully'), null, [
            'total_devueltas' => $state_totales['devuelta'] ?? 0,
            'total_publicas' => $state_totales['publica'] ?? 0,
            'total_externas' => $totalSuma,
            'total_compartidas' => $count_compartida,
        ]);
    }

    private function getCommonExternalsQuery($authUserId)
    {
        return ExternalReceived::where(function ($query) use ($authUserId) {
            $query->where("external_received.functionary_id", $authUserId)
                ->orWhereRelation('externalCopyShares', 'users_id', $authUserId);
        });
    }

    private function tableroFuncionario()
    {
        $authUserId = Auth::user()->id;

        $externalsQuery = $this->getCommonExternalsQuery($authUserId)
            ->select(
                'state',
                DB::raw('COUNT(DISTINCT external_received.id) as total')
            )
            ->groupBy('state');

        $externals = $externalsQuery->get();
        // $count_compartida = $externals->where('externalCopyShares.users_id', Auth::id())->count();
        $count_compartida = ExternalReceived::with('externalCopyShares')->whereRelation('externalCopyShares', 'users_id', Auth::user()->id)->count();

        $estados_originales = ["1", "3"];
        $estados_reemplazar = ["devuelta", "publica"];

        $state_totales = $externals->pluck('total', 'state')
            ->mapWithKeys(function ($total, $state) use ($estados_originales, $estados_reemplazar) {
                return [str_replace($estados_originales, $estados_reemplazar, $state) => $total];
            });

        $totalSuma = $externals->sum('total');


        return $this->sendResponseAvanzado([], trans('data_obtained_successfully'), null, [
            'total_devueltas' => $state_totales['devuelta'] ?? 0,
            'total_publicas' => $state_totales['publica'] ?? 0,
            'total_externas' => $totalSuma,
            'total_compartidas' => $count_compartida,
        ]);
    }

    private function consultasFuncionario($tablero, $filtros, $cantidadPaginado)
    {
        if ($tablero) {
            return $this->tableroFuncionario();
        }

        $authUserId = Auth::user()->id;


        $externalsQuery = $this->getCommonExternalsQuery($authUserId)
            ->select('external_received.*')->with(['externalAnnotations','externalShares'])
            ->when($filtros, function ($queryFiltros) use ($filtros) {
                if ($filtros == "state LIKE '%COPIAS%'") {
                    $queryFiltros->whereRelation('externalCopyShares', 'users_id', Auth::user()->id);
                }else {
                    $queryFiltros->whereRaw($filtros);
                }
            })->orderBy('created_at', 'DESC');

        $externals = $externalsQuery->latest("external_received.updated_at")
            ->groupBy("external_received.id")
            ->paginate($cantidadPaginado);

        $count_externals = $externals->total();
        $externals = $externals->toArray()["data"];

        return $this->sendResponseAvanzado($externals, trans('data_obtained_successfully'), null, ["total_registros" => $count_externals]);
    }


    //Termina Funciones del all

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function allRepositoryReceiveds(Request $request)
    {
        $input = $request->toArray();
        try{

            $userid = Auth::user()->user_joomla_id;

            $likedes1 = '%"id":"'.$userid.'"%';
            $likedes2 = '%"id":'.$userid.',%';
            $likedes3 = '%"id":'.$userid.'}%';

            $table = "";

            $date = date("Y");

            //valida a que tabla realizar la consulta
            if ($input['vigencia'] != '' && $input['vigencia'] != $date && $input['vigencia'] != '2024') {
                $table = "externa_".$input['vigencia'];
            }else{
                $table = "externa";
            }

            $vigencyReceivedsCount = 0;

             // Valida si existen las variables del paginado y si esta filtrando
            if(isset($request["f"]) && $request["f"] != "" && isset($request["?cp"]) && isset($request["pi"])) {


                if (Auth::user()->hasRole('Correspondencia Recibida Admin')) {
                    // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                    $querys = DB::connection('joomla')->table($table)->where('consecutivo', 'LIKE', '%RE%');

                    // Contar la cantidad de elementos que cumplen con todas las condiciones
                    $vigencyReceivedsCount = $querys->whereRaw(base64_decode($request["f"]))->count();

                    // Aplicar la paginación, el orden, y las mismas condiciones en la consulta principal
                    $vigencyReceiveds = $querys->whereRaw(base64_decode($request["f"]))
                    ->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))
                    ->take(base64_decode($request["pi"]))
                    ->orderBy('cf_created', 'DESC')
                    ->get()->toArray();



                }
                // Valida si el usuario logueado usuario normal
                else{
                    // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                    $querys = DB::connection('joomla')->table($table)
                        ->where('consecutivo', 'LIKE', '%RE%')
                        ->where(function($query) use ($userid, $likedes1, $likedes2, $likedes3) {
                            $query->where('funcionario_des', $userid)
                                ->orWhere('funcionario_or', $userid)
                                ->orWhere('copia', 'LIKE', $userid)
                                ->orWhere('copia', 'LIKE', $userid . ',%')
                                ->orWhere('copia', 'LIKE', '%,' . $userid . ',%')
                                ->orWhere('copia', 'LIKE', '%,' . $userid)
                                ->orWhere('destinatarios', 'LIKE', $likedes1)
                                ->orWhere('destinatarios', 'LIKE', $likedes2)
                                ->orWhere('destinatarios', 'LIKE', $likedes3)
                                ->orWhere('compartida', 'LIKE', $userid)
                                ->orWhere('compartida', 'LIKE', $userid . ',%')
                                ->orWhere('compartida', 'LIKE', '%/' . $userid . '/%')
                                ->orWhere('compartida', 'LIKE', '%,' . $userid . ',%')
                                ->orWhere('compartida', 'LIKE', '%,' . $userid)
                                ->orWhere('elaboradopor', $userid)
                                ->orWhere('revisadopor', $userid)
                                ->orWhere('aprobadopor', $userid);
                        });

                    // Clonar la consulta antes de aplicar la paginación y el ordenamiento
                    $clonedQuery = clone $querys;

                    // Aplicar el whereRaw al clonado y obtener el conteo
                    $vigencyReceivedsCount = $clonedQuery->whereRaw(base64_decode($request["f"]))->count();

                    // Obtener los registros con paginación y ordenamiento, sin modificar el conteo
                    $vigencyReceiveds = $querys->whereRaw(base64_decode($request["f"]))
                        ->skip((base64_decode($request["?cp"])-1) * base64_decode($request["pi"]))
                        ->take(base64_decode($request["pi"]))
                        ->orderBy('cf_created', 'DESC')
                        ->get()
                        ->toArray();

                }

            } else if(isset($request["?cp"]) && isset($request["pi"])) {

                if (Auth::user()->hasRole('Correspondencia Recibida Admin')) {

                    $querys = DB::connection('joomla')->table($table)->where('consecutivo', 'LIKE', '%RE%');

                    $vigencyReceivedsCount = $querys->count();
                    $vigencyReceiveds = $querys->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->toArray();
                }
                // Valida si el usuario logueado usuario normal tic
                else{
                    // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                    $querys = DB::connection('joomla')->table($table)->where('consecutivo', 'LIKE', '%RE%')->where(function($query) use ($userid, $likedes1, $likedes2, $likedes3) {
                        $query->where('funcionario_des', $userid)
                            ->orWhere('funcionario_or', $userid)
                            ->orWhere('copia', 'LIKE', $userid)
                            ->orWhere('copia', 'LIKE', $userid . ',%')
                            ->orWhere('copia', 'LIKE', '%,' . $userid . ',%')
                            ->orWhere('copia', 'LIKE', '%,' . $userid)
                            ->orWhere('destinatarios', 'LIKE', $likedes1)
                            ->orWhere('destinatarios', 'LIKE', $likedes2)
                            ->orWhere('destinatarios', 'LIKE', $likedes3)
                            ->orWhere('compartida', 'LIKE', $userid)
                            ->orWhere('compartida', 'LIKE', $userid . ',%')
                            ->orWhere('compartida', 'LIKE', '%/' . $userid . '/%')
                            ->orWhere('compartida', 'LIKE', '%,' . $userid . ',%')
                            ->orWhere('compartida', 'LIKE', '%,' . $userid)
                            ->orWhere('elaboradopor', $userid)
                            ->orWhere('revisadopor', $userid)
                            ->orWhere('aprobadopor', $userid);
                    });

                    $vigencyReceivedsCount = $querys->count();
                    $vigencyReceiveds = $querys->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy('cf_created', 'DESC')->get()->toArray();
                }

            } else {

                if (Auth::user()->hasRole('Correspondencia Recibida Admin')) {
                    // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                    $vigencyReceiveds = DB::connection('joomla')->Select("SELECT * FROM ".env('JOOMLA_DB_PREFIX').$table." WHERE consecutivo LIKE '%RE%'  order by cf_created DESC");

                }else{

                    $vigencyReceiveds = DB::connection('joomla')->Select("SELECT * FROM ".env('JOOMLA_DB_PREFIX').$table." WHERE consecutivo LIKE '%RE%' and (funcionario_des = '".$userid."' or funcionario_or = '".$userid."' or  copia like '".$userid."'  or  copia like '".$userid.",%'  or  copia like '%,".$userid.",%'   or  copia like '%,".$userid."'   or destinatarios like '".$likedes1."' or destinatarios like '".$likedes2."' or destinatarios like '".$likedes3."' or compartida like '".$userid."' or compartida like '".$userid.",%' or compartida like '%/".$userid."/%' or compartida like '%,".$userid.",%'  or compartida like '%,".$userid."'  or elaboradopor=".$userid." or revisadopor=".$userid." or aprobadopor=".$userid.") order by cf_created DESC");
                }

                $vigencyReceivedsCount = count($vigencyReceiveds);


            }

            return $this->sendResponseAvanzado($vigencyReceiveds, trans('data_obtained_successfully'), null, ["total_registros" => $vigencyReceivedsCount]);


        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendErrorData("No existe la vigencia seleccionada. ".config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendErrorData("No existe la vigencia seleccionada. ".config('constants.support_message'), 'info');
        }
    }



    /**
     * Obtiene datos completo del elemento existente
     *
     * @author Carlos Moises Garcia T. - Abr. 06 - 2022
     * @version 1.0.0
     *
     * @param int $id
     */
    public function getDataEdit($id) {

        $externalReceived = $this->externalReceivedRepository->find($id);

        if (empty($externalReceived)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //relaciones
        $externalReceived->externalCopy;
        $externalReceived->externalCopyShares;
        $externalReceived->externalShares;

        // $received_copy = $externalReceived->toArray()["external_received_copy"];
        // $received_shares = $externalReceived->toArray()["external_received_shares"];
        // Valida si viene usuarios para asignar de copias
        // if (!empty($received_copy)) {
        //     $externalReceived["recipientsList"] = $received_copy;
        // }
        // // Valida si viene usuarios para asignar de copias
        // if (!empty($received_shares)) {
        //     $externalReceived["recipientsListShares"] = $received_shares;
        // }
        return $this->sendResponse($externalReceived->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author Carlos Moises Garcia T. - Abr. 06 - 2022
     * @version 1.0.0
     *
     * @param int $id
     */
    public function show($id)
    {

        $received = $this->externalReceivedRepository->find($id);
        if (empty($received)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //relaciones

        $received->typeDocumentary;
        $received->dependencies;
        $received->functionaries;
        $received->citizens;
        $received->externalAnnotations;
        $received->anotacionesPendientes;
        $received->externalRead;
        $received->externalCopy;
        $received->externalCopyShares;
        $received->externalHistory;
        $received->externalShares;
        $received->externalCopyShares;
        $received->serieClasificacionDocumental;
        $received->subserieClasificacionDocumental;
        $received->oficinaProductoraClasificacionDocumental;


        return $this->sendResponse($received->toArray(), trans('data_obtained_successfully'));
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
        $received = $this->externalReceivedRepository->find($id);
        if (empty($received)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Relaciones
        $received->typeDocumentary;
        $received->dependencies;
        $received->functionaries;
        $received->citizens;
        $received->externalAnnotations;
        $received->externalRead;
        $received->externalCopy;
        $received->externalCopyShares;
        $received->externalHistory;
        $received->externalShares;
        $received->serieClasificacionDocumental;
        $received->subserieClasificacionDocumental;
        $received->oficinaProductoraClasificacionDocumental;

        return $this->sendResponse($received->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Carlos Moises Garcia T. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateExternalReceivedRequest $request
     *
     * @return Response
     */
    public function store(CreateExternalReceivedRequest $request) {


        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            // Valida si no seleccionó ningún document_pdf principal
            $input['document_pdf'] = isset($input["document_pdf"]) ? implode(",", (array) $input["document_pdf"]) : null;

            // Valida si no seleccionó ningún adjunto
            $input['attached_document'] = isset($input["attached_document"]) ? implode(",", (array) $input["attached_document"]) : null;

            $input["annexed"] = isset($input["annexed"]) ? $input["annexed"] : "No aplica";


            // Obtiene los datos del ciudadano
            // $userCitizen = Citizen::where("id", $input['citizen_id'])->first();

                // Valida que se halla ingresado un ciudadno, sea existente o personalizado
            if(!isset($input["citizen_id"]) && !isset($input["citizen_name"] )) {
                return $this->sendSuccess('<strong>El nombre del ciudadano es requerido.</strong>'. '<br>' . "Puedes autocompletar un ciudadano o asignar uno personalizado.", 'warning');
            }

            if(empty($input["functionary_name"])) {
                return $this->sendSuccess('<strong>El Funcionario es requerido.</strong>'. '<br>' . "Puede autocompletar el funcionario ingresando su nombre.", 'warning');
            }

            $input['npqr'] = isset($input['npqr']) ? $this->toBoolean($input['npqr']) : null;
            $input['auto_assign'] = isset($input['auto_assign']) ? $this->toBoolean($input['auto_assign']) : null;
            $input['respuesta_correo'] = isset($input['respuesta_correo']) && ($this->toBoolean($input['respuesta_correo']) || $input['respuesta_correo'] == 1) ? 1 : 0;


            $DP="";
            $siglas ="";
            if(!empty($input['functionary_id'])){
                // Obtiene los datos del funcionario
                $functionary = User::find($input['functionary_id']);

                if ($functionary->dependencies) {
                    $input['dependency_id']    = $functionary->dependencies->id;
                    $input['dependency_name']  = $functionary->dependencies->nombre;
                }

                $input['functionary_name'] = $functionary->fullname;
                //DP
                $DP = $functionary->dependencies->codigo ?? '';
                $siglas = $functionary->dependencies->codigo_oficina_productora ?? '';

            }else{
                $dependenciaInformacion = Dependency::where('id', $input["dependency_id"])->first();
                $input['dependency_name']  = $dependenciaInformacion["nombre"];
                $DP = $dependenciaInformacion["codigo"] ?? '';
                $siglas = $dependenciaInformacion["codigo_oficina_productora"] ?? '';

            }
            // $input['citizen_name']     = $userCitizen->name;
            $input['user_id']          = Auth::user()->id;
            $input['user_name']        = Auth::user()->fullname;
            // $input['citizen_id']       =  $userCitizen->id;

            //inicio consecutivo

            //PL
            $typeExternalReceived = TypeDocumentary::where('id', $input["type_documentary_id"])->first();
            $PL = $typeExternalReceived->prefix;

            //Consulta las variables para calcular el consecutio.
            $formatConsecutive = Variables::where('name' , 'var_external_received_consecutive')->pluck('value')->first();
            $formatConsecutivePrefix = Variables::where('name' , 'var_external_received_consecutive_prefix')->pluck('value')->first();

            if(!empty($formatConsecutive) && !empty($formatConsecutivePrefix)){
                //Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order
                $dataConsecutive = UtilController::getNextConsecutive('External_received',$formatConsecutive,$formatConsecutivePrefix,$DP,$PL,$siglas);
                $input['consecutive'] = $dataConsecutive['consecutive'];
                $input["consecutive_order"] = $dataConsecutive['consecutive_order'];
                // $externalReceived = $this->externalReceivedRepository->update(['consecutive'=>$input["consecutive"], 'consecutive_order'=>$input["consecutive_order"]], $id);

            } else{
                $input['consecutive'] = date("Y")."RE".(ExternalReceived::where('year', date("Y"))
                ->max(\DB::raw("CAST(SUBSTRING(consecutive, 7) AS UNSIGNED)")) + 1);
            }
            //fin de consecutivo

            $input['year'] = date("Y");

            $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            // Genera un código de verificación único para cada documento
            $input["validation_code"] = substr(str_shuffle($caracteres_permitidos), 0, 8);
            $input["validation_code_encode"] = JwtController::generateToken($input["validation_code"]);

            // Inserta el registro en la base de datos
            $externalReceived = $this->externalReceivedRepository->create($input);
            $id = $externalReceived->id;


            $input['users_copies'] = isset($input['external_copy']) ? $this->insertCopies($input['external_copy'], $id) : null;

            if ($input['npqr']) {
                $input['pqr'] = $this->crearPqr($input, $id);
            }else if ($input['auto_assign']) {
                $input['pqr'] = $this->crearPqr($input, $id);
            }

            $externalReceived = $this->externalReceivedRepository->update($input, $id);

            $input["external_received_id"] = $id;
            $input['observation_history'] = "Creación de correspondencia";

            $recibida = ReceivedHistory::create($input);

            $externalReceived->pqrDatos;
            $externalReceived->serieClasificacionDocumental;
            $externalReceived->subserieClasificacionDocumental;
            $externalReceived->oficinaProductoraClasificacionDocumental;
            $externalReceived->externalCopy;
            $externalReceived->externalHistory;
            $externalReceived->externalAnnotations;
            $externalReceived->externalCopyShares;


            // Efectua los cambios realizados
            DB::commit();

            if (isset($externalReceived->citizen_email)) {
                $asunto = json_decode('{"subject": "Notificación de correspondencia recibida – Radicado '.$externalReceived->consecutive.'"}');
                $notificacion["consecutive"] = $externalReceived->consecutive;
                $notificacion["issue"] = $externalReceived->issue;
                $notificacion["citizen_name"] = $externalReceived->citizen_name;
                $notificacion["validation_code"] = $externalReceived->validation_code;
                $notificacion["validation_code_encode"] = $input["validation_code_encode"];
                $notificacion["pqr"] = $input['pqr'] ?? 'No aplica';
                $notificacion["id"] = $externalReceived->id;
                $notificacion["anexes"] = $externalReceived->attached_document;
                $notificacion["documents"] =$externalReceived->document_pdf;
                try {
                    SendNotificationController::SendNotification('correspondence::external_receiveds.notificacion_ciudadano',$asunto,$notificacion,$externalReceived->citizen_email,'Correspondencia recibida');
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
                }
            }

            //Notificacion funcionario
            if (isset($functionary->email)) {
                $asunto = json_decode('{"subject": "Nueva correspondencia recibida – Radicado: '.$externalReceived->consecutive.'"}');
                $notificacion["consecutive"] = $externalReceived->consecutive;
                $notificacion["issue"] = $externalReceived->issue;
                $notificacion["citizen_name"] = $externalReceived->citizen_name;
                $notificacion["validation_code"] = $externalReceived->validation_code;
                $notificacion["validation_code_encode"] = $input["validation_code_encode"];
                $notificacion["citizen_name"] = $externalReceived->citizen_name;
                $notificacion["pqr"] = $input['pqr'] ?? 'No aplica';
                $notificacion["id"] = $externalReceived->id;
                $notificacion["id_encrypted"] = base64_encode($externalReceived->id);
                $notificacion["anexes"] = $externalReceived->attached_document;
                $notificacion["documents"] =$externalReceived->document_pdf;
                $notificacion["funcionary_name"] = $externalReceived['functionary_name'];
                try {
                    SendNotificationController::SendNotification('correspondence::external_receiveds.notificacion',$asunto,$notificacion,$functionary->email,'Correspondencia recibida');
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
                }
            }
            return $this->sendResponse($externalReceived->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            if(strpos($e->getMessage(), "Duplicate entry") !== false && strpos($e->getMessage(), "consecutive") !== false) {
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess('Lo sentimos, no pudimos procesar su solicitud debido a un alto flujo de peticiones. Por favor, inténtelo de nuevo al hacer clic en "Guardar". Si la situación persiste, comuníquese con ' . env("MAIL_SUPPORT") ?? 'soporte@seven.com.co', 'info');
            } else {
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'), 'info');
            }
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Devuelve la correspondecia y la pqr asociada.
     *
     * @author Kleverman Salazar Florez. - Ene. 14 - 2024
     * @version 1.0.0
     *
     * @param int $correspondenceId id de la correspondencia
     *
     * @return Response
     */
    public function returnCorrespondence(int $correspondenceId, $reason){
        $correspondence = $this->externalReceivedRepository->update([
            "state" => 1,
            "reason_return" => $reason
        ], $correspondenceId);
        
        // Crear un registro de un historial
        $correspondenceHistory = $correspondence->toArray();
        $correspondenceHistory["external_received_id"] = $correspondenceId;
        $correspondenceHistory["user_name"] = Auth::user()->fullname;
        $correspondenceHistory['observation_history'] = $this->hasPqrAssigned($correspondenceId) ? "Devolución de correspondencia con PQR asociado" : "Devolución de correspondencia";
        $correspondenceHistory['issue'] = $this->hasPqrAssigned($correspondenceId) ? "Devolución de correspondencia con PQR asociado" : "Devolución de correspondencia";
        $correspondenceHistory['attached_document'] = null;
        $correspondenceHistory['reason_return'] = $reason ?? null;
        ReceivedHistory::create($correspondenceHistory);

        // Relaciones
        $correspondence->externalHistory;

        return $this->sendResponse($correspondence->toArray(), trans('msg_success_save'));
    }

    /**
     * Previsualiza un documento PDF cargado en la solicitud.
     * RotuleComponent
     * @param int $id El ID del documento asociado (puede ser opcional según la implementación).
     * @param Request $request La instancia de Request que contiene los datos de la solicitud.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON con la URL de la previsualización o un mensaje de error.
     */
    public function documentPreview ($id, Request $request) {
        $input = $request->all(); // Obtiene todos los datos de la solicitud

        try {
            $pdfFile = $request->file('selectedFile'); // Obtiene el archivo PDF de la solicitud

            // Obtiene la URL de la primera página del PDF utilizando un método auxiliar (getDocumentFirstPage)
            $url_previous = $this->getDocumentFirstPage($pdfFile);

            // Envía una respuesta exitosa con la URL de la primera página del PDF
            return $this->sendResponse($url_previous, "Documento cargado exitosamente");

        } catch (\Illuminate\Database\QueryException $e) {
            // Captura y maneja errores de consulta de base de datos
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            return $this->sendSuccess(config('constants.support_message'), 'warning'); // Retorna un mensaje de éxito informativo

        } catch (\Exception $e) {
            // Captura y maneja otros tipos de excepciones generales
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine() . '- ID: '.($id ?? 'Desconocido'));

            // Maneja errores específicos, como PDF protegidos por contraseña
            if ($e->getMessage() == 'Password-protected PDFs are not supported') {
                $mensaje = "<b>La primera página del PDF no pudo ser cargada.</b> <br>El archivo PDF podría estar protegido con contraseña, lo que no es compatible con esta función. Intente eliminar la protección por contraseña o use un PDF diferente.";
                return $this->sendSuccess($mensaje, 'warning');
            }

            return $this->sendSuccess(config('constants.support_message'), 'warning'); // Retorna un mensaje de éxito informativo
        }
    }

    /**
     * Guarda un documento PDF con rotulación en una posición específica.
     * Esta funciones se usan en RotuleComponent
     * @param int $id El ID del documento asociado.
     * @param Request $request La instancia de Request que contiene los datos de la solicitud.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON con los datos actualizados o un mensaje de error.
     */
    public function saveDocumentWithRotule (int $id, Request $request) {
        $input = $request->all(); // Obtiene todos los datos de la solicitud
            try {
            $pdfFile = $request->file('selectedFile'); // Obtiene el archivo PDF de la solicitud

            // Almacena el PDF original en una ubicación específica y guarda la ruta en la base de datos
            if ($request->hasFile('selectedFile')) {
                $input['document_pdf_old'] = substr($pdfFile->store('public/container/external_original_' . date("Y")), 7);
            }

            // Decodifica el HTML desde base64
            $html = base64_decode($input['pageHtml']);

            // Eliminar clases
            $html = preg_replace('/\s+class="[^"]*"/i', '', $html);

            // Eliminar estilos
            // $html = preg_replace('/\s+style="[^"]*"/i', '', $html);


             $html = '<div style="width: ' . $input["rotule_width"] . 'px; height: auto; border: 1px solid #000; border-radius: 10px; padding: 5px; background-color: #CCC; position: absolute; z-index: -1; background-color: lightgray; top: ' . $input["rotule_position_y"] . '; left: ' . $input["rotule_position_x"] . '; font-size: ' . $input["font_size"] .'; font-family: ' . $input["font_family"] . ';">
                    ' . $html . '
            </div>';


            // Define las opciones del archivo PDF a exportar
            $optionsPdf = [
                'mode' => 'utf-8',
                'format' => $input["document_type"] == "Carta" ? "Letter" : 'Legal', // Define el formato del documento (Carta o Legal)
                'margin_left' => 20,
                'margin_right' => 15,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_header' => 9,
                'margin_footer' => 9
            ];

            // Obtiene los datos de la correspondencia
            $received = ExternalReceived::where("id", $id)->with('functionaries')->first()->toArray();
            // Modifica el PDF con el HTML generado y las opciones definidas
            $urlModifiedPdf = $this->modifyPDF($pdfFile, $html, $optionsPdf);


            // Genera un nombre único para el PDF editado
            $edited_filename = $received['consecutive'].'-'.date("his").'.pdf';
            $original_pdf_path = '' . $input['document_pdf_old'];
            $original_pdf_directory = dirname($original_pdf_path);
            $edited_pdf_path = $original_pdf_directory . '/' . $edited_filename;

            // Guarda el PDF modificado en el almacenamiento (storage)
            Storage::put("public/" . $edited_pdf_path, $urlModifiedPdf);

            $datos['document_pdf'] = $received['document_pdf'] ? implode(",", array_merge(explode(",", $received['document_pdf']), [$edited_pdf_path])) : $edited_pdf_path;
            $received = $this->externalReceivedRepository->update($datos, $id);

            // Registra la historia de la correspondencia actualizada

            $receivedArray = $received->toArray();
            $receivedArray['external_received_id'] = $id;
            $receivedArray['observation_history'] = "Actualización de correspondencia";
            $receivedArray['document_pdf'] = $datos['document_pdf'];
            $receivedArray['user_id']          = Auth::user()->id;
            $receivedArray['user_name']        = Auth::user()->fullname;
            $recibidaHistorial = ReceivedHistory::create($receivedArray);

            if (isset($received->citizen_email)) {
                $asunto = json_decode('{"subject": "Actualización: correspondencia recibida – Radicado: '.$received->consecutive.'"}');
                $notificacion["consecutive"] = $received->consecutive;
                $notificacion["issue"] = $received->issue;
                $notificacion["citizen_name"] = $received->citizen_name;
                $notificacion["validation_code"] = $received->validation_code;
                $notificacion["validation_code_encode"] = JwtController::generateToken($received->validation_code);
                $notificacion["pqr"] = $received['pqr'] ?? 'No aplica';
                $notificacion["id"] = $received->id;
                $notificacion["anexes"] = $received->attached_document;
                $notificacion["documents"] =$received->document_pdf;

                try {
                    SendNotificationController::SendNotification('correspondence::external_receiveds.notificacion_ciudadano',$asunto,$notificacion,$received->citizen_email,'Correspondencia recibida');
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($externalReceived['consecutive'] ?? 'Desconocido') . '- ID: ' . ($id ?? 'Desconocido' ));
                }
            }
            //Notificacion funcionario
            if (isset($received->functionaries->email)) {
                $asunto = json_decode('{"subject": "Actualización: correspondencia recibida '.$received->consecutive.'"}');
                $notificacion["consecutive"] = $received->consecutive;
                $notificacion["issue"] = $received->issue;
                $notificacion["citizen_name"] = $received->citizen_name;
                $notificacion["validation_code"] = $received->validation_code;
                $notificacion["citizen_name"] = $received->citizen_name;
                $notificacion["pqr"] = $input['pqr'] ?? 'No aplica';
                $notificacion["id"] = $received->id;
                $notificacion["anexes"] = $received->attached_document;
                $notificacion["documents"] =$received->document_pdf;
                try {
                    SendNotificationController::SendNotification('correspondence::external_receiveds.notificacion',$asunto,$notificacion,$received->functionaries->email,'Correspondencia recibida');
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($externalReceived['consecutive'] ?? 'Desconocido') . '- ID:' . ($id ?? 'Desconocido'));
                }
            }
            // Confirma los cambios en la base de datos
            DB::commit();

            // Retorna una respuesta JSON con los datos actualizados
            return $this->sendResponse($received->toArray(), trans('msg_success_update'));

        } catch (\Illuminate\Database\QueryException $e) {
            // Captura y maneja errores de consulta de base de datos
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            return $this->sendSuccess(config('constants.support_message'), 'warning'); // Retorna un mensaje de éxito informativo


        } catch (\Exception $e) {
            // Captura y maneja otros tipos de excepciones generales
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine() . '- ID:' . ($id ?? 'Desconocido'));

            // Maneja errores específicos, como PDF protegidos por contraseña
            if ($e->getMessage() == 'Password-protected PDFs are not supported') {
                $mensaje = "<b>La primera página del PDF no pudo ser cargada.</b> <br>El archivo PDF podría estar protegido con contraseña, lo que no es compatible con esta función. Intente eliminar la protección por contraseña o use un PDF diferente.";
                return $this->sendSuccess($mensaje, 'warning'); // Retorna un mensaje de éxito informativo

            }
            return $this->sendSuccess(config('constants.support_message'), 'warning'); // Retorna un mensaje de éxito informativo
        }
    }


    /**
     * Modifica un PDF insertando contenido HTML en la primera página.
     *
     * @param \Illuminate\Http\UploadedFile $pdfFile El archivo PDF a modificar.
     * @param string $html El fragmento HTML a insertar en la primera página del PDF.
     * @param array $options Opciones adicionales para la configuración de mPDF.
     * @return string|integer El PDF modificado como una cadena de bytes (string) o 0 si el archivo PDF es nulo.
     */
    public function modifyPDF($pdfFile, $html, $options) {

        try{
            if ($pdfFile != null && $pdfFile instanceof \Illuminate\Http\UploadedFile) {
                // Crear una instancia de mPDF con las opciones especificadas
                // $mpdf = new \Mpdf\Mpdf($options);

                $mpdf = new Mpdf([
                    'mode' => 'utf-8',
                    'format' => $options['format'],
                    // 'margin_left' => 20,
                    // 'margin_right' => 15,
                    // 'margin_top' => 16,
                    // 'margin_bottom' => 16,
                    // 'margin_header' => 9,
                    // 'margin_footer' => 9
                ]);

                // Cargar el contenido del archivo PDF en mPDF
                $mpdf->SetSourceFile($pdfFile->getPathname());

                // Agregar una nueva página
                $mpdf->AddPage();

                // Obtener el número de páginas del PDF
                $pageCount = $mpdf->SetSourceFile($pdfFile->getPathname());

                // Iterar sobre cada página del PDF
                for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                    // Importar la página actual como una plantilla
                    $tplId = $mpdf->ImportPage($pageNo);

                    // Usar la página importada como la página actual
                    $mpdf->UseTemplate($tplId);

                    // Insertar el HTML en la primera página
                    if ($pageNo === 1) {
                        $mpdf->WriteHTML($html);
                    }

                    // Agregar una nueva página si no es la última página del PDF
                    if ($pageNo < $pageCount) {
                        $mpdf->AddPage();
                    }
                }

                // Generar el PDF modificado como una cadena de bytes (string)
                $modifiedPdf = $mpdf->Output('', 'S');

                return $modifiedPdf;
            } else {
                // Retorna 0 si el archivo PDF es nulo o no es una instancia de UploadedFile
                return 0;
            }

        } catch (\Illuminate\Database\QueryException $e) {
            // Captura y maneja errores de consulta de base de datos
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            return $this->sendSuccess(config('constants.support_message'), 'info'); // Retorna un mensaje de éxito informativo

        } catch (\Exception $e) {
            // Captura y maneja otros tipos de excepciones generales
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

            return $this->sendSuccess(config('constants.support_message'), 'info'); // Retorna un mensaje de éxito informativo
        }
    }

    public function getDocumentFirstPage($pdfFile){
        // return $firstPageImage;
        $cloudinary = new Cloudinary([
            'cloud_name' => env("CLOUDINARY_CLOUD_NAME"),
            'api_key' => env("CLOUDINARY_API_KEY"),
            'api_secret' => env("CLOUDINARY_API_SECRET")
        ]);

        try {
            $pdfFile = $pdfFile;
            $publicId = 'pdf-to-image-' . time().rand(100, 999);

            $response = $cloudinary->uploadApi()->upload($pdfFile->getRealPath(), [
                'public_id' => $publicId,
                'transformation' => [
                    'page' => 1,
                    'format' => 'jpg'
                ]
            ]);

        } catch (\Throwable $th) {

        }



        if(empty(env("CLOUDINARY_LOCAL"))) {
            return "https://res.cloudinary.com/" . env("CLOUDINARY_CLOUD_NAME") . "/image/upload/f_auto,q_auto/$publicId";
        } else {
            $imageUrl = "https://res.cloudinary.com/" . env("CLOUDINARY_CLOUD_NAME") . "/image/upload/f_auto,q_auto/$publicId"; // URL de la imagen
            $destinationPath = 'imagenes_cloudinary';   // Carpeta dentro de storage/app/public
            $imageName = basename($imageUrl);           // Obtener el nombre del archivo desde la URL

            // Obtener el contenido de la imagen
            $imageContent = @file_get_contents($imageUrl);

            if ($imageContent === false) {
                echo "No se pudo descargar la imagen.";
                exit;
            }

            // Guardar la imagen en el disco 'public'
            $path = $destinationPath . '/' . $imageName;
            Storage::disk('public')->put($path, $imageContent);

            return "/storage/".$path;
        }
    }


    /**
     * Devuelve la correspondecia y la pqr asociada.
     *
     * @author Kleverman Salazar Florez. - Ene. 14 - 2024
     * @version 1.0.0
     *
     * @param int $correspondenceId id de la correspondencia
     *
     * @return Response
     */
    public function hasPqrAssigned(int $correspondenceId) : bool{
        $pqr = PQR::where("correspondence_external_received_id",$correspondenceId)->count();
        return $pqr > 0;
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Carlos Moises Garcia T. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateExternalReceivedRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExternalReceivedRequest $request) {

        $input = $request->all();

        /** @var ExternalReceived $externalReceived */
        $externalReceived = $this->externalReceivedRepository->find($id);

        if (empty($externalReceived)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        if(!isset($input["citizen_id"]) && !isset($input["citizen_name"] )) {
            return $this->sendSuccess('<strong>El nombre del ciudadano es requerido.</strong>'. '<br>' . "Puedes autocompletar un ciudadano o asignar uno personalizado.", 'warning');
        }

        if(!isset($input["functionary_name"])) {
            return $this->sendSuccess('<strong>El Funcionario es requerido.</strong>'. '<br>' . "Puede autocompletar el funcionario ingresando su nombre.", 'warning');
        }
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            // Valida si no seleccionó ningún document_pdf principal
            $input['document_pdf'] = isset($input["document_pdf"]) ? implode(",", $input["document_pdf"]) : NULL;

            $input['attached_document'] = isset($input["attached_document"]) ? implode(",", $input["attached_document"]) : null;

            $input["annexed"] = isset($input["annexed"]) ? $input["annexed"] : "No aplica";
            $input['respuesta_correo'] = isset($input['respuesta_correo']) && ($this->toBoolean($input['respuesta_correo']) || $input['respuesta_correo'] == 1) ? 1 : 0;


            // // Obtiene los datos del funcionario
            // $functionary = User::find($input['functionary_id']);

            // // Obtiene los datos del ciudadano
            // // $userCitizen = Citizen::where("id", $input['citizen_id'])->first();

            // if ($functionary->dependencies) {
            //     $input['dependency_id']    = $functionary->dependencies->id;
            //     $input['dependency_name']  = $functionary->dependencies->nombre;
            // }

            // $input['functionary_name'] = $functionary->fullname;

            if(!empty($input['functionary_id'])){
                // Obtiene los datos del funcionario
                $functionary = User::find($input['functionary_id']);

                if ($functionary->dependencies) {
                    $input['dependency_id']    = $functionary->dependencies->id;
                    $input['dependency_name']  = $functionary->dependencies->nombre;
                }

                $input['functionary_name'] = $functionary->fullname;

            }else{
                $dependenciaInformacion = Dependency::where('id', $input["dependency_id"])->first();
                $input['dependency_name']  = $dependenciaInformacion["nombre"];
            }

            // $input['citizen_name']     = $userCitizen->name;
            $input['user_id']          = Auth::user()->id;
            $input['user_name']        = Auth::user()->fullname;
            // $input['citizen_id']       =  $userCitizen->id;
            // Actualiza el registro

            $input['users_copies'] = isset($input['external_copy']) ? $this->insertCopies($input['external_copy'], $id) : null;

            $input['npqr'] = isset($input['npqr']) ? $this->toBoolean($input['npqr']) : null;
            $input['auto_assign'] = isset($input['auto_assign']) ? $this->toBoolean($input['auto_assign']) : null;


            if ($input['npqr']) {
                $input['pqr'] = $this->crearPqr($input, $id);
            }else if ($input['auto_assign']) {
                $input['pqr'] = $this->crearPqr($input, $id);
            }

            // Actualización de PQR basada en el canal
            if (
                isset($input['pqr']) && 
                ($input['channel'] === 4 || $input['channel'] === 1) // Canal 4: Personal, Canal 1: Correo certificado
            ) {
                PQR::where("pqr_id", $input['pqr'])->update([
                    'nombre_ciudadano' => $input['citizen_name'] ?? null,
                    'documento_ciudadano' => $input['citizen_document'] ?? null,
                    'email_ciudadano' => $input['citizen_email'] ?? null,
                    'anexos' => $input['attached_document'] ?? null,
                ]);
            }

            $externalReceived = $this->externalReceivedRepository->update($input, $id);

            $input["external_received_id"] = $externalReceived->id;
            $input['observation_history'] = "Actualización de correspondencia";

            ReceivedHistory::create($input);

            $externalReceived->pqrDatos;
            $externalReceived->serieClasificacionDocumental;
            $externalReceived->subserieClasificacionDocumental;
            $externalReceived->oficinaProductoraClasificacionDocumental;
            $externalReceived->externalCopy;
            $externalReceived->externalCopyShares;
            $externalReceived->externalHistory;
            // Efectua los cambios realizados
            DB::commit();

            if (isset($externalReceived->citizen_email)) {
                $asunto = json_decode('{"subject": "Notificación de correspondencia recibida – Radicado  '.$externalReceived->consecutive.'"}');
                $notificacion["consecutive"] = $externalReceived->consecutive;
                $notificacion["issue"] = $externalReceived->issue;
                $notificacion["citizen_name"] = $externalReceived->citizen_name;
                $notificacion["validation_code"] = $externalReceived->validation_code;
                $notificacion["validation_code_encode"] = JwtController::generateToken($externalReceived->validation_code);
                $notificacion["pqr"] = $input['pqr'] ?? 'No aplica';
                $notificacion["id"] = $externalReceived->id;
                $notificacion["anexes"] = $externalReceived->attached_document;
                $notificacion["documents"] =$externalReceived->document_pdf;
                try {
                    SendNotificationController::SendNotification('correspondence::external_receiveds.notificacion_ciudadano',$asunto,$notificacion,$externalReceived->citizen_email,'Correspondencia recibida');
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($externalReceived['consecutive'] ?? 'Desconocido') . '- ID: ' . ($id ?? 'Desconocido' ));
                }
            }
            //Notificacion funcionario
            if (isset($functionary->email)) {
                $asunto = json_decode('{"subject": "Actualización: correspondencia recibida '.$externalReceived->consecutive.'"}');
                $notificacion["consecutive"] = $externalReceived->consecutive;
                $notificacion["issue"] = $externalReceived->issue;
                $notificacion["funcionary_name"] = $externalReceived['functionary_name'];
                $notificacion["validation_code"] = $externalReceived->validation_code;
                $notificacion["pqr"] = $input['pqr'] ?? 'No aplica';
                $notificacion["id"] = $externalReceived->id;
                $notificacion["anexes"] = $externalReceived->attached_document;
                $notificacion["documents"] =$externalReceived->document_pdf;
                try {
                    SendNotificationController::SendNotification('correspondence::external_receiveds.notificacion',$asunto,$notificacion,$functionary->email,'Correspondencia recibida');
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($externalReceived['consecutive'] ?? 'Desconocido') . '- ID:' . ($id ?? 'Desconocido'));
                }
            }
            return $this->sendResponse($externalReceived->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($externalReceived['consecutive'] ?? 'Desconocido') . '- ID:' . ($id ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    public function crearPqr($input, $id){

        $input["estado"] = "Abierto";

        $input['respuesta_correo'] = isset($input['respuesta_correo']) && ($this->toBoolean($input['respuesta_correo']) || $input['respuesta_correo'] == 1) ? 1 : 0;

        // if ($input["auto_assign"]) {
        //como se documento auto_assign del fields entonces que asigne automaticamente el pqr al destinatario de la recibida.
        if(true){
            // Si se autoasigna el PQR, se le asigna automáticamente el mismo destinatario de la correspondencia
            $input["funcionario_users_id"] = $input["functionary_id"] ?? 0;
            $input["funcionario_destinatario"] = $input["functionary_name"] ?? '';

            // Verificar si funcionario_users_id tiene un valor
            if (!empty($input["funcionario_users_id"])) {
                // Buscar el usuario por su ID
                $funcionario_destinatario = User::find($input["funcionario_users_id"]);

                // Verificar si el usuario existe y tiene una dependencia asignada
                if ($funcionario_destinatario && $funcionario_destinatario->dependencies) {
                    // Asignar el ID de la dependencia si el usuario tiene una
                    $input["dependency_id"] = $funcionario_destinatario->dependencies->id;
                } else {
                    // Asignar null si el usuario no existe o no tiene una dependencia
                    $input["dependency_id"] = null;
                }
            } else {
                // Asignar null si funcionario_users_id no tiene valor
                $input["dependency_id"] = null;
            }


            // Asigna el estado "Asignado" por defecto al PQR
            //valida que tenga estos datos para poder asignar un pqr, sino lo deja abierto
            if((!empty($input["tipo_plazo"]) && !empty($input["plazo"]) && !empty($input["temprana"])) || !empty($input["pqr_eje_tematico_id"])){
                $input["estado"] = "Asignado";

                // Calcula las fechas de vencimiento de la solicitud
                $expiration_date = $this->calculateExpirationDate($input);
                $expiration_date_temprana = $this->calculateExpirationDateTemprana($input);
                    // Asigna las fechas de vencimiento calculadas
                $input['fecha_vence'] = $expiration_date;
                $input['fecha_temprana'] = $expiration_date_temprana;
            }
        }

        if (!isset($input["pqr"]) || !$input["pqr"]) {
            // Consulta el máximo consecutivo de los PQRS
            $pqr_id = PQR::select(DB::raw("MAX(CAST(SUBSTR(pqr_id, 8) AS SIGNED)) AS consecutivo"))->where("vigencia", date("Y"))->pluck("consecutivo")->first();
            // Si ya existe un registro de PQRS, al consecutivo se incrementa en 1
            if($pqr_id){
                // Calcula el consecutivo tentativo del PQR
                $pqr_id_validar = date("Y")."PQR".($pqr_id+1);
                // Consulta si ya existe un pqr_id de la vigencia actual que sea de migración
                $existe_pqr = PQR::where("pqr_id", $pqr_id_validar)->where("vigencia", date("Y")."1")->count();
                // Si existe el pqr_id de un PQR de migración, al nuevo pqr se le concatena al final "-2"
                if($existe_pqr) {
                    $input["pqr_id"] = date("Y")."PQR".($pqr_id+1)."-2";
                } else {
                    $input["pqr_id"] = date("Y")."PQR".($pqr_id+1);
                }
            } else { // De lo contrario es el primer PQR
                $input["pqr_id"] = date("Y")."PQR1";
            }

            //Mapea números de canales a descripciones de canales de comunicación
            $canales = [
                1 => "Correo certificado",
                2 => "Correo electrónico",
                3 => "Fax",
                4 => "Personal",
                5 => "Telefónico",
                6 => "Web",
                7 => "Buzón de sugerencias",
                8 => "Verbal"
            ];

            //Asigna la descripción del cana
            $canal = $canales[$input["channel"]];

            //Valida si la variable canal viene definida
            if (!isset($canal)) {
                $canal = "Personal";
            }

             // Crea un nuevo registro de PQR
             $pqr = PQR::create([

                'users_name' => Auth::user()->fullname,
                'contenido' => $input["issue"],
                'vigencia' => date("Y"),
                'users_id' => Auth::user()->id,
                'pqr_id' => $input["pqr_id"],
                'folios' => $input["folio"],
                'anexos' => $input["annexed"] ?? "No aplica",
                'canal' => $canal,
                'nombre_ciudadano' => $input["citizen_name"],
                'documento_ciudadano' => $input['citizen_document'] ?? null,
                'email_ciudadano' => $input['citizen_email'] ?? null,
                'ciudadano_users_id' => $input['citizen_id'] ?? null,
                'estado' => $input["estado"],
                'fecha_vence' => $input['fecha_vence'] ?? null,
                'fecha_temprana' => $input['fecha_temprana'] ?? null,
                'funcionario_users_id' => $input['funcionario_users_id'] ?? null,
                'funcionario_destinatario' => $input['funcionario_destinatario'] ?? null,
                'nombre_ejetematico' => $input['nombre_ejetematico'] ?? null,
                'tipo_plazo' => $input['tipo_plazo'] ?? null,
                'plazo' => $input['plazo'] ?? null,
                'temprana' => $input['temprana'] ?? null,
                'pqr_eje_tematico_id' => $input['pqr_eje_tematico_id'] ?? null,
                'validation_code' => $input['validation_code'] ?? null,
                'dependency_id' => $input["dependency_id"] ?? null,
                'correspondence_external_received_id' => $id,
                'classification_serie' => isset($input['classification_serie']) ? $input['classification_serie'] : null ,
                'classification_subserie'  => isset($input['classification_subserie']) ? $input['classification_subserie'] : null ,
                'classification_production_office' => isset($input['classification_production_office']) ? $input['classification_production_office'] : null ,
                'pqr_tipo_solicitud_id' => isset($input['pqr_tipo_solicitud_id']) ? $input['pqr_tipo_solicitud_id'] : null,
                'tipo_solicitud_nombre' => isset($input['pqr_tipo_solicitud_id']) ? DB::table('pqr_tipo_solicitud')->select('nombre')->where('id', $input['pqr_tipo_solicitud_id'])->first()->nombre : null,
                'respuesta_correo' => isset($input['respuesta_correo']) ?? null,

            ]);

        }else{



            // Si ya existe un PQR, se actualiza
            $updateResult = PQR::where("pqr_id", $input["pqr"])->update([
                'users_name' => Auth::user()->fullname,
                'contenido' => $input["issue"],
                'users_id' => Auth::user()->id,
                'folios' => $input["folio"],
                'anexos' => $input["annexed"] ?? "No aplica",
                'canal' => "Web",
                'nombre_ciudadano' => $input["citizen_name"],
                'documento_ciudadano' => $input['citizen_document'] ?? null,
                'email_ciudadano' => $input['citizen_email'] ?? null,
                'ciudadano_users_id' => $input['citizen_id'] ?? null,
                'estado' => $input["estado"],
                'fecha_vence' => $input['fecha_vence'] ?? null,
                'fecha_temprana' => $input['fecha_temprana'] ?? null,
                'funcionario_users_id' => $input['funcionario_users_id'] ?? null,
                'funcionario_destinatario' => $input['funcionario_destinatario'] ?? null,
                'nombre_ejetematico' => $input['nombre_ejetematico'] ?? null,
                'tipo_plazo' => $input['tipo_plazo'] ?? null,
                'plazo' => $input['plazo'] ?? null,
                'temprana' => $input['temprana'] ?? null,
                'pqr_eje_tematico_id' => $input['pqr_eje_tematico_id'] ?? null,
                'dependency_id' => $input["dependency_id"] ?? null,
                'correspondence_external_received_id' => $id,
                'classification_serie' => isset($input['classification_serie']) ? $input['classification_serie'] : null ,
                'classification_subserie'  => isset($input['classification_subserie']) ? $input['classification_subserie'] : null ,
                'classification_production_office' => isset($input['classification_production_office']) ? $input['classification_production_office'] : null,
                'respuesta_correo' =>isset($input['respuesta_correo']) ? $input['respuesta_correo'] : 0,

            ]);

            if ($updateResult) {
                // Si la actualización fue exitosa, obtén el registro actualizado
                $pqr = PQR::where("pqr_id", $input["pqr"])->first();
                $input["pqr_id"] = $pqr->pqr_id;

            }

        }
        $input["linea_tiempo"] = $pqr->getLineaTiempoAttribute();
        $input["id_externa_recibida"] = $id;
        $input["pqr_id_pqr"] = $pqr->id;

        $historial = $input;
        $historial['action'] = "Creación de registro";


        // Crea el historial para el nuevo registro de PQR
        $this->crearPQRHistorial($historial, $pqr);

        // Retorna el ID del PQR creado
        return $input["pqr_id"];
    }

    protected function calculateExpirationDate($input)
    {
        $holidayCalendars = HolidayCalendar::get()->toArray();
        $workingHours = WorkingHours::latest()->first();
        $currentDate = date("Y-m-d H:i:s");
        $dateObtained = strval($currentDate);
        $arrayDate = explode(" ", $dateObtained);
        $hour = explode(":", $arrayDate[1]);

        if ($hour[1] == "00") {
            $currentDate = strtotime('-1 minute', strtotime($currentDate));
            $currentDate = date("Y-m-d H:i:s",  $currentDate);
        }

        if (!empty($input["pqr_eje_tematico_id"])) {
            $eje_tematico = PQREjeTematico::find($input["pqr_eje_tematico_id"]);
            return $this->calculateFutureDate(
                Arr::pluck($holidayCalendars, 'date'),
                $currentDate,
                $eje_tematico->plazo_unidad,
                $eje_tematico->tipo_plazo,
                $eje_tematico->plazo,
                $workingHours
            )[0];
        } else {
            return $this->calculateFutureDate(
                Arr::pluck($holidayCalendars, 'date'),
                $currentDate,
                "Días",
                $input["tipo_plazo"],
                $input["plazo"],
                $workingHours
            )[0];
        }
    }

    protected function calculateExpirationDateTemprana($input)
    {
        $holidayCalendars = HolidayCalendar::get()->toArray();
        $workingHours = WorkingHours::latest()->first();
        $currentDate = date("Y-m-d H:i:s");
        $dateObtained = strval($currentDate);
        $arrayDate = explode(" ", $dateObtained);
        $hour = explode(":", $arrayDate[1]);

        if ($hour[1] == "00") {
            $currentDate = strtotime('-1 minute', strtotime($currentDate));
            $currentDate = date("Y-m-d H:i:s",  $currentDate);
        }

        if ($input["pqr_eje_tematico_id"]) {
            $eje_tematico = PQREjeTematico::find($input["pqr_eje_tematico_id"]);
            return $this->calculateFutureDate(
                Arr::pluck($holidayCalendars, 'date'),
                $currentDate,
                $eje_tematico->plazo_unidad,
                $eje_tematico->tipo_plazo,
                $eje_tematico->temprana,
                $workingHours
            )[0];
        } else {
            return $this->calculateFutureDate(
                Arr::pluck($holidayCalendars, 'date'),
                $currentDate,
                "Días",
                $input["tipo_plazo"],
                $input["temprana"],
                $workingHours
            )[0];
        }
    }

    /**
     * Crea un nuevo registro en el historial del pqrs
     *
     * @author Manuel Marin. - Feb. 23 - 2024
     * @version 1.0.0
     *
     * @param array $datos
     *
     * @throws \Exception
     *
     * @return Response
     */

    function crearPQRHistorial($datos , $pqr){

        $pqrHistorial = PQRHistorial::create([
            'users_name' => Auth::user()->fullname,
            'contenido' => $datos["issue"],
            'vigencia' => date("Y"),
            'users_id' => Auth::user()->id,
            'pqr_id' => $datos["pqr_id"],
            'folios' => $datos["folio"],
            'anexos' => $datos["annexed"] ?? "No aplica",
            'canal' => "Web",
            'nombre_ciudadano' => $datos["citizen_name"],
            'documento_ciudadano' => $datos['citizen_document'] ?? null,
            'email_ciudadano' => $datos['citizen_email'] ?? null,
            'ciudadano_users_id' => $datos['citizen_id'] ?? null,
            'estado' => $datos["estado"],
            'fecha_vence' => $datos['fecha_vence'] ?? null,
            'fecha_temprana' => $datos['fecha_temprana'] ?? null,
            'funcionario_users_id' => $datos['funcionario_users_id'] ?? null,
            'funcionario_destinatario' => $datos['funcionario_destinatario'] ?? null,
            'nombre_ejetematico' => $datos['nombre_ejetematico'] ?? null,
            'tipo_plazo' => $datos['tipo_plazo'] ?? null,
            'plazo' => $datos['plazo'] ?? null,
            'temprana' => $datos['temprana'] ?? null,
            'pqr_eje_tematico_id' => $datos['pqr_eje_tematico_id'] ?? null,
            'correspondence_external_received_id' => $datos['id_externa_recibida'],
            'pqr_pqr_id' => $datos["pqr_id_pqr"],
            'linea_tiempo' => $datos["linea_tiempo"],
            'action' => $datos["action"]

        ]);

        return $pqrHistorial;

    }

    /**
     * Elimina un ExternalReceived del almacenamiento
     *
     * @author Carlos Moises Garcia T. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var ExternalReceived $externalReceived */
        $externalReceived = $this->externalReceivedRepository->find($id);

        if (empty($externalReceived)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $externalReceived->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($externalReceived['consecutive'] ?? 'Desconocido') . '- ID:' . ($id ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Leonardo F Enero 2024
     * @version 1.0.0
     *
     * @param vigenci vigencia recibida para realizar la exportación
     */
    public function exportRepositoryExternalReceiveds(Request $request)
    {


        $input = $request->all();

        //Consulta el user joomla id del usuario en sesion
        $userid = Auth::user()->user_joomla_id;

        $likedes1 = '%"id":"'.$userid.'"%';
        $likedes2 = '%"id":'.$userid.',%';
        $likedes3 = '%"id":'.$userid.'}%';

        $table = "";

        //Obtiene la fecha actual
        $date = date("Y");

        //valida a que tabla realizar la consulta
        if (isset($input['vigencia']) && $input['vigencia'] != $date && $input['vigencia'] != '2024') {
            $table = "externa_".$input['vigencia'];
        }else{
            $table = "externa";
        }

        //Valida el rol del usuario en sesion
        if (Auth::user()->hasRole('Correspondencia Recibida Admin')) {
            $querys = DB::connection('joomla')->table($table)->where('consecutivo', 'LIKE', '%RE%');
        }else{
            $querys = DB::connection('joomla')->table($table)->where('consecutivo', 'LIKE', '%RE%')->where(function($query) use ($userid, $likedes1, $likedes2, $likedes3) {
                $query->where('funcionario_des', $userid)
                    ->orWhere('funcionario_or', $userid)
                    ->orWhere('copia', 'LIKE', $userid)
                    ->orWhere('copia', 'LIKE', $userid . ',%')
                    ->orWhere('copia', 'LIKE', '%,' . $userid . ',%')
                    ->orWhere('copia', 'LIKE', '%,' . $userid)
                    ->orWhere('destinatarios', 'LIKE', $likedes1)
                    ->orWhere('destinatarios', 'LIKE', $likedes2)
                    ->orWhere('destinatarios', 'LIKE', $likedes3)
                    ->orWhere('compartida', 'LIKE', $userid)
                    ->orWhere('compartida', 'LIKE', $userid . ',%')
                    ->orWhere('compartida', 'LIKE', '%/' . $userid . '/%')
                    ->orWhere('compartida', 'LIKE', '%,' . $userid . ',%')
                    ->orWhere('compartida', 'LIKE', '%,' . $userid)
                    ->orWhere('elaboradopor', $userid)
                    ->orWhere('revisadopor', $userid)
                    ->orWhere('aprobadopor', $userid);
            });
        }

        //Valida si la consulta trae filtros
        if (isset($input['filtros'])) {
            $externa = $querys->whereRaw($input['filtros'])->orderBy('cf_created', 'DESC')->get()->toArray();
        } else {
            $externa = $querys->orderBy('cf_created', 'DESC')->get()->toArray();
        }



        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // $fileName = date('Y-m-d H:i:s').'-'.trans('request_authorizations').'.'.$fileType;
        $fileName = 'setting.' . $fileType;
        return Excel::download(new RequestExport('correspondence::external_receiveds.reports.report_excel',JwtController::generateToken($externa),'N'), $fileName);

    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Carlos Moises Garcia T. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('external_receiveds').'.'.$fileType;

        //valida si es un administrador de correspondencia recibida
        if (Auth::user()->hasRole('Correspondencia Recibida Admin')) {
            // Valida si esta filtrando
            if(isset($input["filtros"]) && $input["filtros"] != "") {
                // Consulta las correspondencias recibidas según los filtros de búsqueda realizados
                $data = ExternalReceived::whereRaw($input["filtros"])->latest()->get()->toArray();
            } else {
                // Si la variable request no tiene ningún parámetro de filtros de consulta, hace la consulta normal (convencional)
                $data = ExternalReceived::latest()->get()->toArray();
            }
        } else {
            // Valida si esta filtrando
            if(isset($input["filtros"]) && $input["filtros"] != "") {
                 // Consulta las correspondencias recibidas según los filtros de búsqueda realizados
                $data = ExternalReceived::select('external_received.*')
                ->leftJoin('external_received_copy_share', function($join) {
                    $join->on('external_received.id', '=', 'external_received_copy_share.external_received_id')
                    ->whereNull('external_received_copy_share.deleted_at');
                })
                ->where(function($q) {
                    $q->where("external_received.functionary_id", Auth::user()->id);
                    $q->orWhere("external_received_copy_share.users_id", Auth::user()->id);
                })
                ->whereRaw($input["filtros"])
                ->distinct("external_received.id")->latest()->get()->toArray();
            } else {
                // Si la variable request no tiene ningún parámetro de filtros de consulta, hace la consulta normal (convencional)
                $data = ExternalReceived::select('external_received.*')
                ->leftJoin('external_received_copy_share', function($join) {
                    $join->on('external_received.id', '=', 'external_received_copy_share.external_received_id')
                    ->whereNull('external_received_copy_share.deleted_at');
                })
                ->where(function($q) {
                    $q->where("external_received.functionary_id", Auth::user()->id);
                    $q->orWhere("external_received_copy_share.users_id", Auth::user()->id);
                })
                ->distinct("external_received.id")->latest()->get()->toArray();
            }
        }

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {

            //nueva clave para lista de correspondencia
            $input['correspondence_recibida'] = [];


            // $data = JwtController::decodeToken($input['data']);

            array_walk($data, fn(&$object) => $object = (array)$object);

            //ciclo para agregar las correspondencia a los usuarios
            foreach($data as $item){
                //objecto vacio para incluir elementos necesarios
                $object_recibida = new stdClass;
                $object_recibida->consecutive = $item['consecutive'];
                $object_recibida->type_documentary_name = $item['type_documentary_name'];
                $object_recibida->asunto = $item['issue'];
                $object_recibida->functionary_name = $item['functionary_name'];
                $object_recibida->citizen_name = $item['citizen_name'];
                $object_recibida->type_documentary_name=$item['type_documentary_name'];
                $object_recibida->annexed= $item['annexed'];
                $object_recibida->dependency_name = $item['dependency_name'];
                $dt = new DateTime($item['created_at']);
                $object_recibida->time =  $dt->format("H:i:s");
                $object_recibida->date = $dt->format('d/m/Y');
                $object_recibida->user_name=$item['user_name'];
                $object_recibida->recibido_fisico=$item['recibido_fisico'];


                array_push($input['correspondence_recibida'],$object_recibida);
            }


            //elimina el atributo data
            unset($input['data']);


            try {
                $filePDF = PDF::loadView('correspondence::external_receiveds.report_pdf', ['data' => $input['correspondence_recibida']])->setPaper(([0, 0, 841.89, 594.29]));
                return $filePDF->download("reporte_correspondencia.pdf");
            } catch (\Exception $e) {
                return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
            }

            // Descarga el archivo generado
            // return Excel::download(new RequestExport('correspondence::external_receiveds.report_pdf',$input['correspondence_recibida'],'b'), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {

            $info_radicadores = array();

            // $data = JwtController::decodeToken($input['data']);

            array_walk($data, fn(&$object) => $object = (array)$object);

            //Recorrer el array que trae todos los datos de las radicaciones
            foreach($data as $dato){
                //Almacenar al array los nombres de los radicadores
                $info_radicadores[] = $dato['functionary_id'];
            }


            $dataRecibida = [];

            $dataRecibidaCount = 0;

            $cantidad_radicaciones = 0;

            foreach (array_unique($info_radicadores) as $radicador) {

                $informationUser = User::select("name")->where('id', $radicador)->first();

                $dataRecibida[$dataRecibidaCount]['radicador'] = $informationUser['name'] ?? "N/A";


                $numero_radicaciones = 0;

                //Recorrer array para obtener el número de radicaciones del funcionario
                foreach($data as $dato){
                    if ($dato["functionary_id"] == $radicador) {
                        //Código para incrementar el número de radicaciones
                        $dataRecibida[$dataRecibidaCount]['internas'][$numero_radicaciones] = $dato;
                        $numero_radicaciones ++;
                        $cantidad_radicaciones ++;
                    }
                }

                $dataRecibida[$dataRecibidaCount]['total'] = $numero_radicaciones;
                $dataRecibidaCount++;

            }


            $count = count($dataRecibida) +  $cantidad_radicaciones + (count($dataRecibida) * 8);
            // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            return Excel::download(new RequestExportCorrespondence('correspondence::external_receiveds.report_excel',$dataRecibida,$count,'K'), $fileName);

            // return Excel::download(new RequestExportCorrespondence('correspondence::external_receiveds.report_excel', JwtController::generateToken($request_data), 'h'), $fileName);
        }
    }

    public function saveAttachmentLabel($id, Request $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();

        // try {
            // Selecciona los adjuntos de la correspondencia
            $received = ExternalReceived::where("id", $id)->first()->toArray();
            // Valida si existe una variable con un file
            if ($request->hasFile('document_pdf')) {
                $input['document_pdf'] = substr($input['document_pdf']->store('public/container/external_received_'.date("Y")), 7);
            }
            // Valida si la correspondencia ya tenía adjuntos previamente
            // Agrega el nuevo adjunto al array de adjuntos si ya existen adjuntos previos
            $input['document_pdf'] = $received['document_pdf'] ? implode(",", array_merge(explode(",", $received['document_pdf']), [$input['document_pdf']])) : $input['document_pdf'];

            // Actualiza el campo adjunto (document_pdf) de la correspondencia
            $received = $this->externalReceivedRepository->update($input, $id);

            $received["external_received_id"] = $id;
            $received['observation_history'] = "Actualización de correspondencia";
            $received['document_pdf'] = $input['document_pdf'];

            // $recibidaHistorial = ReceivedHistory::create($received);

            $receivedArray = $received->toArray();
            $receivedArray['external_received_id'] = $id;
            $receivedArray['observation_history'] = "Actualización de correspondencia";
            $receivedArray['document_pdf'] = $input['document_pdf'];
            $receivedArray['user_id']          = Auth::user()->id;
            $receivedArray['user_name']        = Auth::user()->fullname;
            $recibidaHistorial = ReceivedHistory::create($receivedArray);
            $received->externalHistory;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($received->toArray(), trans('msg_success_update'));
        // } catch (\Illuminate\Database\QueryException $e) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
        //     // Retorna mensaje de error de base de datos
        //     return $this->sendSuccess(config('constants.support_message'), 'info');
        // } catch (\Exception $e) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
        //     // Retorna error de tipo logico
        //     return $this->sendSuccess(config('constants.support_message'), 'info');
        // }
    }


    public function externalShare(Request $request)
    {

        $input = $request->all();
        $id = $input["id"];

        $external = $this->externalReceivedRepository->find($id);

        if (empty($external)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida si viene usuarios para asignar
        if (!empty($input['external_shares'])) {

            //borra todo para volver a insertarlo
            ExternalReceivedCopyShare::where('external_received_id', $id)->where("type","=","Compartida")->delete();

            //texto para almacenar en la tabla external_received
            $textRecipients = array();
            //recorre los destinatarios
            foreach ($input['external_shares'] as $recipent) {
                //array de destinatarios
                $recipentArray = json_decode($recipent, true);
                $recipentArray["external_received_id"] = $id;
                $recipentArray["type"] = "Compartida";
                $recipentArray["name"] = $recipentArray["fullname"];
                $textRecipients[] = $recipentArray["name"];
                $externalReceived = ExternalReceived :: where ('id' , $id)->first();

                ExternalReceivedCopyShare::create($recipentArray);

                $asunto = json_decode('{"subject": "Notificación  de correspondencia recibida  ' . $externalReceived->consecutive . ' compartida"}');
                $email = User::where('id', $recipentArray['users_id'])->first()->email;
                $notificacion["consecutive"] = $externalReceived->consecutive;
                $notificacion["id"] = $externalReceived->id;
                $notificacion["name"] = $recipentArray["name"];
                $notificacion["id_encrypted"] = base64_encode($externalReceived->id);
                $notificacion['mensaje'] = "Le informamos que se le ha compartido la correspondencia recibida con radicado: <strong>{$externalReceived->consecutive}</strong>. <br><br>Le agradecemos tener en cuenta esta notificación para los fines correspondientes";
                try {
                    SendNotificationController::SendNotification('correspondence::external_receiveds.emails.plantilla_notificaciones',$asunto,$notificacion,$email,'Correspondencia recibida');
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($external['consecutive'] ?? 'Desconocido') . '- ID:' . ($id ?? 'Desconocido'));
                }

            }

            $updateExternal["shares"] = implode("<br>", $textRecipients);
            $external = $this->externalReceivedRepository->update($updateExternal,$id);
        }else{

            //borra todo para volver a insertarlo
            ExternalReceivedCopyShare::where('external_received_id', $id)->where('type', "Compartida")->delete();
            $updateExternal["shares"] = "";
            $external = $this->externalReceivedRepository->update($updateExternal,$id);
        }


        if (!empty($input['annotation'])) {
            ReceivedAnnotation:: create([
                'external_received_id' => $id,
                'users_id'  => Auth::user()->id,
                'users_name' => Auth::user()->fullname,
                'annotation' => $input['annotation']
            ]);
        }
        $external->externalShares;
        $external->externalAnnotations;

        return $this->sendResponse($external->toArray(), trans('msg_success_update'));
    }

    public function read($correspondenceId) {

        $userLogin = Auth::user();

        $readCorrespondence = ExternalReceivedRead::select("id", "access", 'users_name')->where("correspondence_external_id", $correspondenceId)->where("users_id", $userLogin->id)->where("users_name", $userLogin->fullname)->first();
        if($readCorrespondence) {
            // Valida si ya tiene accesos
            if($readCorrespondence["access"]) {
                $accesos = $readCorrespondence["access"]."<br/>".date("Y-m-d H:i:s");
            } else {
                $accesos = date("Y-m-d H:i:s");
            }
            // Actualiza los accesos del leido
            $resultReadCorrespondence = ExternalReceivedRead::where("id", $readCorrespondence["id"])->update(["access" => $accesos], $readCorrespondence["id"]);
        } else {
            $readCorrespondence = date("Y-m-d H:i:s");

            // Valida si es el usuario que esta leyendo el registro, tiene el rol de administrador
            if(Auth::user()->hasRole('Correspondencia Recibida Admin')) {
                $rol = "Administrador";
            } else {
                $rol = "Funcionario";
            }
            // Crea un registro de leido del registro
            $resultReadCorrespondence = ExternalReceivedRead::create([
                'users_name' => Auth::user()->fullname,
                'users_type' => $rol,
                'access' => $readCorrespondence,
                'year' => date("Y"),
                'correspondence_external_id' => $correspondenceId,
                'users_id' => Auth::user()->id
            ]);
        }

            // Obtener el ID del usuario actual
            $userId = Auth::id();

            // Actualizar los registros directamente en la base de datos
            ReceivedAnnotation::where('external_received_id', $correspondenceId)
                ->where(function ($query) use ($userId) {
                    $query->where('leido_por', null) // Si el campo 'leido_por' es null, establece el ID del usuario actual
                        ->orWhere('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
                        ->orWhere('leido_por', 'not like', $userId . ',%'); // También para el caso donde el ID del usuario esté al principio seguido de una coma
                })
                ->update(['leido_por' => \DB::raw("CONCAT_WS(',', COALESCE(leido_por, ''), '$userId')")]);

            // Buscar y obtener la instancia de Internal correspondiente
            $correspondencia = $this->externalReceivedRepository->find($correspondenceId);

            // Cargar ansiosamente las anotaciones pendientes asociadas a la instancia de Internal
            $correspondencia->anotacionesPendientes;

            // Devolver una respuesta con los datos de la instancia de Internal actualizados
            return $this->sendResponse($correspondencia->toArray(), trans('msg_success_update'));

        // return $this->sendResponse($resultReadCorrespondence, "Correspondencia leida con éxito");
    }

    public function insertCopies($copias,$id){
        // Valida si viene usuarios para asignar
        if (!empty($copias)) {

            //borra todo para volver a insertarlo
            ExternalReceivedCopyShare::where('external_received_id', $id)->where("type","=","Copia")->delete();

            //texto para almacenar en la tabla interna
            $textRecipients = array();
            //recorre los destinatarios
            foreach ($copias as $recipent) {
                //array de destinatarios
                $recipentArray = json_decode($recipent,true);
                $recipentArray["external_received_id"] = $id;
                $recipentArray["type"] = "Copia";
                $recipentArray["name"] = $recipentArray["fullname"];
                $textRecipients[] = $recipentArray["name"];
                $externalReceived = ExternalReceived :: where ('id' , $id)->first();

                $asunto = json_decode('{"subject": "Notificación de copia de correspondencia recibida ' . $externalReceived->consecutive . '"}');
                $email = User::where('id', $recipentArray['users_id'])->first()->email;
                $notificacion["consecutive"] = $externalReceived->consecutive;
                $notificacion["id"] = $externalReceived->id;
                $notificacion["name"] = $recipentArray["name"];
                $notificacion["id_encrypted"] = base64_encode($externalReceived->id);

                $notificacion['mensaje'] = "Le informamos que ha sido incluido(a) como destinatario(a) de copia en el registro de la correspondencia con número de radicado: <strong>{$externalReceived->consecutive}</strong>. <br><br>Le agradecemos tener en cuenta esta notificación para los fines correspondientes";
                try {
                    SendNotificationController::SendNotification('correspondence::external_receiveds.emails.plantilla_notificaciones',$asunto,$notificacion,$email,'Correspondencia recibida');
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine() . ' -ID: ' . ($id ?? 'Desconocido'));
                }

                ExternalReceivedCopyShare::create($recipentArray);
            }
            return implode("<br>", $textRecipients);

        }else{
            //borra todo para volver a insertarlo
            ExternalReceivedCopyShare::where('external_received_id', $id)->where('type', "Copia")->delete();
            return "";
        }
    }

    /**
     * Exporta el historial de la externa recibida
     *
     * @author Manuel Marin. - Abril. 09. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function exportHistorial($id)
    {
        $historial = ReceivedHistory::where('external_received_id', $id)->get();

        return Excel::download(new RequestExport('correspondence::external_receiveds.reports.report_historial', JwtController::generateToken($historial->toArray()), 'j'), 'Prueba.xlsx');
    }

    /**
     * Actualiza un registro especifico
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateExternalRequest $request
     *
     * @return Response
     */
    public function updateFile($id, UpdateExternalRequest $request)
    {
        $input = $request->all();

        /** @var ExternalReceived $external */
        $external = $this->externalReceivedRepository->find($id);

        if (empty($external)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida si no seleccionó ningún adjunto
        $input['document_pdf'] = isset($input["new_route"]) ? implode(",", $input["new_route"]) : null;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            // $external = $this->externalReceivedRepository->update($input, $id);
            // $input['correspondence_external_id'] = $external->id;
            // $input['observation_history'] = "Actualización de correspondencia";

            // // Crea un nuevo registro de historial
            // $this->ExternalHistoryRepository->create($input);

            // //Obtiene el historial
            // $external->externalHistory;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($external->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine() . '- ID: '. ($id ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    public function validarExternalCodigoFromEmail(Request $request)
    {

        $input = $request->all();
        $input = AntiXSSController::xssClean($input,["codigoAccesoDocumento","c"]);

        // Obtenemos la dirección IP pública del usuario
        $publicIp = $this->detectIP();

        // Buscamos la correspondencia externa asociada al código de validación
        $external = ExternalReceived::where('validation_code', $input['codigoAccesoDocumento'])->first();

        if(!$external){
            $codigoDecode = JwtController::decodeToken($input['codigoAccesoDocumento']);
            $external = ExternalReceived::where('id', $codigoDecode)->orWhere('validation_code', $codigoDecode)->first();
        }

        // Verificamos si se encontró la correspondencia externa
        if ($external) {
            // Registramos la apertura de la correspondencia externa desde el correo electrónico
            $resultReadCorrespondence = ExternalReceivedRead::create([
                'users_name' => "Apertura desde el correo electrónico: " . $external->citizen_email,
                'users_type' => "Ciudadano",
                'access' => now()->format('Y-m-d H:i:s') . " - IP: " . $publicIp,
                'year' => now()->year,
                'correspondence_external_id' => $external->id,
                'users_id' => 0
            ]);


            return $this->sendResponse([
                'success' => true,
                'rutapdf' => $external->document_pdf,
                'consecutivo' => $external->consecutive,
                'anexosDescripcion' => $external->annexed,
                'rutaanexos' => $external->attached_document,
                'title' => $external->issue,


            ], trans('msg_success_save'));

        } else {
            // Si no se encontró la correspondencia externa, retornamos un mensaje de error
            return $this->sendResponse([
                'success' => false,
            ], 'Documento no encontrado');
        }
    }

    /**
     * Muestra la vista pública para la consulta de PQRS según el consecutivo y código de validación de la correspondencia recibida.
     *
     * @author Desarrollador Seven - Jun. 2 - 2024
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function consultarVistaPQRS(Request $request) {
        return view('correspondence::p_q_r_s_ciudadano.index');
    }

    /**
     * Obtiene el registro de pqr según el consecutivo y el código de validación de la correspondencia recibida
     *
     * @author Desarrollador Seven - Jun. 2 - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtenerPQRSCiudadano(Request $request) {
        // * f: filtros
        // Valida si existe la variable f de filtrado
        if(isset($request["f"]) && $request["f"] != "") {
            // Decodifica los campos filtrados y sanitiza la entrada
            $filtros = AntiXSSController::xssClean(base64_decode($request["f"]));

            // Si en los filtros no esta la propiedad 'consecutive' o 'validation_code', se cumple la condición
            if(strpos($filtros, "consecutive") === false || strpos($filtros, "validation_code") === false) {
                // Retorna un mensaje al usuario indicandole que ambos filtros son requeridos para realizar la consulta
                return $this->sendResponseAvanzado("", trans('data_obtained_successfully'), null, ["no_consulto" => "No", "mensaje_enviado" => "Por favor ingrese un número de radicado y código de validación en los filtros de búsqueda.", "icono" => "info", "total_registros" => 0]);
            }
            //"consecutive='2024RE63' AND validation_code='LF7WZBYS'"

            //"validation_code='LF7WZBYS' AND consecutive='2024RE63'"

            $filtrosFinal='';

            // Expresiones regulares para ambas combinaciones
            $pattern1 = "/consecutive='(.*?)' AND validation_code='(.*?)'/";
            $pattern2 = "/validation_code='(.*?)' AND consecutive='(.*?)'/";

            // Aplicar las expresiones regulares
            if (preg_match($pattern1, $filtros, $matches)) {
                $consecutive = $matches[1];
                $validation_code = $matches[2];
                $filtrosFinal = "(consecutive = '".$consecutive."' OR pqr='".$consecutive."') AND validation_code='".$validation_code."'";

            } elseif (preg_match($pattern2, $filtros, $matches)) {
                $validation_code = $matches[1];
                $consecutive = $matches[2];

                $filtrosFinal = "(consecutive = '".$consecutive."' OR pqr='".$consecutive."') AND validation_code='".$validation_code."'";
            }

            // Consulta la correspondencia recibida y su pqr si lo tiene, según los filtros de búsqueda
            $correspondence_recibida = ExternalReceived::whereRaw($filtrosFinal)->get()->toArray();
            // Si la consulta no retorna ningún registro (=== 0), se cumple la condición
            if (count($correspondence_recibida) === 0) {
                // Retorna un mensaje indicándole al usuario que no encontró ningún registro según los filtros ingresados
                return $this->sendResponseAvanzado($correspondence_recibida, trans('data_obtained_successfully'), null, ["total_registros" => count($correspondence_recibida), "no_consulto" => "No", "mensaje_enviado" => "No se encontró ninguna correspondencia con ese número de radicado y código de validación. Por favor, verifique e intente nuevamente.","icono" => "info"]);
            } else {
                // Asigna la información asociada del PQR a la variable 'correspondence_recibida', ya que esta debe retornar solo una coincidencia
                $temporalRecibida = $correspondence_recibida[0];
                $correspondence_recibida[0] = $correspondence_recibida[0]["pqr_datos"];
                $correspondence_recibida[0]['pqr_correspondence'] = $temporalRecibida;

                // Si encontró un registro de correspondencia, retorna la información del PQR asociado si lo tiene
                return $this->sendResponseAvanzado($correspondence_recibida, trans('data_obtained_successfully'), null, ["total_registros" => count($correspondence_recibida)]);
            }
        } else {
            // Si no esta buscando información, retorna vacío
            return $this->sendResponseAvanzado([], trans('data_obtained_successfully'), null, ["total_registros" => 0]);
        }
    }

    /**
     * Migracion de las correspondencias externas
     *
     * @author Leonardo F.H 11 de junio 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function MigrateExternals(Request $request)
    {
        try {
            $user = Auth::user();
            $input = $request->toArray();
            $successfulRegistrations = 0;
            $failedRegistrations = 0;
            $storedRecords = [];
            $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            if ($request->hasFile('file_import')) {
                // Obtener el archivo del request
                $file = $request->file('file_import');
                // Obtener la extensión original del archivo
                $extension = $file->getClientOriginalExtension();
                // Extensiones permitidas
                $allowedExtensions = ['xls', 'xlsx'];
                // Validar la extensión
                if (!in_array($extension, $allowedExtensions)) {
                    return $this->sendError('El archivo debe ser un documento Excel con extensión .xls o .xlsx.', []);

                }
                $data = Excel::toArray(new ExternalReceivedRead, $input["file_import"]);
                $contArray = count($data[0][0]);
                unset($data[0][0]);
                if ($contArray == 10) {
                    $filteredData = array_filter($data[0], function($row) {
                        return !is_null($row[2]);
                    });
                    // En este for se realizan validaciones de los campos del excel, para, posteriormente continur con el registro
                    foreach ($filteredData as $key => $row) {
                        if ($row[1] != null && $row[3] != null && $row[4] != null && $row[6] != null) {


                            $user_asigned = User::where('name' , $row[3])->with('dependencies')->first();

                            //validacion de el correo tenga una estructura valida
                            if (isset($row[2])   && !filter_var($row[2], FILTER_VALIDATE_EMAIL)) {
                                    return $this->sendError('Error,por favor verifique la dirección de correo electrónico proporcionada  en la fila '.$key.' Ya que no cumple con los requisitos de una direccion valida', []);

                            }

                            //validacion de que el funcionario exista
                            if (empty($user_asigned)) {
                                return $this->sendError('Error,por favor verifique que el nombre del  funcionario asignado en la fila '.$key.' Ya que el nombre ingresado no se encuentra en la lista de funcionarios de la entidad', []);
                            }
                        }else{
                            return $this->sendError('Error,por favor verifique todos los campos de la columna  '.$key, []);

                        }
                    }

                    foreach ($filteredData as $row) {

                        if ($row[0]  != NULL && $row[1] != NULL && $row[3] != NULL && $row[4] != NULL && $row[6] != NULL) {
                            try {
                            $user_asigned = User :: where('name' , $row[3])->with('dependencies')->first();


                                    $externa = ExternalReceived::create([
                                        'dependency_id'=> $user_asigned->dependencies->id,
                                        'functionary_id' => $user_asigned->id,
                                        'type_documentary_id' => NULL,
                                        'dependency_name' => $user_asigned->dependencies->nombre,
                                        'functionary_name' => $row[3] ?? '',
                                        'citizen_name'=> $row[0] ?? '',
                                        'user_name' => $user->name,
                                        'consecutive' => date("Y")."RE".(ExternalReceived::where('year', date("Y"))->max(\DB::raw("CAST(SUBSTRING(consecutive, 7) AS UNSIGNED)")) + 1),
                                        'issue'=> isset($row[5]) ? $row[5] : '',
                                        'folio'=> isset($row[7]) ? $row[7] : '',
                                        'annexed' => 'No Aplica',
                                        'channel' => isset($row[6]) ? '1' : NULL ,
                                        'novelty' => isset($row[8]) ? $row[8] : '',
                                        'year' => date("Y"),
                                        'state' => '3',
                                        'citizen_document' => isset($row[1]) ? $row[1] : '',
                                        'citizen_email' => isset($row[2]) ? $row[2] : '',
                                        'citizen_users_id' => isset($row[1]) ? $row[1] : '',
                                        'validation_code' => substr(str_shuffle($caracteres_permitidos), 0, 8),
                                        'code_temporal' => isset($row[9]) ? $row[9] : ''
                                    ]);


                                    if ($row[2] != null) {

                                        $asunto = json_decode('{"subject": "Actualización: correspondencia recibida '.$externa->consecutive.'"}');
                                        $notificacion["consecutive"] = $externa->consecutive;
                                        $notificacion["issue"] = $externa->issue;
                                        $notificacion["citizen_name"] = $externa->citizen_name;
                                        $notificacion["validation_code"] = $externa->validation_code;
                                        $notificacion["pqr"] = $externa->pqr ?? 'No aplica';
                                        $notificacion["id"] = $externa->id;
                                        SendNotificationController::SendNotification('correspondence::external_receiveds.notificacion_ciudadano',$asunto,$notificacion,$externa->citizen_email,'Correspondencia recibida');

                                    }

                                    $successfulRegistrations++;

                            } catch (\Illuminate\Database\QueryException $error) {
                                $failedRegistrations++;
                                 $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $error->getMessage() . '. Linea: ' . $error->getLine());

                            }
                        }
                    }
                } else {
                    return $this->sendError('Error,por favor verifique que el número de columnas con datos en el excel coincida con el número de columnas del formulario de importación de actividades', []);
                }
            }
            return $this->sendResponse($externa, trans('msg_success_save') . "<br /><br />Registros exitosos: $successfulRegistrations<br />Registros fallidos: $failedRegistrations");
        } catch (\Exception $e) {
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: ' . ($notificacion["consecutive"] ?? 'Desconocido' ));
        }
    }

    public function updateRecibidoFisico($id, UpdateExternalReceivedRequest $request) {

        $input = $request->all();

        /** @var ExternalReceived $externalReceived */
        $externalReceived = $this->externalReceivedRepository->find($id);

        if (empty($externalReceived)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $input = [
                'recibido_fisico' => date("Y-m-d H:i:s"),
            ];

             $externalReceived = $this->externalReceivedRepository->update($input, $id);

            // Update the recibido_fisico field with the current date
            // $updateResult = ExternalReceived::where('id', $id)
            // ->update(['recibido_fisico' => date("Y-m-d H:i:s")]);
            // Efectua los cambios realizados
            DB::commit();
            return $this->sendResponse($externalReceived->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '. ($externalReceived['consecutive'] ?? 'Desconocido' ));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }
     /**
     * Generar una URL para visualizar un documento en una nueva pestaña.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */

     public function watchDocument(Request $request)
     {
         try {
             // Obtener parámetros de la solicitud
             $documentoEncriptado = $request->query('document');
             $idCorrespondence = $request->query('id');
             $idMail = $request->query('id_mail');
             if (!empty($documentoEncriptado)) {
                $documento = Crypt::decryptString($documentoEncriptado);
                $read = $this->readDocument($idCorrespondence,$idMail);
                 return view('correspondence::view_document_public.index', compact(['documento']));
             }
             $read = $this->readDocument($idCorrespondence,$idMail);
             $notificacion = NotificacionesMailIntraweb::where('id_mail', $idMail)->get()->first()->toArray();

             return view('correspondence::view_document_public.correo_leido',compact(['read','notificacion']));

             // dd($documentoEncriptado,$idCorrespondence,$idMail);
         } catch (\Exception $e) {
             $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

         }
     }
    private function readDocument($id,$idMail)
    {
        // Obtenemos la dirección IP pública del usuario
        $publicIp = $this->detectIP();
        // Buscamos la correspondencia recibida
        $external = ExternalReceived::where('id', $id)->get()->first();
        // Verificamos si se encontró la correspondencia externa
        if ($external) {
            // Registramos la apertura de la correspondencia externa desde el correo electrónico
            $resultReadCorrespondence = ExternalReceivedRead::create([
                'users_name' => "Apertura desde el correo electrónico",
                'users_type' => "Ciudadano",
                'access' => now()->format('Y-m-d H:i:s') . " - IP: " . $publicIp,
                'year' => now()->year,
                'correspondence_external_id' => $external->id,
                'users_id' => 0,
            ]);
            if ($idMail != null) {
                // Una vez se envía se actualiza el campo nuevamente
                $notificacion = NotificacionesMailIntraweb::where('id_mail', $idMail)
                ->update([
                    'leido' => 'Si',
                    'fecha_hora_leido' => date('Y-m-d H:i:s'),
                    'ip_leido' => $publicIp,
                    'agente_navegador' => 'Google'
                ]);
            }
             return $this->sendResponse([
                'success' => true
            ], trans('msg_success_save'));
        } else {
            // Si no se encontró la correspondencia externa, retornamos un mensaje de error
            return $this->sendResponse([
                'success' => false,
            ], 'Documento no encontrado');
        }
    }

}
