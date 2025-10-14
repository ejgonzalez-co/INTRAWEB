<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ImprovementPlans\Http\Requests\CreateImprovementPlanRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateImprovementPlanRequest;
use Modules\ImprovementPlans\Repositories\ImprovementPlanRepository;
use Modules\ImprovementPlans\Models\ImprovementPlan;
use Modules\ImprovementPlans\Models\Goal;
use Modules\ImprovementPlans\Models\GoalActivity;
use Modules\ImprovementPlans\Models\ImprovementOpportunity;
use Modules\ImprovementPlans\Models\EvaluationHistory;
use Modules\ImprovementPlans\Models\User;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\RolPermission;
use Modules\ImprovementPlans\Models\GoalResponsible;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Crypt;
use Modules\ImprovementPlans\Models\Evaluation;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ImprovementPlanController extends AppBaseController {

    /** @var  ImprovementPlanRepository */
    private $improvementPlanRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ImprovementPlanRepository $improvementPlanRepo) {
        $this->improvementPlanRepository = $improvementPlanRepo;
    }

    /**
     * Muestra la vista para el CRUD de ImprovementPlan.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        // Obtiene los roles permitidos
        $allowedRoles = $this->getAllowedRoles();

        // Obtiene la instancia del usuario en sesion
        $userLogged = Auth::user();
    
        // Valida si el usuario tiene los permisos de editar y generar reportes
        $canManage = $canGenerateReports = false;
        if ($userLogged->hasRole('Registered')) {
            $canManage = $canGenerateReports =  true;
            $canGenerateReports = true;
        } elseif ($userLogged->hasRole($allowedRoles)) {
            $canManage = $canGenerateReports = $this->hasPermissions($userLogged);
        }
        else{
            return abort(403,"No se encuentra autorizado.");
        }
    
        return view('improvementplans::improvement_plans.index')->with("can_manage", $canManage)->with("can_generate_reports", $canGenerateReports);
    }

    /**
    * Obtiene el valor del campo de gestion de contenido.
    *
    * @author Kleverman Salazar Florez. - Ago. 06 - 2023
    * @version 1.0.0
    *
    * @param string $name
    * @return string
    */
    public function getContentManagementSetting(string $name): string
    {
        $setting = ContentManagement::where('name', $name)->first();

        if ($setting) {
            return $setting->color;
        }
        return null;
    }

    /**
     * Obtiene los roles que tienen acceso al modulo.
     * 
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     *
     * @return array
     */
    public function getAllowedRoles(): array
    {
        // $allowedRoles = Rol::whereIn('id', RolPermission::where('module', 'Planes de mejoramiento')->groupBy('role_id')->pluck('role_id'))->pluck('name')->toArray();
        $allowedRoles = ["Planes de mejoramiento - Gestión (crear, editar y eliminar registros)", "Planes de mejoramiento - Reportes", "Planes de mejoramiento - Solo consulta"];
        return $allowedRoles;
    }

    /**
     * Valida si el usuario tiene permisos para generar reportes y gestionar.
     * 
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     * 
     * @param User $user
     * @return bool
     */
    public function hasPermissions($user): bool
    {
        if ($user->hasRole('Registered')) {
            return true;
        }

        $allowedRoles = $this->getAllowedRoles();
        if (!$user->hasRole($allowedRoles)) {
            return false;
        }

        // $rolePermissions = RolPermission::where('role_id', Rol::where('name', $allowedRoles[0])->first()->id)
        //     ->where('module', 'Planes de mejoramiento')->first();

        return $user->hasRole('Planes de mejoramiento - Gestión (crear, editar y eliminar registros)') || $user->hasRole('Planes de mejoramiento - Reportes');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $userLogged = Auth::user();
        $goalResponsible = GoalResponsible::whereRaw('FIND_IN_SET("' . $userLogged->id.'", responsibles_id)')->get()->toArray();

        $evaluationsId = [];

        if(count($goalResponsible) > 0){
            $goalsId = array_column($goalResponsible,"pm_goals_id");

            $goals = Goal::with("opportunity")->whereIn("id",$goalsId)->get()->toArray();

            $evaluationsId = $this->_getIdOfEvaluations($goals);
        }

        $improvement_plans = ImprovementPlan::with("evaluator")->where("evaluated_id",$userLogged->id)->whereNotNull("no_improvement_plan")
        ->orWhereIn("id",$evaluationsId)->where("status","Cerrada")->where("is_accordance","No")->latest()->get()->map(function($evaluation, $key){
            $evaluation["encrypted_id"] = Crypt::encryptString($evaluation["id"]);
            return $evaluation;
        });

        $improvement_plans = ImprovementPlan::with("evaluator")->whereNotNull("no_improvement_plan")
        ->where("evaluated_id",$userLogged->id)->orWhere("evaluator_id",$userLogged->id)->orWhereIn("id",$evaluationsId)->where("status","Cerrada")->where("is_accordance","No")->latest()->get()->map(function($evaluation, $key){
            $evaluation["encrypted_id"] = Crypt::encryptString($evaluation["id"]);
            return $evaluation;
        });

        return $this->sendResponse($improvement_plans->toArray(), trans('data_obtained_successfully'));
    }

    private function _getIdOfEvaluations(array $goals) : array{
        $evaluationsId = [];
        foreach ($goals as $key => $goal) {
            if($goal["opportunity"]["evaluation"]["status_improvement_plan"] == "Aprobado"){
                $evaluationsId[] = $goal["opportunity"]["evaluation"]["id"];
            }
        }

        return $evaluationsId;
    }

    /**
     * Envia el plan de mejoramiento a revision del evaluador
     *
     * @author Desarrollador Seven - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function sendReviewImprovementPlan(int $improvementPlanId)
    {

        $user = Auth::user();
        // Obtiene los oportunidades de mejora no conformes
        $improvementOpportunities = ImprovementOpportunity::with("goals")->where("evaluations_id", $improvementPlanId)
        ->get();
    
        // Calcula el peso total de los oportunidades de mejora no conformes
        $sumOfWeightOpportunities = ImprovementOpportunity::where("evaluations_id", $improvementPlanId)
            ->sum("weight");
        
        // Valida si el peso total de los oportunidades de mejora no conformes es menor que 100
        if ($sumOfWeightOpportunities < 100) {
            return $this->sendSuccess("La suma de los porcentajes de las oportunidades de mejora debe ser igual a 100%. En este momento, el total es de <b>{$sumOfWeightOpportunities}%</b>.<br> <br>Por favor, diríjase a la sección <b>'Ver oportunidades de mejora' <i class='fas fa-list'></i></b> para completar los porcentajes necesarios.", 'info');
        }

        /**
         * Valida si el peso total de las metas de cada criterio alcanza el 100%.
         * Si algún criterio no tiene metas o la suma del peso de sus metas no llega a 100%,
         * muestra un mensaje de error.
         */
        $arrayValidaciones = [];
        $contadorFaltantes = 0;
        foreach ($improvementOpportunities as $key => $oppotunity) {
            // Valida si falta porcentaje
            $faltaPorcentaje = 100 - $oppotunity->sum_weigth_goals;
            
            // Añade al array, ya que pueden ser varios oportunidades de mejora que faltan
            if ($faltaPorcentaje > 0) {
                $arrayValidaciones[] = ($contadorFaltantes + 1) . ". La oportunidad de mejora '<strong>{$oppotunity->name_opportunity_improvement}</strong>' tiene un total del <strong>{$oppotunity->sum_weigth_goals}%</strong> en las metas.<br> Le falta: <strong>{$faltaPorcentaje}%</strong> para alcanzar el 100%.";
            }
        }
        
        if (!empty($arrayValidaciones)) {
            $mensaje = "Falta completar las metas en las siguientes oportunidades de mejora:<br>" . implode("<br>", $arrayValidaciones);
            return $this->sendSuccess($mensaje . "<br><br>Por favor visite la acción del listado '<strong>Ver oportunidades de mejora <i class='fas fa-list'></i></strong>'", 'info');

        }

        $history = new EvaluationHistory();
        $history->pm_evaluations_id = $improvementPlanId;
        $history->users_id = $user->id;
        $history->status = "Revisión del plan de mejoramiento";
        $history->observation = "Se envió a revisión del plan de mejoramiento";
        $history->user_name = $user->name;

        // Actualiza el registro
        $improvement_plans = $this->improvementPlanRepository->update(["status_improvement_plan" => "Revisión del plan de mejoramiento"], $improvementPlanId);
        
        $history->save();
        $improvement_plans->HistoryEvaluation;


    
        return $this->sendResponse($improvement_plans->toArray(), trans('msg_success_update'));
    }
    

    //Envia el plan, crea el plan de mejoramiento cuando da clic en la accion Enviar plan de mejoramiento al responsable del proceso
    public function sendImprovementPlan(int $improvementPlanId)
    {
        $user = Auth::user();

        // Calcula el peso total de los oportunidades de mejora no conformes
        $oportunidades = ImprovementOpportunity::where("evaluations_id", $improvementPlanId)
            ->count();
        
        // Valida si el peso total de los oportunidades de mejora no conformes es menor que 100
        if ($oportunidades == 0) {
            return $this->sendSuccess("Para avanzar, es necesario crear al menos una oportunidad de mejora. Para hacerlo, acceda a la sección <b>'Ver oportunidades de mejora'</b> <i class='fas fa-folder-plus'></i>.", 'info');
        }

        $history = new EvaluationHistory();
        $history->pm_evaluations_id = $improvementPlanId;
        $history->users_id = $user->id;
        $history->status = "Envío del plan de mejoramiento al Responsable del proceso";
        $history->observation = "Se envió a revisión del plan de mejoramiento";
        $history->user_name = $user->name;

        $numeroPlan = Evaluation::where("id", $improvementPlanId)
        ->value("no_improvement_plan");
        
        if (!$numeroPlan) {
            $numeroPlan = Evaluation::whereNotNull("no_improvement_plan")->count() + 1;
        }
    
        // Actualiza el registro
        $improvement_plans = $this->improvementPlanRepository->update(["status" =>"Cerrada","is_accordance"=>"No","execution_percentage","0","status_improvement_plan" => "Pendiente","no_improvement_plan" => $numeroPlan], $improvementPlanId);
   
        $history->save();

        $oportunidades = $improvement_plans->EvaluationImprovementOpportunities->toarray();
        $mensaje = "";
        foreach ($oportunidades as $key => $oportunidad) {
            $mensaje .= "<br>".($key+1).": Criterio de evaluación: ".$oportunidad["evaluation_criteria"]."<br>".
            "Nombre de la oportunidad de mejora: ".$oportunidad["name_opportunity_improvement"]."<br>".
            "Tipo de oportunidad de mejora o no conformidad: ".$oportunidad["type_oportunity_improvements"]["name"]."<br>".
            "Fecha límite de presentación del plan de mejoramiento: ".$oportunidad["deadline_submission"]."<br>";
        }

        $improvement_plans_email = $improvement_plans;
        $improvement_plans_email["mensaje"] = $mensaje;

        // Obtiene el correo del evaluado
        $evaluado = User::select(['email','name'])->where("id",$improvement_plans->evaluated_id)->first();

        UtilController::sendMail([$evaluado->email],"improvementplans::evaluations.mails.notification_new_plan",$improvement_plans_email,"Notificación PMI");
        

        $improvement_plans->HistoryEvaluation;
        $improvement_plans->EvaluationImprovementOpportunities;
        return $this->sendResponse($improvement_plans->toArray(), trans('msg_success_update'));
    }

    /**
     * Calcula el peso total
     *
     * @author Desarrollador Seven - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    private function _calculateTotalWeight($criteria) : float
    {
        $totalWeight = 0;
        foreach ($criteria as $criterion) {
            $criterion->weight = $criterion ? $criterion->weight : 0;
            $totalWeight += $criterion->weight;
        }
        return $totalWeight;
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateImprovementPlanRequest $request
     *
     * @return Response
     */
    public function store(CreateImprovementPlanRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $improvementPlan = $this->improvementPlanRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($improvementPlan->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateImprovementPlanRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImprovementPlanRequest $request) {

        $input = $request->all();

        /** @var ImprovementPlan $improvementPlan */
        $improvementPlan = $this->improvementPlanRepository->find($id);

        if (empty($improvementPlan)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $improvementPlan = $this->improvementPlanRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($improvementPlan->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ImprovementPlan del almacenamiento
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

        /** @var ImprovementPlan $improvementPlan */
        $improvementPlan = $this->improvementPlanRepository->find($id);

        if (empty($improvementPlan)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $improvementPlan->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
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
        $fileName = time().'-'.trans('improvement_plans').'.'.$fileType;

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
     * Envia solicitud de modificacion del plan de mejoramiento a revision del evaluador
     *
     * @author Desarrollador Seven - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function sendRequestImprovementPlan(int $improvementPlanId)
    {
        // Actualiza el registro
        $improvement_plans = $this->improvementPlanRepository->update(["status_improvement_plan" => "Solicitud de modificación"], $improvementPlanId);
    
        return $this->sendResponse($improvement_plans->toArray(), trans('msg_success_update'));
    }


    public function processRequestImprovementPlan(int $improvementPlanId)
    {
        // Actualiza el registro
        $improvement_plans = $this->improvementPlanRepository->update(["status_improvement_plan" => "Pendiente"], $improvementPlanId);
    
        return $this->sendResponse($improvement_plans->toArray(), trans('msg_success_update'));
    }

      /**
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function show($id) {
        $approved_improvement_plans = ImprovementPlan::with(["evaluator","evaluated","EvaluationDependences","EvaluationCriteria","HistoryEvaluation","EvaluationImprovementOpportunities"])->where("id", $id)->first();
        return $this->sendResponse($approved_improvement_plans->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Envia solicitud de modificacion del plan
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function executeProcessModification(Request $request) {

        $user = Auth::user();
        $input = $request->all();
        foreach($input['activities_plans'] as $activity){
            $dataActivity = json_decode($activity);
            Goal::where("id", $dataActivity->id)->update(["status_modification" => 'Si']);
            GoalActivity::where("id", $dataActivity->pm_goals_id)->update(["status_modification" => 'Si']);
        }

        $improvement_plans = $this->improvementPlanRepository->update(["status_improvement_plan" => "Solicitud de modificación"], $input['id']);

        $history = new EvaluationHistory();
        $history->pm_evaluations_id = $input['id'];
        $history->users_id = $user->id;
        $history->status = "Solicitud de modificación";
        $history->observation = $input["observation"];
        $history->user_name = $user->name;
        $history->save();
        $improvement_plans->HistoryEvaluation;

        return $this->sendResponse($improvement_plans, trans('msg_success_update'));
    }

    
    /**
     * consulta las actividades del plan
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getActivitesPlan($planId) {
        $opportunities = ImprovementOpportunity::where("evaluations_id", $planId)->get();
        $goals = Goal::whereIn("pm_improvement_opportunity_id", $opportunities->pluck("id"))->get();
        $activity = GoalActivity::whereIn("pm_goals_id", $goals->pluck("id"))->get();

        return $this->sendResponse($activity->toArray(), trans('data_obtained_successfully'));
    }
    
    
}
