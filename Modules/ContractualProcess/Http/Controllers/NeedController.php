<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\ContractualProcess\NeedsPaaExport;
use Modules\ContractualProcess\Http\Requests\CreateNeedRequest;
use Modules\ContractualProcess\Http\Requests\UpdateNeedRequest;
use Modules\ContractualProcess\Repositories\NeedRepository;
use Modules\ContractualProcess\Models\PaaCall;
use Modules\ContractualProcess\Models\Need;
use Modules\ContractualProcess\Models\NoveltiesPaa;
use Modules\ContractualProcess\Models\FunctioningNeed;
use Modules\ContractualProcess\Models\Budget;
use Modules\ContractualProcess\Models\PaaModificationRequest;
use Modules\ContractualProcess\Models\PaaModificationRequestEvaluation;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use App\Http\Controllers\SendNotificationController;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class NeedController extends AppBaseController {

    /** @var  NeedRepository */
    private $needRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(NeedRepository $needRepo) {
        $this->needRepository = $needRepo;
    }

    /**
     * Muestra la vista para el CRUD de Need.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('contractual_process::needs.index')->with("call", $request['call'] ?? null);
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
        $needs = Need::with(['paaCalls', 'processLeaders', 'functioningNeeds', 'investmentTechnicalSheets', 'paaProcessAttachments'])
        ->with(['paaModificationRequest' => function ($query) {
            $query->where('state', 1);
            $query->with(['paaModificationRequestEvaluations']);
        }])
        ->when($request, function ($query) use($request) {
            // Valida si existe un id de una convocatoria
            if (!empty($request['pc_paa_calls_id'])) {
                return $query->where('pc_paa_calls_id', $request['pc_paa_calls_id']);
            }
        })
        ->whereHas('paaCalls', function($query) {
            $query->where('deleted_at', '=', null);
        })
        ->whereHas('processLeaders', function($query) {
            // Valida cuando el rol es un lider de proceso
            if (Auth::user()->hasRole('PC Líder de proceso') && !Auth::user()->hasRole(['PC Gestor de recursos', 'PC Gestor planeación', 'PC Gestor presupuesto'])) {
                $query->where('users_id', Auth::user()->id);
            }
        })
        ->latest()
        ->get()
        ->map(function($item) {
            // Valida si existe solicitudes de modificacion del paa
            if (!empty($item->paaModificationRequest()->where('state', 1)->first())) {
                // $paaModificationRequestEvaluation = PaaModificationRequestEvaluation::where('');


                // Valida si existe evaluaciones de la solicitudes de modificacion
                if (!empty($item->paaModificationRequest()->where('state', 1)->first()->paaModificationRequestEvaluations())) {

                    $modificationEvaluations = $item->paaModificationRequest()->where('state', 1)->first()->paaModificationRequestEvaluations()->get()->toArray();

                    // dd(array_search(Auth::user()->id, array_column($modificationEvaluations, 'users_id')));
                    if (array_search(Auth::user()->id, array_column($modificationEvaluations, 'users_id')) !== false) {
                        $item->user_modification_request = false;
                    } else {
                        $item->user_modification_request = true;
                    }
                    // dd($item->user_modification_request,  $modificationEvaluations, Auth::user()->id);
                }
            }
            return $item;
        });
        return $this->sendResponse($needs->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateNeedRequest $request
     *
     * @return Response
     */
    public function store(CreateNeedRequest $request) {

        $input = $request->all();

        try {
            // Inserta el registro en la base de datos
            $need = $this->needRepository->create($input);

            return $this->sendResponse($need->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\NeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\NeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateNeedRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateNeedRequest $request) {

        $input = $request->all();

        /** @var Need $need */
        $need = $this->needRepository->find($id);

        if (empty($need)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $need = $this->needRepository->update($input, $id);
        
            return $this->sendResponse($need->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\NeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\NeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un Need del almacenamiento
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

        /** @var Need $need */
        $need = $this->needRepository->find($id);

        if (empty($need)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $need->delete();

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

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('needs').'.'.$fileType;

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

    /**
     * Enviar a revision las necesidades
     *
     * @author Carlos Moises Garcia T. - May. 07 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function sendReviewNeeds($id, Request $request) {

        $input = $request->all();

        $need = Need::find($id);

        if (empty($need)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $stateNeedOld = $need->state;

            // Obtiene la lista de los usuarios con el rol de gestor de recursos y presupuesto
            $users = User::role(['PC Gestor de recursos', 'PC Gestor presupuesto'])->get();

            foreach ($users as $key => $user) {
                $user->name_process = $need->processLeaders->name_process;
                // Asunto del email
                $custom = json_decode('{"subject": "Envió de Necesidades del PAA"}');
                // Envia el email al usuario
                // Mail::to($user)->send(new SendMail('contractual_process::needs.emails.email_revision_needs_paa', $user, $custom));
                SendNotificationController::SendNotification('contractual_process::needs.emails.email_revision_needs_paa',$custom,$user,$user->email,'Proceso contractual');

            }

            if (!$need->assigned_user_id) {
                $userBudget = User::role('PC Gestor presupuesto')->first();
                $input['assigned_user_id'] = $userBudget->id;
            }

            

            //  Valida si el estado del proceso es que PAA habilitado para modificación
            if ($stateNeedOld == 7) {
                $userBudget = User::role('PC Gestor presupuesto')->first();
                $input['assigned_user_id'] = $userBudget->id;
            }
            
            // Actualiza el registro de la necesidad
            $need = $this->needRepository->update($input, $id);
            
            if($stateNeedOld == 7) {
                $need->functioningNeeds()->whereNull('state')->orWhere('state', 2)->update(
                    ['state' => $this->getObjectOfList(config('contractual_process.paa_needs_status'), 'name', 'En revisión')->id],
                );
            } else {
                // Actualiza las necesidades de funcionamiento cuando el estado es nulo o es estado devuelta
                $need->functioningNeeds()->whereNull('state')->orWhere('state', 3)->update(
                    ['state' => $this->getObjectOfList(config('contractual_process.paa_needs_status'), 'name', 'En revisión')->id],
                );
            }

            // Valida que exista fichas de inversion
            if (!empty($need->investmentTechnicalSheets)) {
                // Recorre las fichas de inversion relacionadas
                $need->investmentTechnicalSheets->map(function($item) use ($stateNeedOld) {

                    
                    if($stateNeedOld == 7) {
                        $item->whereNull('state')->orWhere('state', 2)->update(
                            ['state' => $this->getObjectOfList(config('contractual_process.paa_needs_status'), 'name', 'En revisión')->id],
                        );

                        // Valida si la ficha de inversion tiene un presupuesto
                        if (!empty($item->alternativeBudgets)) {
                            // Recorre las necesidades de la ficha de inversion
                            $item->alternativeBudgets->map(function($alternative) {
                                // Actualiza los registros de las necesidades
                                $alternative->budgets()->whereNull('state')->orWhere('state', 2)->update(
                                    ['state' => $this->getObjectOfList(config('contractual_process.paa_needs_status'), 'name', 'En revisión')->id],
                                );
                            });
                        }
                    } else {
                        $item->whereNull('state')->orWhere('state', 3)->update(
                            ['state' => $this->getObjectOfList(config('contractual_process.paa_needs_status'), 'name', 'En revisión')->id],
                        );

                        // Valida si la ficha de inversion tiene un presupuesto
                        if (!empty($item->alternativeBudgets)) {
                            // Recorre las necesidades de la ficha de inversion
                            $item->alternativeBudgets->map(function($alternative) {
                                // Actualiza los registros de las necesidades
                                $alternative->budgets()->whereNull('state')->orWhere('state', 3)->update(
                                    ['state' => $this->getObjectOfList(config('contractual_process.paa_needs_status'), 'name', 'En revisión')->id],
                                );
                            });
                        }
                    }
                    
                });
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($need->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\NeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\NeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Evalua las necesidades
     *
     * @author Carlos Moises Garcia T. - May. 07 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function assessNeedsPaa(Request $request) {

        $input = $request->all();
        // Obtiene las necesidades
        $need = Need::find($input['pc_needs']);

        // Valida si existe necesidades con el id
        if (empty($need)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Valida si se esta aprobando las necesidades del proceso 
        if ($input['process_evaluation'] == "Aprobar") {

            // Obtiene las evaluaciones rechazadas de la solicitud
            $numberApprovals = $need->noveltiesPaa()->where('kind_novelty', 'like', '%Validado por')->where('users_id', Auth::user()->id);

            if ($numberApprovals->count() == 0) {

                if (Auth::user()->hasRole('PC Gestor presupuesto')) {
                    $input['state_name'] = 'Validado por presupuesto';

                    $userPlanning = User::role('PC Gestor planeación')->first();
                    $input['assigned_user_id'] = $userPlanning->id;
                } else if (Auth::user()->hasRole('PC Gestor planeación')) {
                    $userResource = User::role('PC Gestor de recursos')->first();
                    $input['assigned_user_id'] = $userResource->id;
                    $input['state_name'] = 'Validado por planeación';
                } else if (Auth::user()->hasRole('PC Gestor de recursos')) {
                    // Obtiene el id del estado
                    $input['state'] = $this->getObjectOfList(config('contractual_process.pc_needs_status'), 'name', 'PAA finalizado')->id;
                    $input['state_name'] = 'PAA finalizado';
                    // Obtiene el usuario lider del proceso
                    $user = User::find($need->processLeaders->users->id);
                    // Asunto del email
                    $custom = json_decode('{"subject": "Necesidades del PAA aprobadas"}');
                    // Envia el email al usuario
                    // Mail::to($user)->send(new SendMail('contractual_process::needs.emails.email_approved_needs_paa', $user, $custom));
                    SendNotificationController::SendNotification('contractual_process::needs.emails.email_approved_needs_paa',$custom,$user,$user->email,'Proceso contractual');

                }
            }
            else {

                if (Auth::user()->hasRole('PC Gestor presupuesto')) {
                    $input['state_name'] = 'Validado por presupuesto';

                    $userPlanning = User::role('PC Gestor planeación')->first();
                    $input['assigned_user_id'] = $userPlanning->id;
                } else if (Auth::user()->hasRole('PC Gestor planeación')) {
                    $userResource = User::role('PC Gestor de recursos')->first();
                    $input['assigned_user_id'] = $userResource->id;
                    $input['state_name'] = 'Validado por planeación';
                } else if (Auth::user()->hasRole('PC Gestor de recursos')) {
                    // Obtiene el id del estado
                    $input['state'] = $this->getObjectOfList(config('contractual_process.pc_needs_status'), 'name', 'PAA finalizado')->id;
                    $input['state_name'] = 'PAA finalizado';
                    // Obtiene el usuario lider del proceso
                    $user = User::find($need->processLeaders->users->id);
                    // Asunto del email
                    $custom = json_decode('{"subject": "Necesidades del PAA aprobadas"}');
                    // Envia el email al usuario
                    // Mail::to($user)->send(new SendMail('contractual_process::needs.emails.email_approved_needs_paa', $user, $custom));
                    SendNotificationController::SendNotification('contractual_process::needs.emails.email_approved_needs_paa',$custom,$user,$user->email,'Proceso contractual');

                }
            }
        }
        // Valida si se esta devolviendo las necesidades del proceso 
        else if ($input['process_evaluation'] == "Devolver") {
            // Obtiene el id del estado
            $input['state'] = $this->getObjectOfList(config('contractual_process.pc_needs_status'), 'name', 'PAA devuelto')->id;
            $input['state_name'] = 'PAA devuelto';
            // Obtiene el usuario lider del proceso
            $user = User::find($need->processLeaders->users->id);
            // Asunto del email
            $custom = json_decode('{"subject": "Necesidades del PAA devueltas"}');
            // Envia el email al usuario
            // Mail::to($user)->send(new SendMail('contractual_process::needs.emails.email_return_needs_paa', $user, $custom));
            SendNotificationController::SendNotification('contractual_process::needs.emails.email_return_needs_paa',$custom,$user,$user->email,'Proceso contractual');

        }

        // Valida si viene adjuntos
        if (!empty($input['attached'])) {
            // Valida si viene multiple adjuntos
            if (gettype($input['attached']) == 'array') {
                $attached = implode(",", $input['attached']);
            }else {
                $attached = $input['attached'];
            }
        } else{
            $attached = null;
        }

        // Inserta el registro de la novedad
        $noveltiesPaa = NoveltiesPaa::create([
            'pc_needs_id'  => $need->id,
            'users_id'     => Auth::user()->id,
            'user_name'    => Auth::user()->name,
            'kind_novelty' => $input['state_name'],
            'observation'  => $input['observation'],
            'attached'     =>  $attached,
        ]);
        
        // Actualiza el registro de la necesidad del paa
        $need = $this->needRepository->update($input, $input['pc_needs']);

        return $this->sendResponse($need->toArray(), trans('msg_success_save'));
    }

    /**
     * Obtiene todas las novedades relacionadas a las necesidades
     *
     * @author Carlos Moises Garcia T. - May. 20 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getNoveltiesPaa($id) {
        // Obtiene las novedades relacionadas al id de la necesidad
        $noveltiesPaa = NoveltiesPaa::with(['users' => function ($query) {
            $query->with(['dependencies']);
        }])
        ->where('pc_needs_id', $id)->latest()->get();
        return $this->sendResponse($noveltiesPaa->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Exporta en excel las necesidades aprobadas
     *
     * @author Carlos Moises Garcia T. - May. 20 - 2021
     * @version 1.0.0
     *
     * @param 
     */
    public function exportApprovedNeeds($id) {
        // Nombre del archivo
        $fileName = date('Y-m-d H:i:s').'-'.trans('Needs').'.xlsx';

        $functioningNeeds = FunctioningNeed::where('pc_needs_id', $id)->get();

        $investmentNeeds = Budget::
            with(['alternativeBudgets' => function ($query)  use ($id) {
                $query->with(['investmentTechnicalSheets']);
                $query->whereHas('investmentTechnicalSheets', function($query) use ($id) {
                    $query->where('pc_needs_id', $id);
                });
            }])
            ->get();

        $data;
        $contador = 0;

        foreach ($functioningNeeds as $key => $functioningNeed) {
            $data[$contador]['type_need'] = "Funcionamiento";
            $data[$contador]['description'] = $functioningNeed['description'];
            $data[$contador]['total_value'] = $functioningNeed['estimated_total_value'];
            $contador ++;
        }

        foreach ($investmentNeeds->toArray() as $key => $investmentNeed) {
            if (!empty($investmentNeed['alternative_budgets'])) {
                // dd($investmentNeed);
                $data[$contador]['type_need'] = "Inversión";
                $data[$contador]['description'] = $investmentNeed['description'];
                $data[$contador]['total_value'] = $investmentNeed['total_value'];
                $contador ++;
            }
        }

        return Excel::download(new NeedsPaaExport('contractual_process::needs.report_excel', $data), $fileName);
    }

    /**
     * Obtiene las necesidades de la convocatoria que estan sin evaluar
     *
     * @author Carlos Moises Garcia T. - Jul. 06 - 2021
     * @version 1.0.0
     *
     * @param $id identificador de la convocatoria
     */
    public function getUnassessedNeedsPaa($id) {
        $paaCall = PaaCall::find($id);

        // Valida si existe la convocatoria
        if ($paaCall) {
            $needs = $paaCall->needs->where('state', 2)->toArray();
        } else {
            $needs = [];
        }
        return $this->sendResponse($needs, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene las necesidades de la convocatoria que estan sin evaluar
     *
     * @author Carlos Moises Garcia T. - Jul. 06 - 2021
     * @version 1.0.0
     *
     * @param $id identificador de la convocatoria
     */
    public function notifyUnassessedNeedsPaa($id) {

        $needs = Need::with(['processLeaders'])
        ->where([
            ['state', '!=', 3],
            ['state', '!=', 5],
        ])
        ->whereHas('paaCalls', function($query) use ($id){
            $query->where('id', $id);
        })
        ->get();

        // Valida si hay proceso con necesidades pendientes
        if (!empty($needs->toArray())) {
            foreach ($needs as $need) {
                // Obtiene el usuario lider del proceso
                $user = User::find($need->processLeaders->users->id);
                // Asunto del email
                $custom = json_decode('{"subject": "Necesidades pendientes del PAA"}');
                // Envia el email al usuario
                // Mail::to($user)->send(new SendMail('contractual_process::needs.emails.email_notify_unassessed_needs', $user, $custom));
                SendNotificationController::SendNotification('contractual_process::needs.emails.email_notify_unassessed_needs',$custom,$user,$user->email,'Proceso contractual');

            }
        }
        return $this->sendResponse($needs->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Solicita la modificacion del PAA
     *
     * @author Carlos Moises Garcia T. - Jul. 12 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     */
    public function requestModificationPaa(Request $request) {

        $input = $request->all();
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Obtiene los datos de la necesidad
            $need = Need::find($input['id']);

            // Obtiene el id del estado
            $input['state'] = $this->getObjectOfList(config('contractual_process.pc_needs_status'), 'name', 'Solicitud de modificación de PAA')->id;

            // Actualiza el registro de la necesidad del paa
            $need = $this->needRepository->update([
                'state' => $input['state'],
            ], $need->id);

            // Valida si viene adjuntos
            if (!empty($input['attached'])) {
                // Valida si viene multiple adjuntos
                if (gettype($input['attached']) == 'array') {
                    $attached = implode(",", $input['attached']);
                }else {
                    $attached = $input['attached'];
                }
            } else{
                $attached = null;
            }

            // Inserta el registro de la novedad
            $noveltiesPaa = NoveltiesPaa::create([
                'pc_needs_id'  => $need->id,
                'users_id'     => Auth::user()->id,
                'user_name'    => Auth::user()->name,
                'kind_novelty' => 'Solicitud de modificación de PAA',
                'observation'  => $input['observation'],
                'attached'     => $attached,
            ]);

            $paaModificationRequest = PaaModificationRequest::create([
                'pc_needs_id'  => $need->id,
                'description'  => $input['observation'],
                'attached'     => $attached,
                'state' => $this->getObjectOfList(config('contractual_process.paa_modification_request'), 'name', 'En revisión')->id,
            ]);

            // Obtiene los usuarios dependiendo del rol
            $users = User::role(['PC Gestor de recursos', 'PC Gestor presupuesto', 'PC Gestor planeación'])->get();

            // Recorre los usuarios
            foreach ($users as $key => $user) {
                $user->name_process = $need->processLeaders->name_process;
                // Asunto del email
                $custom = json_decode('{"subject": "Solicitud de modificación o adición del PAA"}');
                // Envia el email al usuario
                // Mail::to($user)->send(new SendMail('contractual_process::needs.emails.email_request_modification_needs', $user, $custom));
                SendNotificationController::SendNotification('contractual_process::needs.emails.email_request_modification_needs',$custom,$user,$user->email,'Proceso contractual');

            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($need->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\NeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\NeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Procesa la solicitud de modificacion del PAA
     *
     * @author Carlos Moises Garcia T. - Jul. 13 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     */
    public function processModificationRequestPaa(Request $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Obtiene los datos de la necesidad
            $need = Need::find($input['id']);

            // Obtiene los datos de la solicitud de modificacion
            $paaModificationRequest = PaaModificationRequest::find($input['request_id']);

            // Obtiene las evaluaciones de la solicitud de modificacion
            // $paaModificationRequestEvaluations = $paaModificationRequest->paaModificationRequestEvaluations();

            // Obtiene las evaluaciones aprobadas de la solicitud
            $approvedEvaluations = $paaModificationRequest->paaModificationRequestEvaluations()->where('state', 2);

            // Obtiene las evaluaciones rechazadas de la solicitud
            $rejectedEvaluations = $paaModificationRequest->paaModificationRequestEvaluations()->where('state', 3)->where('users_id', '!=', Auth::user()->id)->get()->toArray();
            
            // Condicion que valida si existe calificaciones rechazadas
            if ($rejectedEvaluations) {
                return $this->sendSuccess('No se puede aprobar la solicitud, porque ha sido rechazada por el usuario:<br>'.$rejectedEvaluations[0]['user_name'], 'warning');
            }

            // dd($paaModificationRequestEvaluations->where('state', 1)->count());

            // Obtiene el usuario lider del proceso
            $user = User::find($need->processLeaders->users->id);

            // Valida si se esta aprobando la modificacion
            if ($input['process_request'] == 1) {

                // Asigna el id del estado de la evaluacion
                $state = $this->getObjectOfList(config('contractual_process.paa_modification_request'), 'name', 'PAA habilitado para modificación')->id;
                $state_name = "PAA habilitado para modificación";

                // Valida si ya existe dos calificaciones aprobadas
                if ($approvedEvaluations->count() == 2) {
                    // Asigna el estado de la necesidad
                    $state_need = $this->getObjectOfList(config('contractual_process.pc_needs_status'), 'name', 'PAA habilitado para modificación')->id;

                    $paaModificationRequest->update([
                        'state' => $state,
                    ]);

                     // Asunto del email
                    $custom = json_decode('{"subject": "PAA habilitado para modificación"}');
                    // Envia el email al usuario
                    // Mail::to($user)->send(new SendMail('contractual_process::needs.emails.email_enable_modification_paa', $user, $custom));
                    SendNotificationController::SendNotification('contractual_process::needs.emails.email_enable_modification_paa',$custom,$user,$user->email,'Proceso contractual');

                } else {
                    $state_need = $need->state;
                }
            } else if ($input['process_request'] == 2) {

                // Estado de la solicitud de modificacion
                $state = $this->getObjectOfList(config('contractual_process.paa_modification_request'), 'name', 'Rechazada')->id;

                // Asigna el id del estado a la necesidad
                $state_need = $this->getObjectOfList(config('contractual_process.pc_needs_status'), 'name', 'PAA finalizado')->id;
                $state_name = "PAA finalizado";
            }

            // Actualiza el registro de la necesidad del paa
            $need = $this->needRepository->update([
                'state' => $state_need,
            ], $need->id);

            // Valida si viene adjuntos
            if (!empty($input['attached'])) {
                // Valida si viene multiple adjuntos
                if (gettype($input['attached']) == 'array') {
                    $attached = implode(",", $input['attached']);
                }else {
                    $attached = $input['attached'];
                }
            } else{
                $attached = null;
            }

            // Inserta el registro de la novedad
            $noveltiesPaa = NoveltiesPaa::create([
                'pc_needs_id'  => $need->id,
                'users_id'     => Auth::user()->id,
                'user_name'    => Auth::user()->name,
                'kind_novelty' => $state_name,
                'observation'  => (!empty($input['observation'])? $input['observation']: null),
                'attached'     => $attached,
            ]);

            $paaModificationRequestEvaluation = PaaModificationRequestEvaluation::create([
                'pc_paa_modification_requests_id'  => $paaModificationRequest->id,
                'users_id'     => Auth::user()->id,
                'user_name'    => Auth::user()->name,
                'description'  => (!empty($input['observation'])? $input['observation']: null),
                'attached'     => $attached,
                'state'        => $state,
            ]);

            // Valida si se esta rechazando la modificacion
            if ($input['process_request'] == 2) {
                // Asunto del email
                $custom = json_decode('{"subject": "PAA rechazado para modificación"}');
                $user->novelty = $noveltiesPaa;
                // Envia el email al usuario
                // Mail::to($user)->send(new SendMail('contractual_process::needs.emails.email_reject_modification_paa', $user, $custom));
                SendNotificationController::SendNotification('contractual_process::needs.emails.email_reject_modification_paa',$custom,$user,$user->email,'Proceso contractual');

            }

            if (!empty($need->paaModificationRequest()->where('state', 1)->first())) {
                // Valida si existe evaluaciones de la solicitudes de modificacion
                if (!empty($need->paaModificationRequest()->where('state', 1)->first()->paaModificationRequestEvaluations())) {
    
                    $modificationEvaluations = $need->paaModificationRequest()->where('state', 1)->first()->paaModificationRequestEvaluations()->get()->toArray();

                    if (array_search(Auth::user()->id, array_column($modificationEvaluations, 'users_id')) !== false) {
                        $need->user_modification_request = false;
                    } else {
                        $need->user_modification_request = true;
                    }
                }
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($need->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\NeedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\NeedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }
}
