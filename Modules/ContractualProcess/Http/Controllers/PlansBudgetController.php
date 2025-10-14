<?php

namespace Modules\ContractualProcess\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ContractualProcess\Models\InvestmentTechnicalSheet;
use Modules\ContractualProcess\Models\NoveltiesPaa;
use Modules\ContractualProcess\Models\AlternativeBudget;
use Modules\ContractualProcess\Models\FunctioningNeed;
use Modules\ContractualProcess\Models\Budget;
use App\User;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendMail;
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
class PlansBudgetController extends AppBaseController {


    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct() {
        
    }

    /**
     * Muestra la vista para el CRUD de PlansBudget.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('contractual_process::plans_budgets.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $plans_budgets = $this->plansBudgetRepository->all();
        return $this->sendResponse($plans_budgets->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {

        $input = $request->all();

        try {
            // Inserta el registro en la base de datos
            $plansBudget = $this->plansBudgetRepository->create($input);

            return $this->sendResponse($plansBudget->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PlansBudgetController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PlansBudgetController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $input = $request->all();

        /** @var PlansBudget $plansBudget */
        $plansBudget = $this->plansBudgetRepository->find($id);

        if (empty($plansBudget)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        try {
            // Actualiza el registro
            $plansBudget = $this->plansBudgetRepository->update($input, $id);
        
            return $this->sendResponse($plansBudget->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PlansBudgetController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PlansBudgetController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un PlansBudget del almacenamiento
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

        /** @var PlansBudget $plansBudget */
        $plansBudget = $this->plansBudgetRepository->find($id);

        if (empty($plansBudget)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        try {
            // Elimina el registro
            $plansBudget->delete();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PlansBudgetController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PlansBudgetController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('plans_budgets').'.'.$fileType;

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
     * Guarda la evalucion del presupuesto de las necesidades
     *
     * @author Carlos Moises Garcia T. - Jun. 29 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function evaluateBudgetPaa(Request $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida que el tipo de necesidad no este vacio
            if (!empty($input['type_need'])) {


                // Valida si se esta aprobando la necesidad
                if ($input['process_budget'] == 1) {

                    
                    // Obtiene el usuario lider del proceso
                    $leader = User::find($input['user_leader_id']);

                    // Asigna el id del estado
                    $state = $this->getObjectOfList(config('contractual_process.paa_needs_status'), 'name', 'Aprobada')->id;
                    $state_name = "Aprobada";

                    // Obtiene los usurios con el rol de gestor de recursos
                    $users = User::role('PC Gestor de recursos')->get();

                    foreach ($users as $user) {
                        $user->leader_name = $leader->name;
                        // Asunto del email
                        $custom = json_decode('{"subject": "Necesidad aprobada"}');
                        // Envia el correo al lider
                        // Mail::to($user)->send(new SendMail('contractual_process::plans_budgets.email_approve_need', $user, $custom));
                        SendNotificationController::SendNotification('contractual_process::plans_budgets.email_approve_need',$custom,$user,$user->email,'Proceso contractual');

                    }
                } else {
                    // Asigna el id del estado
                    $state = $this->getObjectOfList(config('contractual_process.paa_needs_status'), 'name', 'Devuelta')->id;
                    $state_name = "Devuelta";

                    // Obtiene el usuario lider del proceso
                    $user = User::find($input['user_leader_id']);
                    // Asunto del email
                    $custom = json_decode('{"subject": "Necesidad devuelta"}');
                    // Envia el email al usuario
                    // Mail::to($user)->send(new SendMail('contractual_process::plans_budgets.email_return_need', $user, $custom));
                    SendNotificationController::SendNotification('contractual_process::plans_budgets.email_return_need',$custom,$user,$user->email,'Proceso contractual');

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
                    'pc_needs_id'  =>  $input['need_id'],
                    'users_id'     => Auth::user()->id,
                    'user_name'    => Auth::user()->name,
                    'kind_novelty' => $state_name,
                    'observation'  => (!empty($input['observation'])? $input['observation']: 'Necesidad aprobada'),
                    'attached'     => $attached,
                ]);

                // Valida el tipo de necesidad es funcionamiento
                if ($input['type_need'] == "functioning") {
                    // Obtiene la necesidad por el id y luego la actualiza
                    $functioningNeed = FunctioningNeed::find($input['id']);
                    $functioningNeed->state = $state;
                    $functioningNeed->save();

                    // Efectua los cambios realizados
                    DB::commit();

                    return $this->sendResponse($functioningNeed->toArray(), trans('msg_success_save'), 'success');
                }
                // Valida el tipo de necesidad es inversion
                else if ($input['type_need'] == "investment") {

                    // Valida si el estado es devuelta
                    if ($state == 3) {
                        // Actualiza el estado de la ficha
                        $investmentTechnicalSheet = InvestmentTechnicalSheet::find($input['investment_technical_sheets_id']);
                        $investmentTechnicalSheet->state = $state;
                        $investmentTechnicalSheet->save();
                    }

                    // Obtiene la necesidad por el id y luego la actualiza
                    $investmentNeed = Budget::find($input['id']);
                    $investmentNeed->state = $state;
                    $investmentNeed->save();

                    // Efectua los cambios realizados
                    DB::commit();

                    return $this->sendResponse($investmentNeed->toArray(), trans('msg_success_save'), 'success');
                }
            }
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PlansBudgetController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('contractual_process', 'Modules\ContractualProcess\Http\Controllers\PlansBudgetController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }
}
