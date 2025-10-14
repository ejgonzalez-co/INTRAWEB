<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use App\Exports\ContractualProcess\PaaCallExport;
use Modules\ContractualProcess\Http\Requests\CreatePaaCallRequest;
use Modules\ContractualProcess\Http\Requests\UpdatePaaCallRequest;
use Modules\ContractualProcess\Repositories\PaaCallRepository;
use Modules\ContractualProcess\Models\PaaCall;
use Modules\ContractualProcess\Models\Need;
use Modules\ContractualProcess\Models\ProcessLeaders;
use Modules\ContractualProcess\Models\NoveltiesPaa;
use Modules\ContractualProcess\Models\FunctioningNeed;
use Modules\ContractualProcess\Models\Budget;
use Modules\ContractualProcess\Models\PaaVersion;
use App\Http\Controllers\AppBaseController;
use App\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
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
 * @author Calos Moises Garcia T. - Ago. 03 - 2021
 * @version 1.0.0
 */
class PaaCallController extends AppBaseController {

    /** @var  PaaCallRepository */
    private $paaCallRepository;

    /**
     * Constructor de la clase
     *
     * @author Calos Moises Garcia T. - Ago. 03 - 2021
     * @version 1.0.0
     */
    public function __construct(PaaCallRepository $paaCallRepo) {
        $this->paaCallRepository = $paaCallRepo;
    }

    /**
     * Muestra la vista para el CRUD de PaaCall.
     *
     * @author Calos Moises Garcia T. - Ago. 03 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('contractual_process::paa_calls.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Calos Moises Garcia T. - Ago. 03 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $paacalls = PaaCall::latest()
        ->get()
        ->map(function($item) {
            if (!empty($item->needs)) {
                $item->pending_needs = $item->needs()->where([
                    ['state', '!=', 5],
                ])->count();
            }
            return $item;
        });
        return $this->sendResponse($paacalls->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Calos Moises Garcia T. - Ago. 03 - 2021
     * @version 1.0.0
     *
     * @param CreatePaaCallRequest $request
     *
     * @return Response
     */
    public function store(CreatePaaCallRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $input['users_id'] = Auth::user()->id;

            $input['state']  = $this->getObjectOfList(config('contractual_process.pc_paa_calls_status'), 'name', 'Abierta')->id;


            // Valida si se ingresa un adjunto
            if ($request->hasFile('attached')) {
                $input['attached'] = substr($input['attached']->store('public/contractual_process/annual_action_plan'), 7);
            }

            // Inserta el registro en la base de datos
            $paaCall = $this->paaCallRepository->create($input);

            $users = User::role('PC Líder de proceso')->get();

            foreach ($users as $user) {

                // Obtiene los datos del lider del proceso
                $processLeaders = ProcessLeaders::where('users_id', $user->id)
                    ->where('deleted_at', '=', null)
                    ->first();

                // Valida que exista un proceso relacionado al lider
                if (!empty($processLeaders)){
                    // Inserta el registro en la necesidad de la convocatoria
                    $need =  Need::create([
                        'name_process' => $processLeaders->name_process,
                        'state' => $this->getObjectOfList(config('contractual_process.pc_needs_status'), 'name', 'Sin iniciar PAA')->id,
                        'pc_paa_calls_id' => $paaCall->id,
                        'pc_process_leaders_id' => $processLeaders->id,
                    ]);
                    $user->name_process = $processLeaders->name_process;
                    $user->call = $paaCall->toArray();
                    $custom = json_decode('{"subject": "Convocatoría PAA"}');
                    // Envia el correo al lider
                    // Mail::to($user)->send(new SendMail('contractual_process::paa_calls.emails.email_leaders', $user, $custom));
                    SendNotificationController::SendNotification('contractual_process::needs.emails.email_leaders',$custom,$user,$user->email,'Proceso contractual');

                }
            }
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($paaCall->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\PaaCallController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\PaaCallController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Calos Moises Garcia T. - Ago. 03 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatePaaCallRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePaaCallRequest $request) {

        $input = $request->all();

        /** @var PaaCall $paaCall */
        $paaCall = $this->paaCallRepository->find($id);

        if (empty($paaCall)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {

            // Valida si se ingresa un adjunto
            if ($request->hasFile('attached')) {
                $input['attached'] = substr($input['attached']->store('public/contractual_process/annual_action_plan'), 7);
            }

            // Actualiza el registro
            $paaCall = $this->paaCallRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($paaCall->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\PaaCallController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\PaaCallController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un PaaCall del almacenamiento
     *
     * @author Calos Moises Garcia T. - Ago. 03 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var PaaCall $paaCall */
        $paaCall = $this->paaCallRepository->find($id);

        if (empty($paaCall)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // // Obtiene el numero de necesidades relacionadas
        // $numberRelatedNeeds = $paaCall->needs()->count();
        
        // // Valida si existe necesidades relacionadas
        // if ($numberRelatedNeeds > 0) {
        //     return $this->sendSuccess('No se puede eliminar, existen necesidades relacionadas.', 'waning');
        // }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
        
            // Valida si existe necesidades
            // if ($paaCall->needs()) {
                // dd($paaCall->needs());
                foreach ($paaCall->needs()->get() as $need) {
                    // dd($need);
                    $need->functioningNeeds()->delete();

                    foreach ($need->investmentTechnicalSheets()->get() as $investment) {
                        foreach ($investment->alternativeBudgets()->get() as $alternativeBudget) {
                            $alternativeBudget->budgets()->delete();
                        }
                        $investment->alternativeBudgets()->delete();
                    }
                    $need->investmentTechnicalSheets()->delete();
                }
            // }
            // $paaCall->needs()->functioningNeeds->where('condition', 'met')->delete();
            // $paaCall->needs()->investmentTechnicalSheets()->alternativeBudgets()->delete();
            // $paaCall->needs()->investmentTechnicalSheets()->delete();
            $paaCall->needs()->delete();
            $paaCall->delete();
            
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\PaaCallController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ContractualProcess\Http\Controllers\PaaCallController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Jhoan Sebastian Chilito S. - May. 08 - 2020
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function validateClosingDatesCalls() {
        // Obtiene todas las convocatorias que estan en estado abierta
        $paa_calls = PaaCall::where('state', 1)->get();

        foreach ($paa_calls as $pc_call) {
            // Obtiene la fecha actual
            $currentDateTime = strtotime(date('Y-m-d H:i:s'));
            // Obtiene la fecha actual sin hora
            $currentDate = strtotime(date('Y-m-d'));
            // Obtiene la fecha de alerta de cierre de la convocatoria
            $closingAlertDate = strtotime($pc_call->closing_alert_date);
            // Obtiene la fecha de cierre de la convocatoria
            $closingDate = strtotime($pc_call->closing_date);

            // Valida que la fecha actual sea mayor a la fecha de cierre de la convocatoria
            if ($currentDateTime >  $closingDate) {

                // Actualiza el registro de la convocatoria
                $paaCall = $pc_call->update([
                    'state' => $this->getObjectOfList(config('contractual_process.pc_paa_calls_status'), 'name', 'Cerrada')->id,
                ]);

                // Obtiene los lideres del proceso
                $processLeaders = ProcessLeaders::with(['users'])->get();

                $custom = json_decode('{"subject": "Convoncatoria cerrada"}');

                // Recorre los lideres de proceso
                foreach ($processLeaders as $pcLeaders) {
                    // Asigna la convocatoria a los datos del lider
                    $pcLeaders['paaCalls'] = $paaCall;
                    // Envia notificacion al lider que la convocatoria se cerro
                    SendNotificationController::SendNotification('contractual_process::paa_calls.email.s.email_closed_call',$custom,$pcLeaders,$pcLeaders->users,'Proceso contractual');

                }
            }
            else if ($currentDate == $closingAlertDate) {
                // Obtiene los lideres del proceso
                $processLeaders = ProcessLeaders::with(['users'])->get();

                $custom = json_decode('{"subject": "Convoncatoria proxima a cerrar cerrada"}');

                // Recorre los lideres de proceso
                foreach ($processLeaders as $pcLeaders) {
                    // Asigna la convocatoria a los datos del lider
                    $pcLeaders['paaCalls'] = $pc_call;
                    // Envia notificacion al lider que la convocatoria esta proxima a cerrar
                    SendNotificationController::SendNotification('contractual_process::paa_calls.emails.email_call_closure_alert',$custom,$pcLeaders,$pcLeaders->users,'Proceso contractual');

                    // Mail::to($pcLeaders->users)->send(new SendMail('contractual_process::paa_calls.emails.email_call_closure_alert', $pcLeaders));
                    
                }
            }
        }
    }

    /**
     * Guarda la aprobacion de la convocatoria paa
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function approveCallPaa(Request $request) {

        $input = $request->all();

        // Obtiene los datos de la convocatoria
        $paaCall = PaaCall::find($input['call_id']);

        // Valida si existe el registro
        if (empty($paaCall)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Obtiene el numero de necesidades sin aprobar
            $unapprovedNeeds = Need::where('pc_paa_calls_id', $paaCall->id)->where('state', '!=', 5)->count();

            // Valida si tiene necesidades sin aprobar
            if ($unapprovedNeeds > 0) {
                return $this->sendSuccess("Existe necesidades sin aprobar", 'warning');
            } else {
                $input['state']  = $this->getObjectOfList(config('contractual_process.pc_paa_calls_status'), 'name', 'Cerrada')->id;

                $paaCall = $this->paaCallRepository->update([
                    'state' => $input['state']
                ], $paaCall->id);

                // $needs = Need::where('pc_paa_calls_id', $paaCall->id)->get();

                // Obtiene el numero de registros de la version
                $version = PaaVersion::
                    where('pc_paa_calls_id', $paaCall->id)
                    ->orderBy('version_number', 'DESC')
                    ->pluck('version_number')
                    ->first();

                foreach ($paaCall->needs as $need) {
                    // Asigna el id del proceso
                    $needId = $need->id;

                    // Inserta el registro de la novedad
                    $noveltiesPaa = NoveltiesPaa::create([
                        'pc_needs_id'  =>  $needId,
                        'users_id'     => Auth::user()->id,
                        'user_name'    => Auth::user()->name,
                        'kind_novelty' => "Aprobación del PAA",
                        'observation'  => $input['observation'],
                        'attached'     => (!empty($input['attached'])? implode(",", $input['attached']): null),
                    ]);

                    // Valida que no existe registro de versiones
                    if (empty($version)) {

                        // Obtiene las necesidades de funcionamiento
                        $functioningNeeds = FunctioningNeed::where('pc_needs_id', $needId)->get();

                        // Obtiene las necesidades de inversion
                        $investmentNeeds = Budget::
                            with(['alternativeBudgets' => function ($query)  use ($needId) {
                                $query->with(['investmentTechnicalSheets']);
                                $query->whereHas('investmentTechnicalSheets', function($query) use ($needId) {
                                    $query->where('pc_needs_id', $needId);
                                });
                            }])
                            ->get();

                        // Recorre las necesidades de funciomanieto
                        foreach ($functioningNeeds as $key => $functioningNeed) {
                            PaaVersion::create([
                                'pc_paa_calls_id' => $paaCall->id,
                                'dependencias_id' => $need->processLeaders->users->dependencies->id,
                                'version_number'  => 1,
                                'type_need'       => "Recursos propios funcionamiento",
                                'description'     => $functioningNeed['description'],
                                'total_value'     => $functioningNeed['estimated_total_value'],
                            ]);
                        }

                        // Recorre las necesidades de inversion
                        foreach ($investmentNeeds->toArray() as $key => $investmentNeed) {
                            
                            // Valida que exista la relacion
                            if (!empty($investmentNeed['alternative_budgets'])) {
                                PaaVersion::create([
                                    'pc_paa_calls_id' => $paaCall->id,
                                    'dependencias_id' => $need->processLeaders->users->dependencies->id,
                                    'version_number'  => 1,
                                    'type_need'       => "Recursos propios Inversión",
                                    'description'     => $investmentNeed['description'],
                                    'total_value'     => $investmentNeed['total_value'],
                                ]);
                            }
                        }
                    }

                    // Obtiene el usuario lider del proceso
                    $user = User::find($need->processLeaders->users->id);
                    $user->call = $paaCall;
                    // Asunto del email
                    $custom = json_decode('{"subject": "Cierre de la convocatoría PAA"}');
                    // Envia el email al usuario
                    // Mail::to($user)->send(new SendMail('contractual_process::paa_calls.emails.email_closing_call_paa', $user, $custom));
                    SendNotificationController::SendNotification('contractual_process::paa_calls.emails.email_closing_call_paa',$custom,$user,$user->email,'Proceso contractual');

                }
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($paaCall->toArray(), trans('msg_success_save'), 'success');
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PaaCallsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PaaCallsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Exporta en excel el PAA
     *
     * @author Carlos Moises Garcia T. - Jul. 15 - 2021
     * @version 1.0.0
     *
     * @param 
     */
    public function exportPaaCall($id, Request $request) {

        // Obtiene los datos de la convocatoria
        $paaCall = PaaCall::find($id);

        $data;
        $data[0]['validity'] = $paaCall->validity;

        // Valida que no venga por parametro un numero de version
        if (empty($request->v)) {
            // Obtiene el numero de registros de la version
            $version = PaaVersion::
            where('pc_paa_calls_id', $paaCall->id)
            ->orderBy('version_number', 'DESC')
            ->pluck('version_number')
            ->first();
        } else {
            $version = $request->v;
        }
        // Obtiene el numero de registros de la version
        $needs = PaaVersion::where('pc_paa_calls_id', $paaCall->id)->where('version_number', $version)->get();
        
        $totalValue = 0;
        $accountant = 2;

        // Recorre la lista de necesidades
        foreach ($needs as $need) {
            $data[$accountant]['dependency_name'] = $need->version_number;
            $data[$accountant]['version_number'] = $need->version_number;
            $data[$accountant]['type_need']   = $need->type_need;
            $data[$accountant]['description'] = $need->description;
            $data[$accountant]['total_value'] = $need->total_value;

            $totalValue = $totalValue + $need->total_value;
            $accountant ++;
        }

        $data[1]['total_value'] = $totalValue;

        // Nombre del archivo
        $fileName = /*date('Y-m-d H:i:s').'-'.*/trans('PAA_').$paaCall->validity.'.xlsx';

        return Excel::download(new PaaCallExport('contractual_process::paa_calls.paa_report_excel', $data), $fileName);
    }

    /**
     * Cambia de versión el PAA
     *
     * @author Carlos Moises Garcia T. - Jul. 26 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     */
    public function changeVersionPaa(Request $request) {
        // Obtiene los datos de la convocatoria
        $input = $request->all();

        $paaCall = PaaCall::find($input['call_id']);

        // Valida que existe datos de la convocatoria
        if (empty($paaCall)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Obtiene el numero de registros de la version
            $version = PaaVersion::
            where('pc_paa_calls_id', $paaCall->id)
            ->orderBy('version_number', 'DESC')
            ->pluck('version_number')
            ->first();

            foreach ($paaCall->needs as $need) {
                // Asigna el id del proceso
                $needId = $need->id;

                // Inserta el registro de la novedad
                $noveltiesPaa = NoveltiesPaa::create([
                    'pc_needs_id'  =>  $needId,
                    'users_id'     => Auth::user()->id,
                    'user_name'    => Auth::user()->name,
                    'kind_novelty' => "Cambio de versión del PAA",
                    'observation'  => $input['observation'],
                    'attached'     => (!empty($input['attached'])? implode(",", $input['attached']): null),
                ]);

                // Obtiene las necesidades de funcionamiento
                $functioningNeeds = FunctioningNeed::where('pc_needs_id', $needId)->get();

                // Obtiene las necesidades de inversion
                $investmentNeeds = Budget::
                    with(['alternativeBudgets' => function ($query)  use ($needId) {
                        $query->with(['investmentTechnicalSheets']);
                        $query->whereHas('investmentTechnicalSheets', function($query) use ($needId) {
                            $query->where('pc_needs_id', $needId);
                        });
                    }])
                    ->get();

                // Recorre las necesidades de funciomanieto
                foreach ($functioningNeeds as $key => $functioningNeed) {
                    PaaVersion::create([
                        'pc_paa_calls_id' => $paaCall->id,
                        'dependencias_id' => $need->processLeaders->users->dependencies->id,
                        'version_number'  => $version + 1,
                        'type_need'       => "Recursos propios funcionamiento",
                        'description'     => $functioningNeed['description'],
                        'total_value'     => $functioningNeed['estimated_total_value'],
                    ]);
                }

                // Recorre las necesidades de inversion
                foreach ($investmentNeeds->toArray() as $key => $investmentNeed) {
                    
                    // Valida que exista la relacion
                    if (!empty($investmentNeed['alternative_budgets'])) {
                        PaaVersion::create([
                            'pc_paa_calls_id' => $paaCall->id,
                            'dependencias_id' => $need->processLeaders->users->dependencies->id,
                            'version_number'  => $version + 1,
                            'type_need'       => "Recursos propios Inversión",
                            'description'     => $investmentNeed['description'],
                            'total_value'     => $investmentNeed['total_value'],
                        ]);
                    }
                }
            }

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($paaCall->toArray(), trans('msg_success_save'), 'success');

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PaaCallsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PaaCallsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Calos Moises Garcia T. - Ago. 03 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function getPaaVersions($callId) {
        $paaVersions = PaaVersion::
            select('*', DB::raw('SUM(total_value) AS total'))
            ->orderBy('version_number', 'DESC')
            ->groupBy('version_number')
            ->get();
        // dd($paaVersions);
        
        
        // PaaVersion::
        //     select(DB::raw('pc_paa_versions.*'))
        //     ->where('pc_paa_calls_id', $callId)
        //     ->groupBy('version_number')
        //     ->get();
        return $this->sendResponse($paaVersions->toArray(), trans('data_obtained_successfully'));
    }
}
