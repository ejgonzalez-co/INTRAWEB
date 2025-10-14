<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\correspondence\GenericExport;
use App\Exports\correspondence\RequestExport;
use App\Exports\correspondence\RequestExportCorrespondence;
use App\Exports\correspondence\RequestExportLandscape;
use Modules\Correspondence\Http\Requests\CreateInternalRequest;
use Modules\Correspondence\Http\Requests\UpdateInternalRequest;
use Modules\Correspondence\Repositories\InternalRepository;
use Modules\Correspondence\Repositories\InternalHistoryRepository;
use Illuminate\Database\Eloquent\Builder;

use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\GoogleController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Correspondence\Models\InternalChequeos;
use Modules\Correspondence\Models\Internal;
use Modules\Correspondence\Models\InternalTypes;
use Modules\Correspondence\Models\InternalRecipient;
use Modules\Correspondence\Models\InternalCopyShare;
use Modules\Configuracion\Models\Variables;
use Modules\Intranet\Models\Dependency;
use App\User;
use Illuminate\Support\Facades\Hash;
use Modules\Correspondence\Http\Controllers\UtilController;
use Modules\Correspondence\Models\InternalRead;
use Modules\Correspondence\Models\InternalVersions;
use Modules\Correspondence\Models\InternalSigns;
use Modules\Correspondence\Models\InternalHistory;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
use \stdClass;
use GuzzleHttp\Client;
use Modules\Correspondence\Models\InternalSignHistory;
use App\Http\Controllers\JwtController;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Modules\Correspondence\Models\InternalAnnotation;
use App\Http\Controllers\SendNotificationController;

/**
 * Descripcion de la clase
 *
 * @author Erika Johana Gonzalez C. - Abr. 06 - 2020
 * @version 1.0.0
 */
class InternalController extends AppBaseController
{

    /** @var  InternalRepository */
    private $internalRepository, $internalHistoryRepository;

    /**
     * Constructor de la clase
     *
     * @author Erika Johana Gonzalez C. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(InternalRepository $internalRepo,InternalHistoryRepository $internalRepoHistory)
    {
        $this->internalRepository = $internalRepo;
        $this->internalHistoryRepository = $internalRepoHistory;

    }

    /**
     * Muestra la vista para el CRUD de Internal.
     *
     * @author Erika Johana Gonzalez C. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        if(!Auth::user()->hasRole(["Ciudadano"])){
            $clasificacion = Variables::where('name' , 'clasificacion_documental_interna')->pluck('value')->first();
            return view('correspondence::internals.index',compact(['clasificacion']));
        }
        return view("auth.forbidden");
    }

    /**
     * Muestra la vista de PQRS repositorio del sitio anterior de la entidad.
     *
     * @author Desarrollador Seven - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function indexRepositorio(Request $request) {
        return view('correspondence::internals.index_repositorio')->with("vigencia",  $request['vigencia']);;
    }

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

        // Verificar el rol del usuario y manejar la lógica de acuerdo con su rol
        if (Auth::user()->hasRole('Correspondencia Interna Admin')) {
            return $this->consultasAdmin($tablero, $filtros, $cantidadPaginado);
        }

        return $this->consultasFuncionario($tablero, $filtros, $cantidadPaginado);
    }

    // Maneja la búsqueda por query
    private function buscarQuery($query)
    {
        $internals = DB::table('correspondence_internal')
            ->where('consecutive', 'like', '%'.$query.'%')
            ->where('state', 'like', 'Público')
            ->get();

        return $this->sendResponse($internals->toArray(), trans('data_obtained_successfully'));
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
        // Reemplazar los espacios en blanco por "+" en la cadena de filtros codificada
        $request["f"] = str_replace(" ", "+", $request["f"]);

        // Decodificar filtros si existen
        if (isset($request["f"]) && !empty($request["f"])) {
            $filtros = base64_decode($request["f"]);

            // Escapar la palabra reservada 'from' con comillas invertidas
            $filtros = str_replace("from", "`from`", $filtros);

            // Expresión regular para detectar "status_permission_check LIKE '%SI%' o '%NO%'"
            if (preg_match("/status_permission_check LIKE '%(SI|NO)%'/i", $filtros, $matches)) {
                $valor = $matches[1]; // Obtiene "SI" o "NO"

                $userId = Auth::id();
                $dependenciaId = Auth::user()->id_dependencia;
                $cargoId = Auth::user()->id_cargo;

                // Obtener los grupos de trabajo del usuario
                $groups = DB::table('users_work_groups')
                    ->where('users_id', $userId)
                    ->pluck("work_groups_id")
                    ->toArray();

                $groupIds = implode(",", $groups); // Convertir array a string para SQL

                // Primera consulta: Verifica si el usuario tiene un estado en internal_chequeos
                $subconsulta = "(SELECT estado_check
                                FROM correspondence_internal_chequeos
                                WHERE correspondence_internal_id = correspondence_internal.id
                                AND users_id = $userId
                                LIMIT 1)";

                // Segunda consulta: Si no hay coincidencia en internal_chequeos, busca en internalRecipients y copyShare
                $fallbackConsulta = "(EXISTS (
                                        SELECT 1
                                        FROM correspondence_internal_recipient
                                        WHERE correspondence_internal_recipient.correspondence_internal_id = correspondence_internal.id
                                        AND (
                                            correspondence_internal_recipient.users_id = $userId
                                            OR correspondence_internal_recipient.dependencias_id = $dependenciaId
                                            OR correspondence_internal_recipient.cargos_id = $cargoId";

                if (!empty($groups)) {
                    $fallbackConsulta .= " OR correspondence_internal_recipient.work_groups_id IN ($groupIds)";
                }

                $fallbackConsulta .= "))) OR EXISTS (
                                        SELECT 1
                                        FROM correspondence_internal_copy_share
                                        WHERE correspondence_internal_copy_share.correspondence_internal_id = correspondence_internal.id
                                        AND correspondence_internal_copy_share.users_id = $userId
                                    )";

                // Regla adicional: Si no encuentra coincidencia en internal_chequeos, pero sí en las demás, tomar como 'No'
                $finalQuery = "(
                                    COALESCE(
                                        ($subconsulta),
                                        CASE WHEN ($fallbackConsulta) THEN 'No' ELSE NULL END
                                    ) = '$valor'
                            )
                            AND correspondence_internal.state = 'Público'";

                // Reemplazar el filtro original
                $filtros = str_replace($matches[0], $finalQuery, $filtros);
            }

            return $filtros;
        }

        return "";
    }



    // Maneja la lógica para los usuarios con el rol de 'Correspondencia Interna Admin'
    private function consultasAdmin($tablero, $filtros, $cantidadPaginado = null)
    {
        if ($tablero) {
            return $this->tableroAdmin();
        }

        // Maneja la consulta de solicitudes internas, con los filtros aplicados
        $internalsQuery = Internal::with([
            'internalType', 'internalCopy', 'internalHistory', 'internalAnnotations',
            'internalChequeos','anotacionesPendientes', 'internalRead', 'internalRecipients',
            'internalVersions', 'internalShares', 'serieClasificacionDocumental',
            'subserieClasificacionDocumental', 'oficinaProductoraClasificacionDocumental',
            'historialFirmas', 'users','internalCopyShares'
        ])->when($filtros, function ($queryFiltros) use ($filtros) {
            if ($filtros == "state LIKE '%COPIAS%'") {
                $queryFiltros->whereRelation('internalCopyShares', 'users_id', Auth::user()->id);
            } elseif (strpos($filtros, "total_respuestas LIKE '%PENDIENTE_RESPUESTA_VENCIDAS%'") !== false) {
                $queryFiltros->where('responsable_respuesta', Auth::user()->id)
                    ->where('estado_respuesta', 'Pendiente de tramitar')
                    ->whereDate('fecha_limite_respuesta', '<', now()->toDateString());
            } elseif (strpos($filtros, "total_respuestas LIKE '%RESPUESTAS_FINALIZADAS%'") !== false) {
                $queryFiltros->where('responsable_respuesta', Auth::user()->id)
                    ->where('estado_respuesta', 'Finalizado');
            } elseif (strpos($filtros, "total_respuestas LIKE '%PENDIENTE_RESPUESTA%'") !== false) {
                $queryFiltros->where('responsable_respuesta', Auth::user()->id)
                    ->where('estado_respuesta', 'Pendiente de tramitar')
                    ->whereDate('fecha_limite_respuesta', '>=', now()->toDateString());
            } else {
                $queryFiltros->whereRaw($filtros);
            }
        })->orderBy('updated_at', 'DESC');

        // Si no trae el parámetro 'cantidadPaginado', el origen de la petición es para el reporte
        if($cantidadPaginado) {
            $internals = $internalsQuery->latest("correspondence_internal.updated_at")
            ->paginate($cantidadPaginado);

            $count_internals = $internals->total();
            $internals = $internals->toArray()["data"];
        } else {
            $count_internals = 0;
            $internals = $internalsQuery->get()->toArray();

        }

        return $this->sendResponseAvanzado($internals, trans('data_obtained_successfully'), null, ["total_registros" => $count_internals]);
    }

    // Maneja la lógica cuando se pasa el parámetro 'tablero' para usuarios administradores
    private function tableroAdmin(){

        $estados_originales = ["Público", "Elaboración", "Revisión", "Aprobación", "Pendiente de firma", "Devuelto para modificaciones"];
        $estados_reemplazar = ["publico", "elaboracion", "revision", "aprobacion", "firmar_varios", "devuelto_para_modificaciones"];
        $responsableId = (int) Auth::id(); // Asegurar tipo consistente

        // Base de la consulta con relaciones necesarias
        $internalsQuery = Internal::with(['internalCopyShares'])
        ->select(
            'state',
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN responsable_respuesta = ' . $responsableId. ' AND state="Público" AND estado_respuesta = "Pendiente de tramitar" AND fecha_limite_respuesta >= CURDATE() THEN 1 ELSE 0 END) AS count_pendiente_respuesta'),
            DB::raw('SUM(CASE WHEN responsable_respuesta = ' . $responsableId . ' AND state="Público" AND estado_respuesta = "Finalizado" THEN 1 ELSE 0 END) AS count_respuestas_finalizadas'),
            DB::raw('SUM(CASE WHEN responsable_respuesta = ' . $responsableId . ' AND state="Público" AND estado_respuesta = "Pendiente de tramitar" AND fecha_limite_respuesta < CURDATE() THEN 1 ELSE 0 END) AS count_pendiente_respuesta_vencidas')
        )
        ->groupBy('state');

        // Ejecutar consulta optimizada
        $internals = $internalsQuery->get();

        // Contar las copias compartidas con el usuario actual
        // $count_compartida = $internals->where('internalCopyShares.users_id', Auth::id())->count();
        $count_compartida = Internal::with('internalCopyShares')->whereRelation('internalCopyShares', 'users_id', Auth::user()->id)->where('state','Público')->count();

        // Mapeo de estados
        $state_totales = $internals->pluck('total', 'state')
            ->mapWithKeys(function ($total, $state) use ($estados_originales, $estados_reemplazar) {
                return [str_replace($estados_originales, $estados_reemplazar, $state) => $total];
            });

        // Calcular el total de registros
        $totalSuma = $internals->sum('total');

        $totalPendienteRespuesta = $internals->sum('count_pendiente_respuesta');
        $totalRespuestasFinalizadas = $internals->sum('count_respuestas_finalizadas');
        $totalPendienteVencidas = $internals->sum('count_pendiente_respuesta_vencidas');


        // Devolvemos la respuesta
        return $this->sendResponseAvanzado([], trans('data_obtained_successfully'), null, [
            'estados' => $state_totales,
            'total_internas' => $totalSuma,
            'total_compartidas' => $count_compartida,
            'count_pendiente_respuesta' => $totalPendienteRespuesta,
            'count_respuestas_finalizadas' => $totalRespuestasFinalizadas,
            'count_pendiente_respuesta_vencidas' => $totalPendienteVencidas,
        ]);
    }

    private function getCommonInternalsQuery($groups, $authUserId, $dependenciaId, $cargoId)
    {
        return Internal::
            leftJoin('correspondence_internal_recipient', function ($join) {
                $join->on('correspondence_internal.id', '=', 'correspondence_internal_recipient.correspondence_internal_id')
                    ->whereNull('correspondence_internal_recipient.deleted_at');
            })
            ->leftJoin('correspondence_internal_copy_share', function ($join) {
                $join->on('correspondence_internal.id', '=', 'correspondence_internal_copy_share.correspondence_internal_id')
                    ->whereNull('correspondence_internal_copy_share.deleted_at')
                    ->where('correspondence_internal.state', 'Público'); 
            })
            ->where(function ($queryInicial) use ($groups, $authUserId, $dependenciaId, $cargoId) {
                $queryInicial->where(function ($query) use ($groups, $authUserId, $dependenciaId, $cargoId) {
                    $query->where(function ($subQuery) use ($groups, $authUserId, $dependenciaId, $cargoId) {
                        $subQuery->whereIn("correspondence_internal_recipient.work_groups_id", $groups)
                            ->orWhere("correspondence_internal_recipient.users_id", $authUserId)
                            ->orWhere("correspondence_internal_recipient.dependencias_id", $dependenciaId)
                            ->orWhere("correspondence_internal_recipient.cargos_id", $cargoId)
                            ->orWhere("correspondence_internal.internal_all", 1);
                    })
                    ->where("correspondence_internal.state", "Público");
                })
                ->orWhere(function ($query) use ($authUserId) {
                    $query->where("correspondence_internal.from_id", $authUserId)
                        ->orWhere("correspondence_internal.responsable_respuesta", $authUserId)
                        ->orWhere("correspondence_internal.elaborated_now", $authUserId)
                        ->orWhere("correspondence_internal.reviewd_now", $authUserId)
                        ->orWhere("correspondence_internal.approved_now", $authUserId)
                        ->orWhere("correspondence_internal_copy_share.users_id", $authUserId)
                        ->orWhereRaw("FIND_IN_SET(?, correspondence_internal.elaborated)", [$authUserId])
                        ->orWhereRaw("FIND_IN_SET(?, correspondence_internal.reviewd)", [$authUserId])
                        ->orWhereRaw("FIND_IN_SET(?, correspondence_internal.approved)", [$authUserId])
                        ->orWhereRelation('internalVersions.internalSigns', 'users_id', $authUserId);
                });
            });
    }

    private function tableroFuncionario()
    {
        $groups = DB::table('users_work_groups')
            ->where('users_id', Auth::user()->id)
            ->pluck("work_groups_id")->toArray();

        $authUserId = Auth::user()->id;
        $dependenciaId = Auth::user()->id_dependencia;
        $cargoId = Auth::user()->id_cargo;
        $responsableId = (int) $authUserId;

        $internalsQuery = $this->getCommonInternalsQuery($groups, $authUserId, $dependenciaId, $cargoId)
            ->select(
                'state',
                DB::raw('COUNT(DISTINCT correspondence_internal.id) as total'),
                DB::raw('SUM(CASE WHEN responsable_respuesta = ' . $responsableId . ' AND state="Público" AND estado_respuesta = "Pendiente de tramitar" AND fecha_limite_respuesta >= CURDATE() THEN 1 ELSE 0 END) AS count_pendiente_respuesta'),
                DB::raw('SUM(CASE WHEN responsable_respuesta = ' . $responsableId . ' AND state="Público" AND estado_respuesta = "Finalizado" THEN 1 ELSE 0 END) AS count_respuestas_finalizadas'),
                DB::raw('SUM(CASE WHEN responsable_respuesta = ' . $responsableId . ' AND state="Público" AND  estado_respuesta = "Pendiente de tramitar" AND fecha_limite_respuesta < CURDATE() THEN 1 ELSE 0 END) AS count_pendiente_respuesta_vencidas')
            )
            ->groupBy('state');

        $internals = $internalsQuery->get();
        // $count_compartida = $internals->where('internalCopyShares.users_id', Auth::id())->count();
        $count_compartida = Internal::with('internalCopyShares')->whereRelation('internalCopyShares', 'users_id', Auth::user()->id)->where('state','Público')->count();

        $estados_originales = ["Público", "Elaboración", "Revisión", "Aprobación", "Pendiente de firma", "Devuelto para modificaciones"];
        $estados_reemplazar = ["publico", "elaboracion", "revision", "aprobacion", "firmar_varios", "devuelto_para_modificaciones"];

        $state_totales = $internals->pluck('total', 'state')
            ->mapWithKeys(function ($total, $state) use ($estados_originales, $estados_reemplazar) {
                return [str_replace($estados_originales, $estados_reemplazar, $state) => $total];
            });

        $totalSuma = $internals->sum('total');
        $totalPendienteRespuesta = $internals->sum('count_pendiente_respuesta');
        $totalRespuestasFinalizadas = $internals->sum('count_respuestas_finalizadas');
        $totalPendienteVencidas = $internals->sum('count_pendiente_respuesta_vencidas');

        return $this->sendResponseAvanzado([], trans('data_obtained_successfully'), null, [
            'estados' => $state_totales,
            'total_internas' => $totalSuma,
            'total_compartidas' => $count_compartida,
            'count_pendiente_respuesta' => $totalPendienteRespuesta,
            'count_respuestas_finalizadas' => $totalRespuestasFinalizadas,
            'count_pendiente_respuesta_vencidas' => $totalPendienteVencidas,
        ]);
    }

    private function consultasFuncionario($tablero, $filtros, $cantidadPaginado = null)
    {
        if ($tablero) {
            return $this->tableroFuncionario();
        }

        $groups = DB::table('users_work_groups')
            ->where('users_id', Auth::user()->id)
            ->pluck("work_groups_id")->toArray();

        $authUserId = Auth::user()->id;
        $dependenciaId = Auth::user()->id_dependencia;
        $cargoId = Auth::user()->id_cargo;

        $internalsQuery = $this->getCommonInternalsQuery($groups, $authUserId, $dependenciaId, $cargoId)
            ->select('correspondence_internal.*', 'correspondence_internal_recipient.name')
            ->with([
                'internalType',
                'internalChequeos',
                'internalCopy',
                'internalHistory',
                'internalAnnotations',
                'anotacionesPendientes',
                'internalRead',
                'internalRecipients',
                'internalVersions',
                'internalShares',
                'serieClasificacionDocumental',
                'subserieClasificacionDocumental',
                'oficinaProductoraClasificacionDocumental',
                'historialFirmas',
                'users',
                'internalCopyShares'

            ])
            ->when($filtros, function ($queryFiltros) use ($filtros) {

                // dd($filtros);
                if ($filtros == "state LIKE '%COPIAS%'") {
                    $queryFiltros->whereRelation('internalCopyShares', 'users_id', Auth::user()->id);
                } elseif (strpos($filtros, "total_respuestas LIKE '%PENDIENTE_RESPUESTA_VENCIDAS%'") !== false) {
                    $queryFiltros->where('responsable_respuesta', Auth::user()->id)
                        ->where('estado_respuesta', 'Pendiente de tramitar')
                        ->whereDate('fecha_limite_respuesta', '<', now()->toDateString());
                } elseif (strpos($filtros, "total_respuestas LIKE '%RESPUESTAS_FINALIZADAS%'") !== false) {
                    $queryFiltros->where('responsable_respuesta', Auth::user()->id)
                        ->where('estado_respuesta', 'Finalizado');
                } elseif (strpos($filtros, "total_respuestas LIKE '%PENDIENTE_RESPUESTA%'") !== false) {
                    $queryFiltros->where('responsable_respuesta', Auth::user()->id)
                        ->where('estado_respuesta', 'Pendiente de tramitar')
                        ->whereDate('fecha_limite_respuesta', '>=', now()->toDateString());
                } else {
                    $queryFiltros->whereRaw($filtros);
                }
            })->orderBy('updated_at', 'DESC');


        // Si no trae el parámetro 'cantidadPaginado', el origen de la petición es para el reporte
        if($cantidadPaginado) {
            $internals = $internalsQuery->latest("correspondence_internal.updated_at")
            ->groupBy("correspondence_internal.id")
            ->paginate($cantidadPaginado);

            $count_internals = $internals->total();
            $internals = $internals->toArray()["data"];
        } else {
            $count_internals = 0;
            $internals = $internalsQuery->get()->toArray();

        }

        return $this->sendResponseAvanzado($internals, trans('data_obtained_successfully'), null, ["total_registros" => $count_internals]);
    }


    //Termina Funciones del all

    /**
     * Listado de
     *
     * @author Seven Soluciones Informáticas S.A.S. - Ene. 17 - 2024
     * @version 1.0.0
     *
     * @param int $id del registro procediente de las entradas recientes del dashboard
     */
    public function allRepositoryInternals(Request $request)
    {
        $input = $request->toArray();


        try{
            $userid = Auth::user()->user_joomla_id;

            $likedes1 = '%"id":"'.$userid.'"%';

            $likedes2 = '%"id":'.$userid.',%';

            $likedes3 = '%"id":'.$userid.'}%';

            $date = date("Y");

            $table = '';

            $vigencyReceivedsCount = 0;
            
            //valida a que tabla realizar la consulta
            if ($input['vigencia'] != '' && $input['vigencia'] != $date && $input['vigencia'] != '2024') {
                $table = "interna_".$input['vigencia'];
            }else{
                $table = "interna";
            }

            // Valida si existen las variables del paginado y si esta filtrando
            if(isset($request["f"]) && $request["f"] != "" && isset($request["?cp"]) && isset($request["pi"])) {

                //Valida si el usuario en linea tiene el rol de administrador interna admin
                if (Auth::user()->hasRole('Correspondencia Interna Admin')) {

                    $querys = DB::connection('joomla')->table($table);

                    //Contador de registros
                    $vigencyReceivedsCount = $querys->whereRaw(base64_decode($request["f"]))->count();
                    $vigencyReceiveds = $querys->whereRaw(base64_decode($request["f"]))->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy('cf_created', 'DESC')->get()->toArray();
                } else {
                    
                          // Obtener los grupos del usuario
                    $groups = DB::connection('joomla')->select("SELECT group_id FROM `rghb6_user_usergroup_map` WHERE user_id = ?", [$userid]);

                    // Obtener los datos del usuario
                    $iuser = DB::connection('joomla')->select("SELECT * FROM rghb6_intranet_usuario WHERE userid = ?", [$userid]);
                    $id_cargo = $iuser[0]->id_cargo;
                    $id_dependencia = $iuser[0]->id_dependencia;

                    // Condición para estado 'Pendiente_Visto_Bueno'
                    $vb = "(estado LIKE 'Pendiente_Visto_Bueno' AND consecutivo IN (SELECT consecutivo FROM rghb6_interna_vb WHERE cf_user_id = '".$userid."' AND vb <> 1))";

                    // Condiciones para 'funcionario_des' del usuario
                    $wf = "funcionario_des LIKE '".$userid."' OR funcionario_des LIKE '".$userid.",%' OR funcionario_des LIKE '%,".$userid.",%' OR funcionario_des LIKE '%,".$userid."'";

                  

                    // Extraer los valores de group_id (revisar si 'group_id' es el nombre correcto de la propiedad)
                    $group_ids = array_map(function($item) {
                        return $item->group_id;  // Asegúrate de que 'group_id' es el nombre correcto de la propiedad
                    }, $groups);


                    $wg = implode(" OR ", array_map(function($item) {
                        return "funcionario_des LIKE 'group_".$item."' OR funcionario_des LIKE 'group_".$item.",%' OR funcionario_des LIKE '%,group_".$item.",%' OR funcionario_des LIKE '%,group_".$item."'";
                    }, $group_ids));


                    // Condiciones dinámicas para 'cargo'
                    $wc = "funcionario_des LIKE 'car_".$id_cargo."' OR funcionario_des LIKE 'car_".$id_cargo.",%' OR funcionario_des LIKE '%,car_".$id_cargo.",%' OR funcionario_des LIKE '%,car_".$id_cargo."'";

                    // Condiciones dinámicas para 'dependencia'
                    $wd = "funcionario_des LIKE 'dep_".$id_dependencia."' OR funcionario_des LIKE 'dep_".$id_dependencia.",%' OR funcionario_des LIKE '%,dep_".$id_dependencia.",%' OR funcionario_des LIKE '%,dep_".$id_dependencia."'";

                    // Condiciones dinámicas para 'compartida' con los grupos
                    $cg = implode(" OR ", array_map(function($item) {
                        return "compartida LIKE 'group_".$item."' OR compartida LIKE 'group_".$item.",%' OR compartida LIKE '%,group_".$item.",%' OR compartida LIKE '%,group_".$item."'";
                    }, $group_ids));

                    // Condición para 'all' en 'funcionario_des'
                    $all = "funcionario_des LIKE 'all' OR funcionario_des LIKE 'all,%' OR funcionario_des LIKE '%,all,%' OR funcionario_des LIKE '%,all'";

                    // dd($all);
                    // Condiciones para destinatarios
                    $du = "destinatarios LIKE '%\"id\":\"".$userid."\"%'";
                    $dd = "destinatarios LIKE '%\"id\":\"dep_".$id_dependencia."\"%'";
                    // Generar condiciones dinámicas para 'destinatarios'
                    $dg = implode(" OR ", array_map(function($item) {
                        // Usar comillas escapadas dentro del patrón LIKE
                        return "destinatarios LIKE '%\"id\":\"group_".$item."\"%'";
                    }, $group_ids));
                    $dc = "destinatarios LIKE '%\"id\":\"car_".$id_cargo."\"%'";
                    $dall = "destinatarios LIKE '%\"id\":\"all\"%'";

                    // dd($dall);
                    // Construir la cláusula WHERE completa
                    $wheres[] = "(" . $vb . " OR elaboradopor = '".$userid."' OR aprobadopor = '".$userid."' OR revisadopor = '".$userid."' OR proyecto = '".$userid."' OR (estado = 'Público' AND (consecutivo IN (SELECT consecutivo FROM rghb6_interna_vb WHERE cf_user_id = '".$userid."' AND vb <> 2) OR ".$all." OR ".$wf." OR ".$wg." OR ".$wc." OR ".$wd." OR ".$dall." OR ".$dg." OR ".$dd." OR ".$du." OR ".$dc." OR copia LIKE '".$userid."' OR copia LIKE '".$userid.",%' OR copia LIKE '%,".$userid.",%' OR copia LIKE '%,".$userid."' OR compartida LIKE '".$userid."' OR compartida LIKE '".$userid.",%' OR compartida LIKE '%,".$userid.",%' OR compartida LIKE '%,".$userid."')) OR cf_user_id = '".$userid."' OR funcionario_or = '".$userid."' OR ".$cg.")";

                    // Imprimir la consulta final
                    // dd(implode(" AND ", $wheres));

                      // Consulta principal mejorada
                    $query = DB::connection('joomla')->table(DB::raw("`".env('JOOMLA_DB_PREFIX')."$table`"))
                    ->whereRaw(implode(" AND ", $wheres))
                    ->whereRaw(base64_decode($request["f"]));  // Cambié 'whereraw' a 'whereRaw'

                    // Paginación
                    $pageSize = base64_decode($request["pi"]);  // Tamaño de la página
                    $currentPage = base64_decode($request["?cp"]);  // Página actual (comienza desde 1)

                    // Paginando los resultados
                    $vigencyReceiveds = $query->paginate($pageSize, ['*'], 'page', $currentPage);

                    // Obtener el total de documentos
                    $vigencyReceivedsCount = $vigencyReceiveds->total();  // Total de documentos encontrados

                    // Obtener solo los documentos de la página actual
                    $vigencyReceiveds = $vigencyReceiveds->items();  // Mejor usar items() para obtener los resultados de la página actual

                
                }

            } else if(isset($request["?cp"]) && isset($request["pi"])) {

                if (Auth::user()->hasRole('Correspondencia Interna Admin')) {

                    $querys = DB::connection('joomla')->table($table);

                    $vigencyReceivedsCount = $querys->count();
                    $vigencyReceiveds = $querys->skip((base64_decode($request["?cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->orderBy('cf_created', 'DESC')->get()->toArray();
                }
                // Valida si el usuario logueado usuario normal tic
                else{

                    // Obtener los grupos del usuario
                    $groups = DB::connection('joomla')->select("SELECT group_id FROM `rghb6_user_usergroup_map` WHERE user_id = ?", [$userid]);

                    // Obtener los datos del usuario
                    $iuser = DB::connection('joomla')->select("SELECT * FROM rghb6_intranet_usuario WHERE userid = ?", [$userid]);
                    $id_cargo = $iuser[0]->id_cargo;
                    $id_dependencia = $iuser[0]->id_dependencia;

                    // Condición para estado 'Pendiente_Visto_Bueno'
                    $vb = "(estado LIKE 'Pendiente_Visto_Bueno' AND consecutivo IN (SELECT consecutivo FROM rghb6_interna_vb WHERE cf_user_id = '".$userid."' AND vb <> 1))";

                    // Condiciones para 'funcionario_des' del usuario
                    $wf = "funcionario_des LIKE '".$userid."' OR funcionario_des LIKE '".$userid.",%' OR funcionario_des LIKE '%,".$userid.",%' OR funcionario_des LIKE '%,".$userid."'";

                  

                    // Extraer los valores de group_id (revisar si 'group_id' es el nombre correcto de la propiedad)
                    $group_ids = array_map(function($item) {
                        return $item->group_id;  // Asegúrate de que 'group_id' es el nombre correcto de la propiedad
                    }, $groups);

                    // dd($group_ids);  // Verifica que sea un array de valores simples, como [1, 2, 3, ...]

                    $wg = implode(" OR ", array_map(function($item) {
                        return "funcionario_des LIKE 'group_".$item."' OR funcionario_des LIKE 'group_".$item.",%' OR funcionario_des LIKE '%,group_".$item.",%' OR funcionario_des LIKE '%,group_".$item."'";
                    }, $group_ids));


                    // Condiciones dinámicas para 'cargo'
                    $wc = "funcionario_des LIKE 'car_".$id_cargo."' OR funcionario_des LIKE 'car_".$id_cargo.",%' OR funcionario_des LIKE '%,car_".$id_cargo.",%' OR funcionario_des LIKE '%,car_".$id_cargo."'";

                    // Condiciones dinámicas para 'dependencia'
                    $wd = "funcionario_des LIKE 'dep_".$id_dependencia."' OR funcionario_des LIKE 'dep_".$id_dependencia.",%' OR funcionario_des LIKE '%,dep_".$id_dependencia.",%' OR funcionario_des LIKE '%,dep_".$id_dependencia."'";

                    // Condiciones dinámicas para 'compartida' con los grupos
                    $cg = implode(" OR ", array_map(function($item) {
                        return "compartida LIKE 'group_".$item."' OR compartida LIKE 'group_".$item.",%' OR compartida LIKE '%,group_".$item.",%' OR compartida LIKE '%,group_".$item."'";
                    }, $group_ids));

                    // Condición para 'all' en 'funcionario_des'
                    $all = "funcionario_des LIKE 'all' OR funcionario_des LIKE 'all,%' OR funcionario_des LIKE '%,all,%' OR funcionario_des LIKE '%,all'";

                    // dd($all);
                    // Condiciones para destinatarios
                    $du = "destinatarios LIKE '%\"id\":\"".$userid."\"%'";
                    $dd = "destinatarios LIKE '%\"id\":\"dep_".$id_dependencia."\"%'";
                    // Generar condiciones dinámicas para 'destinatarios'
                    $dg = implode(" OR ", array_map(function($item) {
                        // Usar comillas escapadas dentro del patrón LIKE
                        return "destinatarios LIKE '%\"id\":\"group_".$item."\"%'";
                    }, $group_ids));
                    $dc = "destinatarios LIKE '%\"id\":\"car_".$id_cargo."\"%'";
                    $dall = "destinatarios LIKE '%\"id\":\"all\"%'";

                    // dd($dall);
                    // Construir la cláusula WHERE completa
                    $wheres[] = "(" . $vb . " OR elaboradopor = '".$userid."' OR aprobadopor = '".$userid."' OR revisadopor = '".$userid."' OR proyecto = '".$userid."' OR (estado = 'Público' AND (consecutivo IN (SELECT consecutivo FROM rghb6_interna_vb WHERE cf_user_id = '".$userid."' AND vb <> 2) OR ".$all." OR ".$wf." OR ".$wg." OR ".$wc." OR ".$wd." OR ".$dall." OR ".$dg." OR ".$dd." OR ".$du." OR ".$dc." OR copia LIKE '".$userid."' OR copia LIKE '".$userid.",%' OR copia LIKE '%,".$userid.",%' OR copia LIKE '%,".$userid."' OR compartida LIKE '".$userid."' OR compartida LIKE '".$userid.",%' OR compartida LIKE '%,".$userid.",%' OR compartida LIKE '%,".$userid."')) OR cf_user_id = '".$userid."' OR funcionario_or = '".$userid."' OR ".$cg.")";

                    // Imprimir la consulta final
                    // dd(implode(" AND ", $wheres));

                      // Consulta principal mejorada
                    $query = DB::connection('joomla')->table(DB::raw("`".env('JOOMLA_DB_PREFIX')."$table`"))
                    ->whereRaw(implode(" AND ", $wheres));  // Cambié 'whereraw' a 'whereRaw'

                    // Paginación
                    $pageSize = base64_decode($request["pi"]);  // Tamaño de la página
                    $currentPage = base64_decode($request["?cp"]);  // Página actual (comienza desde 1)

                    // Paginando los resultados
                    $vigencyReceiveds = $query->paginate($pageSize, ['*'], 'page', $currentPage);

                    // Obtener el total de documentos
                    $vigencyReceivedsCount = $vigencyReceiveds->total();  // Total de documentos encontrados

                    // Obtener solo los documentos de la página actual
                    $vigencyReceiveds = $vigencyReceiveds->items();  // Mejor usar items() para obtener los resultados de la página actual
                }

            } else {

                if (Auth::user()->hasRole('Correspondencia Interna Admin')) {
                    // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
                    $vigencyReceiveds = DB::connection('joomla')->Select("SELECT * FROM ".env('JOOMLA_DB_PREFIX').$table." order by cf_created DESC");


                }else{

                    // $vigencyReceiveds = DB::connection('joomla')->Select("SELECT * FROM ".env('JOOMLA_DB_PREFIX').$table." where (  copia like '".$userid."'  or  copia like '".$userid.",%'  or  copia like '%,".$userid.",%'   or  copia like '%,".$userid."'   or funcionarios_destinatarios_ids like '".$likedes1."' or funcionarios_destinatarios_ids like '".$likedes2."' or funcionarios_destinatarios_ids like '".$likedes3."' or compartida like '".$userid."' or compartida like '".$userid.",%' or compartida like '%/".$userid."/%' or compartida like '%,".$userid.",%'  or compartida like '%,".$userid."'  or creado_por_funcionario_id=".$userid." or revisado_por_ids=".$userid." or aprobado_por_ids=".$userid.") order by cf_created DESC");

                    $vigencyReceiveds = DB::connection('joomla')->Select("SELECT * FROM ".env('JOOMLA_DB_PREFIX').$table." where (  copia like '".$userid."'  or  copia like '".$userid.",%'  or  copia like '%,".$userid.",%'   or  copia like '%,".$userid."' or compartida like '".$userid."' or compartida like '".$userid.",%' or compartida like '%/".$userid."/%' or compartida like '%,".$userid.",%'  or compartida like '%,".$userid."') order by cf_created DESC");
                }

                $vigencyReceivedsCount = count($vigencyReceiveds);

            }

            return $this->sendResponseAvanzado($vigencyReceiveds, trans('data_obtained_successfully'), null, ["total_registros" => $vigencyReceivedsCount]);


        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendErrorData("No existe la vigencia seleccionada1. ".config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendErrorData("No existe la vigencia seleccionada2. ".config('constants.support_message'), 'info');
        }

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

        $internals = $this->internalRepository->find($id);
        if (empty($internals)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //relaciones
        $internals->internalRecipients;
        $internals->internalHistory;

        return $this->sendResponse($internals->toArray(), trans('data_obtained_successfully'));
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
        $internals = $this->internalRepository->find($id);
        if (empty($internals)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Relaciones
        $internals->internalType;
        $internals->internalCopy;
        $internals->internalCopyShares;
        $internals->internalHistory;
        $internals->internalAnnotations;
        $internals->internalRead;
        $internals->internalRecipients;
        $internals->internalVersions;
        $internals->internalShares;
        $internals->serieClasificacionDocumental;
        $internals->subserieClasificacionDocumental;
        $internals->oficinaProductoraClasificacionDocumental;
        $internals->users;

        return $this->sendResponse($internals->toArray(), trans('data_obtained_successfully'));
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

        $internals = $this->internalRepository->find($id);
        if (empty($internals)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        //relaciones
        $internals->internalRecipients;
        $internals->internalVersions;
        $internals->internalCopy;
        $internals->internalCopyShares;
        $internals->internalShares;
        return $this->sendResponse($internals->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Erika Johana Gonzalez C. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateInternalRequest $request
     *
     * @return Response
     */
    public function store(CreateInternalRequest $request)
    {

        $input = $request->all();

        // Valida si no seleccionó ningún adjunto

        if (isset($input["document_pdf"])) {
            $input['document_pdf'] = implode(",", (array) $input["document_pdf"]);
        }

        // Valida si no seleccionó ningún anexo
        if($input["annexes_digital"] ?? false) {
            $input['annexes_digital'] = implode(",", $input["annexes_digital"]);
        }


        if(!isset($input["annexes_description"])){
            $input["annexes_description"] = "No aplica";
        }

        if(!isset($input["from_id"])) {
            return $this->sendSuccess('<strong>El Funcionario es requerido.</strong>'. '<br>' . "Puede autocompletar el funcionario ingresando su nombre.", 'warning');
        }


        if(empty($input["internal_recipients"]) && empty($input["internal_all"])) {
            return $this->sendSuccess('<strong>Ingrese por favor al menos un destinatario</strong>'. '<br>' . "Puede autocompletar el funcionario ingresando su nombre.", 'warning');
        }



        $userLogin = Auth::user();

        $input["users_id"]=$userLogin->id;
        $input["users_name"]=$userLogin->fullname;

        $input["state"]="Público";
        $input["year"] = date("Y");
        $input["origen"] = 'FISICO';



        /**Consulta la informacion del usuario remitente */

        $informationUser = User::select("name","id_dependencia","id_cargo")->where('id', $input["from_id"])->first();
        $input["from"] = $informationUser->fullname;

        //datos de la dependencia del usuario remitente
        $infoDependencia = Dependency::where('id', $informationUser["id_dependencia"])->first();
        $input["dependencias_id"] = $infoDependencia["id"];
        $input["dependency_from"] = $infoDependencia["nombre"];

        //Consulta las variables para calcular el consecutio.
        $formatConsecutive = Variables::where('name' , 'var_internal_consecutive')->pluck('value')->first();
        $formatConsecutivePrefix = Variables::where('name' , 'var_internal_consecutive_prefix')->pluck('value')->first();

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
        // dd($siglas);
        //PL
        $typeInternal = InternalTypes::where('id', $input["type"])->first();

        $PL = $typeInternal->prefix;
        $input["type_document"] = $typeInternal->name;

        //Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order
        $dataConsecutive = UtilController::getNextConsecutive('Internal',$formatConsecutive,$formatConsecutivePrefix,$DP,$PL,$siglas);

        $input["consecutive"] = $dataConsecutive['consecutive'];
        $input["consecutive_order"] = $dataConsecutive['consecutive_order'];

        // Inicia la transaccion
        DB::beginTransaction();
        try {


        if (!empty($input["require_answer"])) {
            switch ($input["require_answer"]) {
                case "Se requiere que esta correspondencia reciba una respuesta":
                    $input["responsable_respuesta_nombre"] = User::find($input["responsable_respuesta"])->fullname;
                    $input["estado_respuesta"] = 'Pendiente de tramitar';

                break;

                case "Responder a otra correspondencia":
                    $input["answer_consecutive_name"] = Internal::where('id', $input["answer_consecutive"])->value('consecutive');

                    DB::table('correspondence_internal')
                    ->where('id', $input["answer_consecutive"])
                    ->update(['estado_respuesta' => 'Finalizado', 'answer_consecutive_name' => $input["consecutive"]]);

                break;

                default:
                    $input["answer_consecutive_name"] = "";
            }
        }

        // Inserta el registro en la base de datos
        $internal = $this->internalRepository->create($input);



        // if (!empty($input['internal_recipients'])) {
            $datosDestinatarios = $this->procesarDestinatariosYcopias($input, $internal->id);

            $input["recipients"] = $datosDestinatarios["recipients"] ?? '';
            $input["copies"] = $datosDestinatarios["copies"] ?? '';
            $mailsArray = $datosDestinatarios["mailsArray"] ?? [];
        // }



        $internal = $this->internalRepository->update($input,$internal->id);
        $input['correspondence_internal_id'] = $internal->id;
        $input['observation_history'] = "Creación de correspondencia";

        // Crea un nuevo registro de historial
        $this->internalHistoryRepository->create($input);
        //Obtiene el historial
        $internal->internalHistory;
        $internal->internalType;
        $internal->serieClasificacionDocumental;
        $internal->subserieClasificacionDocumental;
        $internal->oficinaProductoraClasificacionDocumental;
        $internal->internalCopy;
        $internal->internalCopyShares;
        $internal->internalRecipients;
        $internal->internalAnnotations;

        // Efectua los cambios realizados
        DB::commit();

        if(!empty($mailsArray)){
            $notificacion = $internal;
            $internal_id_encrypted = base64_encode($internal["id"]);
            $notificacion->link = '/correspondence/internals?qder='.$internal_id_encrypted;

            // $notificacion->mail= User:: where('id',json_decode($input['internal_recipients'][0])->id)->first()->email;
            $notificacion->mensaje = "Le informamos que " . $internal->from . ", ha publicado la correspondencia interna: <strong>\"".$internal->title . " " .$internal->consecutive."\"</strong> .";
            $asunto = json_decode('{"subject": "Actualización: Correspondencia Interna '.$notificacion->consecutive.'"}');

            SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario',$asunto,$notificacion,explode(",",$mailsArray),'Correspondencia interna');

            // $notificacion->mail= User:: where('id',json_decode($input['internal_recipients'][0])->id)->first()->email;
            $notificacion->mensaje = "Le informamos que " . $internal->from . ", ha publicado la correspondencia interna: <strong>\"".$internal->title . " " .$internal->consecutive."\"</strong> .";
            $asunto = json_decode('{"subject": "Notificación de correspondencia interna '.$notificacion->consecutive.'"}');

            SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario',$asunto,$notificacion,explode(",",$mailsArray),'Correspondencia interna');
        }


            return $this->sendResponse($internal->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Erika Johana Gonzalez C. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateInternalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInternalRequest $request)
    {

        $input = $request->all();

        /** @var Internal $internal */
        $internal = $this->internalRepository->find($id);
        $mailsArray = [];
        if (empty($internal)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        if($internal["state"] == 'Público' && $input["origen"] != 'FISICO'){
            return $this->sendSuccess('<strong>Esta correspondencia ya ha sido publicada, por lo tanto no se pueden realizar ediciones.</strong><br>Por favor, cierre este modal.', 'warning');
        }

        $userLogin = Auth::user();

        $typeInternal = InternalTypes::where('id', $input["type"])->first();

        $input["type_document"] = $typeInternal->name;
        //PL
        $PL = InternalTypes::where('id', $input["type"])->pluck("prefix")->first();


        // Inicia la transaccion
        DB::beginTransaction();
        try {

            if (!empty($input['internal_recipients']) && ($input["origen"] === 'FISICO' || ($input['tipo'] !== 'Firma Conjunta' && $input['tipo'] !== 'Publicación'))) {
                $datosDestinatarios = $this->procesarDestinatariosYcopias($input, $id);
                $input["recipients"] = $datosDestinatarios["recipients"] ?? '';
                $input["copies"] = $datosDestinatarios["copies"] ?? '';
                $mailsArray = $datosDestinatarios["mailsArray"] ?? [];
            }


            // Formatea separado por coma los enlaces de los anexos de la correspondencia
            $input['annexes_digital'] = isset($input["annexes_digital"]) ? implode(",", $input["annexes_digital"]) : null;
            // Valida si el origen es físico, adjunta el documento de la correspondencia
            if($input["origen"] == 'FISICO') {
                // Valida si no seleccionó ningún adjunto
                $input['document_pdf'] = isset($input["document_pdf"]) ? implode(",", (array) $input["document_pdf"]) : null;


                $informationUser = User::where('id', $input["from_id"])->first();
                $input["from"] = $informationUser->fullname;

                $infoDependencia = Dependency::where('id', $informationUser["id_dependencia"])->first();

                $input["dependencias_id"] = $infoDependencia["id"];
                $input["dependency_from"] = $infoDependencia["nombre"];
            } else {


                $input["from"] = $userLogin->fullname;
                $input["from_id"] = $userLogin->id;
                $input["dependencias_id"] = $userLogin->dependencies->id;
                $input["dependency_from"] = $userLogin->dependencies->nombre;
                //DP
                $DP = $userLogin->dependencies->codigo;
                $siglas = $userLogin->dependencies->codigo_oficina_productora ?? '';


                //digital y por estados
                switch ($input["tipo"]) {

                    case 'recuperacion':
                        $input["updated_at"] =  $internal->updated_at;

                        $input["created_at"] =  $internal->created_at;

                        $id_google = explode("/", $input["template"]);
                        $id_google = end($id_google);

                        $google = new GoogleController();

                        $documento_almacenado = $google->saveFileGoogleDrive($id_google, "pdf", $input["consecutive"], "container/internal_".date('Y'));
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
                    break;

                    case 'Publicación':


                        if($userLogin->autorizado_firmar!=1){
                            // Retorna mensaje de error informando que el usuario no puede publicar y firmar el documento
                            return $this->sendSuccess(
                                '<strong>¡Atención!</strong><br /><br />' .
                                'Actualmente no está autorizado para firmar documentos. Por favor, contacte al administrador de Intraweb para obtener los permisos necesarios. ' .
                                'Mientras tanto, puede enviar los documentos para elaboración, revisión y aprobación, pero no podrá publicarlos.',
                                'info'
                            );
                        }

                        if(!empty($input["firma_desde_componente"]) && $input["firma_desde_componente"]!='undefined' && (empty($input["usar_firma_cargada"]) || $input["usar_firma_cargada"] == 'No')){

                            $userLogin->url_digital_signature = $input["firma_desde_componente"];
                        }else{

                             // Valida si el usuario posee una firma para la publicación del documento
                            if(!$userLogin->url_digital_signature){
                                // Retorna mensaje de error informando que el usuario no puede publicar y firmar el documento
                                return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                            }else{
                                if (!file_exists(storage_path("app/public/".$userLogin->url_digital_signature))) {
                                    return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                                }
                            }

                        }

                        $input["firma_desde_componente"] =  $userLogin->url_digital_signature;
                        //Consulta las variables para calcular el consecutio.
                        $formatConsecutive = Variables::where('name' , 'var_internal_consecutive')->pluck('value')->first();
                        $formatConsecutivePrefix = Variables::where('name' , 'var_internal_consecutive_prefix')->pluck('value')->first();


                        //Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order
                        $dataConsecutive = UtilController::getNextConsecutive('Internal',$formatConsecutive,$formatConsecutivePrefix,$DP,$PL,$siglas);
                        $input["consecutive"] = $dataConsecutive['consecutive'];
                        $input["consecutive_order"] = $dataConsecutive['consecutive_order'];
                        // Actualiza el registro
                        $internal = $this->internalRepository->update(['consecutive'=>$input["consecutive"], 'consecutive_order'=>$input["consecutive_order"]], $id);

                        $dependenciaInformacion = Dependency::where('id', $input["dependencias_id"])->first();

                        //estaod publico
                        $input["state"] = "Público";
                        $variables = $typeInternal->variables;

                        $information["#consecutivo"] = $input["consecutive"];
                        $information["#titulo"] = $input["title"];
                        $information["#remitente"] = $input["from"];
                        $information["#dependencia_remitente"] = $input["dependency_from"];
                        $information["#contenido"] = $input["content"] ?? "";
                        $information["#anexos"] = $input["annexes_description"] ?? "No aplica";
                        $information["#tipo_documento"] = $input["type_document"];

                        $datosDestinatarios = $this->procesarDestinatariosYcopias($input, $id);

                        $input["recipients"] = $datosDestinatarios["recipients"] ?? '';
                        $input["copies"] = $datosDestinatarios["copies"] ?? '';
                        $mailsArray = $datosDestinatarios["mailsArray"] ?? [];

                        $information["#destinatarios"] = $this->formatDestinatarios($input["recipients"] ?? null);

                        $elaborated = $input["elaborated_names"] ?? null;
                        $reviewed   = $input["reviewd_names"] ?? null;
                        $approved   = $input["approved_names"] ?? null;
                        $copies     = $input["copies"] ?? null;


                        $information["#elaborado"] = UtilController::formatTextByVariable($elaborated,'Otros');
                        $information["#revisado"]  = UtilController::formatTextByVariable($reviewed ?? $approved ?? $information["#remitente"] ?? $elaborated, 'Otros');
                        $information["#aprobado"]  = UtilController::formatTextByVariable($approved ?? $information["#revisado"], 'Otros');
                        $information["#proyecto"]  = UtilController::formatTextByVariable($elaborated,'Otros');
                        $information["#copias"]    = UtilController::formatTextByVariable($copies, 'Otros');

                        $information["#respuesta_correspondencia"] = $input["answer_consecutive_name"] ?? "No aplica";
                        $information["#codigo_dependencia"] = $DP;
                        $information["#direccion"] = $dependenciaInformacion["direccion"];
                        $information["#dep_piso"] = $dependenciaInformacion["piso"];
                        $information["#codigo_postal"] = $dependenciaInformacion["codigo_postal"];
                        $information["#telefono"] = $dependenciaInformacion["telefono"];
                        $information["#dep_ext"] = $dependenciaInformacion["extension"];
                        $information["#dep_correo"] = $dependenciaInformacion["correo"];



                        $fullHash = hash('sha256',  $userLogin->id . $input["consecutive"]);
                        $hash = "ID firma: " . substr(base64_encode(hex2bin($fullHash)), 0, 17);
                        $input["hash_firma"] = $hash;
                        $signUnique = new \stdClass();
                        $signUnique->name = $this->processFuncionarioText($userLogin->fullname);
                        $signUnique->url_digital_signature = $userLogin->url_digital_signature;
                        $signUnique->escala_firma = $userLogin->escala_firma;
                        $signUnique->hash = $hash;

                        // if (!file_exists(storage_path("app/public/".$signUnique->url_digital_signature))) {
                        //     return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                        // }


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

                        setlocale(LC_ALL,"es_ES");
                        $information["#fecha"] = strftime("%d de %B del %Y");

                        $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                        // Genera un código de verificación único para cada documento
                        $input["validation_code"] = substr(str_shuffle($caracteres_permitidos), 0, 8);
                        $information["#codigo_validacion"] = $input["validation_code"];

                        $id_google = explode("/", $input["template"]);
                        $id_google = end($id_google);

                        $google = new GoogleController();
                        $returnGoogle = $google->editFileDoc("Interna",$id, $id_google, explode(",", $variables), $information, 0);
                        if($returnGoogle['type_message'] == 'info') {
                            // Devuelve los cambios realizados
                            DB::rollback();
                            // Retorna mensaje de error de base de datos
                            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />".$returnGoogle['message'], 'info');
                        }

                        $documento_almacenado = $google->saveFileGoogleDrive(JwtController::decodeToken($returnGoogle['data'])->id, "pdf", $input["consecutive"], "container/internal_".date('Y'));
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

                        $input["elaborated"] = isset($input["elaborated"]) ? $input["elaborated"].",".$input["funcionario_revision"] : $input["funcionario_revision"];
                        $input["elaborated_names"] = isset($input["elaborated_names"]) ? $input["elaborated_names"].", ".$input["user_for_last_update"] : $input["user_for_last_update"];
                        $input["elaborated_now"] = $input["funcionario_revision"];
                        $input["reviewd_now"] = "";
                        $input["approved_now"] = "";


                    break;

                    case 'Revisión':
                        $input["state"] = "Revisión";

                        $input["reviewd"] = isset($input["reviewd"]) ? $input["reviewd"].",".$input["funcionario_revision"] : $input["funcionario_revision"];
                        $input["reviewd_names"] = isset($input["reviewd_names"]) ? $input["reviewd_names"].", ".$input["user_for_last_update"] : $input["user_for_last_update"];
                        $input["reviewd_now"] = $input["funcionario_revision"];
                        $input["elaborated_now"] = "";
                        $input["approved_now"] = "";

                    break;

                    case 'Aprobación':
                        $input["state"] = "Aprobación";

                        $input["approved"] = isset($input["approved"]) ? $input["approved"].",".$input["funcionario_revision"] : $input["funcionario_revision"];
                        $input["approved_names"] = isset($input["approved_names"]) ? $input["approved_names"].", ".$input["user_for_last_update"] : $input["user_for_last_update"];
                        $input["approved_now"] = $input["funcionario_revision"];
                        $input["elaborated_now"] = "";
                        $input["reviewd_now"] = "";
                    break;


                    case 'Firma Conjunta':
                        $input["state"] = "Pendiente de firma";
                        $input["approved_now"] = "";
                        $input["elaborated_now"] = "";
                        $input["reviewd_now"] = "";

                        $numberVersion = InternalVersions::where('correspondence_internal_id', $id)->max("number_version")+1;
                        $variables = $typeInternal->variables;

                        $dependenciaInformacion = Dependency::where('id', $input["dependencias_id"])->first();

                        $information["#consecutivo"] = $input["consecutive"];
                        $information["#titulo"] = $input["title"];
                        // Almacena los usuarios remitentes que firmaron el documento
                        $usuarios_remitentes = [];
                        // Recorre los usuarios que firman el documento
                        foreach ($input['users_sign_text'] as $recipent) {
                            // Array de usuarios firmantes
                            $recipentArray = json_decode($recipent, true);
                            // Se obtiene el nombre de usuario y se asigna al arreglo de remitentes
                            $usuarios_remitentes[] = str_replace("Usuario ", "", $recipentArray["fullname"]);
                        }
                        $input["user_for_last_update"] = implode(" - ", $usuarios_remitentes);
                        // Se pone como remitentes, todos los usuarios que firmaron el documento
                        $information["#remitente"] = implode(" - ", $usuarios_remitentes);
                        $information["#dependencia_remitente"] = $input["dependency_from"];

                        $datosDestinatarios = $this->procesarDestinatariosYcopias($input, $id);
                        $input["recipients"] = $datosDestinatarios["recipients"] ?? '';
                        $input["copies"] = $datosDestinatarios["copies"] ?? '';
                        $mailsArray = $datosDestinatarios["mailsArray"] ?? [];


                        $information["#destinatarios"] = $this->formatDestinatarios($input["recipients"] ?? null);

                        $information["#contenido"] = isset($input["content"]) ? $input["content"] : "";
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



                        $information["#respuesta_correspondencia"] = $input["answer_consecutive_name"] ?? "No aplica";
                        $information["#codigo_dependencia"] = $DP;
                        $information["#firmas"] = "Espacio para firmas";
                        $information["#direccion"] = $dependenciaInformacion["direccion"];
                        $information["#dep_piso"] = $dependenciaInformacion["piso"];
                        $information["#codigo_postal"] = $dependenciaInformacion["codigo_postal"];
                        $information["#telefono"] = $dependenciaInformacion["telefono"];
                        $information["#dep_ext"] = $dependenciaInformacion["extension"];
                        $information["#dep_correo"] = $dependenciaInformacion["correo"];
                        $information["#logo"] = $dependenciaInformacion["logo"];

                        setlocale(LC_ALL,"es_ES");
                        $information["#fecha"] = strftime("%d de %B del %Y");

                        $information["#codigo_validacion"] = $input["validation_code"] ?? "No aplica";

                        $id_google = explode("/", $input["template"]);
                        $id_google = end($id_google);

                        $google = new GoogleController();
                        $returnGoogle = $google->editFileDoc("Interna", $id, $id_google, explode(",", $variables), $information, 0, true);

                        if($returnGoogle['type_message'] == 'info') {
                            // Devuelve los cambios realizados
                            DB::rollback();
                            // Retorna mensaje de error de base de datos
                            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />".$returnGoogle['message'], 'info');
                        }

                        $documento_almacenado = $google->saveFileGoogleDrive(JwtController::decodeToken($returnGoogle['data'])->id, "pdf", $input["consecutive"], "container/internal_".date('Y'), true);
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
                        $inputVersion["correspondence_internal_id"] = $id;
                        $inputVersion["users_id"] = $userLogin->id;
                        $idVersion = InternalVersions::create($inputVersion);

                        $emailArray = [];
                        // Valida si viene usuarios para asignar
                        if (!empty($input['users_sign_text'])) {

                                // Recorre los usuarios que firman el documento
                                foreach ($input['users_sign_text'] as $recipent) {
                                    // Array de usuarios firmantes
                                    $recipentArray = json_decode($recipent, true);
                                      // Guarda el correo electrónico en el array
                                    $emailArray[] = User::where('id',$recipentArray["users_id"])->first()->email;

                                    $inputSign["users_name"] = $recipentArray["name"];
                                    $inputSign["state"] = "Pendiente de firma";
                                    $inputSign["observation"] = "";
                                    $inputSign["correspondence_internal_id"] = $id;
                                    $inputSign["users_id"] = $recipentArray["users_id"];
                                    $inputSign["correspondence_internal_versions_id"] = $idVersion->id;

                                    InternalSigns::create($inputSign);
                                }
                            }


                    break;

                    default:
                        # code...
                        break;
                }

            }




        if (!empty($input["require_answer"])) {
            switch ($input["require_answer"]) {
                case "Se requiere que esta correspondencia reciba una respuesta":
                    $input["responsable_respuesta_nombre"] = User::find($input["responsable_respuesta"])->fullname;
                    $input["estado_respuesta"] = 'Pendiente de tramitar';
                break;

                case "Responder a otra correspondencia":
                    $input["answer_consecutive_name"] = Internal::where('id', $input["answer_consecutive"])->value('consecutive');

                    if($input["state"] == 'Público'){

                        DB::table('correspondence_internal')
                        ->where('id', $input["answer_consecutive"])
                        ->update(['estado_respuesta' => 'Finalizado', 'answer_consecutive_name' => $input["consecutive"]]);
                    }
                    break;

                default:
                    $input["answer_consecutive_name"] = "";
            }
        }


            // Actualiza el registro
            $internal = $this->internalRepository->update($input, $id);

            $input["users_id"]=$userLogin->id;
            $input["users_name"]=$userLogin->fullname;
            $input['correspondence_internal_id'] = $internal->id;
            $input['observation_history'] = "Actualización de correspondencia";

            // Crea un nuevo registro de historial
            $this->internalHistoryRepository->create($input);
            //Obtiene el historial
            $internal->internalHistory;
            $internal->internalVersions;
            $internal->internalType;
            $internal->serieClasificacionDocumental;
            $internal->subserieClasificacionDocumental;
            $internal->oficinaProductoraClasificacionDocumental;
            $internal->internalCopy;
            $internal->internalCopyShares;
            $internal->internalRecipients;
            // Efectua los cambios realizados
            DB::commit();

            if(!empty($mailsArray)){
                $notificacion = $internal;
                $internal_id_encrypted = base64_encode($internal["id"]);
                $notificacion->link = '/correspondence/internals?qder='.$internal_id_encrypted;
                if ($internal->state == 'Público') {
                    $notificacion->mensaje =  "Le informamos que <strong>" . $internal->from . "</strong> ha publicado una nueva correspondencia interna titulada: <strong>\"" . $internal->title . " " . $internal->consecutive . "\"</strong>.";
                    $asunto = json_decode('{"subject": "Actualización: Correspondencia Interna '.$notificacion->consecutive.'"}');
                    SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario',$asunto,$notificacion,explode(",",$mailsArray),'Correspondencia interna');
                }

                else if ($internal->state == 'Elaboración') {
                    $user_aproved= User:: where('id',$internal->elaborated_now)->first();
                    $notificacion->recipient= $user_aproved->name;
                    $notificacion->mensaje = "Le informamos que <strong>" . $input["users_name"] . "</strong> ha enviado para elaboración la correspondencia interna titulada: <strong>" . $notificacion->title . " " . $internal->consecutive . "</strong>.<br>Con el siguiente comentario: \"<em>" . $internal->observation . "</em>\".";
                    $asunto = json_decode('{"subject": "Actualización: Correspondencia Interna '.$notificacion->consecutive.'"}');
                    SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario',$asunto,$notificacion,$user_aproved->email,'Correspondencia interna');
                }

                else if ($internal->state == 'Revisión') {
                    $user_review = User:: where('id',$internal->reviewd_now)->first();
                    $notificacion->mail= $user_review->email;
                    $notificacion->recipient= $user_review->name;
                    $notificacion->mensaje = "Le informamos que <strong>" . $input["users_name"] . "</strong> ha enviado para revisión la correspondencia interna titulada: <strong>" . $notificacion->title . " " . $internal->consecutive . "</strong>.<br>Con el siguiente comentario: \"<em>" . $internal->observation . "</em>\".";
                    $asunto = json_decode('{"subject": "Actualización: Correspondencia Interna '.$notificacion->consecutive.'"}');
                    SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario',$asunto,$notificacion,$user_review->email,'Correspondencia interna');
                }

                else if ($internal->state == 'Aprobación') {
                    $user_approved = User:: where('id',$internal->approved_now)->first();
                    $notificacion->mail= $user_approved->email;
                    $notificacion->recipient= $user_approved->name;
                    $notificacion->mensaje = "Le informamos que <strong>" . $input["users_name"] . "</strong> ha enviado para su aprobación la correspondencia interna titulada <strong>" . $notificacion->title . " " . $internal->consecutive . "</strong>.<br>Con el siguiente comentario: \"<em>" . $internal->observation . "</em>\".";
                    $asunto = json_decode('{"subject": "Actualización: Correspondencia Interna '.$notificacion->consecutive.'"}');
                    SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario',$asunto,$notificacion,$user_approved->email,'Correspondencia interna');
                }

                else if ($internal->state == 'Pendiente de firma') {
                    $notificacion->mensaje = "Le informamos que la correspondencia interna titulada <strong>" . $notificacion->title . " " . $internal->consecutive . "</strong> está pendiente de su firma.<br>El comentario adicional es: \"<em>" . $internal->observation . "</em>\".";
                    $asunto = json_decode('{"subject": "Actualización: Correspondencia Interna '.$notificacion->consecutive.'"}');
                    $notificacion->mail = $emailArray;
                    SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario',$asunto,$notificacion,$emailArray,'Correspondencia interna');

                }
            }


            return $this->sendResponse($internal->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {

            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
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
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine(), 'Consecutivo: '.($internal['consecutive'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico
     *
     * @author Erika Johana Gonzalez C. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateInternalRequest $request
     *
     * @return Response
     */
    public function updateFile($id, UpdateInternalRequest $request)
    {
        $input = $request->all();

        /** @var Internal $internal */
        $internal = $this->internalRepository->find($id);

        if (empty($internal)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida si no seleccionó ningún adjunto
        $input['document_pdf'] = isset($input["new_route"]) ? implode(",", $input["new_route"]) : null;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $internal = $this->internalRepository->update($input, $id);
            $userLogin = Auth::user();

            // $input["users_id"]=$userLogin->id;
            // $input["users_name"]=$userLogin->fullname;
            // $input['correspondence_internal_id'] = $internal->id;
            // $input['observation_history'] = "Actualización de correspondencia";

            // // Crea un nuevo registro de historial
            // $this->internalHistoryRepository->create($input);
            // //Obtiene el historial
            // $internal->internalHistory;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($internal->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine() . ' ID: ' . ($id ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    public function shareInternal(UpdateInternalRequest $request)
    {

        $input = $request->all();
        $id = $input["id"];

        $internal = $this->internalRepository->find($id);

        if (empty($internal)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Valida si viene usuarios para asignar
        if (!empty($input['internal_shares'])) {

            //borra todo para volver a insertarlo
            InternalCopyShare::where('correspondence_internal_id', $id)->where("type","=","Compartida")->delete();

            //texto para almacenar en la tabla interna
            $textRecipients = array();
            //recorre los destinatarios
            foreach ($input['internal_shares'] as $recipent) {
                //array de destinatarios
                $recipentArray = json_decode($recipent, true);
                $recipentArray["correspondence_internal_id"] = $id;
                $recipentArray["type"] = "Compartida";
                $recipentArray["name"] = $recipentArray["fullname"];
                $textRecipients[] = $recipentArray["fullname"];

                InternalCopyShare::create($recipentArray);

                $internal = Internal :: where ('id' , $id)->first();

                $asunto = json_decode('{"subject": "Notificación de correspondencia interna ' . $internal->consecutive . ' compartida"}');
                $email = User::where('id', $recipentArray['users_id'])->first()->email;
                $notificacion["consecutive"] = $internal->consecutive;
                $notificacion["id"] = $internal->id;
                $notificacion["name"] = $recipentArray["name"];
                $notificacion['mensaje'] = "Le informamos que se le ha compartido la correspondencia interna con radicado: <strong>{$internal->consecutive}</strong>. <br><br>Le agradecemos tener en cuenta esta notificación para los fines correspondientes";
                try {
                    SendNotificationController::SendNotification('correspondence::internals.email.plantilla_notificaciones',$asunto,$notificacion,$email,'Correspondencia interna');
                } catch (\Swift_TransportException $e) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\internalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                } catch (\Exception $e) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\internalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine() . ' ID: ' . ($id ?? 'Desconocido'));
                }
            }
            $updateInternal["shares"] = implode("<br>", $textRecipients);
            $internal = $this->internalRepository->update($updateInternal,$id);
        }else{

            //borra todo para volver a insertarlo
            InternalCopyShare::where('correspondence_internal_id', $id)->where('type', "Compartida")->delete();
            $updateInternal["shares"] = "";
            $internal = $this->internalRepository->update($updateInternal,$id);
        }

        if (!empty($input['annotation'])) {
            InternalAnnotation :: create([
                'correspondence_internal_id' => $id,
                'users_id'  => Auth::user()->id,
                'users_name' => Auth::user()->fullname,
                'content' => $input['annotation']
            ]);
        }
        $internal->internalShares;
        $internal->internalAnnotations;
        $internal->internalCopyShares;


        return $this->sendResponse($internal->toArray(), trans('msg_success_update'));
    }

    public function signInternal(UpdateInternalRequest $request)
    {

        // Inicia la transaccion
        DB::beginTransaction();
        try{
            $input = $request->all();
            // dd($input);
            $id = $input["id"];


            $internal = $this->internalRepository->find($id);

            if (empty($internal)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            if($input["type_send"] ==  "Devolver para modificaciones"){

                $input["state"] = "Devuelto para modificaciones";
                $input["user_from_last_update"] = Auth::user()->fullname;
                $input["user_for_last_update"] = $input["from"];
                $input["observation_inicial"] = $input['observations'];

                //obtiene la ultima version
                $dataVersion = json_decode($input['internal_versions'][0]);

                //Actualiza el estado de la version
                InternalVersions::where('id', $dataVersion->id)
                ->update(['state' =>  $input["state"]]);

                //Actualiza el estado de las firmas de la version
                InternalSigns::where('correspondence_internal_versions_id', $dataVersion->id)
                ->where('users_id', Auth::user()->id)
                ->update(['state' =>  $input["state"] , 'observation' =>  $input["observations"] ]);

                $internal = $this->internalRepository->update($input,$id);

                $input['correspondence_internal_id'] = $internal->id;
                // Crea un nuevo registro de historial
                $input['observation_history'] = "Devolución de correspondencia";
                $input['observation'] = $input['observations'];
                $input['users_name'] = Auth::user()->fullname;
                $input['users_id'] = Auth::user()->id;

                $this->internalHistoryRepository->create($input);

                // envia la notificación al devolver por no firmar.
                $notificacion =$internal;
                $internal_id_encrypted = base64_encode($internal["id"]);
                $notificacion->link = '/correspondence/internals?qder='.$internal_id_encrypted;
                $user_Returned = User:: where('id',$internal->from_id)->first();
                $notificacion->mail= $user_Returned->email;
                $notificacion->recipient= $user_Returned->name;
                $notificacion->mensaje = "Le informamos que <strong>" . $internal->from . "</strong> ha devuelto para modificaciones la correspondencia interna titulada <strong>" . $notificacion->title . " " . $internal->consecutive . "</strong>.<br>Con el siguiente comentario: \"<em>" . $input["observations"] . "</em>\".";
                $asunto = json_decode('{"subject": "Actualización: Correspondencia Interna '.$notificacion->consecutive.'"}');
                SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario',$asunto,$notificacion,$user_Returned->email,'Correspondencia interna');

            }else{

                //aprobar firma
                $dataVersion = json_decode($input['internal_versions'][0]);

                $signsActual = InternalSigns::where('correspondence_internal_versions_id', $dataVersion->id)
                ->where('state','Pendiente de firma')
                ->get()->toArray();

                // Se crea una cadena hash para identificar la firma del usuario en el documento
                $fullHash = hash('sha256', Auth::user()->id . $input["consecutive"]);
                $hash = "ID firma: ".substr(base64_encode(hex2bin($fullHash)), 0, 17);

                // Obtiene la IP del usuario en sesión
                $publicIp = $this->detectIP();

                // $cantidadFirmasFaltante = array_filter($dataVersion->internal_signs, function($item){
                //     return $item->state == 'Pendiente de firma';
                // });
                $userLogin = Auth::user();

                $mailsArray=[];
                //solo falta una firma, la suya
                if (count($signsActual) == 1){

                    // $request->merge(['tipo' => 'publicacion']);
                    // $this->update($id,$request);



                    $informationUser = User::select("name","id_dependencia")->where('id', $input["from_id"])->first();


                    $infoDependencia = Dependency::where('id', $informationUser["id_dependencia"])->first();

                    $input["dependencias_id"] = $infoDependencia["id"];
                    $input["dependency_from"] = $infoDependencia["nombre"];
                    $typeInternal = InternalTypes::where('id', $input["type"])->first();

                    $input["type_document"] = $typeInternal->name;
                    //DP
                    $DP = Dependency::where('id', $input["dependencias_id"])->pluck("codigo")->first();

                    $siglas = $infoDependencia["codigo_oficina_productora"] ?? '';

                    //PL
                    $PL = InternalTypes::where('id', $input["type"])->pluck("prefix")->first();


                    //Consulta las variables para calcular el consecutio.
                    $formatConsecutive = Variables::where('name' , 'var_internal_consecutive')->pluck('value')->first();
                    $formatConsecutivePrefix = Variables::where('name' , 'var_internal_consecutive_prefix')->pluck('value')->first();


                    //Llamado a la funcion que calcula el proximo consecutivo y retorna el consecutivo y numero de order
                    $dataConsecutive = UtilController::getNextConsecutive('Internal',$formatConsecutive,$formatConsecutivePrefix,$DP,$PL,$siglas);
                    $input["consecutive"] = $dataConsecutive['consecutive'];
                    $input["consecutive_order"] = $dataConsecutive['consecutive_order'];
                    // Actualiza el registro
                    $internal = $this->internalRepository->update(['consecutive'=>$input["consecutive"], 'consecutive_order'=>$input["consecutive_order"]], $id);

                    //estaod publico
                    $input["state"] = "Público";
                    $variables = $typeInternal->variables;

                    $information["#consecutivo"] = $input["consecutive"];
                    $information["#titulo"] = $input["title"];
                    // Almacena los usuarios remitentes que firmaron el documento
                    $usuarios_remitentes = [];
                    // foreach($dataVersion->internal_signs as $usuario) {
                    //     // Se obtiene el nombre de usuario y se asigna al arreglo de remitentes
                    //     // $usuarios_remitentes[] = $usuario->users->name;
                    //     $informationUser = User::where('id', $usuario->users->id)->first();

                    //     $usuarios_remitentes[] = $informationUser->fullname;
                    // }

                    // Valida si viene usuarios para asignar

                    if (!empty($input['internal_recipients'])) {
                    //borra todo para volver a insertarlo
                        InternalRecipient::where('correspondence_internal_id', $id)->delete();

                        //texto para almacenar en la tabla interna
                        $textRecipients = array();
                        //recorre los destinatarios

                        // if (!empty($input['internal_recipients'])) {
                            $datosDestinatarios = $this->procesarDestinatariosYcopias($input, $id);

                            $input["recipients"] = $datosDestinatarios["recipients"] ?? '';
                            $input["copies"] = $datosDestinatarios["copies"] ?? '';
                            $mailsArray = $datosDestinatarios["mailsArray"] ?? [];
                        // }


                    } else {

                        //borra todo para volver a insertarlo
                        InternalRecipient::where('correspondence_internal_id', $id)->delete();
                        $input["recipients"]="Todos";
                    }


                    if(!empty($input["firma_desde_componente"]) && $input["firma_desde_componente"]!='undefined' && (empty($input["usar_firma_cargada"]) || $input["usar_firma_cargada"] == 'No')){

                        $userLogin->url_digital_signature = $input["firma_desde_componente"];
                    }else{

                         // Valida si el usuario posee una firma para la publicación del documento
                        if(!$userLogin->url_digital_signature){
                            // Retorna mensaje de error informando que el usuario no puede publicar y firmar el documento
                            return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                        }else{
                            if (!file_exists(storage_path("app/public/".$userLogin->url_digital_signature))) {
                                return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                            }
                        }

                    }
                    $input["firma"] = $userLogin->url_digital_signature;

                    //actualiza registro de firma del usuario en sesion
                    InternalSigns::where('correspondence_internal_versions_id', $dataVersion->id)
                    ->where('users_id', Auth::user()->id)
                    ->update(['firma' =>  $input["firma"], 'state' =>  "Firma aprobada", 'observation' =>  $input["observations"] ,'hash' => $hash, 'ip' => $publicIp]);

                    $firmasFinales=[];

                    $contadorFirmas = InternalSigns::where('correspondence_internal_versions_id', $dataVersion->id)->with('users')->get();
                        $firmasFinales = [];

                        foreach ($contadorFirmas as $datosFirma) {

                            if($datosFirma["firma"]){

                                $datosFirma->users->url_digital_signature = $datosFirma["firma"];

                            }else{

                                $dataUser = User::where('id',$datosFirma->users->id)->get()->first()->toArray();

                                if (!$dataUser['url_digital_signature']) {
                                    return $this->sendErrorData("El usuario ".$datosFirma->users->name." no tiene una firma digital habilitada. Debe adjuntar una para continuar con el proceso de firma.",'warning');
                                }

                                if ($dataUser['autorizado_firmar'] == 0) {
                                    return $this->sendErrorData("El usuario ".$datosFirma->users->name." no está autorizado para firmar. Debe estar autorizado para continuar con el proceso de firma.",'warning');
                                }

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

                    // dd($usuarios_remitentes);

                    $dependenciaInformacion = Dependency::where('id', $input["dependencias_id"])->first();


                    $information["#firmas"] = $firmasFinales;

                    // Campo de remitentes que se muestra en el listado
                    $input["from"] = implode(" - ", $usuarios_remitentes);
                    // Se pone como remitentes, todos los usuarios que firmaron el documento
                    $information["#remitente"] = $input["from"];
                    $information["#dependencia_remitente"] = $input["dependency_from"];
                    $information["#destinatarios"] = $this->formatDestinatarios($input["recipients"] ?? null);
                    $information["#contenido"] = isset($input["content"]) ? $input["content"] : "";
                    $information["#anexos"] = $input["annexes_description"] ?? "No aplica";
                    $information["#tipo_documento"] = $input["type_document"];

                    $elaborated = $input["elaborated_names"] ?? null;
                    $reviewed   = $input["reviewd_names"] ?? null;
                    $approved   = $input["approved_names"] ?? null;
                    $copies     = $input["copies"] ?? null;
                    $information["#elaborado"] = UtilController::formatTextByVariable($elaborated,'Otros');
                    $information["#revisado"]  = UtilController::formatTextByVariable($reviewed ?? $approved ?? $information["#remitente"] ?? $elaborated, 'Otros');
                    $information["#aprobado"]  = UtilController::formatTextByVariable($approved ?? $information["#revisado"], 'Otros');
                    $information["#proyecto"]  = UtilController::formatTextByVariable($elaborated,'Otros');
                    $information["#copias"]    = UtilController::formatTextByVariable($copies, 'Otros');



                    $information["#respuesta_correspondencia"] = $input["answer_consecutive_name"] ?? "No aplica";
                    $information["#codigo_dependencia"] = $DP;
                    $information["#direccion"] = $dependenciaInformacion["direccion"];
                    $information["#dep_piso"] = $dependenciaInformacion["piso"];
                    $information["#codigo_postal"] = $dependenciaInformacion["codigo_postal"];
                    $information["#telefono"] = $dependenciaInformacion["telefono"];
                    $information["#dep_ext"] = $dependenciaInformacion["extension"];
                    $information["#dep_correo"] = $dependenciaInformacion["correo"];
                    $information["#logo"] = $dependenciaInformacion["logo"];
                    // $information["#firmas"] = $dataVersion->internal_signs;
                    // dd($dataVersion->internal_signs);

                    setlocale(LC_ALL,"es_ES");
                    $information["#fecha"] = strftime("%d de %B del %Y");
                    $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                    // Genera un código de verificación único para cada documento
                    $input["validation_code"] = substr(str_shuffle($caracteres_permitidos), 0, 8);
                    $information["#codigo_validacion"] = $input["validation_code"];

                    $id_google = explode("/", $input["template"]);
                    $id_google = end($id_google);


                    $google = new GoogleController();
                    $returnGoogle = $google->editFileDoc("Interna", $id, $id_google, explode(",", $variables), $information, 0);
                    if($returnGoogle['type_message'] == 'info') {
                        // Devuelve los cambios realizados
                        DB::rollback();
                        // Retorna mensaje de error de base de datos
                        return $this->sendSuccess($returnGoogle['message'], 'info');
                    }
                    $documento_almacenado = $google->saveFileGoogleDrive($id_google, "pdf", $input["consecutive"], "container/internal_".date('Y'));
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

                    if (!empty($input["require_answer"])) {
                        switch ($input["require_answer"]) {
                            case "Se requiere que esta correspondencia reciba una respuesta":
                                $input["responsable_respuesta_nombre"] = User::find($input["responsable_respuesta"])->fullname;
                                $input["estado_respuesta"] = 'Pendiente de tramitar';

                            break;

                            case "Responder a otra correspondencia":
                                $input["answer_consecutive_name"] = Internal::where('id', $input["answer_consecutive"])->value('consecutive');

                                DB::table('correspondence_internal')
                                ->where('id', $input["answer_consecutive"])
                                ->update(['estado_respuesta' => 'Finalizado', 'answer_consecutive_name' => $input["consecutive"]]);

                            break;

                            default:
                                $input["answer_consecutive_name"] = "";
                        }
                    }


                    $internal = $this->internalRepository->update($input,$id);

                    $history = $input;
                    $history['observation_history'] = "Actualización de correspondencia";
                    $history['observation'] = $input['observations'];
                    $history['correspondence_internal_id'] = $id;
                    $history['users_name'] = Auth::user()->fullname;
                    $history['users_id'] = Auth::user()->id;

                    // $history['user_for_last_update'] = User :: select('name')->where('id',$input['from_id'])->first()->name;

                    // Crea un nuevo registro de historial
                    $this->internalHistoryRepository->create($history);

                    if(!empty($mailsArray)){
                        $notificacion = $internal;
                        $notificacion->mail= $mailsArray;
                        $notificacion->recipient= $input["recipients"] ;
                        $internal_id_encrypted = base64_encode($internal["id"]);
                        $notificacion->link = '/correspondence/internals?qder='.$internal_id_encrypted;

                        $notificacion->mensaje = "Se ha publicado la correspondencia interna con el consecutivo <strong>{$input['consecutive']}</strong> y el título <strong>{$notificacion->title}</strong>, publicada por los funcionarios <strong>{$notificacion->from}</strong>.";
                        // $notificacion -> mensaje = "Le informamos que <strong>" . $internal->from . "</strong>, le ha compartido la correspondencia interna: <strong> " . $notificacion->title . '<strong> ' . $input["consecutive"];
                        $asunto = json_decode('{"subject": "Actualización: Correspondencia Interna '.$input["consecutive"].'"}');
                        SendNotificationController::SendNotification('correspondence::internals.email.notificacion_funcionario',$asunto,$notificacion,explode(",",$mailsArray),'Correspondencia interna');
                    }
                }else{


                    if(!empty($input["firma_desde_componente"]) && $input["firma_desde_componente"]!='undefined' && (empty($input["usar_firma_cargada"]) || $input["usar_firma_cargada"] == 'No')){

                        $userLogin->url_digital_signature = $input["firma_desde_componente"];
                    }else{

                         // Valida si el usuario posee una firma para la publicación del documento
                        if(!$userLogin->url_digital_signature){
                            // Retorna mensaje de error informando que el usuario no puede publicar y firmar el documento
                            return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                        }else{
                            if (!file_exists(storage_path("app/public/".$userLogin->url_digital_signature))) {
                                return $this->sendSuccess('<strong>¡Atención! Falta de Firma</strong><br /><br />La firma es un requisito obligatorio para garantizar la autenticidad y veracidad de las acciones realizadas en nuestra plataforma. Para resolver esta situación, le pedimos que <a href="/profile" target="_blank">edite su perfil</a> y suba una firma válida.', 'info');
                            }
                        }

                    }

                    $input["firma"] =  $userLogin->url_digital_signature;

                    //actualiza registro de firma del usuario en sesion
                    InternalSigns::where('correspondence_internal_versions_id', $dataVersion->id)
                    ->where('users_id', Auth::user()->id)
                    ->update(['firma' =>  $input["firma"] ,'state' =>  "Firma aprobada", 'observation' =>  $input["observations"] ,'hash' => $hash, 'ip' => $publicIp]);


                    $history = $input;
                    $history['observation'] = $input['observations'];
                    $history['observation_history'] = "Firma aprobada";
                    $history['correspondence_internal_id'] = $id;
                    $history['users_name'] = Auth::user()->fullname;
                    $history['users_id'] = Auth::user()->id;

                    // $history['user_for_last_update'] = User :: select('name')->where('id',$input['from_id'])->first()->name;

                    $this->internalHistoryRepository->create($history);
                }
            }

            $internal->internalHistory;
            $internal->internalVersions;

            // Efectua los cambios realizados
            DB::commit();
            return $this->sendResponse($internal->toArray(), trans('msg_success_update'));

        }
        catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
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
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '.($internal['consecutive'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    public function asignarFirmas($firmas)
    {

        $arrayRemitentes = array();
        $arrayRemitentesIds = array();

        $datosFirmado = (array_filter($datosFirmado));

        $arrayDatosFirmado = array();
        foreach ($datosFirmado as $key => $firmado_id) {
            $db->setQuery("SELECT * FROM `intranet_intranet_usuario` WHERE userid=".$firmado_id["cf_user_id"]);
            $datosDestinatario = $db->loadObject();

            $arrayDatosFirmado[] = $datosDestinatario;

        }

        //Crea tablA
        $table = new Table(array('width' => 8000, 'unit' => TblWidth::TWIP));

        $numeroFirmas = count($arrayDatosFirmado);

        $contadorF = 1;
        $contadorNF = 1;
        $totalFilas = $numeroFirmas + 1;

        for ($c = 1; $c <= $totalFilas; $c++) {

        //IMPAR
            if ($c%2!=0) {
                if ($contadorF<$numeroFirmas) {
                    $table->addRow();

                    for ($j=0; $j < 2; $j++) {
                        $cell = $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(1.25), [
                    'valign' => 'bottom',

                    ]);
                        $cell->addText('${F'.$contadorF.'}');
                        // $cell->addText('${F'.$contadorF.'}', null, [
                        //     'align' => 'center'
                        // ]);

                        $contadorF++;
                    }
                } else {
                    if ($contadorF-1 !== $numeroFirmas) {
                        $table->addRow();

                        $cell = $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(1.25), [
                    'valign' => 'bottom',
                    'align' => 'center'

                    ]);
                        $cell->addText('${F'.$contadorF.'}');
                        // $cell->addText('${F'.$contadorF.'}', null, [
                        //     'align' => 'center'
                        // ]);

                        $contadorF++;
                    }
                }
            } else {
                // $table->addCell(1750)->addText('${F0}');
                // $table->addCell(1750)->addText('${F1}');

                if ($contadorNF<$numeroFirmas) {
                    $table->addRow();

                    for ($i=0; $i < 2; $i++) {
                        $cell = $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(0), [
                    'valign' => 'center'
                ]);

                        $myFontStyle = array('name' => 'Arial', 'size' => 11);

                        $cell->addText('${NF'.$contadorNF.'}', $myFontStyle);

                        $contadorNF++;
                    }
                } else {
                    $table->addRow();

                    $cell = $table->addCell(\PhpOffice\PhpWord\Shared\Converter::inchToTwip(0), [
                    'valign' => 'center'
                ]);

                    $myFontStyle = array('name' => 'Arial', 'size' => 11);

                    $cell->addText('${NF'.$contadorNF.'}', $myFontStyle);


                    $contadorNF++;
                }
            }
        }

        $templateWord->setComplexBlock('firmas', $table);

        $contador = 1;

        foreach ($arrayDatosFirmado as $key => $value) {

            $arrayRemitentes[] = $value->nombre;
            $arrayRemitentesIds[] = $value->userid;


            $templateWord->setImageValue("F".$contador, $value->firma);

            $templateWord->setValue("NF".$contador, $value->nombre."\n".$value->cargo);

            $contador++;
        }
        $templateWord->setValue("nombre_funcionario","");

    }

    /**
     * Elimina un Internal del almacenamiento
     *
     * @author Erika Johana Gonzalez C. - Abr. 06 - 2020
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

        /** @var Internal $internal */
        $internal = $this->internalRepository->find($id);

        if (empty($internal)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            if($internal["state"] == 'Público' && $internal["origen"] != 'FISICO'){
                return $this->sendSuccess('<strong>Esta correspondencia ya ha sido publicada, por lo tanto no es posible eliminarla.</strong><br>Por favor, cierre este modal y actualice la página para visualizar el estado más reciente de la correspondencia.','warning');
            } else {
                // Elimina el registro
                $internal->delete();
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine(). ' Consecutivo: '.($internal['consecutive'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Erika Johana Gonzalez C. - May. 08 - 2020
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
        $fileName = time().'-'.trans('internals').'.'.$fileType;
        // Codifica los filtros y se lo asigna a la variable 'f'
        $request["f"] = base64_encode($request["filtros"]);
        // Preparar filtros
        $filtros = $this->prepararFiltros($request);
        // Verificar el rol del usuario y manejar la lógica de acuerdo con su rol
        if (Auth::user()->hasRole('Correspondencia Interna Admin')) {
            $data = JwtController::decodeToken($this->consultasAdmin(null, $filtros)["data"]);
        } else {
            $data = JwtController::decodeToken($this->consultasFuncionario(null, $filtros)["data"]);
        }
        
        array_walk($data, fn(&$object) => $object = (array)$object);

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {

                //nueva clave para lista de correspondencia
                $input['correspondence_interna'] = [];

                //ciclo para agregar las correspondencia a los usuarios
                foreach($data as $item){
                    //objecto vacio para incluir elementos necesarios
                    $object_interna = new stdClass;
                    $object_interna->consecutive = $item['consecutive'];
                    $object_interna->state = $item['state'];
                    $object_interna->recipients=$item['recipients'] ?? '';
                    $object_interna->from = $item['from'];
                    $object_interna->plantilla = !empty($item["internal_type"]->name) ? $item["internal_type"]->name : ''; // Utiliza -> para acceder a la propiedad del objeto
                    $object_interna->created_at= $item['created_at'];
                    $object_interna->origen = $item['origen'];
                    $object_interna->modification = !empty($item["internal_history"][0]->created_at) ? $item["internal_history"][0]->created_at : ''; // Utiliza -> para acceder a la propiedad del objeto

                    $input['correspondence_interna'][] = $object_interna;

                }

                //elimina el atributo data
                unset($input['data']);
                //count de correspondencia interna
                $count = count($input['correspondence_interna']);


            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new RequestExportLandscape('correspondence::internals.report_pdf',JwtController::generateToken($input['correspondence_interna']), 'h'), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
            // return Excel::download(new GenericExport($input['correspondence_interna']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } elseif (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel


            $info_radicadores = array();

            //Recorrer el array que trae todos los datos de las radicaciones
            foreach($data as $dato){
                //Almacenar al array los nombres de los radicadores
                $info_radicadores[] = $dato['users_id'];
            }


            $dataInterna = [];

            $dataInternaCount = 0;

            $cantidad_radicaciones = 0;

            foreach (array_unique($info_radicadores) as $radicador) {

                $informationUser = User::select("name")->where('id', $radicador)->first();

                $dataInterna[$dataInternaCount]['radicador'] = $informationUser['name'] ?? "N/A";


                $numero_radicaciones = 0;

                //Recorrer array para obtener el número de radicaciones del funcionario
                foreach($data  as $dato){
                    if ($dato["users_id"] == $radicador) {
                        //Código para incrementar el número de radicaciones
                        $dataInterna[$dataInternaCount]['internas'][$numero_radicaciones] = $dato;
                        $numero_radicaciones ++;
                        $cantidad_radicaciones ++;
                    }
                }

                $dataInterna[$dataInternaCount]['total'] = $numero_radicaciones;
                $dataInternaCount++;

            }


            //count de correspondencia interna
            $count = count($dataInterna) +  $cantidad_radicaciones + (count($dataInterna) * 8);
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');

            // Descarga el archivo generado
            return Excel::download(new RequestExportCorrespondence('correspondence::internals.report_excel',$dataInterna,$count,'K'), $fileName);
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Seven
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function exportRepositoryCorrespondenceInternal( Request $request )
    {
        $input = $request->all();

        $userid = Auth::user()->user_joomla_id;

        $likedes1 = '%"id":"'.$userid.'"%';

        $likedes2 = '%"id":'.$userid.',%';

        $likedes3 = '%"id":'.$userid.'}%';

        $date = date("Y");

        $table = '';

        //valida a que tabla realizar la consulta
        if (isset($input['vigencia']) && $input['vigencia'] != $date && $input['vigencia'] != '2024') {
            $table = "interna_".$input['vigencia'];
        }else{
            $table = "interna";
        }

        //Valida si el usuario en linea tiene el rol de administrador interna admin
        if (Auth::user()->hasRole('Correspondencia Interna Admin')) {

            $querys = DB::connection('joomla')->table($table);

        }else{
            // Si la variable request no tiene ningún parámetro de paginado y consulta, hace la consulta normal (convencional)
            $querys = DB::connection('joomla')->table($table)->where(function($query) use ($userid, $likedes1, $likedes2, $likedes3) {
                $query->where('copia', 'LIKE', $userid)
                    ->orWhere('copia', 'LIKE', $userid . ',%')
                    ->orWhere('copia', 'LIKE', '%,' . $userid . ',%')
                    ->orWhere('copia', 'LIKE', '%,' . $userid)
                    // ->orWhere('funcionarios_destinatarios_ids', 'LIKE', $likedes1)
                    // ->orWhere('funcionarios_destinatarios_ids', 'LIKE', $likedes2)
                    // ->orWhere('funcionarios_destinatarios_ids', 'LIKE', $likedes3)
                    ->orWhere('compartida', 'LIKE', $userid)
                    ->orWhere('compartida', 'LIKE', $userid . ',%')
                    ->orWhere('compartida', 'LIKE', '%/' . $userid . '/%')
                    ->orWhere('compartida', 'LIKE', '%,' . $userid . ',%')
                    ->orWhere('compartida', 'LIKE', '%,' . $userid)
                    // ->orWhere('creado_por_funcionario_id', 'LIKE', '%,' .$userid)
                    // ->orWhere('revisado_por_ids', 'LIKE', '%,' .$userid)
                    ->orWhere('aprobado_por_ids', 'LIKE', '%,' .$userid);
            });
        }

        //Valida si la consulta trae filtros
        if (isset($input['filtros'])) {
            $interna = $querys->whereRaw($input['filtros'])->orderBy('cf_created', 'DESC')->get()->toArray();
        } else {
            $interna = $querys->orderBy('cf_created', 'DESC')->get()->toArray();
        }

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // $fileName = date('Y-m-d H:i:s').'-'.trans('request_authorizations').'.'.$fileType;
        $fileName = 'setting.' . $fileType;
        return Excel::download(new RequestExport('correspondence::internals.reports.report_excel',JwtController::generateToken($interna),'j'), $fileName);

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
        $query =$request->input('query');
        $searchQuery = $query;
        // $users = DB::table('users')
        // ->select(DB::raw('CONCAT("Usuario ", users.name) AS name, users.id as users_id, concat("usuario",users.id) as id, "Usuario" AS type'))
        // ->where('users.name', 'like', '%'.$query.'%')
        // ->whereNotNull('users.id_cargo')
        // ->join('cargos', 'cargos.id', '=', 'users.id_cargo')
        // ->get();

        $users = User::where(function ($query) use ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhereHas('dependencies', function ($subQuery) use ($searchQuery) {
                    // Hace una subconsulta la cual busca tambien por lo que query sea a el nombre de la dependencia
                      $subQuery->where('nombre', 'like', '%' . $searchQuery . '%');// Obtiene todos los usuarios que tengan esa dependencias asociada
                  });
        }) // Filtra por nombre que contenga el valor de $query
        ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
        ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
        ->where('id_cargo', '!=', 0)
        ->where('id_dependencia', '!=', 0)
        ->get() // Realiza la consulta y obtiene una colección de usuarios
        ->map(function ($user) {
            $user->users_id = $user->id;
            $user->type = 'Usuario';
            $user->users_email = $user->email;
            // Agrega el atributo 'fullname' a cada instancia de usuario
            $user->name = $user->fullname; // Accede al mutador para calcular el fullname
            return $user; // Retorna el usuario con el nuevo atributo 'fullname'
        });

        // Grupos
        $query =$request->input('query');
        $grupos = DB::table('work_groups')
        ->select(DB::raw('CONCAT("Grupo ", work_groups.name) AS name, work_groups.id as work_groups_id, concat("grupo",work_groups.id) as id, "Grupo" AS type, "" AS email'))
        ->where('work_groups.name', 'like', $query.'%')
        ->where('work_groups.state', '=', 1)
        ->get();

        // Dependencias
        $query =$request->input('query');
        $dependencias = DB::table('dependencias')
        ->select(DB::raw('CONCAT("Dependencia ", dependencias.nombre) AS name, dependencias.id as dependencias_id, concat("dependencia",dependencias.id) as id, "Dependencia" AS type, "" AS email'))
        ->where('dependencias.nombre', 'like', $query.'%')
        ->get();

        // Cargos
        $query =$request->input('query');
        $position = DB::table('cargos')
        ->select(DB::raw('CONCAT("Cargo ", cargos.nombre) AS name, cargos.id as cargos_id, concat("cargo",cargos.id) as id, "Cargo" AS type,"" AS email'))
        ->where('cargos.nombre', 'like', $query.'%')
        ->get();

        $recipients = array_merge($users->toArray(), $grupos->toArray(), $dependencias->toArray(), $position->toArray());

        // dd($recipients);
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
        // Se inicializa la variable con el $query para que no tenga problemas al momento de usarla en la funcion
        $searchQuery = $query;
        // Filtra a todos los usuarios en donde el nombre coincida
        $users = User::where(function ($query) use ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhereHas('dependencies', function ($subQuery) use ($searchQuery) {
                    // Hace una subconsulta la cual busca tambien por lo que query sea a el nombre de la dependencia
                      $subQuery->where('nombre', 'like', '%' . $searchQuery . '%');// Obtiene todos los usuarios que tengan esa dependencias asociada
                  });
        })
        ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
        ->where('id_cargo', '!=', 0)
        ->where('id_dependencia', '!=', 0)
        ->where("is_assignable_pqr_correspondence",0)
        ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
        ->get(); // Realiza la consulta y obtiene una colección de usuarios
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    public function getOnlyUsersSign(Request $request)
    {
        // Usuarios
        $query = $request->input('query');
        //consulta inicial, se documenta por si se vuleve a necesitar.
        // $users = DB::table('users')
        //     ->select(DB::raw('CONCAT("Usuario ", users.name) AS name, users.id as users_id, "Usuario" AS type'))
        //     ->where('users.name', 'like', '%' . $query . '%')
        //     ->whereNotNull('users.id_cargo')
        //     ->where('users.block', '!=', 1) // Agrega la condición block != 1
        //     ->where('users.autorizado_firmar', 1) // Agrega la condición autorizado_firmar = 1
        //     ->join('cargos', 'cargos.id', '=', 'users.id_cargo')
        //     ->get();


        // $users = User::where('name', 'like', '%' . $query . '%') // Filtra por nombre que contenga el valor de $query
        // ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
        // ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
        // ->where('autorizado_firmar', 1) // Filtra los registros donde 'autorizado_firmar' sea igual a 1
        // ->get() // Realiza la consulta y obtiene una colección de usuarios
        // ->map(function ($user) {
        //     $user->users_id = $user->id;

        //     // Agrega el atributo 'fullname' a cada instancia de usuario
        //     $user->fullname = $user->fullname; // Accede al mutador para calcular el fullname
        //     return $user; // Retorna el usuario con el nuevo atributo 'fullname'
        // });

        $searchQuery = $query;
        // Filtra a todos los usuarios en donde el nombre coincida
        $users = User::where(function ($query) use ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhereHas('dependencies', function ($subQuery) use ($searchQuery) {
                    // Hace una subconsulta la cual busca tambien por lo que query sea a el nombre de la dependencia
                      $subQuery->where('nombre', 'like', '%' . $searchQuery . '%');// Obtiene todos los usuarios que tengan esa dependencias asociada
                  });
        })
        ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
        ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
        ->where('id_cargo', '!=', 0)
        ->where('id_dependencia', '!=', 0)
        ->where('autorizado_firmar', 1) // Filtra los registros donde 'autorizado_firmar' sea igual a 1
        ->where('url_digital_signature', '!=', '') // Asegura que el campo 'url_digital_signature' no esté vacío
        ->whereNotNull('url_digital_signature')
        ->where("is_assignable_pqr_correspondence",0)
        ->get() // Realiza la consulta y obtiene una colección de usuarios
        ->map(function ($user) {
            $user->users_id = $user->id;

            // Agrega el atributo 'fullname' a cada instancia de usuario
            $user->fullname = $user->fullname; // Accede al mutador para calcular el fullname
            return $user; // Retorna el usuario con el nuevo atributo 'fullname'
        });



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

        $types = InternalTypes::orderBy("name")->get()->toArray();

        return $this->sendResponse($types, trans('data_obtained_successfully'));
    }

    public function guardarAdjuntoRotulo($id, Request $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();

        try {
            $interna = Internal::where("id", $id)->first()->toArray();

            // Valida y almacena el nuevo adjunto
            if ($request->hasFile('document_pdf')) {
                $input['document_pdf'] = substr($input['document_pdf']->store('public/container/internal_'.date("Y")), 7);
            }
            // Agrega el nuevo adjunto al array de adjuntos si ya existen adjuntos previos
            $input['document_pdf'] = $interna['document_pdf'] ? implode(",", array_merge(explode(",", $interna['document_pdf']), [$input['document_pdf']])) : $input['document_pdf'];

            // Actualiza la correspondencia externa
            $internal_rotule = $this->internalRepository->update($input, $id);
            // // Modifica los datos de la correspondencia externa
            $interna['correspondence_internal_id'] = $id;
            $interna['observation_history'] = "Actualización de correspondencia";
            $interna['document_pdf'] = $input['document_pdf'];

            // Crea un nuevo registro en el historial
            $this->internalHistoryRepository->create($interna);

            // Obtiene el historial después de crear el nuevo registro
            $internal_rotule->internalHistory;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($internal_rotule->toArray(), trans('msg_success_update'));

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine() . ' ID: ' . ($id ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($internal_rotule->toArray(), trans('msg_success_save'));
    }

    public function readCheck($correspondenceId,$estado)
    {
        $userLogin = Auth::user();

        $exist = InternalChequeos::where('users_id',$userLogin->id)->where('correspondence_internal_id', $correspondenceId)->exists();

        if ($exist) {

            InternalChequeos::where('users_id',$userLogin->id)->where('correspondence_internal_id', $correspondenceId)->update([
                'estado_check' => $estado
            ]);

        }else{
            InternalChequeos::create([
                'users_id' => $userLogin->id,
                'correspondence_internal_id' => $correspondenceId,
                'user_name' => $userLogin->name,
                'fullname' => $userLogin->fullname,
                'estado_check' => 'Si'
            ]);
        }

        $data = ['status_permission_check' => $estado];

        return $this->sendResponseAvanzado($data, trans('msg_success_update'));

    }

    public function read($correspondenceId) {

        $userLogin = Auth::user();

        $readCorrespondence = InternalRead::select("id", "access", 'users_name')->where("correspondence_internal_id", $correspondenceId)->where("users_id", $userLogin->id)->where("users_name", $userLogin->fullname)->first();
        if($readCorrespondence) {
            // Valida si ya tiene accesos
            if($readCorrespondence["access"]) {
                $accesos = $readCorrespondence["access"]."<br/>".date("Y-m-d H:i:s");
            } else {
                $accesos = date("Y-m-d H:i:s");
            }
            // Actualiza los accesos del leido
            $resultReadCorrespondence = InternalRead::where("id", $readCorrespondence["id"])->update(["access" => $accesos], $readCorrespondence["id"]);
        } else {
            $readCorrespondence = date("Y-m-d H:i:s");

            // Valida si es el usuario que esta leyendo el registro, tiene el rol de administrador
            if(Auth::user()->hasRole('Correspondencia Interna Admin')) {
                $rol = "Administrador";
            } else {
                $rol = "Funcionario";
            }
            // Crea un registro de leido del registro
            $resultReadCorrespondence = InternalRead::create([
                'users_name' => Auth::user()->fullname,
                'users_type' => $rol,
                'access' => $readCorrespondence,
                'year' => date("Y"),
                'correspondence_internal_id' => $correspondenceId,
                'users_id' => Auth::user()->id
            ]);
        }

            // Obtener el ID del usuario actual
            $userId = Auth::id();

            // Actualizar los registros directamente en la base de datos
            InternalAnnotation::where('correspondence_internal_id', $correspondenceId)
                ->where(function ($query) use ($userId) {
                    $query->where('leido_por', null) // Si el campo 'leido_por' es null, establece el ID del usuario actual
                        ->orWhere('leido_por', 'not like', '%,' . $userId . ',%') // Si ya contiene el ID del usuario actual, no lo actualiza
                        ->orWhere('leido_por', 'not like', $userId . ',%'); // También para el caso donde el ID del usuario esté al principio seguido de una coma
                })
                ->update(['leido_por' => \DB::raw("CONCAT_WS(',', COALESCE(leido_por, ''), '$userId')")]);

            // Buscar y obtener la instancia de Internal correspondiente
            $internal = $this->internalRepository->find($correspondenceId);

            // Cargar ansiosamente las anotaciones pendientes asociadas a la instancia de Internal
            $internal->anotacionesPendientes;

            // Devolver una respuesta con los datos de la instancia de Internal actualizados
            return $this->sendResponse($internal->toArray(), trans('msg_success_update'));


        // return $this->sendResponse($resultReadCorrespondence, "Correspondencia leida con éxito");
    }

    public function crearInternaCeroPapeles(Request $request) {
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
        $input["from_id"]= $userLogin->id;


        //datos de la dependencia del usuario remitente
        $infoDependencia = Dependency::where('id', $userLogin->id_dependencia)->first();
        // dd($userLogin->id_dependencia,  $infoDependencia);
        $input["dependencias_id"] = $infoDependencia["id"] ;
        $input["dependency_from"] = $infoDependencia["nombre"] ;

        //PL
        $tipo_documento = InternalTypes::where('id', $input["type"])->first();
        $input["type_document"] = $tipo_documento["name"];

        //si es editor web
        if($input["editor"]=="Web"){
            $tipo_documento["template"] = $tipo_documento["template_web"];
        }



        if(!$tipo_documento["template"]){
            // Valida si el usuario es administrador de correspondencia interna
            if(Auth::user()->hasRole('Correspondencia Interna Admin')) {
                // Si es un administrador le muestra el mensaje de advertencia con el link de acceso a la configuración de tipos de plantillas de interna
                return $this->sendSuccess('<strong>¡Atención! Configuración de Plantillas Requerida</strong><br /><br />Es necesario configurar primero la plantilla desde la opción <a href="internal-types" target="_blank">Tipos documentales Interna</a>. Esta configuración es esencial para crear documentos.', 'info');
            } else {
                // Si es un usuario funcionario, le muestra el mensaje de información indicándole que se comunique con el administrador
                return $this->sendSuccess('Lamentamos informarte que este tipo de documento no tiene una plantilla configurada actualmente. Por favor, te solicitamos que te comuniques con el administrador del sistema para resolver este inconveniente.', 'info');
            }
        }



         // First, check if the file exists
         if (!file_exists(storage_path("app/public/".$tipo_documento["template"]))) {

              // Valida si el usuario es administrador de correspondencia interna
              if(Auth::user()->hasRole('Correspondencia Interna Admin')) {
                // Si es un administrador le muestra el mensaje de advertencia con el link de acceso a la configuración de tipos de plantillas de interna
                return $this->sendSuccess('<strong>¡Atención! Configuración de Plantillas Requerida</strong><br /><br />Es necesario configurar primero la plantilla desde la opción <a href="internal-types" target="_blank">Tipos documentales Interna</a>. Esta configuración es esencial para crear documentos.', 'info');
            } else {
                // Si es un usuario funcionario, le muestra el mensaje de información indicándole que se comunique con el administrador
                return $this->sendSuccess('Lo sentimos, actualmente no hay una plantilla configurada para este tipo de documento o la plantilla ha dejado de estar disponible. Por favor, te solicitamos que te comuniques con el administrador del sistema para resolver este inconveniente.', 'info');
            }
        }

        try {


            if (!file_exists(base_path()."/app/IntegracionGoogle/token_google.json")) {
                $this->generateSevenLog('error_integracion_google', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: No hay token.json. Linea: GoogleController');
                return $this->sendSuccess(config('constants.support_message')."", 'info');
            }

            $google = new GoogleController();
            $id_google = $google->crearDocumentoGoogleDrive($input["title"], storage_path("app/public/".$tipo_documento["template"]), "Interna Cero Papeles -".env("APP_NAME"));

        } catch (\Throwable $th) {
            $this->generateSevenLog('error_integracion_google', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error en GoogleController'.$th->getMessage());

            return $this->sendSuccess(config('constants.support_message'), 'info');
        }


        // Decodifica el JSON
        $decodedJson = json_decode($id_google);

        // Primero, verifica si la decodificación fue exitosa y no es nula
        if ($decodedJson !== null) {
            // Luego, verifica si la propiedad TYPE_ERROR existe
            if (isset($decodedJson->TYPE_ERROR) && $decodedJson->TYPE_ERROR) {
                return $this->sendSuccess($decodedJson->MESSAGE, 'info');
            }

        }

        $input["template"] = "https://docs.google.com/document/d/".$id_google;
        $input["consecutive"] = "I".date("YmdHis");


        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $internal = $this->internalRepository->create($input);

            $input['correspondence_internal_id'] = $internal->id;
            $input['observation_history'] = "Creación de correspondencia";

            // Crea un nuevo registro de historial
            $this->internalHistoryRepository->create($input);
            //Obtiene el historial
            $internal->internalHistory;
            $internal->internalType;
            $internal->users;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($internal->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Muestra la vista principal para validar las correspondencias internas
     *
     * @param Request $request
     * @return void
     */
    public function validarInternal(Request $request)
    {
        return view('correspondence::internals_validar_correspondencia.index');
    }

    /**
     * Valida la existencia de la correspondencia interna según el código ingresado por el usuario
     *
     * @param [type] $codigo
     * @param Request $request
     * @return void
     */
    public function validarInternalCodigo($codigo, Request $request)
    {
        // Consulta la correspondencia interna según el código enviado por el usuario
        $internal = Internal::whereRaw("validation_code = BINARY '".$codigo."'")->get();
        // Retorna la información de la correspondencia, en caso tal de que coincida algún registro
        return $this->sendResponse($internal->toArray(), trans('msg_success_update'));
    }

    /**
     * Valida la existencia de la correspondencia interna según el código ingresado por el usuario
     *
     * @param Request $request
     * @return void
     */
    public function validarInternalDocumento(Request $request)
    {
        $input = $request->all();
        $hash = hash_file('sha256', $request['documento_adjunto']);
        // Retorna la veracidad del documento de la correspondencia, en caso tal de que coincida algún registro
        return $this->sendResponse(["documentos_identicos" => $input["hash"] == $hash], "Documento verificado");
    }

    /**
     * Envia una consulta a la API de OpenAI y obtiene una respuesta generada.
     *
     * @param Request $request Objeto de solicitud HTTP de Laravel.
     * @return \Illuminate\Http\JsonResponse Respuesta JSON con el contenido generado o un mensaje de error.
     */
    public function sendMessage(Request $request)
    {

        // <option value="improve" selected>Mejorar redacción</option>
        // <option value="check_spelling">Revisar ortografía</option>
        // <option value="ideas">Dame ideas para arrancar el documento</option>

        // dd($request);
        // Obtener la consulta del objeto de solicitud
        $query = base64_decode($request->input('query'));

        $query = urldecode($query);

        // Verifica si la consulta no está vacía
        if ($query) {
            // Inicializa el cliente HTTP (Guzzle)
            $client = new Client();


            switch ($request->input('type')) {
                case 'improve':
                    $prompt = 'mejora la redaccion y dame un texto de menos de 50 palabras sobre: ' . $query;
                    break;

                case 'check_spelling':
                    $prompt = 'solo corrige la ortografia de este texto: ' . $query;
                    break;

                case 'ideas':
                    $prompt = 'dame una lista de 3 ideas en formato html para empezar un texto y que no pase las 50 palabras sobre: ' . $query;
                    break;

                default:
                    $prompt = 'mejora la redaccion y dame un texto de 50 palabras sobre: ' . $query;
                break;
            }

            try {
                // Realiza una solicitud POST a la API de OpenAI
                $response = $client->post('https://api.openai.com/v1/chat/completions', [
                    'headers' => [
                        'Authorization' => 'Bearer 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',  // Clave API real
                        'Content-Type' => 'application/json'
                    ],
                    'json' => [
                        // 'max_tokens' => 100,
                        'model' => 'gpt-3.5-turbo-1106',  // Modelo de OpenAI utilizado
                        'messages' => [
                            ['role' => 'system', 'content' => $prompt]
                            // Puedes agregar más mensajes según sea necesario
                        ]
                    ]
                ]);

                // Decodifica la respuesta JSON de la API
                $body = json_decode($response->getBody()->getContents(), true);
                // return response()->json($body);

                // Extrae el contenido de la respuesta
                $content = $body['choices'][0]['message']['content'] ?? 'No content available';

                // Devuelve el contenido en una respuesta JSON
                return response()->json(['content' => $content]);
            } catch (\Exception $e) {
                // Maneja cualquier excepción y devuelve un mensaje de error
                return response()->json(['error' => $e->getMessage() . '. Linea: ' . $e->getLine()], 500);
            }
        }
        // Retorna un mensaje de error si la consulta está vacía
        return response()->json(['error' => 'No query provided'], 400);
    }

    /**
     * Exporta el historial del PQRS
     *
     * @author Manuel Marin. - Abril. 09. 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function exportHistorial($id)
    {
        $historial = InternalHistory::where('correspondence_internal_id', $id)->get();

        return Excel::download(new RequestExport('correspondence::internals.reports.report_historial', JwtController::generateToken($historial->toArray()), 'K'), 'Prueba.xlsx');
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



    public function procesarDestinatariosYcopias($input, $idCorrespondence)
    {
        try {
            // Inicializar variables
            $textRecipients = [];
            $mailsArray = [];
            $textCopias = [];

            // Validar y procesar destinatarios internos
            InternalRecipient::where('correspondence_internal_id', $idCorrespondence)->delete();

            if (isset($input["internal_all"]) && $input["internal_all"] == 1) {
                $textRecipients[] = "Todos";
            } else {
                if (!empty($input['internal_recipients'])) {
                    foreach ($input['internal_recipients'] as $recipient) {
                        $recipientArray = json_decode($recipient, true);


                        if (is_array($recipientArray)) {
                            $recipientArray["correspondence_internal_id"] = $idCorrespondence;
                            $textRecipients[] = $recipientArray["name"] ?? '';


                            if(empty($recipientArray['type'])){
                                $recipientArray['type'] = "Usuario";

                            }


                            if ($recipientArray['type'] == 'Usuario' && !empty($recipientArray['recipient_id'])) {

                                $recipientArray["users_id"] = $recipientArray["recipient_id"] ?? 0;
                                $recipientArray["users_dependencia_id"] = $recipientArray["users_dependencia_id"] ?? 0;
                                $user = User::find($recipientArray['users_id']);

                                if ($user) {
                                    $mailsArray[] = $user->email ?? '';
                                }else{
                                    $mailsArray[] = $recipientArray["users_email"] ?? '';
                                }

                            }else if($recipientArray['type'] == 'Usuario' && empty($recipientArray['recipient_id'])){

                                //entra cuando autocompleta el funcionario pero ingresa un espacio o algo y pierde el recipient_id
                                if(!empty($recipientArray["users_email"])){
                                    $user = User::where('email', $recipientArray['users_email'])->first();
                                    if ($user) {
                                        // Aquí tienes el ID del usuario
                                        $recipientArray["users_id"] = $user->id ?? 0;
                                        $recipientArray["users_dependencia_id"] = $user->id_dependencia ?? 0;

                                    } else {
                                        // El usuario no fue encontrado
                                    }

                                }
                            }
                            InternalRecipient::create($recipientArray);
                        }
                    }
                }
            }

            // Validar y procesar copias internas
            InternalCopyShare::where('correspondence_internal_id', $idCorrespondence)->where("type", "=", "Copia")->delete();

            if (!empty($input['internal_copy'])) {
                foreach ($input['internal_copy'] as $recipient) {
                    $recipientArray = json_decode($recipient, true);
                    if (is_array($recipientArray)) {
                        $recipientArray["correspondence_internal_id"] = $idCorrespondence;
                        $recipientArray["name"] = $recipientArray["fullname"] ?? '';
                        $recipientArray["type"] = "Copia";
                        $textCopias[] = $recipientArray["name"];
                        $internal = Internal :: where ('id' , $idCorrespondence)->first();

                        $asunto = json_decode('{"subject": "Notificación de copia de correspondencia interna ' . $internal->consecutive . '"}');
                        $email = User::where('id', $recipientArray['users_id'])->first()->email;
                        $notificacion["consecutive"] = $internal->consecutive;
                        $notificacion["id"] = $internal->id;
                        $notificacion["name"] = $recipientArray["name"];
                        $notificacion['mensaje'] = "Le informamos que ha sido incluido(a) como destinatario(a) de copia en el registro de la correspondencia interna con número de radicado: <strong>{$internal->consecutive}</strong>. <br><br>Le agradecemos tener en cuenta esta notificación para los fines correspondientes";
                        try {
                            if($input["tipo"] == 'Publicación'){
                            SendNotificationController::SendNotification('correspondence::internals.email.plantilla_notificaciones',$asunto,$notificacion,$email,'Correspondencia interna');
                            }
                        } catch (\Swift_TransportException $e) {
                            // Manejar la excepción de autenticación SMTP aquí
                            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\internalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());

                        } catch (\Exception $e) {
                            // Por ejemplo, registrar el error
                            $this->generateSevenLog('correspondence', 'Modules\Correspondence\Http\Controllers\internalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido') . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine() . 'ID Correspondence: ' . ($idCorrespondence ?? 'Desconocido'));
                        }

                        InternalCopyShare::create($recipientArray);
                    }
                }
            }


            return [
                'copies' => implode("<br>", $textCopias),
                'recipients' => implode("<br>", $textRecipients),
                'mailsArray' => implode(",", $mailsArray)
            ];

        } catch (\Illuminate\Database\QueryException $error) {
            DB::rollback();
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());

            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            DB::rollback();
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine() . 'ID Correspondence: ' . ($idCorrespondence ?? 'Desconocido'));

            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

      /**
     * Obtiene los usuarios para respuesta
     *
     * @author Erika Johana Gonzalez C. - Ene. 19. 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getResponsableInterna(Request $request)
    {
        // Usuarios
        $query = $request->input('query');
        // Se inicializa la variable con el $query para que no tenga problemas al momento de usarla en la funcion
        $searchQuery = $query;
        // Filtra a todos los usuarios en donde el nombre coincida
        $users = User::where(function ($query) use ($searchQuery) {
            $query->where('name', 'like', '%' . $searchQuery . '%')
                  ->orWhereHas('dependencies', function ($subQuery) use ($searchQuery) {
                    // Hace una subconsulta la cual busca tambien por lo que query sea a el nombre de la dependencia
                      $subQuery->where('nombre', 'like', '%' . $searchQuery . '%');// Obtiene todos los usuarios que tengan esa dependencias asociada
                  });
        })
        ->whereNotNull('id_cargo') // Asegura que el campo 'id_cargo' no sea nulo
        ->where('id_cargo', '!=', 0)
        ->where('id_dependencia', '!=', 0)
        ->where("is_assignable_pqr_correspondence",0)
        ->where('block', '!=', 1) // Filtra los registros donde 'block' no sea igual a 1
        ->get(); // Realiza la consulta y obtiene una colección de usuarios
        return $this->sendResponse($users->toArray(), trans('data_obtained_successfully'));
    }

    public function formatDestinatarios($text) {
        // Obtener el texto formateado desde formatTextByVariable
        $text = UtilController::formatTextByVariable($text,'Destinatarios');
    
        // Si el texto es vacío o null, devolver "No aplica"
        if (!$text) {
            return "No aplica";
        }

        // Reemplazar "\\n" por saltos de línea reales y eliminar caracteres de retorno de carro
        $text = str_replace(["\\n", "\r"], ["\n", ""], $text);

        // Eliminar espacios innecesarios al inicio de cada línea sin afectar los dobles saltos de línea
        $text = preg_replace('/^[\t ]+/m', '', $text);

        return $text;
    }

    public function procesarDestinatariosYcopiasSinGuardar($input, $idCorrespondence)
    {
        try {
            // Inicializar variables
            $textRecipients = [];
            $mailsArray = [];
            $textCopias = [];

            // dd($input['internal_recipients']);
            // Validar y procesar destinatarios internos
            if (isset($input["internal_all"]) && $input["internal_all"] == 1) {
                $textRecipients[] = "Todos";
            } else {
                if (!empty($input['internal_recipients'])) {
                    foreach ($input['internal_recipients'] as $recipient) {
                        // dd( $recipient);
                        // $recipientArray = json_decode($recipient, true);
                        $recipientArray = $recipient;
                        // dd($recipientArray);
                        // dd($recipientArray);
                        if (is_array($recipientArray)) {
                            $textRecipients[] = $recipientArray["name"] ?? '';

                            if(empty($recipientArray['type'])){
                                $recipientArray['type'] = "Usuario";
                            }

                            if ($recipientArray['type'] == 'Usuario' && !empty($recipientArray['recipient_id'])) {
                                $user = User::find($recipientArray['recipient_id']);
                                if ($user) {
                                    $mailsArray[] = $user->email ?? '';
                                }else{
                                    $mailsArray[] = $recipientArray["users_email"] ?? '';
                                }
                            }else if($recipientArray['type'] == 'Usuario' && empty($recipientArray['recipient_id'])){
                                if(!empty($recipientArray["users_email"])){
                                    $user = User::where('email', $recipientArray['users_email'])->first();
                                    if ($user) {
                                        // Aquí tienes el ID del usuario
                                    } else {
                                        // El usuario no fue encontrado
                                    }
                                }
                            }
                        }
                    }
                }
            }

            // Validar y procesar copias internas
            if (!empty($input['internal_copy'])) {
                foreach ($input['internal_copy'] as $recipient) {
                    // $recipientArray = json_decode($recipient, true);

                    $recipientArray = $recipient;


                    if (is_array($recipientArray)) {
                        $recipientArray["name"] = $recipientArray["fullname"] ?? '';
                        $recipientArray["type"] = "Copia";
                        $textCopias[] = $recipientArray["name"];
                    }
                }
            }

            return [
                'copies' => implode("<br>", $textCopias),
                'recipients' => implode("<br>", $textRecipients),
                'mailsArray' => implode(",", $mailsArray)
            ];

        } catch (\Exception $e) {
            // Por ejemplo, registrar el error
            $this->generateSevenLog('correspondence_internal', 'Modules\Correspondence\Http\Controllers\InternalController - '. Auth::user()->fullname.' -  Error: '.$e->getMessage() . '. Linea: ' . $e->getLine());

            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    public function previewDocument(Request $request)
    {
        $input = $request->all();
        $id = $input["id"];
        $userLogin = Auth::user();

        $datosDestinatarios = $this->procesarDestinatariosYcopiasSinGuardar($input, $id);

        $input["recipients"] = $datosDestinatarios["recipients"] ?? '';
        $input["copies"] = $datosDestinatarios["copies"] ?? '';

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

        // Preparar la información
        $information = $this->prepareDocumentInformation($input, $signUnique2, $hash);

        // Genera el código de validación único

        $information["#codigo_validacion"] = "Código de validación";

        // Obtener la ID de Google Docs
        $id_google = explode("/", $input["template"]);
        $id_google = end($id_google);

        // Editar el archivo en Google Docs
        $google = new GoogleController();
        $returnGoogle = $google->editFileDoc("Interna", $id, $id_google, array_column($input['internal_type']['variables_documento'], "variable"), $information, 0, true,true);

        // Manejar el caso de error de Google Docs
        if ($returnGoogle['type_message'] == 'info') {
            DB::rollback();
            return $this->sendSuccess("<strong>¡Advertencia!</strong><br /><br />" . $returnGoogle['message'], 'info');
        }

        $nombreArchivo = $input["consecutive"];
        // Guardar el archivo PDF en Google Drive
        $ruta_documento_temp = $google->saveFileGoogleDrive(JwtController::decodeToken($returnGoogle['data'])->id, "pdf", $nombreArchivo , "container/internal_previews" . date('Y'), true, true);

        // Definir la ruta del archivo PDF para la vista previa
        $input['template_preview'] = "/container/internal_previews" . date('Y') . "/" . $nombreArchivo  . ".pdf?time=" . time();


        $internal = Internal::find($id); // Obtén una instancia del modelo

        if ($internal) {
            $internal->timestamps = false; // Desactiva los timestamps para *esta instancia*
            $internal->template_preview = $input['template_preview'];
            $internal->save(); // Guarda los cambios

            $internal->timestamps = true; // Vuelve a activar los timestamps (¡Importante!)
        }

        $input['template_preview'] = $ruta_documento_temp;

        return $this->sendResponse($input['template_preview'], trans('data_obtained_successfully'));
    }


    /**
     * Prepara la información del documento para la generación del PDF.
     *
     * @param array $input Datos del formulario del documento.
     * @param string $signUnique2 Identificador único para la firma (si aplica).
     * @param string $hash Hash del documento (si aplica).
     * @return array Información formateada del documento.
     */
    private function prepareDocumentInformation($input, $signUnique2, $hash)
    {
        $information = [
            "#firmas" => 1, // Valor por defecto para el número de firmas
        ];

        $information["#remitente"] =  Auth::user()->fullname;;

        if (isset($input["tipo"])) {
            // Determinar el número de firmas según el tipo de documento
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
                    break;
                // Los casos 'Aprobación', 'Revisión' y 'Elaboración' comparten lógica similar, se pueden unificar
                case 'Aprobación':
                case 'Revisión':
                case 'Elaboración':
                    $key = strtolower($input["tipo"]); // Obtiene 'aprobacion', 'revision' o 'elaboracion'
                    $information["#" . $key] = UtilController::formatNames(
                        !empty($input["funcionario_revision"])
                            ? ($input[$key . '_names'] ?? null) . ',' . ($input["user_for_last_update"] ?? null)
                            : ($input[$key . '_names'] ?? null)
                    );
                    break;
            }
        }

        $elaborated = $input["elaborated_names"] ?? null;
        $reviewed   = $input["reviewd_names"] ?? null;
        $approved   = $input["approved_names"] ?? null;
        $copies     = $input["copies"] ?? null;

        $information["#copias"]    = UtilController::formatTextByVariable($input["copies"], 'Otros');
        $information["#consecutivo"] = $input["consecutive"];
        $information["#titulo"] = $input["title"];
        $information["#dependencia_remitente"] = $input["dependency_from"];
        $information["#destinatarios"] = $this->formatDestinatarios($input["recipients"] ?? null);
        $information["#contenido"] = $input["content"] ?? "";
        $information["#anexos"] = $input["annexes_description"] ?? "No aplica";
        $information["#tipo_documento"] = $input["type_document"];
        $information["#respuesta_correspondencia"] = $input["answer_consecutive_name"] ?? "No aplica";


        // Obtener información de la dependencia. Se optimiza la consulta a la base de datos
        $dependencia = Dependency::find($input["dependencias_id"]);

        if ($dependencia) {  // Verifica si se encontró la dependencia
            $information["#codigo_dependencia"] = $dependencia->codigo;
            $information["#direccion"] = $dependencia->direccion;
            $information["#dep_piso"] = $dependencia->piso;
            $information["#codigo_postal"] = $dependencia->codigo_postal;
            $information["#telefono"] = $dependencia->telefono;
            $information["#dep_ext"] = $dependencia->extension;
            $information["#dep_correo"] = $dependencia->correo;
            $information["#logo"] = $dependencia->logo;
        } else {
            // Manejar el caso en que no se encuentra la dependencia.  Puedes loguear un error o asignar valores por defecto.
            Log::warning("No se encontró la dependencia con ID: " . $input["dependencias_id"]);
            // Ejemplo:
            // $information["#codigo_dependencia"] = "N/A";
        }


        // Formatear la fecha.  Se usa Carbon para mayor flexibilidad y manejo de locales.
        $information["#fecha"] = now()->locale('es_ES')->isoFormat('D [de] MMMM [del] YYYY');

        // Asignar los campos de "Elaborado", "Revisado" y "Aprobado" después del switch
        $information["#elaborado"] = UtilController::formatTextByVariable($elaborated,'Otros');
        $information["#revisado"]  = UtilController::formatTextByVariable($reviewed ?? $approved ?? $information["#remitente"] ?? $elaborated, 'Otros');
        $information["#aprobado"]  = UtilController::formatTextByVariable($approved ?? $information["#revisado"], 'Otros');
        $information["#proyecto"]  = UtilController::formatTextByVariable($elaborated,'Otros');

        return $information;
    }


}
