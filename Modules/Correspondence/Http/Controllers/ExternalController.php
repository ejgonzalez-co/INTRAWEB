<?php

namespace Modules\Correspondence\Http\Controllers;

use Faker\Factory;
use App\Exports\correspondence\GenericExport;
use App\Exports\correspondence\RequestExport;
use App\Exports\correspondence\RequestExportLandscape;
use App\Exports\correspondence\RequestExportCorrespondence;
use Modules\Correspondence\Http\Requests\CreateExternalRequest;
use Modules\Correspondence\Http\Requests\UpdateExternalRequest;
use Modules\Correspondence\Repositories\ExternalRepository;
use Modules\Correspondence\Repositories\ExternalHistoryRepository;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\AntiXSSController;
use App\Http\Controllers\GoogleController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Correspondence\Models\External;
use Modules\Correspondence\Models\ExternalReceived;
use Modules\Correspondence\Models\ExternalTypes;
use Modules\Correspondence\Models\ExternalRecipient;
use Modules\Correspondence\Models\ExternalCopyShare;
use Modules\Correspondence\Models\ExternalVersions;
use Modules\Correspondence\Models\ExternalSigns;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
use Modules\Configuracion\Models\Variables;
use Modules\Intranet\Models\Dependency;
use App\User;
use Modules\Correspondence\Http\Controllers\UtilController;
use App\Models\State;
use App\Models\City;
use Modules\Correspondence\Models\ExternalRead;
use Modules\PQRS\Models\PQR;
use \stdClass;
use Modules\Correspondence\Models\ExternalHistory;
use Modules\Correspondence\Models\ExternalCitizen;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Http\Controllers\JwtController;
use App\Http\Controllers\SendNotificationController;
use Modules\Correspondence\Models\ExternalAnnotation;
use Modules\PQRS\Models\PQRHistorial;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Mpdf\Mpdf;
use Cloudinary\Cloudinary;
use Cloudinary\Transformation;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Crypt;
use Modules\NotificacionesIntraweb\Models\NotificacionesMailIntraweb;

/**
 * Descripcion de la clase
 *
 * @author Erika Gonzalez. - Abr. 06 - 2020
 * @version 1.0.0
 */
class ExternalController extends AppBaseController
{

    /** @var  ExternalRepository */
    private $externalRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Gonzalez. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(ExternalRepository $externalRepo, ExternalHistoryRepository $externalRepoHistory)
    {
        $this->externalRepository = $externalRepo;
        $this->ExternalHistoryRepository = $externalRepoHistory;
    }

    /**
     * Muestra la vista para el CRUD de External.
     *
     * @author Erika Gonzalez. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if (!Auth::user()->hasRole(["Ciudadano"])) {
            $clasificacion = Variables::where('name', 'clasificacion_documental_enviada')->pluck('value')->first();

            return view('correspondence::externals.index', compact(['clasificacion']));
        }
        return view("auth.forbidden");
    }

    /**
     * Muestra la vista de externa repositorio del sitio anterior de la entidad.
     *
     * @author Desarrollador Seven - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexRepositorio(Request $request)
    {
        return view('correspondence::externals.index_repositorio')->with("vigencia",  $request['vigencia']);
    }

    public function getCorrespondencePublics(Request $request)
    {
        $externals = ExternalReceived::select(["id", "consecutive", "issue"])
            ->where("state", 3)
            ->where("consecutive", 'like', '%' . $request['query'] . '%')
            ->get()->toArray();
        return $this->sendResponse($externals, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Erika Gonzalez. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */

    //Comienza Funciones del all

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Erika Johana Gonzalez C. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
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

        // Preparar filtros para el rol de consulta
        $filtrosRolConsulta = $this->prepararFiltros($request);

        if (!Auth::user()->hasRole('Consulta correspondencias')) {
            // Verificar el rol del usuario y manejar la lógica de acuerdo con su rol
            if (Auth::user()->hasRole('Correspondencia Enviada Admin')) {
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
        $externals = DB::table('correspondence_external')
            ->where('consecutive', 'like', '%' . $query . '%')
            ->where('state', 'like', 'Público')
            ->get();

        return $this->sendResponse($externals->toArray(), trans('data_obtained_successfully'));
    }

    function eliminar_patrones_total_respuestas($filtros) {
        // Limpiar la cadena
        $filtros = trim($filtros);

        // Patrón para eliminar 'total_respuestas LIKE %<algo>%'
        $patron = '/\btotal_respuestas\s+LIKE\s+\'?%[^%]+%\'?\s*/i';

        // Reemplazar todas las ocurrencias del patrón por una cadena vacía
        return preg_replace($patron, '', $filtros);
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

    // Maneja la lógica para los usuarios con el rol de 'Correspondencia Enviada Admin'
    private function consultasAdmin($tablero, $filtros, $cantidadPaginado)
    {
        if ($tablero) {
            return $this->tableroAdmin();
        }

        // Maneja la consulta de solicitudes internas, con los filtros aplicados
        $externalsQuery = External::with([
            'externalReceiveds',
            'externalType',
            'externalCopy',
            'externalHistory',
            'externalAnnotations',
            'anotacionesPendientes',
            'externalRead',
            'externalVersions',
            'serieClasificacionDocumental',
            'subserieClasificacionDocumental',
            'oficinaProductoraClasificacionDocumental',
            'externalShares',
            'citizens',
            'externalCopyShares',
            'users'
        ])->when($filtros, function ($queryFiltros) use ($filtros) {
            if ($filtros == "state LIKE '%COPIAS%'") {
                $queryFiltros->whereRelation('externalCopyShares', 'users_id', Auth::user()->id);
            }else {
                $queryFiltros->whereRaw($filtros);
            }
        })->orderBy('updated_at', 'DESC');

        $externals = $externalsQuery->latest("correspondence_external.updated_at")
            ->paginate($cantidadPaginado);


        $count_externals = $externals->total();
        $externals = $externals->toArray()["data"];
        // dd($externals);

        return $this->sendResponseAvanzado($externals, trans('data_obtained_successfully'), null, ["total_registros" => $count_externals]);
    }

    // Maneja la lógica cuando se pasa el parámetro 'tablero' para usuarios administradores
    private function tableroAdmin(){

        $estados_originales = ["Público", "Elaboración", "Revisión", "Aprobación", "Pendiente de firma", "Devuelto para modificaciones"];
        $estados_reemplazar = ["publico", "elaboracion", "revision", "aprobacion", "firmar_varios", "devuelto_para_modificaciones"];
        $responsableId = (int) Auth::id(); // Asegurar tipo consistente

        // Base de la consulta con relaciones necesarias
        $externalsQuery = External::with(['externalCopyShares'])
            ->select(
                'state',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('state');

        // Ejecutar consulta optimizada
        $externals = $externalsQuery->get();

        // Contar las copias compartidas con el usuario actual
        // $count_compartida = $externals->where('externalCopyShares.users_id', Auth::id())->count();
        $count_compartida = External::with('externalCopyShares')->whereRelation('externalCopyShares', 'users_id', Auth::user()->id)->where('state','Público')->count();

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
            'estados' => $state_totales,
            'total_externas' => $totalSuma,
            'total_compartidas' => $count_compartida,
        ]);
    }

    private function getCommonExternalsQuery($authUserId)
    {
        return External::where(function ($query) use ($authUserId) {
            $query->where("correspondence_external.from_id", $authUserId)
                ->orWhere("correspondence_external.users_id", $authUserId)
                ->orWhere("correspondence_external.elaborated_now", $authUserId)
                ->orWhere("correspondence_external.reviewd_now", $authUserId)
                ->orWhere("correspondence_external.approved_now", $authUserId)
                ->orWhereRaw("FIND_IN_SET(?, correspondence_external.elaborated)", [$authUserId])
                ->orWhereRaw("FIND_IN_SET(?, correspondence_external.reviewd)", [$authUserId])
                ->orWhereRaw("FIND_IN_SET(?, correspondence_external.approved)", [$authUserId])
                ->orWhereRelation('externalVersions.externalSigns', 'users_id', $authUserId)
                ->orWhere(function($subQuery) use ($authUserId) {
                    // Combinamos ambas condiciones en una sola subconsulta
                    $subQuery->whereHas('externalCopyShares', function($q) use ($authUserId) {
                        $q->where('users_id', $authUserId);
                    })->where('correspondence_external.state', 'Público');
                });
        });
    }

    private function tableroFuncionario()
    {
        $authUserId = Auth::user()->id;

        $externalsQuery = $this->getCommonExternalsQuery($authUserId)
            ->select(
                'state',
                DB::raw('COUNT(DISTINCT correspondence_external.id) as total')
            )
            ->groupBy('state');

        $externals = $externalsQuery->get();
        // $count_compartida = $externals->where('externalCopyShares.users_id', Auth::id())->count();
        $count_compartida = External::with('externalCopyShares')->whereRelation('externalCopyShares', 'users_id', Auth::user()->id)->where('state','Público')->count();

        $estados_originales = ["Público", "Elaboración", "Revisión", "Aprobación", "Pendiente de firma", "Devuelto para modificaciones"];
        $estados_reemplazar = ["publico", "elaboracion", "revision", "aprobacion", "firmar_varios", "devuelto_para_modificaciones"];

        $state_totales = $externals->pluck('total', 'state')
            ->mapWithKeys(function ($total, $state) use ($estados_originales, $estados_reemplazar) {
                return [str_replace($estados_originales, $estados_reemplazar, $state) => $total];
            });

        $totalSuma = $externals->sum('total');


        return $this->sendResponseAvanzado([], trans('data_obtained_successfully'), null, [
            'estados' => $state_totales,
            'total_externas' => $totalSuma,
            'total_compartidas' => $count_compartida
        ]);
    }

    private function consultasFuncionario($tablero, $filtros, $cantidadPaginado)
    {
        if ($tablero) {
            return $this->tableroFuncionario();
        }

        $authUserId = Auth::user()->id;


        $externalsQuery = $this->getCommonExternalsQuery($authUserId)
            ->select('correspondence_external.*')
            ->with([
                'externalReceiveds',
                'externalType',
                'externalCopy',
                'externalHistory',
                'externalAnnotations',
                'anotacionesPendientes',
                'externalRead',
                'externalVersions',
                'serieClasificacionDocumental',
                'subserieClasificacionDocumental',
                'oficinaProductoraClasificacionDocumental',
                'externalShares',
                'citizens',
                'externalCopyShares'
            ])
            ->when($filtros, function ($queryFiltros) use ($filtros) {
                if ($filtros == "state LIKE '%COPIAS%'") {
                    $queryFiltros->whereRelation('externalCopyShares', 'users_id', Auth::user()->id);
                }else {
                    $queryFiltros->whereRaw($filtros);
                }
            })->orderBy('updated_at', 'DESC');

        $externals = $externalsQuery->latest("correspondence_external.updated_at")
            ->groupBy("correspondence_external.id")
            ->paginate($cantidadPaginado);

        $count_externals = $externals->total();
        $externals = $externals->toArray()["data"];

        return $this->sendResponseAvanzado($externals, trans('data_obtained_successfully'), null, ["total_registros" => $count_externals]);
    }


    //Termina Funciones del all


    /**
     * Obtiene todos los elementos existentes del repositorio
     *
     * @author Erika Gonzalez. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function allRepositoryExternals(Request $request)
    {
        $input = $request->toArray();

        try {

            $userid = Auth::user()->user_joomla_id;

            $likedes1 = '%"id":"' . $userid . '"%';

            $likedes2 = '%"id":' . $userid . ',%';

            $likedes3 = '%"id":' . $userid . '}%';

            $table = "";

            $date = date("Y");

            //valida a que tabla realizar la consulta
            if ($input['vigencia'] != '' && $input['vigencia'] != $date && $input['vigencia'] != '2024') {
                $table = "externa_" . $input['vigencia'];
            } else {
                $table = "externa";
            }

            // * cp: currentPage
            // * pi: pageItems
            // * f: filtros
            $vigencyReceivedsCount = 0;
            // Valida si existen las variables del paginado y si esta filtrando
            if (isset($request["f"]) && $request["f"] != "" && isset($request["?cp"]) && isset($request["pi"])) {

                if (Auth::user()->hasRole('Correspondencia Enviada Admin')) {
                    // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)

                    $querys = DB::connection('joomla')->table($table)->where('consecutivo', 'LIKE', '%EE%')->whereRaw(base64_decode($request["f"]));

                    $vigencyReceivedsCount = $querys->count();
                    $vigencyReceiveds = $querys->skip((base64_decode($request["?cp"]) - 1) * base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy('cf_created', 'DESC')->get()->toArray();
                } else {
                    // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                    $querys = DB::connection('joomla')->table($table)->where('consecutivo', 'LIKE', '%EE%')->where(function ($query) use ($userid, $likedes1, $likedes2, $likedes3) {
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
                    })->whereRaw(base64_decode($request["f"]));

                    $vigencyReceivedsCount = $querys->count();
                    $vigencyReceiveds = $querys->skip((base64_decode($request["?cp"]) - 1) * base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy('cf_created', 'DESC')->get()->toArray();
                }
            } else if (isset($request["?cp"]) && isset($request["pi"])) {

                if (Auth::user()->hasRole('Correspondencia Enviada Admin')) {
                    // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                    $querys = DB::connection('joomla')->table($table)->where('consecutivo', 'LIKE', '%EE%');

                    $vigencyReceivedsCount = $querys->count();
                    $vigencyReceiveds = $querys->skip((base64_decode($request["?cp"]) - 1) * base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy('cf_created', 'DESC')->get()->toArray();
                } else {
                    // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                    $querys = DB::connection('joomla')->table($table)->where('consecutivo', 'LIKE', '%EE%')->where(function ($query) use ($userid, $likedes1, $likedes2, $likedes3) {
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
                    $vigencyReceiveds = $querys->skip((base64_decode($request["?cp"]) - 1) * base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy('cf_created', 'DESC')->get()->toArray();
                }
            } else {

                if (Auth::user()->hasRole('Correspondencia Enviada Admin')) {
                    // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                    $vigencyReceiveds = DB::connection('joomla')->Select("SELECT * FROM " .env('JOOMLA_DB_PREFIX'). $table . " WHERE consecutivo LIKE '%EE%'  order by cf_created DESC");
                } else {

                    $vigencyReceiveds = DB::connection('joomla')->Select("SELECT * FROM " .env('JOOMLA_DB_PREFIX'). $table . " WHERE consecutivo LIKE '%EE%' and (funcionario_des = '" . $userid . "' or funcionario_or = '" . $userid . "' or  copia like '" . $userid . "'  or  copia like '" . $userid . ",%'  or  copia like '%," . $userid . ",%'   or  copia like '%," . $userid . "'   or destinatarios like '" . $likedes1 . "' or destinatarios like '" . $likedes2 . "' or destinatarios like '" . $likedes3 . "' or compartida like '" . $userid . "' or compartida like '" . $userid . ",%' or compartida like '%/" . $userid . "/%' or compartida like '%," . $userid . ",%'  or compartida like '%," . $userid . "'  or elaboradopor=" . $userid . " or revisadopor=" . $userid . " or aprobadopor=" . $userid . ") order by cf_created DESC");
                }

                $vigencyReceivedsCount = count($vigencyReceiveds);
            }

            return $this->sendResponseAvanzado($vigencyReceiveds, trans('data_obtained_successfully'), null, ["total_registros" => $vigencyReceivedsCount]);
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendErrorData("No existe la vigencia seleccionada. " . config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendErrorData("No existe la vigencia seleccionada. " . config('constants.support_message'), 'info');
        }
    }


    //Ruta para ejercutar funcion que completa datos en las tablas de joomla
    public function scriptData()
    {
        // $vigencyReceiveds = DB::connection('joomla')->Select("SELECT * FROM rghb6_pqr");

        // foreach ($vigencyReceiveds as $value) {
        //     $inventario = DB::connection('joomla')->table('rghb6_cd_doc')->where("documento",$value->id)->first();

        //     if ($inventario != '') {

        //         $depencia = DB::connection('joomla')->table('rghb6_intranet_dependencia')->where("id",$inventario->codigo_oficina)->first();

        //         DB::connection('joomla')->Select("UPDATE rghb6_pqr SET oficina_productora = '".$inventario->nombre_dependencia. ' - ' . $depencia->codigo."', serie_subserie = '".$inventario->nombre_serie. ' - ' .$inventario->code_serie. "' WHERE id = '".$value->id."'");
        //     }

        // }

        $vigencyReceiveds = DB::connection('joomla')->Select("SELECT * FROM rghb6_pqr");

        foreach ($vigencyReceiveds as $value) {
            $inventario = DB::connection('joomla')->table('externa')->where("pqr", $value->id)->first();

            if ($inventario != '') {

                DB::connection('joomla')->Select("UPDATE rghb6_pqr SET id_correspondence = '" . $inventario->consecutivo . "' WHERE id = '" . $value->id . "'");
            }
        }

        return 'Listo';
    }

    public function getAnnotation($request)
    {

        $data = explode('-', $request);

        $annotations = DB::connection('joomla')->table($data[1])->where($data[2], $data[0])->get();

        return $this->sendResponse($annotations, trans('data_obtained_successfully'));
    }

    /**
     * Muestra el detalle completo del elemento existente
     *
     * @author Erika Johana Gonzalez - Abr. 16 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function show($id)
    {
        $externals = $this->externalRepository->find($id);
        if (empty($externals)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //relaciones
        $externals->externalRecipients;
        $externals->externalHistory;

        return $this->sendResponse($externals->toArray(), trans('data_obtained_successfully'));
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
        $externals = $this->externalRepository->find($id);


        if (empty($externals)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Relaciones
        $externals->citizens;
        $externals->externalType;
        $externals->externalCopy;
        $externals->externalCopyShares;
        $externals->externalHistory;
        $externals->externalAnnotations;
        $externals->externalRead;
        $externals->externalShares;
        $externals->externalVersions;
        $externals->serieClasificacionDocumental;
        $externals->subserieClasificacionDocumental;
        $externals->oficinaProductoraClasificacionDocumental;

        return $this->sendResponse($externals->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene datos completo del elemento existente
     *
     * @author Erika Johana Gonzalez - Abr. 16 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function getDataEdit($id)
    {

        $externals = $this->externalRepository->find($id);
        if (empty($externals)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //relaciones
        $externals->externalRecipients;
        $externals->externalVersions;
        $externals->externalCopy;
        $externals->externalCopyShares;
        $externals->citizens;
        $externals->externalShares;
        return $this->sendResponse($externals->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene datos completo del elemento existente
     *
     * @author Erika Johana Gonzalez - Abr. 16 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function PQRCreate($input, $idExternal)
    {
        PQR::where('id', $input["pqr_id"])->update([
            'fecha_fin' =>  date('Y-m-d H:i:s'),
            'estado' => "Finalizado",
            'adjunto' => $input["document_pdf"] ?? '',
            'correspondence_external_id' => $idExternal,
            'respuesta' => "El PQR fue finalizado desde la correspondencia enviada con el consecutivo : " . $input["consecutive"],
            'tipo_finalizacion' => $input["tipo_finalizacion"] ?? 'Respuesta al ciudadano',
            'no_oficio_respuesta' =>  $input["consecutive"]
        ]);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Gonzalez. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateExternalRequest $request
     *
     * @return Response
     */
    public function store(CreateExternalRequest $request)
    {
        $input = $request->all();
        $input["state"] = "Público";

        if (!isset($input["annexes_description"])) {
            $input["annexes_description"] = "No aplica";
        }

        if ($input['require_answer'] == 'Si') {

            if (empty($input["pqr_id"])) {
                return $this->sendSuccess('<strong>PQRS no seleccionado.</strong><br>Por favor, ingrese, seleccione el PQRS que desea finalizar de la lista.', 'warning');
            }
            $pqrExiste = PQR::where('pqr_id', $request["pqr_consecutive"])->count();
            if ($pqrExiste == 0) {
                return $this->sendSuccess('<strong>El PQRS ingresado no existe.</strong>' . '<br>' . "Por favor seleccione un PQRS valido.", 'warning');
            }
        }

        if ($input['have_assigned_correspondence_received'] == 'Si') {

            if (empty($input["external_received_id"])) {
                return $this->sendSuccess('<strong>Correspondencia no seleccionado.</strong><br>Por favor, ingrese, seleccione la correspondencia que desea finalizar de la lista.', 'warning');
            }
            $externalReceived = ExternalReceived::where('id', $request["external_received_id"])->count();
            if ($externalReceived == 0) {
                return $this->sendSuccess('<strong>La correspondencia ingresada no existe.</strong>' . '<br>' . "Por favor seleccione una correspondencia valida.", 'warning');
            }
        }

        // Formatea separado por coma los enlaces de los anexos de la correspondencia
        $input['annexes_digital'] = isset($input["annexes_digital"]) ? implode(",", $input["annexes_digital"]) : null;
        // Valida que se halla ingresado un ciudadno, sea existente o personalizado
        if (empty($input["citizens"])) {
            return $this->sendSuccess('<strong>Por favor agregue un ciudadano a la lista.</strong>' . '<br>' . "Puede autocompletar un ciudadano o asignar uno personalizado, si es anonimo Puede ingresar en el campo nombre del ciudadano Ciudadano anónimo", 'warning');
        }

        if (!isset($input["from_id"])) {
            return $this->sendSuccess('<strong>El Funcionario es requerido.</strong>' . '<br>' . "Puede autocompletar el funcionario ingresando su nombre.", 'warning');
        }

        $userLogin = Auth::user();

        $input["users_id"] = $userLogin->id;
        $input["users_name"] = $userLogin->fullname;

        $input["year"] = date("Y");
        $input["origen"] = 'FISICO';
        if ($input["origen"] == 'FISICO') {
            // Valida si no seleccionó ningún adjunto
            $input['document_pdf'] = isset($input["document_pdf"]) ? implode(",", $input["document_pdf"]) : null;
        }

        /**Consulta la informacion del usuario remitente */
        // $informationUser = User::select("name","id_dependencia")->where('id', $input["from_id"])->first();

        $informationUser = User::where('id', $input["from_id"])->first();

        $input["from"] = $informationUser->fullname;

        $emailFromId =  $informationUser->email;

        /**Consulta la informacion de departamento y ciudad */
        // $input["city_name"] = City::select("name")->where("id", $input["city_id"])->pluck("name")->first();
        // $input["department_name"] = State::select("name")->where("id", $input["department_id"])->pluck("name")->first();

        //datos de la dependencia del usuario remitente
        $infoDependencia = Dependency::where('id', $informationUser->id_dependencia)->first();
        $input["dependencias_id"] = $infoDependencia["id"];
        $input["dependency_from"] = $infoDependencia["nombre"];

        //Consulta las variables para calcular el consecutio.
        $formatConsecutive = Variables::where('name', 'var_external_consecutive')->pluck('value')->first();
        $formatConsecutivePrefix = Variables::where('name', 'var_external_consecutive_prefix')->pluck('value')->first();

        // Verifica si $formatConsecutive o $formatConsecutivePrefix no tienen un valor asignado
        if (!$formatConsecutive || !$formatConsecutivePrefix) {
            // Asigna el valor predeterminado "Y-DP-CO" a $formatConsecutive si está vacío
            $formatConsecutive = "Y-DP-CO";
            // Asigna el valor predeterminado "Y-DP-" a $formatConsecutivePrefix si está vacío
            $formatConsecutivePrefix = "Y-DP-";
        }

        //DP
        $DP = $infoDependencia["codigo"];
        $siglas = $infoDependencia["codigo_oficina_productora"] ?? '';

        //PL
        $typeExternal = ExternalTypes::where('id', $input["type"])->first();

        $PL = $typeExternal->prefix;
        $input["type_document"] = $typeExternal->name;;

        //Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order
        $dataConsecutive = UtilController::getNextConsecutive('External', $formatConsecutive, $formatConsecutivePrefix, $DP, $PL,$siglas);

        $input["consecutive"] = $dataConsecutive['consecutive'];
        $input["consecutive_order"] = $dataConsecutive['consecutive_order'];

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            // $input["citizen_name"] = array_map(fn($item) => json_decode($item)->citizen_name, $input["citizens"]);
            //logica ciudadanos aqui
            // $input["citizen_name"] = implode(", ",array_map(fn($item) => json_decode($item)->citizen_name, $input["citizens"]));
            $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

            $input["validation_code"] = substr(str_shuffle($caracteres_permitidos), 0, 8);
            $input["validation_code_encode"] = JwtController::generateToken($input["validation_code"]);

            $external = $this->externalRepository->create($input);

            // Asigna los ciudadanos de datos de destino

            $datosCiudadanos = $this->logicaCiudadanos($input["citizens"], $external["id"]);
            $mails = $datosCiudadanos["mails"];
            $input["citizen_name"] = $datosCiudadanos["datos_ciudadano"];


            if (!empty($input['external_copy'])) {
                $datosCopias = $this->logicaCopias($input["external_copy"], $external);
                $input["copies"] = $datosCopias["copies"];
            }
            $external = $this->externalRepository->update($input, $external["id"]);

            // Efectua los cambios realizados
            DB::commit();

            $input['correspondence_external_id'] = $external->id;
            $input['observation_history'] = "Creación de correspondencia";
            $history = $input;
            $history['user_for_last_update'] = User::select('name')->where('id', $input['from_id'])->first()->name;
            // Crea un nuevo registro de historial
            $this->ExternalHistoryRepository->create($history);

            //Obtiene el historial
            $external->externalHistory;
            $external->externalType;
            $external->serieClasificacionDocumental;
            $external->subserieClasificacionDocumental;
            $external->oficinaProductoraClasificacionDocumental;
            $external->externalCopy;
            $external->externalCopyShares;
            $external->citizens;
            $external->externalAnnotations;

            //fin pqrs
            if ($input['require_answer'] == 'Si' && $input["state"] == 'Público') {
                //update pqr
                $this->PQRCreate($input, $external->id);
                $informacionPqr = PQR::where('id', $input["pqr_id"])->first()->toArray();
                $informacionPqr["pqr_pqr_id"] = $input["pqr_id"];
                $informacionPqr["action"] = "Finalización de registro";

                //Consulta las variables para implementar encuesta de satisfacción
                $variable = Variables::where('name', 'enviar_encuesta_satisfaccion_pqr')->pluck('value')->first();
                $encuesta_satisfaccion = (!empty($variable) && $variable == 'Si')
                    ?  "<br><br><p><small>Para nosotros es muy importante su opinión, por eso con el fin de propiciar el buen servicio al cliente y mejoramiento continuo en nuestra entidad respetuosamente relacionamos el enlace mediante el cual usted podrá acceder a la encuesta de satisfacción correspondiente a su PQRSDF. <a href='" . url('pqrs/survey-satisfaction-pqrs') . "?cHFyX2lk=" . JwtController::generateToken($informacionPqr["id"])  . "'>Enlace para contestar encuesta de satisfacción</a></small></p>"
                    : "";

                // Se crea el historial del PQR
                PQRHistorial::create($informacionPqr);

                $input['encuesta'] = $encuesta_satisfaccion ;
            }


            if (!empty($input["citizens"]) && $input["state"] == 'Público') {

                // Elimina los ciudadanos para poner los nuevos
                // $mails =  array_map(fn($item) => !empty(json_decode($item)->citizen_email) ? json_decode($item)->citizen_email : "", $input["citizens"]);
                $mails[] = $emailFromId;
                $input['id']=$external->id;
                $input['citizen_name'] = explode('<br>',$input['citizen_name'])[0];
                // dd($mails);
                $asunto = json_decode('{"subject": "Nueva correspondencia enviada – Radicado: ' . $input["consecutive"] . '"}');
                SendNotificationController::SendNotification('correspondence::externals.emails.notificacion', $asunto, $input, $mails, 'Correspondencia externa');
            }

            return $this->sendResponse($external->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Gonzalez. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateExternalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExternalRequest $request)
    {
        $input = $request->all();
        $datosCiudadano = array();

        /** @var External $external */
        $external = $this->externalRepository->find($id);


        if (empty($external)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        if($external["state"] == 'Público' && $input["origen"] != 'FISICO'){
            return $this->sendSuccess('<strong>Esta correspondencia ya ha sido publicada, por lo tanto no se pueden realizar ediciones.</strong><br>Por favor, cierre este modal.', 'warning');
        }


        if ($input['require_answer'] == 'Si') {

            if (empty($input["pqr_id"])) {
                return $this->sendSuccess('<strong>PQRS no seleccionado.</strong><br>Por favor, ingrese, seleccione el PQRS que desea finalizar de la lista.', 'warning');
            }
            $pqrExiste = PQR::where('pqr_id', $request["pqr_consecutive"])->count();
            if ($pqrExiste == 0) {
                return $this->sendSuccess('<strong>El PQRS ingresado no existe.</strong>' . '<br>' . "Por favor seleecione un PQRS valido.", 'warning');
            }
        }
        // Valida que se halla ingresado un ciudadno, sea existente o personalizado
        if (empty($input["citizens"])) {
            return $this->sendSuccess('<strong>Por favor agregue un ciudadano a la lista.</strong>' . '<br>' . "Puede autocompletar un ciudadano o asignar uno personalizado, si es anonimo Puede ingresar en el campo nombre del ciudadano Ciudadano anónimo", 'warning');
        }

        if (!isset($input["from_id"])) {
            return $this->sendSuccess('<strong>El Funcionario es requerido.</strong>' . '<br>' . "Puede autocompletar el funcionario ingresando su nombre.", 'warning');
        }

        if (!isset($input["annexes_description"])) {
            $input["annexes_description"] = "No aplica";
        }

        // Formatea separado por coma los enlaces de los anexos de la correspondencia
        $input['annexes_digital'] = isset($input["annexes_digital"]) ? implode(",", $input["annexes_digital"]) : null;

        $userLogin = Auth::user();
        $input["users_id"] = $userLogin->id;
        $input["users_name"] = $userLogin->fullname;

        //PL
        $typeExternal = ExternalTypes::where('id', $input["type"])->first();
        $PL = $typeExternal->prefix;
        $input["type_document"] = $typeExternal->name;

        // Inicia la transaccion
        DB::beginTransaction();

        try {

            if (!empty($input["citizens"])) {
                // Elimina los ciudadanos para poner los nuevos
                ExternalCitizen::where("correspondence_external_id", $external["id"])->delete();
                $datosCiudadanos = $this->logicaCiudadanos($input["citizens"], $external["id"]);
                $mails = $datosCiudadanos["mails"];
                $input["citizen_name"] = $datosCiudadanos["datos_ciudadano"];

            }


            // $external = $this->externalRepository->update($input,$external["id"]);


            // $input["citizen_name"] = implode(",",array_map(fn($item) => json_decode($item)->citizen_name, $input["citizens"]));

            if ($input["origen"] == 'FISICO') {
                // Valida si no seleccionó ningún adjunto
                $input['document_pdf'] = isset($input["document_pdf"]) ? implode(",", $input["document_pdf"]) : null;

                $informationUser = User::where('id', $input["from_id"])->first();
                $input["from"] = $informationUser->fullname;

                $infoDependencia = Dependency::where('id', $informationUser["id_dependencia"])->first();

                $input["dependencias_id"] = $infoDependencia["id"];
                $input["dependency_from"] = $infoDependencia["nombre"];
                //DP
                $DP = $infoDependencia["codigo"];

                $emailFromId =  $informationUser->email;
            } else {

                $input["from"] = $userLogin->fullname;
                $input["from_id"] = $userLogin->id;
                $input["dependencias_id"] = $userLogin->dependencies->id;
                $input["dependency_from"] = $userLogin->dependencies->nombre;
                //DP
                $DP = $userLogin->dependencies->codigo;
                $siglas = $userLogin->dependencies->codigo_oficina_productora ?? '';

                $emailFromId =  $userLogin->email;
                //digital y por estados
                switch ($input["tipo"]) {
                    case 'Publicación':
                        // Valida si el usuario posee una firma para la publicación del documento
                        if (!$userLogin->url_digital_signature) {
                            // Retorna mensaje de error informando que el usuario no puede publicar y firmar el documento
                            return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que edite su perfil y suba una firma válida.', 'info');
                        }

                        if ($userLogin->autorizado_firmar != 1) {
                            // Retorna mensaje de error informando que el usuario no puede publicar y firmar el documento
                            return $this->sendSuccess(
                                '<strong>¡Atención!</strong><br /><br />' .
                                    'Actualmente no está autorizado para firmar documentos. Por favor, contacte al administrador de Intraweb para obtener los permisos necesarios. ' .
                                    'Mientras tanto, puede enviar los documentos para elaboración, revisión y aprobación, pero no podrá publicarlos.',
                                'info'
                            );
                        }
                        //Consulta las variables para calcular el consecutio.
                        $formatConsecutive = Variables::where('name', 'var_external_consecutive')->pluck('value')->first();
                        $formatConsecutivePrefix = Variables::where('name', 'var_external_consecutive_prefix')->pluck('value')->first();


                        //Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order
                        $dataConsecutive = UtilController::getNextConsecutive('External', $formatConsecutive, $formatConsecutivePrefix, $DP, $PL,$siglas);
                        $input["consecutive"] = $dataConsecutive['consecutive'];
                        $input["consecutive_order"] = $dataConsecutive['consecutive_order'];
                        // Actualiza el registro
                        $external = $this->externalRepository->update(['consecutive'=>$input["consecutive"], 'consecutive_order'=>$input["consecutive_order"]], $id);

                        $dependenciaInformacion = Dependency::where('id', $input["dependencias_id"])->first();

                        //estaod publico
                        $input["state"] = "Público";
                        $variables = $typeExternal->variables;

                        $information["#consecutivo"] = $input["consecutive"];
                        $information["#titulo"] = $input["title"];
                        $information["#remitente"] = $input["from"];
                        $information["#dependencia_remitente"] = $input["dependency_from"];
                        $information["#ciudadano_documento"] = '';
                        $information["#ciudadano_email"] = '';
                        $information["#ciudadano"] = str_replace("<br>", "\n", $input["citizen_name"]);
                        $information["#copias"] = str_replace("<br>", ", ", $input["copies"] ?? "No aplica");
                        $information["#contenido"] = $input["content"] ?? "";
                        $information["#anexos"] = $input["annexes_description"] ?? "No aplica";
                        $information["#tipo_documento"] = $input["type_document"];

                        $elaborated = $input["elaborated_names"] ?? null;
                        $reviewed   = $input["reviewd_names"] ?? null;
                        $approved   = $input["approved_names"] ?? null;

                        if (!empty($input['external_copy'])) {
                            $datosCopias = $this->logicaCopias($input["external_copy"], $input);
                            $input["copies"] = $datosCopias["copies"];
                        } else {
                            //borra todo para volver a insertarlo
                            ExternalCopyShare::where('correspondence_external_id', $id)->where('type', "Copia")->delete();
                            $input["copies"] = "";
                        }

                        $copies     = $input["copies"] ?? null;

                        $information["#elaborado"] = UtilController::formatNames($elaborated);
                        $information["#revisado"]  = UtilController::formatNames($reviewed ?? $approved ?? $information["#remitente"] ?? $elaborated);
                        $information["#aprobado"]  = UtilController::formatNames($approved ?? $information["#revisado"]);
                        $information["#proyecto"]  = UtilController::formatNames($elaborated);
                        $information["#copias"]    = UtilController::formatNames($copies);

                        $information["#respuesta_pqr"] = $input["pqr_consecutive"] ?? "No aplica";
                        $information["#codigo_dependencia"] = $DP;
                        $information["#direccion"] = $dependenciaInformacion["direccion"];
                        $information["#dep_piso"] = $dependenciaInformacion["piso"];
                        $information["#codigo_postal"] = $dependenciaInformacion["codigo_postal"];
                        $information["#telefono"] = $dependenciaInformacion["telefono"];
                        $information["#dep_ext"] = $dependenciaInformacion["extension"];
                        $information["#dep_correo"] = $dependenciaInformacion["correo"];


                        $fullHash = hash('sha256', $userLogin->id . $input["consecutive"]);
                        $hash = "ID firma: " . substr(base64_encode(hex2bin($fullHash)), 0, 17);
                        $input["hash_firma"] = $hash;
                        $signUnique = new \stdClass();
                        $signUnique->name = $this->processFuncionarioText($userLogin->fullname);
                        $signUnique->url_digital_signature = $userLogin->url_digital_signature;
                        $signUnique->escala_firma = $userLogin->escala_firma;
                        $signUnique->hash = $hash;

                        $searchString = "#logo";
                        if (strpos($variables, $searchString) !== false) {
                            if (!file_exists(storage_path("app/public/".$dependenciaInformacion["logo"]))) {
                                return $this->sendSuccess('<strong>¡Atención! Falta el Logo</strong><br /><br />El logo requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/correspondence/internal-types" target="_blank">edite el tipo de correspondencia</a> y suba un logo válido.', 'info');
                            }
                        }


                        $signUnique2 = new \stdClass();
                        $signUnique2->users = $signUnique;

                        $information["#firmas"] = [$signUnique2];
                        $information["#logo"] = $dependenciaInformacion["logo"] ?? '';

                        setlocale(LC_ALL, "es_ES");
                        $information["#fecha"] = strftime("%d %B del %Y");
                        $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        // Genera un código de verificación único para cada documento
                        $input["validation_code"] = substr(str_shuffle($caracteres_permitidos), 0, 8);
                        $input["validation_code_encode"] = JwtController::generateToken($input["validation_code"]);
                        $information["#codigo_validacion"] = $input["validation_code"];
                        $id_google = explode("/", $input["template"]);
                        $id_google = end($id_google);

                        $google = new GoogleController();
                        $returnGoogle = $google->editFileDoc("Enviada", $id, $id_google, explode(",", $variables), $information, 0);

                        if ($returnGoogle['type_message'] == 'info') {
                            // Devuelve los cambios realizados
                            DB::rollback();
                            // Retorna mensaje de error de base de datos
                            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $returnGoogle['message'], 'info');
                        }

                        $documento_almacenado = $google->saveFileGoogleDrive(JwtController::decodeToken($returnGoogle['data'])->id, "pdf", $input["consecutive"], 'container/external_'.date('Y'));
                        // Si la variable 'documento_almacenado' tiene valor en la propiedad 'type_message' y este es igual a 'warning', hubo un error al guardar el documento
                        if(!empty($documento_almacenado["type_message"]) && $documento_almacenado["type_message"] == "warning") {
                            // Devuelve los cambios realizados
                            DB::rollback();
                            // Retorna mensaje de advertencia al usuario
                            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $documento_almacenado["message"], 'warning');
                        }

                        $input["document_pdf"] = $documento_almacenado;
                        // Valida el 'TIPO_ALMACENAMIENTO_GENERAL', si es AWS
                        if(env("TIPO_ALMACENAMIENTO_GENERAL") == "AWS") {
                            // Crea una instancia de tipo Request para enviar a la función 'readObjectAWS'
                            $requestObjectAWS = new Request();
                            // Ruta del documento
                            $requestObjectAWS["path"] = $input["document_pdf"];
                            // Tipo de url de descarga 'temporal_aws', quiere decir que se obtendrá el archivo (URL) directamente desde S3
                            $requestObjectAWS["tipoURL"] = "temporal_aws";
                            // Se hace la solicitud a la función 'readObjectAWS' para obtener la URL prefirmada del archivo
                            $archivo_aws = $this->readObjectAWS($requestObjectAWS);
                            // Se decodifica la URL
                            $archivo = JwtController::decodeToken($archivo_aws['data']);
                            // Se calcula el hash del archivo directamente desde la URL prefirmada de S3 de AWS
                            $input["hash_document_pdf"] = hash_file('sha256', $archivo);
                        } else {
                            // Genera una cadena hash usando el archivo local del campo document_pdf
                            $input["hash_document_pdf"] = hash_file('sha256', 'storage/' . $input["document_pdf"]);
                        }
                        // Elimina los permisos del documento
                        $google->removePermissions($id_google);

                        break;

                    case 'Elaboración':
                        $input["state"] = "Elaboración";
                        $input["elaborated"] = isset($input["elaborated"]) ? $input["elaborated"] . "," . $input["funcionario_revision"] : $input["funcionario_revision"];
                        $input["elaborated_names"] = isset($input["elaborated_names"]) ? $input["elaborated_names"] . ", " . $input["user_for_last_update"] : $input["user_for_last_update"];
                        $input["elaborated_now"] = $input["funcionario_revision"];
                        $input["reviewd_now"] = "";
                        $input["approved_now"] = "";
                        break;

                    case 'Revisión':
                        $input["state"] = "Revisión";
                        $input["reviewd"] = isset($input["reviewd"]) ? $input["reviewd"] . "," . $input["funcionario_revision"] : $input["funcionario_revision"];
                        $input["reviewd_names"] = isset($input["reviewd_names"]) ? $input["reviewd_names"] . ", " . $input["user_for_last_update"] : $input["user_for_last_update"];
                        $input["reviewd_now"] = $input["funcionario_revision"];
                        $input["elaborated_now"] = "";
                        $input["approved_now"] = "";
                        break;

                    case 'Aprobación':
                        $input["state"] = "Aprobación";
                        $input["approved"] = isset($input["approved"]) ? $input["approved"] . "," . $input["funcionario_revision"] : $input["funcionario_revision"];
                        $input["approved_names"] = isset($input["approved_names"]) ? $input["approved_names"] . ", " . $input["user_for_last_update"] : $input["user_for_last_update"];
                        $input["approved_now"] = $input["funcionario_revision"];
                        $input["elaborated_now"] = "";
                        $input["reviewd_now"] = "";
                        break;


                    case 'Firma Conjunta':
                        $input["state"] = "Pendiente de firma";
                        $input["approved_now"] = "";
                        $input["elaborated_now"] = "";
                        $input["reviewd_now"] = "";

                        $numberVersion = ExternalVersions::where('correspondence_external_id', $id)->max("number_version") + 1;
                        $variables = $typeExternal->variables;

                        $information["#firmas"] = "Espacio para firmas";

                        $usuarios_remitentes = [];
                        // Recorre los usuarios que firman el documento
                        foreach ($input['users_sign_text'] as $recipent) {
                            // Array de usuarios firmantes
                            $recipentArray = json_decode($recipent, true);
                            // Se obtiene el nombre de usuario y se asigna al arreglo de remitentes
                            $usuarios_remitentes[] = str_replace("Usuario ", "", $recipentArray["fullname"]);
                        }
                        $input["user_for_last_update"] = implode(" - ", $usuarios_remitentes);

                        $dependenciaInformacion = Dependency::where('id', $input["dependencias_id"])->first();

                        $information["#consecutivo"] = $input["consecutive"];

                        if (!empty($input['external_copy'])) {
                            $datosCopias = $this->logicaCopias($input["external_copy"], $input);
                            $input["copies"] = $datosCopias["copies"];
                        } else {
                            //borra todo para volver a insertarlo
                            ExternalCopyShare::where('correspondence_external_id', $id)->where('type', "Copia")->delete();
                            $input["copies"] = "";
                        }

                        $information["#titulo"] = $input["title"];
                        $information["#remitente"] = implode(" - ", $usuarios_remitentes);
                        $information["#dependencia_remitente"] = $input["dependency_from"];
                        $information["#ciudadano_documento"] = '';
                        $information["#ciudadano_email"] = '';
                        $information["#ciudadano"] = str_replace("<br>", "\n", $input["citizen_name"]);
                        $information["#copias"] = str_replace("<br>", ", ", $input["copies"] ?? "No aplica");
                        $information["#contenido"] = $input["content"] ?? "";
                        $information["#anexos"] = $input["annexes_description"] ?? "No aplica";
                        $information["#tipo_documento"] = $input["type_document"];


                        $elaborated = $input["elaborated_names"] ?? null;
                        $reviewed   = $input["reviewd_names"] ?? null;
                        $approved   = $input["approved_names"] ?? null;
                        $copies     = $input["copies"] ?? null;

                        $information["#elaborado"] = UtilController::formatNames($elaborated);
                        $information["#revisado"]  = UtilController::formatNames($reviewed ?? $approved ?? $information["#remitente"] ?? $elaborated);
                        $information["#aprobado"]  = UtilController::formatNames($approved ?? $information["#revisado"]);
                        $information["#proyecto"]  = UtilController::formatNames($elaborated);
                        $information["#copias"]    = UtilController::formatNames($copies);


                        $information["#respuesta_pqr"] = $input["pqr_consecutive"] ?? "No aplica";
                        $information["#codigo_dependencia"] = $DP;
                        $information["#direccion"] = $dependenciaInformacion["direccion"];
                        $information["#dep_piso"] = $dependenciaInformacion["piso"];
                        $information["#codigo_postal"] = $dependenciaInformacion["codigo_postal"];
                        $information["#telefono"] = $dependenciaInformacion["telefono"];
                        $information["#dep_ext"] = $dependenciaInformacion["extension"];
                        $information["#dep_correo"] = $dependenciaInformacion["correo"];
                        $information["#logo"] = $dependenciaInformacion["logo"];

                        setlocale(LC_ALL, "es_ES");
                        $information["#fecha"] = strftime("%d de %B del %Y");

                        $information["#codigo_validacion"] = $input["validation_code"] ?? "No aplica";

                        $id_google = explode("/", $input["template"]);
                        $id_google = end($id_google);

                        $google = new GoogleController();
                        $returnGoogle = $google->editFileDoc("Enviada", $id, $id_google, explode(",", $variables), $information, 0, true);

                        if ($returnGoogle['type_message'] == 'info') {
                            // Devuelve los cambios realizados
                            DB::rollback();
                            // Retorna mensaje de error de base de datos
                            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $returnGoogle['message'], 'info');
                        }

                        $documento_almacenado = $google->saveFileGoogleDrive(JwtController::decodeToken($returnGoogle['data'])->id, "pdf", $input["consecutive"], 'container/external_'.date('Y'), true);
                        // Si la variable 'documento_almacenado' tiene valor en la propiedad 'type_message' y este es igual a 'warning', hubo un error al guardar el documento
                        if(!empty($documento_almacenado["type_message"]) && $documento_almacenado["type_message"] == "warning") {
                            // Devuelve los cambios realizados
                            DB::rollback();
                            // Retorna mensaje de advertencia al usuario
                            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $documento_almacenado["message"], 'warning');
                        }

                        $inputVersion["document_pdf_temp"] = $documento_almacenado;
                        $inputVersion["number_version"] = $numberVersion;
                        $inputVersion["users_name"] = $userLogin->fullname;
                        $inputVersion["state"] = "Pendiente";
                        $inputVersion["observation"] = $input["observation"];
                        $inputVersion["correspondence_external_id"] = $id;
                        $inputVersion["users_id"] = $userLogin->id;
                        $idVersion = ExternalVersions::create($inputVersion);
                        $emailArray = [];

                        // Valida si viene usuarios para asignar
                        if (!empty($input['users_sign_text'])) {
                            //recorre los destinatarios
                            foreach ($input['users_sign_text'] as $recipent) {
                                //array de destinatarios
                                $recipentArray = json_decode($recipent, true);
                                $emailArray[] = User::where('id', $recipentArray["users_id"])->first()->email;
                                $inputSign["users_name"] = $recipentArray["fullname"];
                                $inputSign["state"] = "Pendiente de firma";
                                $inputSign["observation"] = "";
                                $inputSign["correspondence_external_id"] = $id;
                                $inputSign["users_id"] = $recipentArray["users_id"];
                                $inputSign["correspondence_external_versions_id"] = $idVersion->id;

                                ExternalSigns::create($inputSign);
                            }
                        }

                        break;

                    default:
                        # code...
                        break;
                }
            }


            // Actualiza el registro
            $external = $this->externalRepository->update($input, $id);

            $externalNotificacion = $external;

            $input['correspondence_external_id'] = $external->id;
            $input['observation_history'] = "Actualización de correspondencia";
            $history =   $input;
            // $history['user_for_last_update'] = User :: select('name')->where('id',$input['from_id'])->first()->name;

            // Crea un nuevo registro de historial
            $this->ExternalHistoryRepository->create($history);

            //Obtiene el historial
            $external->externalHistory;
            $external->externalType;
            $external->externalCopy;
            $external->externalCopyShares;
            $external->serieClasificacionDocumental;
            $external->subserieClasificacionDocumental;
            $external->oficinaProductoraClasificacionDocumental;
            $external->externalVersions;
            $external->citizens;

            //fin pqrs
            if ($input['require_answer'] == 'Si' && $external->state == 'Público') {
                //update pqr
                $this->PQRCreate($input, $external->id);
                $informacionPqr = PQR::where('id', $input["pqr_id"])->first()->toArray();
                $informacionPqr["pqr_pqr_id"] = $input["pqr_id"];
                $informacionPqr["action"] = "Finalización de registro";

                //Consulta las variables para implementar encuesta de satisfacción
                $variable = Variables::where('name', 'enviar_encuesta_satisfaccion_pqr')->pluck('value')->first();
                $encuesta_satisfaccion = (!empty($variable) && $variable == 'Si')
                    ?  "<br><br><p><small>Para nosotros es muy importante su opinión, por eso con el fin de propiciar el buen servicio al cliente y mejoramiento continuo en nuestra entidad respetuosamente relacionamos el enlace mediante el cual usted podrá acceder a la encuesta de satisfacción correspondiente a su PQRSDF. <a href='" . url('pqrs/survey-satisfaction-pqrs') . "?cHFyX2lk=" . JwtController::generateToken($informacionPqr["id"])  . "'>Enlace para contestar encuesta de satisfacción</a></small></p>"
                    : "";

                // Se crea el historial del PQR
                PQRHistorial::create($informacionPqr);

                $input['encuesta'] = $encuesta_satisfaccion ;
            }
            // Efectua los cambios realizados
            DB::commit();

            if (!empty($input["citizens"]) && $external->state == 'Público') {
                $input = $externalNotificacion->toArray();
                // Elimina los ciudadanos para poner los nuevos
                // $mails =  array_map(fn($item) => !empty(json_decode($item)->citizen_email) ? json_decode($item)->citizen_email : "", $input["citizens"]);
                $mails[] = $emailFromId;
                $input['citizen_name'] = explode('<br>',$input['citizen_name'])[0];
                // dd($mails);
                $asunto = json_decode('{"subject": "Actualización: Correspondencia enviada – Radicado:' . $input["consecutive"] . '"}');
                SendNotificationController::SendNotification('correspondence::externals.emails.notificacion', $asunto, $input, $mails, 'Correspondencia externa');
            }


            $notificacion = $externalNotificacion;
            $external_id_encrypted = base64_encode($external["id"]);
            $notificacion->link =  '/correspondence/externals?qder='.$external_id_encrypted;
            if ($external->state == 'Elaboración') {

                $user_aproved = User::where('id', $external->elaborated_now)->first();
                $notificacion->recipient = $user_aproved->name;
                $notificacion->mail = $user_aproved->email;
                $notificacion->mensaje = "Le informamos que <strong>" . $input["users_name"] . "</strong> ha enviado a elaboración la correspondencia externa titulada: <strong>" . $notificacion->title . " " . $external->consecutive . "</strong>.<br>Con el siguiente comentario: \"<em>" . $external->observation . "</em>\".";
                $asunto = json_decode('{"subject": "Actualización: Correspondencia Externa ' . $input["consecutive"] . '"}');
                SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario', $asunto, $notificacion->toArray(), $user_aproved->email, 'Correspondencia externa');
            } else if ($external->state == 'Revisión') {
                $user_review = User::where('id', $external->reviewd_now)->first();
                $notificacion->recipient = $user_review->name;
                $notificacion->mail = $user_review->name;
                $notificacion->mensaje = "Le informamos que <strong>" . $input["users_name"] . "</strong> ha enviado para revisión la correspondencia externa titulada: <strong>" . $notificacion->title . " " . $external->consecutive . "</strong>.<br>Con el siguiente comentario: \"<em>" . $external->observation . "</em>\".";
                $asunto = json_decode('{"subject": "Actualización: Correspondencia Externa ' . $input["consecutive"] . '"}');
                SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario', $asunto, $notificacion->toArray(), $user_review->email, 'Correspondencia externa');
            } else if ($external->state == 'Aprobación') {
                $user_approved = User::where('id', $external->approved_now)->first();
                $notificacion->recipient = $user_approved->name;
                $notificacion->mail = $user_approved->name;
                $notificacion->mensaje = "Le informamos que <strong>" . $input["users_name"] . "</strong> ha enviado para su aprobación la correspondencia externa titulada <strong>" . $notificacion->title . " " . $external->consecutive . "</strong>.<br>Con el siguiente comentario: \"<em>" . $external->observation . "</em>\".";
                $asunto = json_decode('{"subject": "Actualización: Correspondencia Externa ' . $input["consecutive"] . '"}');
                SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario', $asunto, $notificacion->toArray(), $user_approved->email, 'Correspondencia externa');
            } else if ($external->state == 'Pendiente de firma') {
                $notificacion->mensaje = "Le informamos que tiene pendiente de firma la correspondencia externa titulada <strong>" . $notificacion->title . " " . $external->consecutive . "</strong>.<br>Con el siguiente comentario: \"<em>" . $external->observation . "</em>\".";
                $asunto = json_decode('{"subject": "Actualización: Correspondencia Externa ' . $input["consecutive"] . '"}');
                SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario', $asunto, $notificacion->toArray(), $emailArray, 'Correspondencia externa');
            }

            return $this->sendResponse($external->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $error->getMessage() . '. Linea: ' . $error->getLine());
            if(strpos($error->getMessage(), "Duplicate entry") !== false && strpos($error->getMessage(), "consecutive") !== false) {
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess('Lo sentimos, no pudimos procesar su solicitud debido a un alto flujo de peticiones. Por favor, inténtelo de nuevo al hacer clic en "Guardar". Si la situación persiste, comuníquese con ' . env("MAIL_SUPPORT") ?? 'soporte@seven.com.co', 'info');
            } else {
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'), 'info');
            }
        } catch (\Exception $e) {

            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }


    public function guardarAdjuntoRotulo($id, Request $request)
    {
        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();

        try {
            $externa = External::where("id", $id)->first()->toArray();

            // Valida y almacena el nuevo adjunto
            if ($request->hasFile('document_pdf')) {
                $input['document_pdf'] = substr($input['document_pdf']->store('public/container/external_' . date("Y")), 7);
            }
            // Agrega el nuevo adjunto al array de adjuntos si ya existen adjuntos previos
            $input['document_pdf'] = $externa['document_pdf'] ? implode(",", array_merge(explode(",", $externa['document_pdf']), [$input['document_pdf']])) : $input['document_pdf'];

            // Actualiza la correspondencia externa
            $external_rotule = $this->externalRepository->update($input, $id);

            // // Modifica los datos de la correspondencia externa
            $externa['correspondence_external_id'] = $id;
            $externa['observation_history'] = "Actualización de correspondencia";
            $externa['document_pdf'] = $input['document_pdf'];

            // // Crea un nuevo registro en el historial
            $this->ExternalHistoryRepository->create($externa);

            // Obtiene el historial después de crear el nuevo registro
            $external_rotule->externalHistory;


            // Efectua los cambios realizados
            DB::commit();


            // $external = $this->externalRepository->find($id);

            // if (empty($external)) {
            //     return $this->sendError(trans('not_found_element'), 200);
            // }

            return $this->sendResponse($external_rotule->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_external', 'Modules\Correspondence\Http\Controllers\ExternalController - '. Auth::user()->fullname.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_external', 'Modules\Correspondence\Http\Controllers\ExternalController - '. Auth::user()->fullname.' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($external_rotule->toArray(), trans('msg_success_save'));
    }

    /**
     * Actualiza un registro especifico
     *
     * @author Erika Gonzalez. - Abr. 06 - 2020
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

        /** @var External $external */
        $external = $this->externalRepository->find($id);

        if (empty($external)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida si no seleccionó ningún adjunto
        $input['document_pdf'] = isset($input["new_route"]) ? implode(",", $input["new_route"]) : null;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            // $external = $this->externalRepository->update($input, $id);
            // $input['correspondence_external_id'] = $external->id;
            // $input['observation_history'] = "Actualización de correspondencia";

            // // Crea un nuevo registro de historial
            // $this->ExternalHistoryRepository->create($input);

            // //Obtiene el historial
            // $external->externalHistory;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($external->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_external', 'Modules\Correspondence\Http\Controllers\ExternalController - '. Auth::user()->fullname.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_external', 'Modules\Correspondence\Http\Controllers\ExternalController - '. Auth::user()->fullname.' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    public function shareExternal(UpdateExternalRequest $request)
    {

        $input = $request->all();
        $id = $input["id"];

        $external = $this->externalRepository->find($id);

        if (empty($external)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida si viene usuarios para asignar
        if (!empty($input['external_shares'])) {

            //borra todo para volver a insertarlo
            ExternalCopyShare::where('correspondence_external_id', $id)->where("type", "=", "Compartida")->delete();

            //texto para almacenar en la tabla externa
            $textRecipients = array();
            //recorre los destinatarios
            foreach ($input['external_shares'] as $recipent) {
                //array de destinatarios
                $recipentArray = json_decode($recipent, true);
                $recipentArray["correspondence_external_id"] = $id;
                $recipentArray["type"] = "Compartida";
                $recipentArray["name"] = $recipentArray["fullname"];
                $textRecipients[] = $recipentArray["name"];

                $external = External :: where ('id' , $id)->first();
                ExternalCopyShare::create($recipentArray);

                $asunto = json_decode('{"subject": "Notificación de  correspondencia enviada ' . $external->consecutive . ' compartida"}');
                $email = User::where('id', $recipentArray['users_id'])->first()->email;
                $notificacion["consecutive"] = $external->consecutive;
                $notificacion["id"] = $external->id;
                $notificacion["name"] = $recipentArray["name"];
                $notificacion["id_encrypted"] = base64_encode($external->id);
                $notificacion['mensaje'] = "Le informamos que se le ha compartido la correspondencia enviada con radicado: <strong>{$external->consecutive}</strong>. <br><br>Le agradecemos tener en cuenta esta notificación para los fines correspondientes";
                $notificacion["id_encrypted"] = base64_encode($external->id);

                try {
                    SendNotificationController::SendNotification('correspondence::externals.emails.plantilla_notificaciones',$asunto,$notificacion,$email,'Correspondencia enviada');
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - '. Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - '. Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
                }
            }
            $updateExternal["shares"] = implode("<br>", $textRecipients);
            $external = $this->externalRepository->update($updateExternal, $id);
        } else {

            //borra todo para volver a insertarlo
            ExternalCopyShare::where('correspondence_external_id', $id)->where('type', "Compartida")->delete();
            $updateExternal["shares"] = "";
            $external = $this->externalRepository->update($updateExternal, $id);
        }
        if (!empty($input['annotation'])) {
            ExternalAnnotation :: create([
                'correspondence_external_id' => $id,
                'users_id'  => Auth::user()->id,
                'users_name' => Auth::user()->fullname,
                'content' => $input['annotation']
            ]);
        }
        $external->externalShares;
        $external->externalAnnotations;
        $external->externalCopyShares;


        return $this->sendResponse($external->toArray(), trans('msg_success_update'));
    }

    /**
     * Elimina un External del almacenamiento
     *
     * @author Erika Gonzalez. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id)
    {

        /** @var External $external */
        $external = $this->externalRepository->find($id);

        if (empty($external)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            if($external["state"] == 'Público' && $external["origen"] != 'FISICO'){
                return $this->sendSuccess('<strong>Esta correspondencia ya ha sido publicada, por lo tanto no es posible eliminarla.</strong><br>Por favor, cierre este modal y actualice la página para visualizar el estado más reciente de la correspondencia.','warning');
            } else {
                // Elimina el registro
                $external->delete();
            }


            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_external', 'Modules\Correspondence\Http\Controllers\ExternalController - '. Auth::user()->fullname.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_external', 'Modules\Correspondence\Http\Controllers\ExternalController - '. Auth::user()->fullname.' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Erika Gonzalez. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request)
    {

        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time() . '-' . trans('externals') . '.' . $fileType;

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        //valida si es un administrador de correspondencia externa
        if (Auth::user()->hasRole('Correspondencia Enviada Admin')) {

            // Valida si existen las variables del paginado y si esta filtrando
            if (isset($input["filtros"]) && $input["filtros"] != "") {
                // Consulta los tipo de solicitudes según el paginado y los filtros de búsqueda realizados
                $filtroDecodificado = $input["filtros"];
                if ($filtroDecodificado == "state LIKE '%COPIAS%'") {
                    $externals = External::with([
                        'externalReceiveds', 'externalType', 'externalCopy', 'externalHistory', 'externalAnnotations', 'anotacionesPendientes', 'externalRead', 'externalRecipients', 'externalVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'externalShares', 'citizens'
                    ])->whereRelation('externalCopyShares', 'users_id', Auth::user()->id)->get()->toArray();
                } else {
                    // Escapar la palabra clave reservada 'from'
                    $filtroEscapado = str_replace('from', '`from`', $filtroDecodificado);
                    $externals = External::with(['externalReceiveds', 'externalType', 'externalCopy', 'externalHistory', 'externalAnnotations', 'anotacionesPendientes', 'externalRead', 'externalShares', 'externalVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'citizens'])->whereRaw($filtroEscapado)->latest("updated_at")->get()->toArray();
                }
            } else {
                // Consulta los tipo de solicitudes según el paginado seleccionado
                $externals = External::with(['externalReceiveds', 'externalType', 'externalCopy', 'externalHistory', 'externalAnnotations', 'anotacionesPendientes', 'externalRead', 'externalShares', 'externalVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'citizens'])->latest("updated_at")->get()->toArray();
            }
        } else {

            // Valida si existen las variables del paginado y si esta filtrando
            if (isset($input["filtros"]) && $input["filtros"] != "") {

                $filtroDecodificado = $input["filtros"];

                if ($filtroDecodificado == "state LIKE '%COPIAS%'") {
                    $externals = External::with([
                        'externalReceiveds', 'externalType', 'externalCopy', 'externalHistory', 'externalAnnotations', 'anotacionesPendientes', 'externalRead', 'externalRecipients', 'externalVersions', 'serieClasificacionDocumental', 'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental', 'externalShares', 'citizens'
                    ])->whereRelation('externalCopyShares', 'users_id', Auth::user()->id)->get()->toArray();
                    $count_externals = $count_compartida;
                } else {

                    // Escapar la palabra clave reservada 'from'
                    $filtroEscapado = str_replace('from', '`from`', $filtroDecodificado);
                    $externals = External::select('correspondence_external.*')
                        ->with([
                            'externalReceiveds',
                            'externalType',
                            'externalCopy',
                            'externalHistory',
                            'externalAnnotations',
                            'anotacionesPendientes',
                            'externalRead',
                            'externalRecipients',
                            'externalVersions',
                            'serieClasificacionDocumental',
                            'subserieClasificacionDocumental',
                            'oficinaProductoraClasificacionDocumental',
                            'externalShares',
                            'citizens'
                        ])
                        ->where(function ($query) use ($filtroEscapado) {
                            $query->whereRaw("(" . $filtroEscapado . ")") // Agregar paréntesis aquí
                                ->where(function ($query) {
                                    $query->where(function ($query) { // Agregar una capa adicional de anidación
                                        $query->where("correspondence_external.from_id", Auth::user()->id)
                                            ->orWhere("correspondence_external.elaborated_now", Auth::user()->id)
                                            ->orWhere("correspondence_external.reviewd_now", Auth::user()->id)
                                            ->orWhere("correspondence_external.approved_now", Auth::user()->id);

                                        $authUserId = Auth::user()->id;
                                        $query->orWhere(function ($subQuery) use ($authUserId) {
                                            $subQuery->where('correspondence_external.elaborated', 'LIKE', "%,$authUserId,%")
                                                ->orWhere('correspondence_external.elaborated', 'LIKE', "$authUserId,%")
                                                ->orWhere('correspondence_external.elaborated', 'LIKE', "%,$authUserId")
                                                ->orWhere('correspondence_external.elaborated', '=', $authUserId);
                                        });
                                        $query->orWhere(function ($subQuery) use ($authUserId) {
                                            $subQuery->where('correspondence_external.reviewd', 'LIKE', "%,$authUserId,%")
                                                ->orWhere('correspondence_external.reviewd', 'LIKE', "$authUserId,%")
                                                ->orWhere('correspondence_external.reviewd', 'LIKE', "%,$authUserId")
                                                ->orWhere('correspondence_external.reviewd', '=', $authUserId);
                                        });
                                        $query->orWhere(function ($subQuery) use ($authUserId) {
                                            $subQuery->where('correspondence_external.approved', 'LIKE', "%,$authUserId,%")
                                                ->orWhere('correspondence_external.approved', 'LIKE', "$authUserId,%")
                                                ->orWhere('correspondence_external.approved', 'LIKE', "%,$authUserId")
                                                ->orWhere('correspondence_external.approved', '=', $authUserId);
                                        });
                                        $query->orWhereRelation('externalVersions.externalSigns', 'users_id', Auth::user()->id)
                                            ->orWhereRelation('externalCopyShares', 'users_id', Auth::user()->id);
                                    });
                                });
                        })
                        ->groupBy("correspondence_external.id")
                        ->distinct("correspondence_external.id")
                        ->latest()
                        ->get()
                        ->toArray();
                }



            } else {

                $externals = External::select('correspondence_external.*')
                    ->with([
                        'externalReceiveds',
                        'externalType',
                        'externalCopy',
                        'externalHistory',
                        'externalAnnotations',
                        'anotacionesPendientes',
                        'externalRead',
                        'externalRecipients',
                        'externalVersions',
                        'serieClasificacionDocumental',
                        'subserieClasificacionDocumental',
                        'oficinaProductoraClasificacionDocumental',
                        'externalShares',
                        'citizens'
                    ])
                    ->where(function ($query) {
                        $query->where(function ($query) { // Agregar una capa adicional de anidación
                            $query->where("correspondence_external.from_id", Auth::user()->id)
                                ->orWhere("correspondence_external.elaborated_now", Auth::user()->id)
                                ->orWhere("correspondence_external.reviewd_now", Auth::user()->id)
                                ->orWhere("correspondence_external.approved_now", Auth::user()->id);

                            $authUserId = Auth::user()->id;
                            $query->orWhere(function ($subQuery) use ($authUserId) {
                                $subQuery->where('correspondence_external.elaborated', 'LIKE', "%,$authUserId,%")
                                    ->orWhere('correspondence_external.elaborated', 'LIKE', "$authUserId,%")
                                    ->orWhere('correspondence_external.elaborated', 'LIKE', "%,$authUserId")
                                    ->orWhere('correspondence_external.elaborated', '=', $authUserId);
                            });
                            $query->orWhere(function ($subQuery) use ($authUserId) {
                                $subQuery->where('correspondence_external.reviewd', 'LIKE', "%,$authUserId,%")
                                    ->orWhere('correspondence_external.reviewd', 'LIKE', "$authUserId,%")
                                    ->orWhere('correspondence_external.reviewd', 'LIKE', "%,$authUserId")
                                    ->orWhere('correspondence_external.reviewd', '=', $authUserId);
                            });
                            $query->orWhere(function ($subQuery) use ($authUserId) {
                                $subQuery->where('correspondence_external.approved', 'LIKE', "%,$authUserId,%")
                                    ->orWhere('correspondence_external.approved', 'LIKE', "$authUserId,%")
                                    ->orWhere('correspondence_external.approved', 'LIKE', "%,$authUserId")
                                    ->orWhere('correspondence_external.approved', '=', $authUserId);
                            });
                            $query->orWhereRelation('externalVersions.externalSigns', 'users_id', Auth::user()->id)
                                ->orWhereRelation('externalCopyShares', 'users_id', Auth::user()->id);
                        });
                    })
                    ->groupBy("correspondence_external.id")
                    ->distinct("correspondence_external.id")
                    ->latest()
                    ->get()
                    ->toArray();



            }

        }


        $data = $externals;

        array_walk($data, fn (&$object) => $object = (array)$object);

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {

            //nueva clave para lista de correspondencia
            $input['correspondence_externa'] = [];

            //ciclo para agregar las correspondencia a los usuarios
            foreach ($data as $item) {
                //objecto vacio para incluir elementos necesarios
                $object_interna = new stdClass;
                $object_interna->consecutive = isset($item['consecutive']) ? $item['consecutive'] : '';
                $object_interna->state = isset($item['state']) ? $item['state'] : '';
                $object_interna->recipients = isset($item['citizen_name']) ? $item['citizen_name'] : ''; // Utiliza -> para acceder a la propiedad del objeto
                $object_interna->from = isset($item['from']) ? $item['from'] : '';
                $object_interna->plantilla = isset($item["external_type"]->name) ? $item["external_type"]->name : ''; // Utiliza -> para acceder a la propiedad del objeto
                $object_interna->created_at = isset($item['created_at']) ? $item['created_at'] : '';
                $object_interna->origen = isset($item['origen']) ? $item['origen'] : '';
                $object_interna->modification = isset($item["external_history"][0]->created_at) ? $item["external_history"][0]->created_at : ''; // Utiliza -> para acceder a la propiedad del objeto
                $input['correspondence_externa'][] = $object_interna;
            }

            //elimina el atributo data
            unset($input['data']);
            //count de correspondencia interna
            $count = count($input['correspondence_externa']);

            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new RequestExportLandscape('correspondence::externals.report_pdf', JwtController::generateToken($input['correspondence_externa']), 'h'), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);

            // return Excel::download(new RequestExport('correspondence::externals.report_pdf',$input['correspondence_externa'],'a'), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
            // return Excel::download(new GenericExport($input['correspondence_externa']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } elseif (strcmp($fileType, 'xlsx') == 0) {

            $info_radicadores = array();


            //Recorrer el array que trae todos los datos de las radicaciones
            foreach ($data as $dato) {
                //Almacenar al array los nombres de los radicadores
                $info_radicadores[] = $dato['users_id'];
            }


            $dataExterna = [];

            $dataExternaCount = 0;

            $cantidad_radicaciones = 0;

            foreach (array_unique($info_radicadores) as $radicador) {

                $informationUser = User::select("name")->where('id', $radicador)->first();

                $dataExterna[$dataExternaCount]['radicador'] = $informationUser['name'] ?? "N/A";


                $numero_radicaciones = 0;

                //Recorrer array para obtener el número de radicaciones del funcionario
                foreach ($data as $dato) {
                    if ($dato["users_id"] == $radicador) {
                        //Código para incrementar el número de radicaciones
                        $dataExterna[$dataExternaCount]['internas'][$numero_radicaciones] = $dato;
                        $numero_radicaciones++;
                        $cantidad_radicaciones++;
                    }
                }

                $dataExterna[$dataExternaCount]['total'] = $numero_radicaciones;
                $dataExternaCount++;
            }

            $count = count($dataExterna) +  $cantidad_radicaciones + (count($dataExterna) * 8);


            // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            return Excel::download(new RequestExportCorrespondence('correspondence::externals.report_excel', $dataExterna, $count, 'h'), $fileName);
        }
    }


    /**
     * Organiza la exportacion de datos
     *
     * @author Erika Gonzalez. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function exportRepositoryExternal(Request $request)
    {

        $input = $request->all();

        //Consulta el user joomla id del usuario en sesion
        $userid = Auth::user()->user_joomla_id;

        $likedes1 = '%"id":"' . $userid . '"%';
        $likedes2 = '%"id":' . $userid . ',%';
        $likedes3 = '%"id":' . $userid . '}%';

        $table = "";

        //Obtiene la fecha actual
        $date = date("Y");

        //valida a que tabla realizar la consulta
        if (isset($input['vigencia']) && $input['vigencia'] != $date && $input['vigencia'] != '2024') {
            $table = "externa_" . $input['vigencia'];
        } else {
            $table = "externa";
        }

        //Valida el rol del usuario en sesion
        if (Auth::user()->hasRole('Correspondencia Enviada Admin')) {
            $querys = DB::connection('joomla')->table($table)->where('consecutivo', 'LIKE', '%EE%');
        } else {
            $querys = DB::connection('joomla')->table($table)->where('consecutivo', 'LIKE', '%EE%')->where(function ($query) use ($userid, $likedes1, $likedes2, $likedes3) {
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
        return Excel::download(new RequestExport('correspondence::externals.reports.report_excel', JwtController::generateToken($externa), 'l'), $fileName);
    }


    /**
     * Obtiene los destinatarios posibles de la intranet
     *
     * @author Erika Johana Gonzalez C. - Ene. 19. 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getRecipients(Request $request)
    {
        // Usuarios
        $query = $request->input('query');
        $users = DB::table('users')
            ->select(DB::raw('CONCAT("Usuario ", users.name) AS name, users.id as users_id, "Usuario" AS type'))
            ->where('users.name', 'like', '%' . $query . '%')
            ->whereNotNull('users.id_cargo')
            ->join('cargos', 'cargos.id', '=', 'users.id_cargo')
            ->get();

        // Grupos
        $query = $request->input('query');
        $grupos = DB::table('work_groups')
            ->select(DB::raw('CONCAT("Grupo ", work_groups.name) AS name, work_groups.id as work_groups_id, "Grupo" AS type'))
            ->where('work_groups.name', 'like', '%' . $query . '%')
            ->get();

        // Dependencias
        $query = $request->input('query');
        $dependencias = DB::table('dependencias')
            ->select(DB::raw('CONCAT("Dependencia ", dependencias.nombre) AS name, dependencias.id as dependencias_id, "Dependencia" AS type'))
            ->where('dependencias.nombre', 'like', '%' . $query . '%')
            ->get();

        // Cargos
        $query = $request->input('query');
        $position = DB::table('cargos')
            ->select(DB::raw('CONCAT("Cargo ", cargos.nombre) AS name, cargos.id as cargos_id, "Cargo" AS type'))
            ->where('cargos.nombre', 'like', '%' . $query . '%')
            ->get();

        $recipients = array_merge($users->toArray(), $grupos->toArray(), $dependencias->toArray(), $position->toArray());

        return $this->sendResponse($recipients, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene los destinatarios posibles de la intranet
     *
     * @author Erika Johana Gonzalez C. - Ene. 19. 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getOnlyUsers(Request $request)
    {
        // Usuarios
        $query = $request->input('query');
        $users = DB::table('users')
            ->select(DB::raw('CONCAT("Usuario ", users.name) AS name, users.id as users_id, "Usuario" AS type'))
            ->where('users.name', 'like', '%' . $query . '%')
            ->whereNotNull('users.id_cargo')
            ->join('cargos', 'cargos.id', '=', 'users.id_cargo')
            ->get();


        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene los tipos de documentos
     *
     * @author Erika Johana Gonzalez C. - Ene. 19. 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getTypes(Request $request)
    {

        $types = ExternalTypes::orderBy("name")->get()->toArray();

        return $this->sendResponse($types, trans('data_obtained_successfully'));
    }

    public function read($correspondenceId)
    {

        $userLogin = Auth::user();

        $readCorrespondence = ExternalRead::select("id", "access", 'users_name')->where("correspondence_external_id", $correspondenceId)->where("users_id", $userLogin->id)->where("users_name", $userLogin->fullname)->first();
        if ($readCorrespondence) {
            // Valida si ya tiene accesos
            if ($readCorrespondence["access"]) {
                $accesos = $readCorrespondence["access"] . "<br/>" . date("Y-m-d H:i:s");
            } else {
                $accesos = date("Y-m-d H:i:s");
            }
            // Actualiza los accesos del leido
            $resultReadCorrespondence = ExternalRead::where("id", $readCorrespondence["id"])->update(["access" => $accesos], $readCorrespondence["id"]);
        } else {
            $readCorrespondence = date("Y-m-d H:i:s");

            // Valida si es el usuario que esta leyendo el registro, tiene el rol de administrador
            if (Auth::user()->hasRole('Correspondencia Externa Admin')) {
                $rol = "Administrador";
            } else {
                $rol = "Funcionario";
            }
            // Crea un registro de leido del registro
            $resultReadCorrespondence = ExternalRead::create([
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
        ExternalAnnotation::where('correspondence_external_id', $correspondenceId)
            ->where(function ($query) use ($userId) {
                $query->where('leido_por', null) // Si el campo 'leido_por' es null, establece el ID del usuario actual
                    ->orWhere('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
                    ->orWhere('leido_por', 'not like', $userId . ',%'); // También para el caso donde el ID del usuario esté al principio seguido de una coma
            })
            ->update(['leido_por' => \DB::raw("CONCAT_WS(',', COALESCE(leido_por, ''), '$userId')")]);

        // Buscar y obtener la instancia de External correspondiente
        $correspondencia = $this->externalRepository->find($correspondenceId);

        // Cargar ansiosamente las anotaciones pendientes asociadas a la instancia de External
        $correspondencia->anotacionesPendientes;

        // Devolver una respuesta con los datos de la instancia de External actualizados
        return $this->sendResponse($correspondencia->toArray(), trans('msg_success_update'));


        // return $this->sendResponse($resultReadCorrespondence, "Correspondencia leida con éxito");
    }

    public function crearExternaCeroPapeles(Request $request)
    {
        $input = $request->all();

        $userLogin = Auth::user();

        $input["users_id"] = $userLogin->id;
        $input["users_name"] = $userLogin->fullname;

        $input["state"] = "Elaboración";
        $input["year"] = date("Y");
        $input["origen"] = 'DIGITAL';

        $input["elaborated"] = $userLogin->id;
        $input["elaborated_names"] = $userLogin->fullname;
        $input["elaborated_now"] = $userLogin->id;

        /* Consulta la informacion del usuario remitente */
        $input["from"] = $userLogin->fullname;
        $input["from_id"] = $userLogin->id;

        //datos de la dependencia del usuario remitente
        $infoDependencia = Dependency::where('id', $userLogin->id_dependencia)->first();
        $input["dependencias_id"] = $infoDependencia["id"];
        $input["dependency_from"] = $infoDependencia["nombre"];

        //PL
        $tipo_documento = ExternalTypes::where('id', $input["type"])->first();
        $input["type_document"] = $tipo_documento["name"];

        $input["citizen_name"] = "";
        $input["channel"] = "6";

        //si es editor web
        if ($input["editor"] == "Web") {
            $tipo_documento["template"] = $tipo_documento["template_web"];
        }

        if (!$tipo_documento["template"]) {
            // Valida si el usuario es administrador de correspondencia externa
            if (Auth::user()->hasRole('Correspondencia Enviada Admin')) {
                // Si es un administrador le muestra el mensaje de advertencia con el link de acceso a la configuración de tipos de plantillas de externa enviada
                return $this->sendSuccess('<strong>¡Atención! Configuración de Plantillas Requerida</strong><br /><br />Es necesario configurar primero la plantilla desde la opción <a href="external-types" target="_blank">Tipos documentales Externa</a>. Esta configuración es esencial para crear documentos.', 'info');
            } else {
                // Si es un usuario funcionario, le muestra el mensaje de información indicándole que se comunique con el administrador
                return $this->sendSuccess('Lamentamos informarle que este tipo de documento no tiene una plantilla configurada actualmente. Por favor, te solicitamos que te comuniques con el administrador del sistema para resolver este inconveniente.', 'info');
            }
        }


        // First, check if the file exists
        if (!file_exists(storage_path("app/public/" . $tipo_documento["template"]))) {

            // Valida si el usuario es administrador de correspondencia interna
            if (Auth::user()->hasRole('Correspondencia Enviada Admin')) {
                // Si es un administrador le muestra el mensaje de advertencia con el link de acceso a la configuración de tipos de plantillas de interna
                return $this->sendSuccess('<strong>¡Atención! Configuración de Plantillas Requerida</strong><br /><br />Es necesario configurar primero la plantilla desde la opción <a href="external-types" target="_blank">Tipos documentales Externa</a>. Esta configuración es esencial para crear documentos.', 'info');
            } else {
                // Si es un usuario funcionario, le muestra el mensaje de información indicándole que se comunique con el administrador
                return $this->sendSuccess('Lo sentimos, actualmente no hay una plantilla configurada para este tipo de documento o la plantilla ha dejado de estar disponible. Por favor, te solicitamos que te comuniques con el administrador del sistema para resolver este inconveniente.', 'info');
            }
        }
        try {

            if (!file_exists(base_path() . "/app/IntegracionGoogle/token_google.json")) {
                $this->generateSevenLog('error_integracion_google', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: No hay token.json. Linea: GoogleController');
                return $this->sendSuccess(config('constants.support_message') . "", 'info');
            }
            $google = new GoogleController();
            $id_google = $google->crearDocumentoGoogleDrive($input["title"], storage_path("app/public/" . $tipo_documento["template"]), "Externa Cero Papeles -" . env("APP_NAME"));
        } catch (\Throwable $th) {
            $this->generateSevenLog('error_integracion_google', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error en GoogleController' . $th->getMessage());

            return $this->sendSuccess($th->getMessage(), 'info');
        }

        $input["template"] = "https://docs.google.com/document/d/" . $id_google;
        $input["consecutive"] = "E" . date("YmdHis");


        // Inicia la transaccion
        DB::beginTransaction();
        try {

            // Inserta el registro en la base de datos
            $external = $this->externalRepository->create($input);

            $input['correspondence_external_id'] = $external->id;
            $input['observation_history'] = "Creación de correspondencia";

            // Crea un nuevo registro de historial
            $this->ExternalHistoryRepository->create($input);

            //Obtiene el historial
            $external->externalHistory;
            $external->externalType;
            $external->users;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($external->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_external', 'Modules\Correspondence\Http\Controllers\ExternalController - '. Auth::user()->fullname.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_external', 'Modules\Correspondence\Http\Controllers\ExternalController - '. Auth::user()->fullname.' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }



    public function signExternal(UpdateExternalRequest $request)
    {
        try {
            // Inicia la transaccion
            DB::beginTransaction();
            $input = $request->all();

            $id = $input["id"];

            $external = $this->externalRepository->find($id);

            if (empty($external)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            if ($input["type_send"] ==  "Devolver para modificaciones") {

                $input["state"] = "Devuelto para modificaciones";
                $input["user_from_last_update"] = Auth::user()->fullname;
                $input["user_for_last_update"] = $input["from"];
                $input["observation_inicial"] = $input['observations'];

                //obtiene la ultima version
                $dataVersion = json_decode($input['external_versions'][0]);

                //Actualiza el estado de la version
                ExternalVersions::where('id', $dataVersion->id)
                    ->update(['state' =>  $input["state"]]);

                //Actualiza el estado de las firmas de la version
                ExternalSigns::where('correspondence_external_versions_id', $dataVersion->id)
                    ->where('users_id', Auth::user()->id)
                    ->update(['state' =>  $input["state"], 'observation' =>  $input["observations"]]);

                $external = $this->externalRepository->update($input, $id);

                $input['correspondence_external_id'] = $external->id;
                $input['observation_history'] = "Devolución de correspondencia";
                $input['observation'] = $input['observations'];
                $input['users_name'] = Auth::user()->fullname;
                $input['users_id'] = Auth::user()->id;

                // Crea un nuevo registro de historial
                $this->ExternalHistoryRepository->create($input);


                // envia la notificación al devolver por no firmar.
                $notificacion = $external;
                $asunto = json_decode('{"subject": "Notificación de correspondencia externa ' . $external->consecutive . '"}');

                $external_id_encrypted = base64_encode($external["id"]);
                $notificacion->link =  '/correspondence/externals?qder='.$external_id_encrypted;
                $notificacion->mensaje = "Le informamos que { $external->user_from_last_update }, ha devuelto la correspondencia externa: <strong> { $external->title } </strong>, con consecutivo: <strong>{ $external->consecutive }</strong>. <br> Con el siguiente comentario: \" " . $input['observations'] . "  \" ";
                $notificacion->mail = User::where('id', $external->from_id)->first()->email;
                $notificacion->recipient = $input["from"] . '.';

                SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario', $asunto, $notificacion->toArray(), $notificacion->mail, 'Correspondencia externa');
            } else {

                //aprobar firma
                $dataVersion = json_decode($input['external_versions'][0]);

                $signsActual = ExternalSigns::where('correspondence_external_versions_id', $dataVersion->id)
                    ->where('state', 'Pendiente de firma')
                    ->get()->toArray();


                // Se crea una cadena hash para identificar la firma del usuario en el documento
                $fullHash = hash('sha256', Auth::user()->id . $input["consecutive"]);
                $hash = "ID firma: " . substr(base64_encode(hex2bin($fullHash)), 0, 17);

                // Obtiene la IP del usuario en sesión
                $publicIp = $this->detectIP();

                //solo falta una firma, la suya
                if (count($signsActual) == 1) {

                    // $request->merge(['tipo' => 'publicacion']);
                    // $this->update($id,$request);

                    $userLogin = Auth::user();


                    $informationUser = User::select("name", "id_dependencia")->where('id', $input["from_id"])->first();


                    $infoDependencia = Dependency::where('id', $informationUser["id_dependencia"])->first();

                    $input["dependencias_id"] = $infoDependencia["id"];
                    $input["dependency_from"] = $infoDependencia["nombre"];
                    $typeExternal = ExternalTypes::where('id', $input["type"])->first();

                    $input["type_document"] = $typeExternal->name;
                    //DP
                    $DP = Dependency::where('id', $input["dependencias_id"])->pluck("codigo")->first();
                    $siglas = $infoDependencia["codigo_oficina_productora"] ?? '';

                    //PL
                    $PL = ExternalTypes::where('id', $input["type"])->pluck("prefix")->first();


                    //Consulta las variables para calcular el consecutio.
                    $formatConsecutive = Variables::where('name', 'var_external_consecutive')->pluck('value')->first();
                    $formatConsecutivePrefix = Variables::where('name', 'var_external_consecutive_prefix')->pluck('value')->first();

                    $dependenciaInformacion = Dependency::where('id', $input["dependencias_id"])->first();


                    //Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order
                    $dataConsecutive = UtilController::getNextConsecutive('External', $formatConsecutive, $formatConsecutivePrefix, $DP, $PL,$siglas);
                    $input["consecutive"] = $dataConsecutive['consecutive'];
                    $input["consecutive_order"] = $dataConsecutive['consecutive_order'];
                    $external = $this->externalRepository->update(['consecutive'=>$input["consecutive"], 'consecutive_order'=>$input["consecutive_order"]], $id);

                    //estaod publico
                    $input["state"] = "Público";
                    $variables = $typeExternal->variables;

                    $information["#consecutivo"] = $input["consecutive"];
                    $information["#titulo"] = $input["title"];
                    $information["#direccion"] = $dependenciaInformacion["direccion"];
                    $information["#dep_piso"] = $dependenciaInformacion["piso"];
                    $information["#codigo_postal"] = $dependenciaInformacion["codigo_postal"];
                    $information["#telefono"] = $dependenciaInformacion["telefono"];
                    $information["#dep_ext"] = $dependenciaInformacion["extension"];
                    $information["#dep_correo"] = $dependenciaInformacion["correo"];
                    $information["#logo"] = $dependenciaInformacion["logo"];

                    // Almacena los usuarios remitentes que firmaron el documento
                    //   $usuarios_remitentes = [];
                    //   foreach($dataVersion->external_signs as $usuario) {
                    //       // Se obtiene el nombre de usuario y se asigna al arreglo de remitentes
                    //     //   $usuarios_remitentes[] = $usuario->users->name;
                    //       $informationUser = User::where('id', $usuario->users->id)->first();

                    //       $usuarios_remitentes[] = $informationUser->fullname;
                    //   }

                    $usuarios_remitentes = [];

                    $firmasFinales = [];
                    $contadorFirmas = ExternalSigns::where('correspondence_external_versions_id', $dataVersion->id)->with('users')->get();
                    $firmasFinales = [];

                    foreach ($contadorFirmas as $datosFirma) {

                        $dataUser = User::where('id', $datosFirma->users->id)->get()->first()->toArray();

                        // Valida si el usuario posee una firma para la publicación del documento
                        if(!$dataUser['url_digital_signature']){
                            // Retorna mensaje de error informando que el usuario no puede publicar y firmar el documento
                            return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                        }else{
                            if (!file_exists(storage_path("app/public/".$dataUser['url_digital_signature']))) {
                                return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                            }
                        }

                        if ($dataUser['autorizado_firmar'] == 0) {
                            return $this->sendErrorData("El usuario " . $datosFirma->users->name . " no está autorizado para firmar. Debe estar autorizado para continuar con el proceso de firma.", 'warning');
                        }


                        $usuario = new \stdClass();
                        $usuario->users = new \stdClass();
                        $usuario->users->name = $this->processFuncionarioText($datosFirma->users->fullname);
                        $usuario->users->url_digital_signature = $datosFirma->users->url_digital_signature;
                        $usuario->users->escala_firma = $datosFirma->users->escala_firma;
                        $usuario->users->hash = !empty($datosFirma->hash) ? $datosFirma->hash : $hash;

                        $firmasFinales[] = $usuario;
                        $usuarios_remitentes[] = $datosFirma->users->fullname;
                    }
                    // dd($hash);
                    //actualiza registro de firma del usuario en sesion
                    ExternalSigns::where('correspondence_external_versions_id', $dataVersion->id)
                        ->where('users_id', Auth::user()->id)
                        ->update(['state' =>  "Firma aprobada", 'observation' =>  $input["observations"], 'hash' => $hash, 'ip' => $publicIp]);

                    $information["#firmas"] = $firmasFinales;

                    // Campo de remitentes que se muestra en el listado
                    $input["from"] = implode(" - ", $usuarios_remitentes);
                    $information["#remitente"] = $input["from"];
                    $information["#dependencia_remitente"] = $input["dependency_from"];
                    $information["#ciudadano_documento"] = '';
                    $information["#ciudadano_email"] = '';
                    $information["#ciudadano"] = str_replace("<br>", "\n", $input["citizen_name"]);
                    $information["#copias"] = str_replace("<br>", ", ", $input["copies"] ?? "No aplica");
                    $information["#contenido"] = $input["content"] ?? "";
                    $information["#anexos"] = $input["annexes_description"] ?? "No aplica";
                    $information["#tipo_documento"] = $input["type_document"];
                    $information["#elaborado"] = $input["elaborated_names"];
                    $information["#revisado"] = $input["reviewd_names"] ?? ($input["approved_names"] ?? $information["#remitente"]);
                    $information["#aprobado"] = $input["approved_names"] ?? $information["#revisado"];
                    $information["#proyecto"] = $input["elaborated_names"];
                    $information["#respuesta_pqr"] = $input["pqr_consecutive"] ?? "No aplica";
                    $information["#codigo_dependencia"] = $DP;


                    setlocale(LC_ALL, "es_ES");
                    $information["#fecha"] = strftime("%d de %B del %Y");
                    $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    // Genera un código de verificación único para cada documento
                    $input["validation_code"] = substr(str_shuffle($caracteres_permitidos), 0, 8);
                    $input["validation_code_encode"] = JwtController::generateToken($input["validation_code"]);
                    $information["#codigo_validacion"] = $input["validation_code"];

                    $id_google = explode("/", $input["template"]);
                    $id_google = end($id_google);


                    $google = new GoogleController();
                    $returnGoogle = $google->editFileDoc("Enviada", $id, $id_google, explode(",", $variables), $information, 0);
                    if ($returnGoogle['type_message'] == 'info') {
                        // Devuelve los cambios realizados
                        DB::rollback();
                        // Retorna mensaje de error de base de datos
                        return $this->sendSuccess($returnGoogle['message'], 'info');
                    }
                    $documento_almacenado = $google->saveFileGoogleDrive($id_google, "pdf", $input["consecutive"], 'container/external_'.date('Y'));
                    // Si la variable 'documento_almacenado' tiene valor en la propiedad 'type_message' y este es igual a 'warning', hubo un error al guardar el documento
                    if(!empty($documento_almacenado["type_message"]) && $documento_almacenado["type_message"] == "warning") {
                        // Devuelve los cambios realizados
                        DB::rollback();
                        // Retorna mensaje de advertencia al usuario
                        return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $documento_almacenado["message"], 'warning');
                    }
                    $input["document_pdf"] = $documento_almacenado;
                    // Valida el 'TIPO_ALMACENAMIENTO_GENERAL', si es AWS
                    if(env("TIPO_ALMACENAMIENTO_GENERAL") == "AWS") {
                        // Crea una instancia de tipo Request para enviar a la función 'readObjectAWS'
                        $requestObjectAWS = new Request();
                        // Ruta del documento
                        $requestObjectAWS["path"] = $input["document_pdf"];
                        // Tipo de url de descarga 'temporal_aws', quiere decir que se obtendrá el archivo (URL) directamente desde S3
                        $requestObjectAWS["tipoURL"] = "temporal_aws";
                        // Se hace la solicitud a la función 'readObjectAWS' para obtener la URL prefirmada del archivo
                        $archivo_aws = $this->readObjectAWS($requestObjectAWS);
                        // Se decodifica la URL
                        $archivo = JwtController::decodeToken($archivo_aws['data']);
                        // Se calcula el hash del archivo directamente desde la URL prefirmada de S3 de AWS
                        $input["hash_document_pdf"] = hash_file('sha256', $archivo);
                    } else {
                        // Genera una cadena hash usando el archivo local del campo document_pdf
                        $input["hash_document_pdf"] = hash_file('sha256', 'storage/' . $input["document_pdf"]);
                    }

                    $external = $this->externalRepository->update($input, $id);

                    $history = $input;
                    $history['observation'] = $input['observations'];
                    $history['observation_history'] = "Actualización de correspondencia";
                    $history['correspondence_external_id'] = $id;
                    $history['users_name'] = Auth::user()->fullname;
                    $history['users_id'] = Auth::user()->id;

                    // $history['user_for_last_update'] = User :: select('name')->where('id',$input['from_id'])->first()->name;

                    // Crea un nuevo registro de historial
                    $this->ExternalHistoryRepository->create($history);

                    //fin pqrs
                    if ($input['require_answer'] == 'Si' && $input["state"] = "Público") {
                        //update pqr
                        $this->PQRCreate($input, $external->id);
                        $informacionPqr = PQR::where('id', $input["pqr_id"])->first()->toArray();
                        $informacionPqr["pqr_pqr_id"] = $input["pqr_id"];
                        $informacionPqr["action"] = "Finalización de registro";
                        // Se crea el historial del PQR
                        PQRHistorial::create($informacionPqr);
                    }

                    if (!empty($input["citizens"]) && $input["state"] == 'Público') {
                        // Elimina los ciudadanos para poner los nuevos
                        $input["citizen_name"] = array_map(fn ($item) => json_decode($item)->citizen_name, $input["citizens"]);
                        $input["citizen_name"] = implode(",", array_map(fn ($item) => json_decode($item)->citizen_name, $input["citizens"]));
                        $mails =  array_map(fn ($item) => !empty(json_decode($item)->citizen_email) ? json_decode($item)->citizen_email : "", $input["citizens"]);
                        $mails[] = Auth::user()->email;
                        $asunto = json_decode('{"subject": "Actualización: Correspondencia Externa ' . $input["consecutive"] . '"}');
                        SendNotificationController::SendNotification('correspondence::externals.emails.notificacion', $asunto, $input, $mails, 'Correspondencia externa');
                    }
                } else {

                    //actualiza registro de firma del usuario en sesion
                    ExternalSigns::where('correspondence_external_versions_id', $dataVersion->id)
                        ->where('users_id', Auth::user()->id)
                        ->update(['state' =>  "Firma aprobada", 'observation' =>  $input["observations"], 'hash' => $hash, 'ip' => $publicIp]);

                    $history = $input;
                    $history['observation'] = $input['observations'];
                    $history['observation_history'] = "Firma aprobada";
                    $history['correspondence_external_id'] = $id;
                    $history['users_name'] = Auth::user()->fullname;
                    $history['users_id'] = Auth::user()->id;
                    // $history['user_for_last_update'] = User :: select('name')->where('id',$input['from_id'])->first()->name;

                    // Crea un nuevo registro de historial
                    $this->ExternalHistoryRepository->create($history);
                }
            }

            $external->externalVersions;
            $external->externalHistory;
            // Efectua los cambios realizados
            DB::commit();
            return $this->sendResponse($external->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_external', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $error->getMessage());
            if(strpos($error->getMessage(), "Duplicate entry") !== false && strpos($error->getMessage(), "consecutive") !== false) {
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
            $this->generateSevenLog('correspondence_external', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Muestra la vista principal para validar las correspondencia externas enviadas
     *
     * @param Request $request
     * @return void
     */
    public function validarExternal(Request $request)
    {
        return view('correspondence::externals_validar_correspondencia.index');
    }

    /**
     * Valida la existencia de la correspondencia externa enviada según el código ingresado por el usuario
     *
     * @param [type] $codigo
     * @param Request $request
     * @return void
     */
    public function validarExternalCodigo($codigo, Request $request)
    {
        // Consulta la correspondencia externa enviada según el código enviado por el usuario
        $external = External::whereRaw("validation_code = BINARY '" . $codigo . "'")->get();
        // Retorna la información de la correspondencia, en caso tal de que coincida algún registro
        return $this->sendResponse($external->toArray(), trans('msg_success_update'));
    }

    /**
     * Valida la existencia de la correspondencia externa enviada según el código ingresado por el usuario
     *
     * @param Request $request
     * @return void
     */
    public function validarExternalDocumento(Request $request)
    {
        $input = $request->all();
        $hash = hash_file('sha256', $request['documento_adjunto']);
        // Retorna la veracidad del documento de la correspondencia, en caso tal de que coincida algún registro
        return $this->sendResponse(["documentos_identicos" => $input["hash"] == $hash], "Documento verificado");
    }


    public function faker()
    {
        $faker = Factory::create();

        // Create 2 External records
        for ($i = 0; $i < 5000; $i++) {
            $external = new External();
            $external->consecutive = $faker->unique()->randomNumber();
            $external->state = $faker->randomElement(['Elaboración', 'Revisión', 'Aprobación', 'Pendiente de firma', 'Devuelto para modificaciones']);
            $external->title = $faker->sentence();
            $external->content = $faker->paragraph();
            $external->folios = $faker->randomNumber();
            $external->annexes = $faker->randomElement(['Sí', 'No']);
            $external->annexes_description = $faker->sentence();
            $external->type_document = $faker->randomElement(['Carta', 'Memorando', 'Oficio']);
            $external->require_answer = $faker->randomElement(['Sí', 'No']);
            $external->answer_consecutive = $faker->randomNumber();
            $external->template = $faker->randomElement(['Plantilla 1', 'Plantilla 2', 'Plantilla 3']);
            $external->editor = $faker->randomElement(['Editor 1', 'Editor 2', 'Editor 3']);
            $external->origen = $faker->randomElement(['Ciudadanía', 'Dependencia externa']);
            $external->document = $faker->sentence();
            $external->from = $faker->name();
            $external->dependency_from = $faker->randomElement(['Dependencia 1', 'Dependencia 2', 'Dependencia 3']);
            $external->elaborated = $faker->name();
            $external->reviewd = $faker->name();
            $external->approved = $faker->name();
            $external->elaborated_names = $faker->sentence();
            $external->reviewd_names = $faker->sentence();
            $external->approved_names = $faker->sentence();
            $external->creator_name = $faker->name();
            $external->creator_dependency_name = $faker->sentence();
            $external->elaborated_now = $faker->name();
            $external->reviewd_now = $faker->name();
            $external->approved_now = $faker->name();
            $external->number_review = $faker->randomNumber();
            $external->observation = $faker->sentence();
            $external->times_read = $faker->randomNumber();
            $external->user_from_last_update = $faker->name();
            $external->user_for_last_update = $faker->name();
            $external->created_at = $faker->dateTimeBetween('2021-01-01', '2023-12-31'); // Establecer fecha entre 2021 y 2023
            $external->users_id = $faker->randomElement(['44', '123', '134']);
            $external->dependencias_id = $faker->randomElement(['6', '3', '2']);
            $external->type = $faker->randomElement(['3', '4']);
            $external->year = $faker->randomElement(['2022', '2023', '2021']);


            $external->save();

            // Crear un nuevo registro de historial copiando todos los campos de External
            $externalHistory = new ExternalHistory($external->getAttributes());
            $externalHistory->correspondence_external_id = $external->id;
            $externalHistory->observation_history = "Creación de correspondencia";
            $externalHistory->save();
        }
    }

    /**
     * Exporta el historial de la externa enviada
     *
     * @author Manuel Marin. - Abril. 09. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function exportHistorial($id)
    {
        $historial = ExternalHistory::where('correspondence_external_id', $id)->get();

        return Excel::download(new RequestExport('correspondence::externals.reports.report_historial', JwtController::generateToken($historial->toArray()), 'J'), 'Prueba.xlsx');
    }

    public function indexEmail(Request $request)
    {
        $code = $request["c"];
        //id es id de la tabla de_documento_firmar
        return view('correspondence::externals.index_viewer', compact('code'));
    }



    public function validarExternalCodigoFromEmail(Request $request)
    {
        $input = $request->all();
        $input = AntiXSSController::xssClean($input,["codigoAccesoDocumento","c"]);

        // Obtenemos la dirección IP pública del usuario
        $publicIp = $this->detectIP();
        // $codigoDecode = JwtController::decodeToken($input['codigoAccesoDocumento']);

        // Buscamos la correspondencia externa asociada al código de validación
        $external = External::where('validation_code', $input['codigoAccesoDocumento'])->first();

        // Verificamos si se encontró la correspondencia externa
        if ($external) {
            // Registramos la apertura de la correspondencia externa desde el correo electrónico
            $resultReadCorrespondence = ExternalRead::create([
                'users_name' => "Apertura desde el correo electrónico",
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
                'anexosDescripcion' => $external->annexes_description,
                'rutaanexos' => $external->annexes_digital,
                'title' => $external->title,


            ], trans('msg_success_save'));
        } else {
            // Si no se encontró la correspondencia externa, retornamos un mensaje de error
            return $this->sendResponse([
                'success' => false,
            ], 'Documento no encontrado');
        }
    }


    /**
     * Guarda un documento PDF con rotulación en una posición específica.
     * Esta funciones se usan en RotuleComponent
     * @param int $id El ID del documento asociado.
     * @param Request $request La instancia de Request que contiene los datos de la solicitud.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON con los datos actualizados o un mensaje de error.
     */
    public function saveDocumentWithRotule(int $id, Request $request)
    {
        $input = $request->all(); // Obtiene todos los datos de la solicitud

        try {

            $pdfFile = $request->file('selectedFile'); // Obtiene el archivo PDF de la solicitud

            // Almacena el PDF original en una ubicación específica y guarda la ruta en la base de datos
            if ($request->hasFile('selectedFile')) {
                $input['document_pdf_old'] = substr($pdfFile->store('public/container/external_enviada_' . date("Y")), 7);
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
                'margin_top' => 16,
                'margin_bottom' => 16,
                'margin_header' => 9,
                'margin_footer' => 9
            ];


            // Obtiene los datos de la correspondencia
            $external = External::where("id", $id)->first()->toArray();

            // Modifica el PDF con el HTML generado y las opciones definidas
            $urlModifiedPdf = $this->modifyPDF($pdfFile, $html, $optionsPdf);


            // Genera un nombre único para el PDF editado
            $edited_filename = $external['consecutive'].'-'.date("his").'.pdf';

            // $edited_filename = 'rotu-' . $id . '-' . date("Y-m-d-H-i-s") . Str::random(7) . '.pdf';
            $original_pdf_path = '' . $input['document_pdf_old'];
            $original_pdf_directory = dirname($original_pdf_path);
            $edited_pdf_path = $original_pdf_directory . '/' . $edited_filename;

            // Guarda el PDF modificado en el almacenamiento (storage)
            Storage::put("public/" . $edited_pdf_path, $urlModifiedPdf);



            $input['document_pdf'] = $external['document_pdf'] ? implode(",", array_merge(explode(",", $external['document_pdf']), [$edited_pdf_path])) : $edited_pdf_path;
            $externa = $this->externalRepository->update($input, $id);

            // Registra la historia de la correspondencia actualizada
            $external['correspondence_external_id'] = $id;
            $external['users_name']        = Auth::user()->fullname;
            $external['users_id']          = Auth::user()->id;
            $external['observation_history'] = "Actualización de correspondencia";
            $external['document_pdf'] = $input['document_pdf'];

            // dd($externa);
            // // Crea un nuevo registro en el historial
            $this->ExternalHistoryRepository->create($external);

            // Obtiene el historial después de crear el nuevo registro
            $externa->externalHistory;

            $correspondence = External::where('id', $id)->first();

            // Se obtienen los correos de los destinatarios y se les envía el correo
            if ($correspondence) {
                // Utiliza una expresión regular para buscar direcciones de correo electrónico en el campo 'citizen_name'
                preg_match_all('/[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}/', $correspondence['citizen_name'], $matches);
                // Almacena las direcciones de correo encontradas en la variable $mails
                $mails = $matches[0];
                // Crea un objeto JSON con el asunto del correo, incluyendo el número de consecutivo de la correspondencia
                $asunto = json_decode('{"subject": "Actualización: Correspondencia Externa ' . $correspondence["consecutive"] . '"}');
                // Llama al método SendNotification de SendNotificationController para enviar la notificación por correo electrónico
                SendNotificationController::SendNotification('correspondence::externals.emails.notificacion', $asunto, $correspondence, $mails, 'Correspondencia externa');
            }

            // Confirma los cambios en la base de datos
            DB::commit();

            // Retorna una respuesta JSON con los datos actualizados
            return $this->sendResponse($externa->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $e) {
            // Captura y maneja errores de consulta de base de datos
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            return $this->sendSuccess(config('constants.support_message'), 'warning'); // Retorna un mensaje de éxito informativo


        } catch (\Exception $e) {
            // Captura y maneja otros tipos de excepciones generales
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

            // Maneja errores específicos, como PDF protegidos por contraseña
            if ($e->getMessage() == 'Password-protected PDFs are not supported') {
                $mensaje = "<b>La primera página del PDF no pudo ser cargada.</b> <br>El archivo PDF podría estar protegido con contraseña, lo que no es compatible con esta función. Intente eliminar la protección por contraseña o use un PDF diferente.";
                return $this->sendSuccess($mensaje, 'warning'); // Retorna un mensaje de éxito informativo

            }
            return $this->sendSuccess(config('constants.support_message'), 'warning'); // Retorna un mensaje de éxito informativo
        }
    }

    public function modifyPDF($pdfFile, $html, $options)
    {

        try {
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
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            return $this->sendSuccess(config('constants.support_message'), 'info'); // Retorna un mensaje de éxito informativo

        } catch (\Exception $e) {
            // Captura y maneja otros tipos de excepciones generales
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

            return $this->sendSuccess(config('constants.support_message'), 'info'); // Retorna un mensaje de éxito informativo
        }
    }


    /**
     * Previsualiza un documento PDF cargado en la solicitud.
     * RotuleComponent
     * @param int $id El ID del documento asociado (puede ser opcional según la implementación).
     * @param Request $request La instancia de Request que contiene los datos de la solicitud.
     * @return \Illuminate\Http\JsonResponse Una respuesta JSON con la URL de la previsualización o un mensaje de error.
     */
    public function documentPreview($id, Request $request)
    {

        $input = $request->all(); // Obtiene todos los datos de la solicitud

        try {
            $pdfFile = $request->file('selectedFile'); // Obtiene el archivo PDF de la solicitud

            // Obtiene la URL de la primera página del PDF utilizando un método auxiliar (getDocumentFirstPage)
            $url_previous = $this->getDocumentFirstPage($pdfFile);

            // Envía una respuesta exitosa con la URL de la primera página del PDF
            return $this->sendResponse($url_previous, "Documento cargado exitosamente1");
        } catch (\Illuminate\Database\QueryException $e) {
            // Captura y maneja errores de consulta de base de datos
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            return $this->sendSuccess(config('constants.support_message'), 'warning'); // Retorna un mensaje de éxito informativo

        } catch (\Exception $e) {
            // Captura y maneja otros tipos de excepciones generales
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalReceivedController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

            // Maneja errores específicos, como PDF protegidos por contraseña
            if ($e->getMessage() == 'Password-protected PDFs are not supported') {
                $mensaje = "<b>La primera página del PDF no pudo ser cargada.</b> <br>El archivo PDF podría estar protegido con contraseña, lo que no es compatible con esta función. Intente eliminar la protección por contraseña o use un PDF diferente.";
                return $this->sendSuccess($mensaje, 'warning');
            }

            return $this->sendSuccess(config('constants.support_message'), 'warning'); // Retorna un mensaje de éxito informativo
        }
    }

    public function getDocumentFirstPage($pdfFile)
    {
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


    public function logicaCiudadanos($ciudadanos, $idCorrespondencia, $activarGuardado = true)
    {
        $listadoCiudadanos = array();
        $mails = array();

        // Asigna los ciudadanos de datos de destino
        foreach ($ciudadanos as $citizen) {
            $citizen = is_string($citizen) ? json_decode($citizen) : (object)$citizen;

            if ($activarGuardado) {
                ExternalCitizen::create([
                    "correspondence_external_id" => $idCorrespondencia,
                    "citizen_id" => $citizen->citizen_id ?? NULL,
                    "citizen_name" => $citizen->citizen_name,
                    "citizen_document" => $citizen->citizen_document ?? "",
                    "citizen_email" => $citizen->citizen_email ?? "",
                    "department_id" => $citizen->department_id ?? NULL,
                    "city_id" => $citizen->city_id ?? NULL,
                    "trato" => $citizen->trato ?? "",
                    "cargo" => $citizen->cargo ?? "",
                    "entidad" => $citizen->entidad ?? "",
                    "direccion" => $citizen->direccion ?? "",
                    "phone" => $citizen->phone ?? ""
                ]);
            }

            if (!empty($citizen->citizen_email)) {
                $mails[] = $citizen->citizen_email;
            }

            if (!empty($citizen->citizen_name)) {
                $datos = [];

                if (!empty($citizen->trato)) {
                    $datos[] = $citizen->trato;
                }

                if (!empty($citizen->citizen_name)) {
                    $datos[] = strtoupper($citizen->citizen_name);
                }

                if (!empty($citizen->cargo)) {
                    $datos[] = $citizen->cargo;
                }
                if (!empty($citizen->entidad)) {
                    $datos[] = $citizen->entidad;
                }
                if (!empty($citizen->direccion)) {
                    $datos[] = $citizen->direccion;
                }
                if (!empty($citizen->phone)) {
                    $datos[] = $citizen->phone;
                }
                if (!empty($citizen->departamento_informacion)) {
                    // Extraer y validar el nombre del departamento
                    $departamentoNombre = null;
                    if (isset($citizen->departamento_informacion->name)) {
                        $departamentoNombre = $citizen->departamento_informacion->name;
                    }
                    
                    // Extraer y validar el nombre de la ciudad
                    $ciudadNombre = null;
                    if (isset($citizen->ciudad_informacion)) {
                        if (is_object($citizen->ciudad_informacion) && isset($citizen->ciudad_informacion->name)) {
                            $ciudadNombre = $citizen->ciudad_informacion->name;
                        } elseif (is_array($citizen->ciudad_informacion) && isset($citizen->ciudad_informacion['name'])) {
                            $ciudadNombre = $citizen->ciudad_informacion['name'];
                        }
                    }
                    
                    // Agregar la información geográfica al array de datos
                    if (!empty($ciudadNombre)) {
                        $datos[] = !empty($departamentoNombre) 
                            ? "{$ciudadNombre}/{$departamentoNombre}" 
                            : $ciudadNombre;
                    } elseif (!empty($departamentoNombre)) {
                        // Si solo tenemos departamento, lo incluimos
                        $datos[] = $departamentoNombre;
                    }
                }
                if (!empty($citizen->citizen_email)) {
                    $datos[] = $citizen->citizen_email;
                }
                $listadoCiudadanos[] = implode("<br>", $datos);
            }
        }

        return [
            'datos_ciudadano' => implode("<br><br>", $listadoCiudadanos),
            'mails' => $mails
        ];
    }

    public function logicaCopias($copias, $infoCorrespondecia, $activarGuardado = true)
    {
        // Valida si viene usuarios para asignar en las copias
        if (!empty($copias)) {
            // Si el guardado está activado, borra los registros existentes
            if ($activarGuardado) {
                ExternalCopyShare::where('correspondence_external_id', $infoCorrespondecia["id"])->where("type", "=", "Copia")->delete();
            }

            //texto para almacenar en la tabla externa
            $textCopias = array();
            //recorre los destinatarios
            foreach ($copias as $recipent) {
                //array de destinatarios

                $recipentArray = is_string($recipent) ? json_decode($recipent, true) : $recipent;

                $recipentArray["name"] = $recipentArray["fullname"];
                $textCopias[] = $recipentArray["name"];

                // Si el guardado está activado, guarda y envía correo
                if ($activarGuardado) {
                    $recipentArray["correspondence_external_id"] = $infoCorrespondecia["id"];
                    $recipentArray["type"] = "Copia";

                    $external = External::where('id', $infoCorrespondecia["id"])->first();

                    $asunto = json_decode('{"subject": "Notificación de copia de correspondencia enviada ' . $infoCorrespondecia["consecutive"] . '"}');
                    $email = User::where('id', $recipentArray['users_id'])->first()->email;
                    $notificacion["consecutive"] = $infoCorrespondecia["consecutive"];
                    $notificacion["id"] = $external->id;
                    $notificacion["name"] = $recipentArray["name"];
                    $notificacion["id_encrypted"] = base64_encode($external->id);

                    $notificacion['mensaje'] = "Le informamos que ha sido incluido(a) como destinatario(a) de copia en el registro de la correspondencia enviada con número de radicado: <strong>{$infoCorrespondecia["consecutive"]}</strong>. <br><br>Le agradecemos tener en cuenta esta notificación para los fines correspondientes";
                    try {
                        if($infoCorrespondecia["tipo"] == 'Publicación'){
                        SendNotificationController::SendNotification('correspondence::externals.emails.plantilla_notificaciones', $asunto, $notificacion, $email, 'Correspondencia enviada');
                        }
                    } catch (\Swift_TransportException $e) {
                        // Manejar la excepción de autenticación SMTP aquí
                        $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
                    } catch (\Exception $e) {
                        // Por ejemplo, registrar el error
                        $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . Auth::user()->fullname . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
                    }

                    ExternalCopyShare::create($recipentArray);
                }
            }
        }

        return [
            'copies' => implode("<br>", $textCopias)
        ];
    }

    function processFuncionarioText($text)
    {
        // Paso 1: Eliminar el contenido entre paréntesis y los propios paréntesis.
        $text = trim($text); // Aseguramos que no haya espacios al principio y al final.
        $textArray = explode("(", $text, 2);

        // Inicializamos el array de datos del funcionario.
        $datosFuncionario = array();

        // Paso 2: Asignar la primera parte del texto (antes del paréntesis) al array.
        //nombre
        if (isset($textArray[0])) {
            $datosFuncionario[] = strtoupper(trim($textArray[0]));
        }

        // Paso 3: Procesar la segunda parte del texto (dentro del paréntesis).
        if (isset($textArray[1])) {
            $infoCargoDependencia = explode(",", $textArray[1]);

            // Agregar el cargo y la dependencia al array, eliminando los paréntesis restantes.
            // Aseguramos que cada parte tenga espacios eliminados al principio y al final.
            if (isset($infoCargoDependencia[0])) {
                // Fecha actual
                $fecha_actual = date("Y-m-d");
                // Datos del usuario en sesión
                $user = Auth::user();
                // Valida si el funcionario esta como encargado
                if(!empty($user["funcionario_encargado"]) &&
                    !empty($user["fecha_inicio_encargo"]) &&
                    !empty($user["fecha_fin_encargo"]) &&
                    $user["funcionario_encargado"] &&
                    $fecha_actual >= $user["fecha_inicio_encargo"] &&
                    $fecha_actual <= $user["fecha_fin_encargo"]) {
                    // Indica que el funcionario esta como encargado
                    $datosFuncionario[] = trim($infoCargoDependencia[0])." (E)";
                } else {
                    $datosFuncionario[] = trim($infoCargoDependencia[0]);
                }
            }
            if (isset($infoCargoDependencia[1])) {
                $datosFuncionario[] = trim(str_replace(")", "", $infoCargoDependencia[1]));
            }
        }

        // Paso 4: Devolver el texto formateado con saltos de línea.
        return implode("\n", $datosFuncionario);
    }

    public function previewDocument(Request $request)
    {
        $input = $request->all();
        $id = $input["id"];
        $userLogin = Auth::user();


        // Bloque 1: Generación del hash para la firma digital
        $fullHash = hash('sha256', $userLogin->id . $input["consecutive"]);
        $hash = "ID firma: " . substr(base64_encode(hex2bin($fullHash)), 0, 17);
        $input["hash_firma"] = 'ID firma: Espacio para el ID de la firma';

        // Crear el objeto de la firma
        $signUnique = new \stdClass();
        $signUnique->name = $this->processFuncionarioText($userLogin->fullname);
        $signUnique->url_digital_signature = $userLogin->url_digital_signature;
        $signUnique->hash = $input["hash_firma"];

        // Definir la estructura de las firmas
        $signUnique2 = new \stdClass();
        $signUnique2->users = $signUnique;
        // dd($input);

        $datosCiudadanos = $this->logicaCiudadanos($input["citizens"], $id, false);
        $mails = $datosCiudadanos["mails"];
        $input["citizen_name"] = $datosCiudadanos["datos_ciudadano"];


        if (!empty($input['external_copy'])) {
            $datosCopias = $this->logicaCopias($input["external_copy"], $input, false);
            $input["copies"] = $datosCopias["copies"];
        }


        // Preparar la información
        $information = $this->prepareDocumentInformation($input, $signUnique2, $hash);

        // Genera el código de validación único
        $information["#codigo_validacion"] = "Código de validación";


        // Obtener la ID de Google Docs
        $id_google = explode("/", $input["template"]);
        $id_google = end($id_google);

        // Editar el archivo en Google Docs
        $google = new GoogleController();
        $returnGoogle = $google->editFileDoc("Enviada", $id, $id_google, array_column($input['external_type']['variables_documento'], "variable"), $information, 0, true, true);

        // Manejar el caso de error de Google Docs
        if ($returnGoogle['type_message'] == 'info') {
            DB::rollback();
            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $returnGoogle['message'], 'info');
        }


        $nombreArchivo = $input["consecutive"];
        // Guardar el archivo PDF en Google Drive
        $ruta_documento_temp = $google->saveFileGoogleDrive(JwtController::decodeToken($returnGoogle['data'])->id, "pdf", $nombreArchivo, "container/external_previews" . date('Y'), true, true);

        // Definir la ruta del archivo PDF para la vista previa
        $input['template_preview'] = "/container/external_previews" . date('Y') . "/" . $nombreArchivo . ".pdf?time=" . time();


        // Actualizar el documento con la nueva vista previa
        $externalUpdate = External::find($id); // Obtén una instancia del modelo

        if ($externalUpdate) {
            $externalUpdate->timestamps = false; // Desactiva los timestamps para *esta instancia*
            $externalUpdate->template_preview = $input['template_preview'];
            $externalUpdate->save(); // Guarda los cambios

            $externalUpdate->timestamps = true; // Vuelve a activar los timestamps (¡Importante!)
        }

        $input['template_preview'] = $ruta_documento_temp;

        return $this->sendResponse($input['template_preview'], trans('data_obtained_successfully'));
    }

    // Función para preparar la información del documento
    private function prepareDocumentInformation($input, $signUnique2, $hash)
    {

        $information = [];
        $userLogin = Auth::user();

        $information["#firmas"] = 1;
        $information["#remitente"] =  $userLogin->fullname;
        $elaborated = $input["elaborated_names"] ?? null;
        $reviewed   = $input["reviewd_names"] ?? null;
        $approved   = $input["approved_names"] ?? null;
        if (isset($input["tipo"])) {
            switch ($input["tipo"]) {
                case 'Firma Conjunta':

                    // Almacena los usuarios remitentes que firmaron el documento
                    $usuarios_remitentes = [];
                    // Recorre los usuarios que firman el documento
                    foreach ($input['users_sign_text'] as $recipent) {
                        // Array de usuarios firmantes

                        $recipentArray = is_string($recipent) ? json_decode($recipent, true) : $recipent;

                        // Se obtiene el nombre de usuario y se asigna al arreglo de remitentes
                        $usuarios_remitentes[] = str_replace("Usuario ", "", $recipentArray["fullname"]);
                    }
                    $input["user_for_last_update"] = implode(" - ", $usuarios_remitentes);
                    $information["#remitente"] = implode(" - ", $usuarios_remitentes);

                    $information["#firmas"] = !empty($input["users_sign"]) ? count($input['users_sign']) : 0;


                    // if(!empty($input["users_sign"])){
                    //     $information["#firmas"] = count($input['users_sign']);
                    // }else{
                    //     $information["#firmas"] = 0;
                    // }
                    $elaborated = $input["elaborated_names"] ?? null;
                    $reviewed   = $input["reviewd_names"] ?? null;
                    $approved   = $input["approved_names"] ?? null;
                    break;
                case 'Aprobación':
                    if (!empty($input["funcionario_revision"])) {
                        $approved   = $input["approved_names"] . ',' . $input["user_for_last_update"] ?? null;
                    } else {
                        $approved   = $input["approved_names"] ?? null;
                    }
                    $elaborated = $input["elaborated_names"] ?? null;
                    $reviewed   = $input["reviewd_names"] ?? null;
                    break;
                case 'Revisión':
                    $elaborated = $input["elaborated_names"] ?? null;
                    if (!empty($input["funcionario_revision"])) {
                        $reviewed = $input["reviewd_names"] . ',' . $input["user_for_last_update"] ?? null;
                    } else {
                        $reviewed = $input["reviewd_names"] ?? null;
                    }
                    $approved   = $input["approved_names"] ?? null;
                    break;
                case 'Elaboración':
                    if (!empty($input["funcionario_revision"])) {
                        $elaborated = $input["elaborated_names"] . ',' . $input["user_for_last_update"] ?? null;
                    } else {
                        $elaborated = $input["elaborated_names"] ?? null;
                    }
                    $reviewed   = $input["reviewd_names"] ?? null;
                    $approved   = $input["approved_names"] ?? null;
                    break;
                case 'Publicación':
                    $elaborated = $input["elaborated_names"] ?? null;
                    $reviewed   = $input["reviewd_names"] ?? null;
                    $approved   = $input["approved_names"] ?? null;
                    break;
                default:
                    $information["#firmas"] = 1;
                    break;
            }
        }
        $copies     = $input["copies"] ?? null;
        $information["#elaborado"] = UtilController::formatNames($elaborated);
        $information["#revisado"]  = UtilController::formatNames($reviewed ?? $approved ?? $information["#remitente"] ?? $elaborated);
        $information["#aprobado"]  = UtilController::formatNames($approved ?? $information["#revisado"]);
        $information["#proyecto"]  = UtilController::formatNames($elaborated);
        $information["#copias"]    = UtilController::formatNames($copies);

        $information["#consecutivo"] = $input["consecutive"];
        $information["#titulo"] = $input["title"];
        $information["#dependencia_remitente"] = $input["dependency_from"];
        $information["#contenido"] = $input["content"] ?? "";
        $information["#anexos"] = $input["annexes_description"] ?? "No aplica";
        $information["#tipo_documento"] = $input["type_document"];

        $information["#ciudadano"] = empty($input["citizen_name"]) ? "Sin ciudadano" : str_replace("<br>", "\n", $input["citizen_name"]);

        // Obtener la información de la dependencia
        $DP = Dependency::where('id', $input["dependencias_id"])->pluck("codigo")->first();
        $dependenciaInformacion = Dependency::where('id', $input["dependencias_id"])->first();
        $information["#codigo_dependencia"] = $DP;
        $information["#direccion"] = $dependenciaInformacion["direccion"];
        $information["#dep_piso"] = $dependenciaInformacion["piso"];
        $information["#codigo_postal"] = $dependenciaInformacion["codigo_postal"];
        $information["#telefono"] = $dependenciaInformacion["telefono"];
        $information["#dep_ext"] = $dependenciaInformacion["extension"];
        $information["#dep_correo"] = $dependenciaInformacion["correo"];
        $information["#logo"] = $dependenciaInformacion["logo"];

        // Formatear la fecha
        setlocale(LC_ALL,"es_ES");
        $information["#fecha"] = strftime("%d de %B del %Y");

        return $information;
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

            // Valida si llega un documento
            if (!empty($documentoEncriptado)) {
                $read = $this->readDocument($idCorrespondence,$idMail);
                $documento = Crypt::decryptString($documentoEncriptado);
                return view('correspondence::view_document_public.index', compact(['documento']));
            }
            $read = $this->readDocument($idCorrespondence,$idMail);

            $notificacion = NotificacionesMailIntraweb::where('id_mail', $idMail)->get()->first()->toArray();

             // Retonara la vista publica, para informar de que se leyó exitosamente el correo
            return view('correspondence::view_document_public.correo_leido',compact(['read','notificacion']));

            // dd($documentoEncriptado,$idCorrespondence,$idMail);
        } catch (\Exception $e) {
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\ExternalController - ' . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

        }
    }

    private function readDocument($id, $idMail = null)
    {
        // Obtenemos la dirección IP pública del usuario
        $publicIp = $this->detectIP();
        // $codigoDecode = JwtController::decodeToken($input['codigoAccesoDocumento']);

        // Buscamos la correspondencia externa asociada al código de validación
        $external = External::where('id', $id)->get()->first();
        // Verificamos si se encontró la correspondencia externa
        if ($external) {
            // Registramos la apertura de la correspondencia externa desde el correo electrónico
            ExternalRead::create([
                'users_name' => "Apertura desde el correo electrónico",
                'users_type' => "Ciudadano",
                'access' => now()->format('Y-m-d H:i:s') . " - IP: " . $publicIp,
                'year' => now()->year,
                'correspondence_external_id' => $external->id,
                'users_id' => 0
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
                'success' => true,
            ], trans('msg_success_save'));
        } else {
            // Si no se encontró la correspondencia externa, retornamos un mensaje de error
            return $this->sendResponse([
                'success' => false,
            ], 'Documento no encontrado');
        }
    }
}
