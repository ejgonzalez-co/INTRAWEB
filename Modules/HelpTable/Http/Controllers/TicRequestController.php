<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateTicRequestRequest;
use Modules\HelpTable\Http\Requests\UpdateTicRequestRequest;
use Modules\HelpTable\Repositories\TicRequestRepository;
use Modules\HelpTable\Models\TicRequest;
use Modules\HelpTable\Models\TicTypeRequest;
use Modules\HelpTable\Models\TicRequestHistory;
use Modules\HelpTable\Models\TicRequestStatus;
use Modules\HelpTable\Models\HolidayCalendar;
use Modules\HelpTable\Models\WorkingHours;
use Modules\HelpTable\Models\TicMaintenance;
use App\Http\Controllers\AppBaseController;
use Modules\HelpTable\Http\Controllers\UtilController;
use Illuminate\Http\Request;
use App\Http\Controllers\JwtController;
use App\User;
use App\Exports\RequestExport;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Arr;
use Response;
use Auth;
use DB;
use App\Http\Controllers\SendNotificationController;
use Modules\HelpTable\Models\DependenciaTicRequest;
use Modules\HelpTable\Models\SedeTicRequest;
use Carbon\Carbon;
use ParagonIE\ConstantTime\Base64;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class TicRequestController extends AppBaseController {

    /** @var  TicRequestRepository */
    private $ticRequestRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(TicRequestRepository $ticRequestRepo) {
        $this->ticRequestRepository = $ticRequestRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicRequest.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador TIC","Usuario TIC","Soporte TIC","Proveedor TIC","Revisor concepto técnico TIC","Aprobación concepto técnico TIC"])){
            return view('help_table::tic_requests.index');
        }
        return view("auth.forbidden");
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $tic_requests = $this->ticRequestRepository->all();

        $count_tic_requests = 0;

        $modified_status_data  = [];


$fechas = [];
$anio = null;

if (!empty($request['f'])) {
    // 1. Decodificar base64
    $cadena = base64_decode($request['f']);

    $filtrosExtras = [];   // Filtros distintos a fechas/año
    $filtrosFecha = [];    // Filtros nuevos de fecha/año

    // 2. Separar condiciones
    $condiciones = explode(' AND ', $cadena);

    foreach ($condiciones as $cond) {
        $cond = trim($cond);

        // Detectar y extraer fecha_desde
        if (str_contains($cond, 'fecha_desde')) {
            preg_match("/'%([^']+)'/", $cond, $match);
            $fechas['fecha_desde'] = str_replace('%', '', $match[1] ?? null);
            continue;
        }

        // Detectar y extraer fecha_hasta
        if (str_contains($cond, 'fecha_hasta')) {
            preg_match("/'%([^']+)'/", $cond, $match);
            $fechas['fecha_hasta'] = str_replace('%', '', $match[1] ?? null);
            continue;
        }

        // Detectar año estilo: year LIKE '%2023%'
        if (str_contains($cond, "year LIKE")) {
            preg_match("/'%(\d{4})%?'/", $cond, $match);
            $anio = trim($match[1] ?? '');
            continue;
        }

        // Detectar año estilo: YEAR(created_at) = '2023'
        if (str_contains($cond, "YEAR(created_at)")) {
            preg_match("/YEAR\(created_at\)\s*=\s*'(\d{4})'/", $cond, $match);
            $anio = trim($match[1] ?? '');
            continue;
        }

        // Guardar cualquier otro filtro
        $filtrosExtras[] = $cond;
    }

    // 3. Construir condiciones nuevas de fechas
    if (!empty($fechas['fecha_desde']) && !empty($fechas['fecha_hasta'])) {
        $filtrosFecha[] = "created_at BETWEEN '{$fechas['fecha_desde']} 00:00:00' AND '{$fechas['fecha_hasta']} 23:59:59'";
    } elseif (!empty($fechas['fecha_desde'])) {
        $filtrosFecha[] = "created_at >= '{$fechas['fecha_desde']} 00:00:00'";
    } elseif (!empty($fechas['fecha_hasta'])) {
        $filtrosFecha[] = "created_at <= '{$fechas['fecha_hasta']} 23:59:59'";
    }

    // 4. Agregar condición de año si existe
    if (!empty($anio)) {
        $filtrosFecha[] = "YEAR(created_at) = '{$anio}'";
    }

    // 5. Recombinar todo
    $condicionesFinales = array_merge($filtrosExtras, $filtrosFecha);
    $request['f'] = base64_encode(implode(' AND ', $condicionesFinales));
}

        // Obtiene los datos para el consolidad
        $tic_requests = TicRequest::select('request_status','affair','id', DB::raw('COUNT(*) as total'))
        ->groupBy('request_status')
        ->when(Auth::user()->id, function ($query) {
            // Valida si el usuario logueado es un tecnico tic o un proveedor
            if (Auth::user()->hasRole('Proveedor TIC')) {
                $query->where('assigned_user_id', Auth::user()->id);
            }
            // Valida si el usuario logueado usuario normal tic
            else if (Auth::user()->hasRole('Usuario TIC')) {
                $query->where('users_id', Auth::user()->id);
            }
            else if (Auth::user()->hasRole('Soporte TIC')) {
                $query->where('assigned_user_id', Auth::user()->id)->orWhere('user_created_id', Auth::user()->id);
            }
            return $query;
        })->get()
        ->toArray();

        $statuses = ["Abierta","Asignada","En proceso","Cerrada (Encuesta pendiente)","Cerrada (Encuesta realizada)","Cerrada (Sin Encuesta)","Devuelta","Pre Cierre"];

        $statuses_to_reemplace = ["abierta","asignada","en_proceso","cerrada_encuesta_pendiente","cerrada_encuesta_realizada","cerrada_sin_encuesta","devuelta","precierre"];

        foreach ($tic_requests as $key => $tic_request) {
            $modified_status_data[str_replace($statuses, $statuses_to_reemplace, $tic_request["request_status"])] = $tic_request["total"];
        }

        $status_data = $modified_status_data;

        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
                $tic_requests = TicRequest::
                with(['ticRequestsDocuments','ticTypeRequest', 'dependenciaTicRequest','sedeTicRequest', 'ticRequestStatus', 'assignedBy', 'assignedUser', 'ticMaintenances', 'ticKnowledgeBases','ticTypeTicCategories'])
                ->with(['users' => function ($query) {
                    $query->with(['dependencies']);
                }])
                ->when(Auth::user()->id, function ($query) {
                    // Valida si el usuario logueado es un tecnico tic o un proveedor
                    if (Auth::user()->hasRole('Proveedor TIC')) {
                        $query->where('assigned_user_id', Auth::user()->id);
                    }
                    // Valida si el usuario logueado usuario normal tic
                    else if (Auth::user()->hasRole('Usuario TIC')) {
                        $query->where('users_id', Auth::user()->id);
                    }
                    else if (Auth::user()->hasRole('Soporte TIC')) {

                        $query->where(function($subQuery) {
                            $subQuery->where('assigned_user_id', Auth::user()->id)
                                     ->orWhere('user_created_id', Auth::user()->id);
                        });

                    }
                    return $query;
                })->whereRaw(base64_decode($request["f"]))->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->map(function($request, $key) {
                    return UtilController::stateTimeline($request);
                })->toArray();

                // Contar el número total de registros de la consulta realizada según los filtros
                $count_tic_requests = TicRequest::
                with(['ticRequestsDocuments','ticTypeRequest', 'dependenciaTicRequest','sedeTicRequest', 'assignedUser', 'ticKnowledgeBases'])
                ->with(['users' => function ($query) {
                    $query->with(['dependencies']);
                }])
                ->when(Auth::user()->id, function ($query) {
                    // Valida si el usuario logueado es un tecnico tic o un proveedor
                    if (Auth::user()->hasRole('Proveedor TIC')) {
                        $query->where('assigned_user_id', Auth::user()->id);
                    }
                    // Valida si el usuario logueado usuario normal tic
                    else if (Auth::user()->hasRole('Usuario TIC')) {
                        $query->where('users_id', Auth::user()->id);
                    }
                    else if (Auth::user()->hasRole('Soporte TIC')) {

                        $query->where(function($subQuery) {
                            $subQuery->where('assigned_user_id', Auth::user()->id)
                                     ->orWhere('user_created_id', Auth::user()->id);
                        });
                    }
                    return $query;
                })->whereRaw(base64_decode($request["f"]))->count();
            }
            else if(isset($request["cp"]) && isset($request["pi"])) {
                $tic_requests = TicRequest::
                    with(['ticRequestsDocuments','ticTypeRequest', 'dependenciaTicRequest','sedeTicRequest', 'ticRequestStatus', 'assignedBy', 'assignedUser', 'ticMaintenances', 'ticKnowledgeBases','ticTypeTicCategories'])
                    ->with(['users' => function ($query) {
                        $query->with(['dependencies']);
                    }])
                    ->when(Auth::user()->id, function ($query) {
                        // Valida si el usuario logueado es un tecnico tic o un proveedor
                        if (Auth::user()->hasRole('Proveedor TIC')) {
                            $query->where('assigned_user_id', Auth::user()->id);
                        }
                        // Valida si el usuario logueado usuario normal tic
                        else if (Auth::user()->hasRole('Usuario TIC')) {
                            $query->where('users_id', Auth::user()->id);
                        }
                        else if (Auth::user()->hasRole('Soporte TIC')) {

                            $query->where(function($subQuery) {
                                $subQuery->where('assigned_user_id', Auth::user()->id)
                                         ->orWhere('user_created_id', Auth::user()->id);
                            });
                        }
                        return $query;
                    })
                    ->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))->get()->map(function($request, $key) {
                        return UtilController::stateTimeline($request);
                    })->toArray();

                // Contar el número total de registros de la consulta realizada según el paginado seleccionado
                $count_tic_requests = TicRequest::
                with(['ticRequestsDocuments','ticTypeRequest', 'dependenciaTicRequest','sedeTicRequest', 'assignedUser', 'ticKnowledgeBases'])
                ->with(['users' => function ($query) {
                    $query->with(['dependencies']);
                }])
                ->when(Auth::user()->id, function ($query) {
                    // Valida si el usuario logueado es un tecnico tic o un proveedor
                    if (Auth::user()->hasRole('Proveedor TIC')) {
                        $query->where('assigned_user_id', Auth::user()->id);
                    }
                    // Valida si el usuario logueado usuario normal tic
                    else if (Auth::user()->hasRole('Usuario TIC')) {
                        $query->where('users_id', Auth::user()->id);
                    }
                    else if (Auth::user()->hasRole('Soporte TIC')) {

                        $query->where(function($subQuery) {
                            $subQuery->where('assigned_user_id', Auth::user()->id)
                                     ->orWhere('user_created_id', Auth::user()->id);
                        });
                    }
                    return $query;
                })->count();
        }

        return $this->sendResponseAvanzado($tic_requests, trans('data_obtained_successfully'), null, ["estados" => $status_data,"total_registros" => $count_tic_requests]);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateTicRequestRequest $request
     *
     * @return Response
     */
    public function store(CreateTicRequestRequest $request) {

        $input = $request->all();
        // Inicia la transaccion
        DB::beginTransaction();

        if(!empty($input["url_documents"])){
            $input["url_documents"] = implode(",",$input["url_documents"]);
        }

        $input['acceso_remoto'] = isset($input['acceso_remoto']) && ($this->toBoolean($input['acceso_remoto']) || $input['acceso_remoto'] == 1) ? 1 : 0;

        try {
            // Valida si el usuario logueado es un administrador
            if (Auth::user()->hasRole('Administrador TIC')/* || Auth::user()->hasRole('Usuario TIC')*/) {
                $input['assigned_by_id'] = Auth::user()->id;
            }
            if (Auth::user()->hasRole('Usuario TIC')) {
                $ticRequest = TicRequest::where('users_id', Auth::user()->id)->where('ht_tic_request_status_id', 4)->count();

                if ($ticRequest >= env('QUANTITY_PENDING_REQUESTS', 3)) {
                    return $this->sendResponse("info", 'Usted posee '. $ticRequest .' solicitudes con encuesta pendiente, debe realizarlas para poder registrar una nueva solicitud.','info');
                }
            }

            $input['user_created_id'] = Auth::user()->id;
            $input['user_created_name'] = User::find($input['user_created_id'])->name;

            // Obtiene automaticamente el id de la sede del usuario creador
            // $input["ht_sedes_tic_request_id"] = User::select("sedes_id")->find($input["users_id"])->sedes_id;

            // Organiza campos booleanos
            $input['generate_maintenance'] = $this->toBoolean($input['generate_maintenance']);

            if(empty($input['users_id'])){
                $input['users_id'] = Auth::user()->id;
            }

            // Valida que no venga el estado de la solicitud
            if (empty($input['ht_tic_request_status_id'])) {
                // Obtiene el primer estado de las solicitudes
                $ticRequestStatus = TicRequestStatus::where('id', 1)->first();
                // Asigna el id del estado de la solicitud
                $input['ht_tic_request_status_id'] = $ticRequestStatus->id;
            }else{
                // Obtiene los datos del estado de la solicitud
                $ticRequestStatus = TicRequestStatus::where('id', $input['ht_tic_request_status_id'])->first();
            }

            $input['request_status'] = $ticRequestStatus->name;

            if(!empty($input['assigned_by_id'])){
                $input['assigned_by_name'] = User::find($input['assigned_by_id'])->name;
            }

            if(!empty($input['users_id'])){
                $input['users_name'] = User::find($input['users_id'])->name;
            }

            if(!empty($input['assigned_user_id'])){
                $input['assigned_user_name'] = User::find($input['assigned_user_id'])->name;
            }
            // Inserta el registro de la solicitud
            $ticRequest = $this->ticRequestRepository->create($input);
            
            //Valida que el estado de la solicitud sea ASIGNADO 
            if ($input['ht_tic_request_status_id'] == 2 && !empty($input['support_type'])) {
                $user = User::find($input['assigned_user_id']);
                // Asigna el la solicitud al a los datos de usuario
                $user->continue = $request->toArray();
                // Asunto del email
                $custom = json_decode('{"subject": "Notificación asignación de solicitud"}');
                // Envia notificacion al usuario asignado
                SendNotificationController::SendNotification('help_table::tic_requests.email.email_assign_request',$custom,$user,$user['email'],'Mesa de ayuda');
            }    
            /**
             * Valida que la solcitud tenga un tipo de solicitud asignada
             * Valida que que venga un fuincionario
             * */
            if (!empty($input['ht_tic_type_request_id'])  && !empty($input['assigned_user_id'])) {
                // Obtiene todos los dias no laborales disponibles
                $holidayCalendars = HolidayCalendar::get()->toArray();
                // Obtiene el horario laboral
                $workingHours = WorkingHours::latest()->first();
                // Calcula la fecha de vencimiento de la solicitud
                $expiration_date = $this->calculateFutureDate(
                            Arr::pluck($holidayCalendars, 'date'),
                            $ticRequest->created_at,
                            $ticRequest->ticTypeRequest->unit_time_name,
                            $ticRequest->ticTypeRequest->type_term_name,
                            $ticRequest->ticTypeRequest->term,
                            $workingHours
                        );
                // Calcula la fecha proxima a vencerse de la solicitud
                $prox_date_to_expire = $this->calculateFutureDate(
                    Arr::pluck($holidayCalendars, 'date'),
                    $ticRequest->created_at,
                    $ticRequest->ticTypeRequest->unit_time_name,
                    $ticRequest->ticTypeRequest->type_term_name,
                    $ticRequest->ticTypeRequest->term,
                    $workingHours
                );

                // Asigna los datos para actualizar la solicitud
                $input['prox_date_to_expire'] = $prox_date_to_expire[0];
                $input['next_hour_to_expire'] = $prox_date_to_expire[1];
                $input['expiration_date']     = $expiration_date[0];
                $input['hours']               = $expiration_date[1];
                $input['assignment_date']     = date('Y-m-d H:i:s');
            }

      
            // Valida si existe un mantenimiento relacionado a la solicitud
            if ($input['generate_maintenance']){
                $input['ht_tic_requests_id'] = $ticRequest->id;
                $ticMaintenance = TicMaintenance::create($input);

                $ticMaintenance->dependencias;
                $ticMaintenance->ticAssets;
                $ticMaintenance->ticProvider;
                $ticMaintenance->ticRequests;
            }

            // Actualiza el registro de la solicitud
            $ticRequest = $this->ticRequestRepository->update($input, $ticRequest->id);

            $input['ht_tic_requests_id'] = $ticRequest->id;

            // Inserta el registro en el historial de la solicitud
            TicRequestHistory::create($input);

                    


            $ticRequest->users;
            $ticRequest->users->dependencies = $ticRequest->users->dependencies;
            $ticRequest->ticTypeRequest;
            $ticRequest->ticRequestStatus;
            $ticRequest->assignedBy;
            $ticRequest->assignedUser;
            $ticRequest->ticTypeTicCategories;
            $ticRequest->ticMaintenances;
            $ticRequest->ticRequestHistories;
            $ticRequest->ticSatisfactionPolls;
            $ticRequest->ticRequestsDocuments;

        $ticRequestOld = $this->ticRequestRepository->find($ticRequest->id);
         

            $ticRequest = UtilController::stateTimeline($ticRequest);

             // Valida si se esta asignado a un usuario de soporte y el estado sea asignado
            if (isset($ticRequest->assignedUser) && $ticRequest->ht_tic_request_status_id == 2) {
                // Asigna el la solicitud al a los datos de usuario
                $assignedUser['request'] = $ticRequest->toArray();
                // Asunto del email
                $custom = json_decode('{"subject": "Notificación para asignación de caso"}');
                    // Envia notificacion al usuario asignado
                try {
                    SendNotificationController::SendNotification('help_table::tic_requests.email.email_assign_request',$custom,$assignedUser,$assignedUser['request']["assigned_user"]["email"],'Mesa de ayuda');
                    // Envia notificacion al usuario que asigna la solicitud
                    $custom = json_decode('{"subject": "Su solicitud ha sido asignada"}');
                    SendNotificationController::SendNotification('help_table::tic_requests.email.email_assign_funcionary',$custom,$assignedUser,$ticRequestOld->requestUser->email,'Mesa de ayuda');

                } catch (\Swift_TransportException $exception) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                } catch (\Exception $exception) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                }
            }if ($ticRequest->ht_tic_request_status_id == 3) {
                $custom = json_decode('{"subject": "Su solicitud está siendo atendida"}');
                $dateAttention =  date('Y-m-d H:i:s');
                $ticRequest->date_attention = $dateAttention;
                    // Envia notificacion al usuario asignado
                try {
                    SendNotificationController::SendNotification('help_table::tic_requests.email.email_process_request',$custom,$ticRequest,$ticRequest->requestUser->email,'Mesa de ayuda');

                } catch (\Swift_TransportException $exception) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                } catch (\Exception $exception) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                }
            }

            /**
             * Valida si el estado es encuesta pendiente
             * La solicitud queda a la espera que se responda la encuesta de satisfacion
             */

            
            if ($input['ht_tic_request_status_id'] == "4" && empty($ticRequestOld->closing_date) && empty($ticRequestOld->date_attention)) {
                    // 1. Define la fecha una sola vez.
                    $now = date('Y-m-d H:i:s');

                    // 2. Prepara todos los datos nuevos en el array `$input`.
                    $input['date_attention'] = $now;
                    $input['closing_date'] = $now;
                    if ($now > $ticRequestOld->expiration_date) {
                        $input['request_status'] = $ticRequestStatus->name;
                    }
                    $input['survey_status'] = $this->getObjectOfList(config('help_table.tic_poll_status'), 'name', 'Encuesta pendiente')->id;

                    // 3. Obtén los datos antiguos de la solicitud.
                    $requestData = $ticRequestOld->toArray();
                    
                    // 4. (LA SOLUCIÓN) Combina los datos antiguos con los nuevos del `$input`.
                    // `array_merge` sobreescribirá las claves de `$requestData` con las de `$input` si existen.
                    $finalRequestData = array_merge($requestData, $input);

                    // 5. Asigna los datos finales y actualizados para la notificación.
                    $user = User::find($ticRequestOld->users_id);
                    $user->request = $finalRequestData; // Usamos los datos combinados
                    $user->continue = $request->all();

                    // 6. Prepara y envía la notificación.
                    $custom = json_decode('{"subject": "Su solicitud ha sido cerrada"}');
                    
                    try {
                        // Envia notificacion al usuario
                        SendNotificationController::SendNotification(
                            'help_table::tic_requests.email.email_request_closed',
                            $custom,
                            $user,
                            $ticRequestOld->requestUser->email,
                            'Mesa de ayuda'
                        );
                    } catch (\Swift_TransportException $exception) {
                        // Manejar la excepción de transporte SMTP
                        $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                    } catch (\Exception $exception) {
                        // Manejar cualquier otra excepción
                        $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                    }
            }

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
             return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
             return $this->sendSuccess(config('constants.support_message'), 'info');
        }

        // Efectua los cambios realizados
        DB::commit();

        // Valida si el usuario logueado es un administrador o un usuario
        // if (Auth::user()->hasRole('Administrador TIC') || Auth::user()->hasRole('Usuario TIC')) {
            return $this->sendResponse($ticRequest->toArray(), trans('msg_success_save'));
        // } else {
        //     return $this->sendResponse(null, trans('msg_success_save'));
        // }
    }

    /**
     * Muestra el detalle completo de elemento existente
     *
     * @author Carlos Moises Garcia T. - Abr. 14 - 2021
     * @version 1.0.0
     *
     * @param int $id
     */
    public function show($id) 
{
    $ticRequest = TicRequest::where('id', $id)
        ->with([
            'ticRequestsDocuments',
            'ticTypeRequest', 
            'dependenciaTicRequest',
            'sedeTicRequest', 
            'ticRequestStatus', 
            'assignedBy', 
            'assignedUser', 
            'ticMaintenances.dependencias', // Aquí cargas la relación anidada
            'ticKnowledgeBases',
            'ticTypeTicCategories',
            'users.dependencies', // Optimización: carga eager loading para users y sus dependencias
            'ticRequestHistories' => function($query) {
                $query->with([
                    'users',
                    'ticTypeRequest',
                    'ticRequestStatus',
                    'assignedBy',
                    'assignedUser',
                    'ticTypeTicCategories'
                ]);
            },
            'ticSatisfactionPolls',
            'ticTypeAssets'
        ])
        ->first(); // Cambia get() por first() ya que buscas un solo registro

    if (empty($ticRequest)) {
        return $this->sendError(trans('not_found_element'), 200);
    }

    // Transformación de historiales (puedes mantener esta parte)
    $ticRequest->ticRequestHistories->map(function($history) {
        return UtilController::stateTimeline($history);
    });

    return $this->sendResponse($ticRequest->toArray(), trans('data_obtained_successfully'));
}

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTicRequestRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicRequestRequest $request) {

        $input = $request->all();
        /** @var TicRequest $ticRequest */
        $ticRequestOld = $this->ticRequestRepository->find($id);
        if (empty($ticRequestOld)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();

        $input['acceso_remoto'] = isset($input['acceso_remoto']) && ($this->toBoolean($input['acceso_remoto']) || $input['acceso_remoto'] == 1) ? 1 : 0;

        try {

            if(!empty($input["url_documents"])){
                $input["url_documents"] = implode(",",$input["url_documents"]);
            }
            // Valida que el usuario logueado sea un usuario tic y la solicitud es devuelta
            if (Auth::user()->hasRole('Usuario TIC') && $input['ht_tic_request_status_id'] == 7) {
                $input['ht_tic_request_status_id'] = 1;
                $input['reshipment_date'] = date('Y-m-d H:i:s');

                $adminUsers = User::role(['Administrador TIC'])
                ->where('deleted_at', '=', null)
                ->get();

                // Recorre la lista de usuarios
                foreach ($adminUsers as $key => $user) {

                    // Asigna el la solicitud al a los datos de usuario
                    $user->request = $ticRequestOld->toArray();
                    // Asunto del email
                    $custom = json_decode('{"subject": "Notificación de respuesta a devolución"}');
                        // Envia notificacion al usuario asignado
                    try {
                        SendNotificationController::SendNotification('help_table::tic_requests.email.email_response_return',$custom,$user,$user['email'],'Mesa de ayuda');
                        // Manejar la excepción de autenticación SMTP aquí
                        // $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                    } catch (\Exception $exception) {
                        // Por ejemplo, registrar el error
                        $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                    }
                }
            }

            // Obtiene los datos del estado de la solicitud
            $ticRequestStatus = TicRequestStatus::where('id', $input['ht_tic_request_status_id'])->first();

            $input['request_status'] = $ticRequestStatus->name;

            // Valida si el usuario logueado es un administrador
            if (Auth::user()->hasRole('Administrador TIC')) {

                $input['assigned_by_id'] = Auth::user()->id;

                /**
                 * Valida que la solcitud tenga un tipo de solicitud asignada
                 * Valida que que venga un fuincionario
                 * Condicion cuando es la primera vez que se va asignar
                 *
                 */
                if (!empty($input['ht_tic_type_request_id'])  && !empty($input['assigned_user_id'])) {

                    // Obtiene la informacion del tipo de solicitud
                    $ticTypeRequest = TicTypeRequest::find($input['ht_tic_type_request_id']);
                    // Valida si existe una fecha de devolucion
                    if ($ticRequestOld->reshipment_date) {
                        // Fecha base para calcular la fecha futura
                        $baseDate = $ticRequestOld->reshipment_date;
                    } else {
                        // Fecha base para calcular la fecha futura
                        $baseDate = $ticRequestOld->created_at;
                    }

                    // Obtiene todos los dias no laborales disponibles
                    $holidayCalendars = HolidayCalendar::get()->toArray();
                    // Obtiene el horario laboral
                    $workingHours = WorkingHours::latest()->first();
                    // Calcula la fecha de vencimiento de la solicitud
                    $expiration_date = $this->calculateFutureDate(
                                Arr::pluck($holidayCalendars, 'date'),
                                $baseDate,
                                $ticTypeRequest->unit_time_name,
                                $ticTypeRequest->type_term_name,
                                $ticTypeRequest->term,
                                $workingHours
                            );
                    // Calcula la fecha proxima a vencerse de la solicitud
                    $prox_date_to_expire = $this->calculateFutureDate(
                        Arr::pluck($holidayCalendars, 'date'),
                        $baseDate,
                        $ticTypeRequest->unit_time_name,
                        $ticTypeRequest->type_term_name,
                        $ticTypeRequest->term,
                        $workingHours
                    );

                    // Asigna los datos para actualizar la solicitud
                    $input['prox_date_to_expire'] = $prox_date_to_expire[0];
                    $input['next_hour_to_expire'] = $prox_date_to_expire[1];
                    $input['expiration_date']     = $expiration_date[0];
                    $input['hours']               = $expiration_date[1];
                    $input['assignment_date']     = date('Y-m-d H:i:s');

                    $assignedUser = User::find($input['assigned_user_id']);
                    // Envia notificacion al usuario asignado
                    // Mail::to($assignedUser)->send(new SendMail('help_table::tic_requests.email.email_assign_request', $assignedUser));
                }

                /**
                 * Valida que la solcitud tenga un tipo de solicitud asignada previamente
                 * Valida si el tipo de solicitud anterior es diferente al nuevo
                 *
                 */
                if (!empty($ticRequestOld->ht_tic_type_request_id) && $ticRequestOld->ht_tic_type_request_id != $input['ht_tic_type_request_id']) {

                    // Obtiene la informacion del tipo de solicitud
                    $ticTypeRequest = TicTypeRequest::find($input['ht_tic_type_request_id']);
                    // Valida si existe una fecha de devolucion
                    if ($ticRequestOld->reshipment_date) {
                        // Fecha base para calcular la fecha futura
                        $baseDate = $ticRequestOld->reshipment_date;
                    } else {
                        // Fecha base para calcular la fecha futura
                        $baseDate = $ticRequestOld->created_at;
                    }

                    // Obtiene todos los dias no laborales disponibles
                    $holidayCalendars = HolidayCalendar::get()->toArray();
                    // Obtiene el horario laboral
                    $workingHours = WorkingHours::latest()->first();
                    // Calcula la fecha de vencimiento de la solicitud
                    $expiration_date = $this->calculateFutureDate(
                                Arr::pluck($holidayCalendars, 'date'),
                                $baseDate,
                                $ticTypeRequest->unit_time_name,
                                $ticTypeRequest->type_term_name,
                                $ticTypeRequest->term,
                                $workingHours
                            );
                    // Calcula la fecha proxima a vencerse de la solicitud
                    $prox_date_to_expire = $this->calculateFutureDate(
                        Arr::pluck($holidayCalendars, 'date'),
                        $baseDate,
                        $ticTypeRequest->unit_time_name,
                        $ticTypeRequest->type_term_name,
                        $ticTypeRequest->term,
                        $workingHours
                    );

                    // Asigna los datos para actualizar la solicitud
                    $input['prox_date_to_expire'] = $prox_date_to_expire[0];
                    $input['next_hour_to_expire'] = $prox_date_to_expire[1];
                    $input['expiration_date']     = $expiration_date[0];
                    $input['hours']               = $expiration_date[1];
                    $input['assignment_date']     = date('Y-m-d H:i:s');
                    // Envia notificacion al usuario asignado
                    // Mail::to($assignedUser)->send(new SendMail('help_table::tic_requests.email.email_assign_request', $assignedUser));
                }

                /**
                 * Valida si el estado es sin encuesta
                 * La solicitud queda a la espera que se responda la encuesta de satisfacion
                 */
                if ($input['ht_tic_request_status_id'] == 6 && empty($ticRequestOld->closing_date) && empty($ticRequestOld->date_attention)){
                    $dateAttention =  date('Y-m-d H:i:s');
                    if ($dateAttention > $ticRequestOld->expiration_date) {
                        $input['request_status'] = $ticRequestStatus->name;
                    }

                    $input['date_attention'] = $dateAttention;
                    $input['closing_date'] = $dateAttention;
                    $input['survey_status'] = $this->getObjectOfList(config('help_table.tic_poll_status'), 'name', 'Sin encuesta')->id;
                }

                // Valida que no tenga un usuario asignado y el estado de la solicitud es devuelta
                if ($input['ht_tic_request_status_id'] == 7 && empty($ticRequestOld->assigned_user_id)) {
                    $user = User::find($ticRequestOld->users_id);

                    // Asigna el la solicitud al a los datos de usuario
                    $user->request = $ticRequestOld->toArray();
                    $user->continue = $request->toArray();
                    // Asunto del email
                    $custom = json_decode('{"subject": "Notificación devolución caso"}');
                    // Envia notificacion al usuario asignado
                    try {
                        // Envia notificacion al usuario asignado
                        SendNotificationController::SendNotification('help_table::tic_requests.email.email_request_returned',$custom,$user,$user['email'],'Mesa de ayuda');
                        // Manejar la excepción de autenticación SMTP aquí
                        // $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                    } catch (\Exception $exception) {
                        // Por ejemplo, registrar el error
                        $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                    }
                }
            }
            // dd($ticRequestOld['ht_tic_request_status_id'],$input['ht_tic_request_status_id']);
            //Se envia correo cuando se asigna una mesa de ayuda
            if ($input['ht_tic_request_status_id'] == 2 && !empty($input['assigned_user_id'] && $ticRequestOld['ht_tic_request_status_id'] != 2)) {
                $user = User::find($input['assigned_user_id']);
                // Asigna el la solicitud al a los datos de usuario
                $user->continue = $request->toArray();
                // Asunto del email
                $custom = json_decode('{"subject": "Notificación asignación de solicitud"}');
                // Envia notificacion al usuario asignado
                SendNotificationController::SendNotification('help_table::tic_requests.email.email_assign_request',$custom,$user,$user['email'],'Mesa de ayuda');
            }  

            if (Auth::user()->hasRole('Proveedor TIC')) {
                //Valida que el estado de la solicitud esté en PRE-CIERRE 
                if (isset($input['ht_tic_request_status_id']) && $input['ht_tic_request_status_id'] == 8) {
                    //Valida que la solicitud tenga un Mantenimiento   
                        if (!empty($input['generate_maintenance'])) {
                            $user = User::find($ticRequestOld->users_id);
                            // Asigna el la solicitud al a los datos de usuario
                            $user->request = $ticRequestOld->toArray();
                            $admin = User::find($user['request']['assigned_by_id']);
                            $user->continue = $request->toArray();
                            $user->admin = $admin;
                            $custom = json_decode('{"subject": "Solicitud en Estado de Pre-Cierre"}');
                            // Envia notificacion al usuario asignado
                            SendNotificationController::SendNotification('help_table::tic_requests.email.email_preecierre_maintenance',$custom,$user,'penasantiago2030@gmail.com','Mesa de Ayuda');
                        }else{
                            $user = User::find($ticRequestOld->users_id);
                            // Asigna el la solicitud al a los datos de usuario
                            $user->request = $ticRequestOld->toArray();
                            $admin = User::find($user['request']['assigned_by_id']);
                            $user->continue = $request->toArray();
                            $user->admin = $admin;
                            $custom = json_decode('{"subject": "Solicitud en Estado de Pre-Cierre"}');
                            // Envia notificacion al usuario asignado
                            SendNotificationController::SendNotification('help_table::tic_requests.email.email_precierre',$custom,$user,$user['admin']['email'],'Mesa de Ayuda');

                        }
                    }
            }
            /**
             * Valida si el estado es encuesta pendiente
             * La solicitud queda a la espera que se responda la encuesta de satisfacion
             */

            if ($input['ht_tic_request_status_id'] == "4" && empty($ticRequestOld->closing_date) && empty($ticRequestOld->date_attention)) {
                    // 1. Define la fecha una sola vez.
                    $now = date('Y-m-d H:i:s');

                    // 2. Prepara todos los datos nuevos en el array `$input`.
                    $input['date_attention'] = $now;
                    $input['closing_date'] = $now;
                    if ($now > $ticRequestOld->expiration_date) {
                        $input['request_status'] = $ticRequestStatus->name;
                    }
                    $input['survey_status'] = $this->getObjectOfList(config('help_table.tic_poll_status'), 'name', 'Encuesta pendiente')->id;

                    // 3. Obtén los datos antiguos de la solicitud.
                    $requestData = $ticRequestOld->toArray();
                    
                    // 4. (LA SOLUCIÓN) Combina los datos antiguos con los nuevos del `$input`.
                    // `array_merge` sobreescribirá las claves de `$requestData` con las de `$input` si existen.
                    $finalRequestData = array_merge($requestData, $input);

                    // 5. Asigna los datos finales y actualizados para la notificación.
                    $user = User::find($ticRequestOld->users_id);
                    $user->request = $finalRequestData; // Usamos los datos combinados
                    $user->continue = $request->all();

                    // 6. Prepara y envía la notificación.
                    $custom = json_decode('{"subject": "Su solicitud ha sido cerrada"}');
                    
                    try {
                        // Envia notificacion al usuario
                        SendNotificationController::SendNotification(
                            'help_table::tic_requests.email.email_request_closed',
                            $custom,
                            $user,
                            $ticRequestOld->requestUser->email,
                            'Mesa de ayuda'
                        );
                    } catch (\Swift_TransportException $exception) {
                        // Manejar la excepción de transporte SMTP
                        $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                    } catch (\Exception $exception) {
                        // Manejar cualquier otra excepción
                        $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                    }
            }

            // Valida si existe un mantenimiento relacionado a la solicitud
            if (!empty($input['generate_maintenance'])){
                // Organiza campos booleanos
                $input['generate_maintenance'] = $this->toBoolean($input['generate_maintenance']);
                // Valida si es verdadero la creacion de un mantenimiento
                if ($input['generate_maintenance']) {
                    $input['id_tower_inventory'] = $input['tower_inventory']['id'];
                    $input['asset_type'] = $input['tower_inventory']['asset_type'];
                    $input['ht_tic_requests_id'] = $ticRequestOld->id;
                    $htTicMaintenance = TicMaintenance::create($input);

                    $htTicMaintenance->dependencias;
                    $htTicMaintenance->ticAssets;
                    $htTicMaintenance->ticProvider;
                    $htTicMaintenance->ticRequests;
                }
            }

            //you 
            if(!empty($input['assigned_by_id'])){
                $input['assigned_by_name'] = User::find($input['assigned_by_id'])->name;
            }

            if(!empty($input['users_id'])){
                $input['users_name'] = User::find($input['users_id'])->name;
            }

            if(!empty($input['assigned_user_id'])){
                $input['assigned_user_name'] = User::find($input['assigned_user_id'])->name;
            }

            // Obtiene automaticamente el id de la sede del usuario creador
            // $input["ht_sedes_tic_request_id"] = User::select("sedes_id")->find($input["users_id"])->sedes_id;

            // Actualiza el registro de la solicitud
            $ticRequest = $this->ticRequestRepository->update($input, $id);


            // Asigna el id del de la solicitud que se esta actualizando
            $input['ht_tic_requests_id'] = $ticRequest->id;
            // Asigna el id del usuario logueado el cual crea el registro de historial y el nombre
            $input['users_id']    = Auth::user()->id;
            $input['users_name']    = Auth::user()->name;

            // Inserta el registro en el historial de la solicitud
            TicRequestHistory::create($input);

            $ticRequest->users;
            $ticRequest->users->dependencies = $ticRequest->users->dependencies;
            $ticRequest->ticTypeRequest;
            $ticRequest->ticRequestStatus;
            $ticRequest->assignedBy;
            $ticRequest->assignedUser;
            $ticRequest->ticTypeTicCategories;
            $ticRequest->ticMaintenance;
            $ticRequest->ticRequestHistories;
            $ticRequest->ticSatisfactionPolls;
            $ticRequest->ticRequestsDocuments;
            $ticRequest->dependenciaTicRequest;
            $ticRequest = UtilController::stateTimeline($ticRequest);
            // Valida si se esta asignado a un usuario de soporte y el estado sea asignado
            if (isset($assignedUser) && $ticRequest->ht_tic_request_status_id == 2) {
                // Asigna el la solicitud al a los datos de usuario
                $assignedUser->request = $ticRequest->toArray();
                // Asunto del email
                $custom = json_decode('{"subject": "Notificación para asignación de caso"}');
                    // Envia notificacion al usuario asignado
                try {
                    SendNotificationController::SendNotification('help_table::tic_requests.email.email_assign_request',$custom,$assignedUser,$assignedUser['email'],'Mesa de ayuda');
                    // Envia notificacion al usuario que asigna la solicitud
                    $custom = json_decode('{"subject": "Su solicitud ha sido asignada"}');
                    SendNotificationController::SendNotification('help_table::tic_requests.email.email_assign_funcionary',$custom,$assignedUser,$ticRequest->requestUser->email,'Mesa de ayuda');

                } catch (\Swift_TransportException $exception) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                } catch (\Exception $exception) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                }
            }if ($ticRequest->ht_tic_request_status_id == 3) {
                $custom = json_decode('{"subject": "Su solicitud está siendo atendida"}');
                $dateAttention =  date('Y-m-d H:i:s');
                $ticRequest->date_attention = $dateAttention;
                    // Envia notificacion al usuario asignado
                try {
                    SendNotificationController::SendNotification('help_table::tic_requests.email.email_process_request',$custom,$ticRequest,$ticRequest->requestUser->email,'Mesa de ayuda');

                } catch (\Swift_TransportException $exception) {
                    // Manejar la excepción de autenticación SMTP aquí
                    $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                } catch (\Exception $exception) {
                    // Por ejemplo, registrar el error
                    $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$exception->getMessage());
                }
            }


        }
        catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$error->getMessage().' -  Linea: '.$error->getLine());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestController - '. Auth::user()->name.' -  Error: '.$e->getMessage().' -  Linea: '.$e->getLine());
            // Retorna error de tipo logico
             return $this->sendSuccess(config('constants.support_message'), 'info');
        }
        // Efectua los cambios realizados
        DB::commit();

        return $this->sendResponse($ticRequest->toArray(), trans('msg_success_update'));
    }

    /**
     * Elimina un TicRequest del almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var TicRequest $ticRequest */
        $ticRequest = $this->ticRequestRepository->find($id);

        dd($ticRequest);

        if (empty($ticRequest)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $ticRequest->delete();

        return $this->sendSuccess(trans('msg_success_drop'));
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {

        $input = $request->all();
        if(array_key_exists("filtros", $input)) {
            $fechas = [];
            $anio = null;
            
                // 1. Decodificar base64
                $cadena = $input['filtros'];
                $filtrosExtras = [];   // Filtros distintos a fechas/año
                $filtrosFecha = [];    // Filtros nuevos de fecha/año

                // 2. Separar condiciones
                $condiciones = explode(' AND ', $cadena);

                foreach ($condiciones as $cond) {
                    $cond = trim($cond);

                    // Detectar y extraer fecha_desde
                    if (str_contains($cond, 'fecha_desde')) {
                        preg_match("/'%([^']+)'/", $cond, $match);
                        $fechas['fecha_desde'] = str_replace('%', '', $match[1] ?? null);
                        continue;
                    }

                    // Detectar y extraer fecha_hasta
                    if (str_contains($cond, 'fecha_hasta')) {
                        preg_match("/'%([^']+)'/", $cond, $match);
                        $fechas['fecha_hasta'] = str_replace('%', '', $match[1] ?? null);
                        continue;
                    }

                    // Detectar año estilo: year LIKE '%2023%'
                    if (str_contains($cond, "year LIKE")) {
                        preg_match("/'%(\d{4})%?'/", $cond, $match);
                        $anio = trim($match[1] ?? '');
                        continue;
                    }

                    // Detectar año estilo: YEAR(created_at) = '2023'
                    if (str_contains($cond, "YEAR(created_at)")) {
                        preg_match("/YEAR\(created_at\)\s*=\s*'(\d{4})'/", $cond, $match);
                        $anio = trim($match[1] ?? '');
                        continue;
                    }

                    // Guardar cualquier otro filtro
                    $filtrosExtras[] = $cond;
                }

                // 3. Construir condiciones nuevas de fechas
                if (!empty($fechas['fecha_desde']) && !empty($fechas['fecha_hasta'])) {
                    $filtrosFecha[] = "created_at BETWEEN '{$fechas['fecha_desde']} 00:00:00' AND '{$fechas['fecha_hasta']} 23:59:59'";
                } elseif (!empty($fechas['fecha_desde'])) {
                    $filtrosFecha[] = "created_at >= '{$fechas['fecha_desde']} 00:00:00'";
                } elseif (!empty($fechas['fecha_hasta'])) {
                    $filtrosFecha[] = "created_at <= '{$fechas['fecha_hasta']} 23:59:59'";
                }

                // 4. Agregar condición de año si existe
                if (!empty($anio)) {
                    $filtrosFecha[] = "YEAR(created_at) = '{$anio}'";
                }

                // 5. Recombinar todo
                $condicionesFinales = array_merge($filtrosExtras, $filtrosFecha);
                $request['f'] = implode(' AND ', $condicionesFinales);
                $input["filtros"] = $request['f'];
            if($input["filtros"] != "") {
                $input["data"] = TicRequest::with(['ticTypeRequest', 'ticRequestStatus', 'assignedBy', 'assignedUser', 'ticMaintenances', 'ticKnowledgeBases','ticTypeTicCategories','ticTypeAssets'])
                    ->with(['users' => function ($query) {
                        $query->with(['dependencies']);
                    }])
                    ->when(Auth::user()->id, function ($query) {
                        // Valida si el usuario logueado es un tecnico tic o un proveedor
                        if (Auth::user()->hasRole('Soporte TIC') || Auth::user()->hasRole('Proveedor TIC')) {
                            $query->where('assigned_user_id', Auth::user()->id);
                        }
                        // Valida si el usuario logueado usuario normal tic
                        else if (Auth::user()->hasRole('Usuario TIC')) {
                            $query->where('users_id', Auth::user()->id);
                        }
                        return $query;
                    })->whereRaw($input["filtros"])->latest()->get()->map(function($request, $key) {
                        return UtilController::stateTimeline($request);
                    })->toArray();
            } else {
                $input["data"] = TicRequest::with(['ticTypeRequest', 'ticRequestStatus', 'assignedBy', 'assignedUser', 'ticMaintenances', 'ticKnowledgeBases','ticTypeTicCategories','ticTypeAssets'])
                    ->with(['users' => function ($query) {
                        $query->with(['dependencies']);
                    }])
                    ->when(Auth::user()->id, function ($query) {
                        // Valida si el usuario logueado es un tecnico tic o un proveedor
                        if (Auth::user()->hasRole('Soporte TIC') || Auth::user()->hasRole('Proveedor TIC')) {
                            $query->where('assigned_user_id', Auth::user()->id);
                        }
                        // Valida si el usuario logueado usuario normal tic
                        else if (Auth::user()->hasRole('Usuario TIC')) {
                            $query->where('users_id', Auth::user()->id);
                        }
                        return $query;
                    })->latest()->get()->map(function($request, $key) {
                        return UtilController::stateTimeline($request);
                    })->toArray();
            }

        }

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        $fileName = date('Y-m-d H:i:s').'-'.trans('Tic Requests').'.'.$fileType;

        return Excel::download(new RequestExport('help_table::tic_requests.report_excel', JwtController::generateToken($input['data']), 'R'), $fileName);
    }

    public function boardConsolidatedRequests($state){
        // Obtiene el listado de las solicitudes
        $requestsTic = RequestTic::latest()
        ->get();
    }

    /**
     * Muestra la vista para ver el listado de solicitudes en el televisor.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function getRequestsTv() {

        // Obtiene los estados de las solicitudes
        $ticRequestStatuses = TicRequestStatus::with(['ticRequests'])->get()
        ->map(function($requestStatus, $key) {
            $requestStatus->request_counter = count($requestStatus->ticRequests);

            $requestStatus->ticRequests = $requestStatus->ticRequests->map(function($request, $key) {
                return UtilController::stateTimeline($request);
            });

            $requestStatus->on_time     = $requestStatus->ticRequests->where('status_name', $requestStatus->name.'<br>(A tiempo)')->count();
            $requestStatus->next_defeat = $requestStatus->ticRequests->where('status_name', $requestStatus->name.'<br>(Próximo a vencer)')->count();
            $requestStatus->expired     = $requestStatus->ticRequests->where('status_name', $requestStatus->name.'<br>(Vencida)')->count();

            return $requestStatus;

        });
        return view('help_table::tic_requests.index_tv')->with('consolidatedRequestBoard', $ticRequestStatuses);

    }

    /**
     * Obtiene todos los elementos existentes para la vista del televisor
     *
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function allTv() {

        // $ticRequests = TicRequest::
        //     with(['ticTypeRequest', 'ticRequestStatus', 'assignedBy', 'assignedUser', 'ticTypeTicCategories', 'ticMaintenances', 'ticSatisfactionPolls', 'ticKnowledgeBases'])
        //     ->with(['ticRequestHistories' => function ($query) {
        //         $query->with(['users']);
        //         $query->with(['ticTypeRequest']);
        //         $query->with(['ticRequestStatus']);
        //         $query->with(['assignedBy']);
        //         $query->with(['assignedUser']);
        //         $query->with(['ticTypeTicCategories']);
        //     }])
        //     ->with(['users' => function ($query) {
        //         $query->with(['dependencies']);
        //     }])
        //     // ->where('ht_tic_request_status_id', '!=', 4)
        //     // ->where('ht_tic_request_status_id', '!=', 5)
        //     // ->where('ht_tic_request_status_id', '!=', 6)
        //     ->latest()
        //     ->get()
        //     ->map(function($request, $key) {
        //         $request = UtilController::stateTimeline($request);

        //         $request->ticRequestHistories = $request->ticRequestHistories->map(function($history, $key) {
        //             return UtilController::stateTimeline($history);
        //         });
        //         return $request;
        //     });

        // $tic_requests = TicRequest::
        //     with(['ticTypeRequest', 'ticRequestStatus', 'assignedBy', 'assignedUser', 'ticTypeTicCategories', 'ticMaintenances', 'ticSatisfactionPolls', 'ticKnowledgeBases'])
        //     ->with(['ticRequestHistories' => function ($query) {
        //         $query->with(['users']);
        //         $query->with(['ticTypeRequest']);
        //         $query->with(['ticRequestStatus']);
        //         $query->with(['assignedBy']);
        //         $query->with(['assignedUser']);
        //         $query->with(['ticTypeTicCategories']);
        //     }])
        //     ->with(['users' => function ($query) {
        //         $query->with(['dependencies']);
        //     }])
        //     ->where('ht_tic_request_status_id', '!=', 4)
        //     ->where('ht_tic_request_status_id', '!=', 5)
        //     ->where('ht_tic_request_status_id', '!=', 6)
        $ticRequests = TicRequest::
            with(['ticTypeRequest', 'ticRequestStatus', 'assignedBy', 'assignedUser'])
            ->with(['users' => function ($query) {
                $query->with(['dependencies']);
            }])
            // ->where('ht_tic_request_status_id', '!=', 4)
            // ->where('ht_tic_request_status_id', '!=', 5)
            // ->where('ht_tic_request_status_id', '!=', 6)
            ->latest()
            ->get()
            ->map(function($request, $key) {
                $request = UtilController::stateTimeline($request);
                return $request;
            });
        return $this->sendResponse($ticRequests->toArray(), trans('data_obtained_successfully'));

        //         $request->ticRequestHistories = $request->ticRequestHistories->map(function($history, $key) {
        //             return UtilController::stateTimeline($history);
        //         });
        //         return $request;
        //     });

        // return $this->sendResponse($tic_requests->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Actualiza el estado de una solicitud.
     *
     * @author Carlos Moises Garcia T. - Mar. 19 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function updateStatus(Request $request) {

        $input = $request->all();

        /** @var Request $request */
        $ticRequestOld = $this->ticRequestRepository->find($input['id']);

        if (empty($ticRequestOld)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        // DB::beginTransaction();

        // try {
            if (Auth::user()->hasRole('Usuario TIC') && $input['ht_tic_request_status_id'] == 7) {
                $input['ht_tic_request_status_id'] = 1;
                $input['reshipment_date'] = date('Y-m-d H:i:s');
            }

            // Obtiene los datos del estado de la solicitud
            $ticRequestStatus = TicRequestStatus::where('id', $input['ht_tic_request_status_id'])->first();

            $input['request_status'] = $ticRequestStatus->name;

            $ticRequest = $this->ticRequestRepository->update($input, $input['id']);

            // Asigna el id del de la solicitud que se esta actualizando
            $input['ht_tic_requests_id'] = $ticRequest->id;
            // Asigna el id del usuario logueado el cual crea el registro de historial
            $input['users_id']    = Auth::user()->id;

            // Inserta el registro en el historial de la solicitud
            TicRequestHistory::create($input);

            $ticRequest->users;
            $ticRequest->users->dependencies = $ticRequest->users->dependencies;
            $ticRequest->ticTypeRequest;
            $ticRequest->ticRequestStatus;
            $ticRequest->assignedBy;
            $ticRequest->assignedUser;
            $ticRequest->ticTypeTicCategories;
            $ticRequest->ticMaintenances;
            $ticRequest->ticRequestHistories;
            $ticRequest->ticSatisfactionPolls;
            $ticRequest->ticRequestsDocuments;

            $ticRequest = UtilController::stateTimeline($ticRequest);

        // } catch (\Exception $e) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Envia mensaje de error
        //     return $this->sendError(trans('msg_error_update'));
        // }
        // // Efectua los cambios realizados
        // DB::commit();

        return $this->sendResponse($ticRequest->toArray(), trans('msg_success_update'));
    }


    /**
     * Valida si un usuario puede poner solicitudes
     *
     * @author Carlos Moises Garcia T. - Ago. 27 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function validateRegistrationRequests() {

        // Obtiene la fecha actual
        // $currentDate = date('Y-m-d');
        // Obtiene el numero permitido de solicitudes por dia
        $requestsAllowed = config('help_table.requests_allowed');

        // Obtiene los datos de la solicitudes pendientes por encuesta
        // $requestsPendingPoll = TicRequest::where('users_id', Auth::user()->id)->where('ht_tic_request_status_id', 4)->whereDate('created_at', '<', $currentDate)->count();

        // Obtiene los datos de la solicitudes pendientes por encuesta
        $requestsPendingPoll = TicRequest::where('users_id', Auth::user()->id)->where('ht_tic_request_status_id', '!=', 5)->where('ht_tic_request_status_id', '!=', 8)->count();

        // Obtiene el numero de solicitudes que ha puesto en el dia actual
        // $requests = TicRequest::where('users_id', Auth::user()->id)->where('ht_tic_request_status_id', '!=', 4)->count();

        // // Obtiene el numero de solicitudes que ha dado respuesta a al encuesta el dia actual
        // $requestsPoll = TicRequest::where('users_id', Auth::user()->id)->where('ht_tic_request_status_id', 5)->count();

        // Valida si tiene encuestas pendientes por responder
        if ($requestsPendingPoll >= $requestsAllowed) {
            return $this->sendSuccess('No es posible generar una nueva solicitud, recuerde que son 3 solicitudes permitidas o tiene encuestas pendientes por diligenciar. Por favor verificar para poder generar una nueva solicitudes.<br><b>¡Muchas gracias!</b>', 'info');
        }
        // Valida si tiene mas de las solicitudes permitidas por dia
        // else if ($totalRequests >= $requestsAllowed) {
        //     return $this->sendMessage('No es posible generar una nueva solicitud, ha llegado al límite permitido por día.', 'info');
        // }
        else {
            return $this->sendSuccess('ok', 'success');
        }
    }

    public function getStatuses($status){
        $getStatus = TicRequest::where('ht_tic_request_status_id', $status)->get();

        return $this->sendResponse($getStatus->toArray(), trans('msg_success_update'));
    }

         /**
     * Envia las dependencias
     *
     * @author Nicolas Dario Ortiz P. - Ago. 27 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDependenciasTicRequest($id) {

        $dependencias=DependenciaTicRequest::where('ht_sedes_tic_request_id',$id)->get();

        return $this->sendResponse($dependencias->toArray(), trans('data_obtained_successfully'));
    }

            /**
     * Envia las sedes
     *
     * @author Nicolas Dario Ortiz P. - Ago. 27 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getSedesTicRequest() {
        
        $dependencias=SedeTicRequest::where('estado','Activo')->get();
       
       
        return $this->sendResponse($dependencias->toArray(), trans('data_obtained_successfully'));
    }

    public function emailExpiredCron() {
        // Consultar las tareas que se vencen dentro de una hora (3541 a 3600 segundos)
        // y dentro de un día (86340 a 86400 segundos)
        $requests = TicRequest::whereRaw('
        notification_expired is NULL
        AND 
            ((TIMESTAMPDIFF(SECOND, NOW(), prox_date_to_expire) BETWEEN 3541 AND 3600) 
            OR 
            (TIMESTAMPDIFF(SECOND, NOW(), prox_date_to_expire) BETWEEN 86340 AND 86400))
            
        ')
        ->select('id','assigned_user_name','assigned_user_id')
        ->get()
        ->toArray();
        foreach ($requests as $request ) {
            //Busca el usuario asignado
            $user = User::find($request['assigned_user_id']);
    
            $custom = json_decode('{"subject": " Solicitud Próxima a Vencer"}');
            // Envia notificacion al usuario asignado
            SendNotificationController::SendNotification('help_table::tic_requests.email.email_to_expiration',$custom,$request,$user['email'],'Mesa de ayuda');
            //Cambia el estado de la notificacion a 1
            TicRequest::where('id',$request['id'])->update(['notification_expired' => 1]);

        }
    
    }
    
}
