<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ImprovementPlans\Repositories\ClosedImprovementPlanRepository;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\ClosedImprovementPlan;
use Modules\ImprovementPlans\Models\NonConformingCriteria;
use Modules\ImprovementPlans\Models\Goal;
use Modules\ImprovementPlans\Models\GoalProgress;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Http\Requests\UpdateClosedImprovementPlanRequest;
use Modules\ImprovementPlans\Models\RolPermission;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\ImprovementPlans\Http\Controllers\UtilController;
use Modules\ImprovementPlans\Models\EvaluationCriterion;
use Modules\ImprovementPlans\Models\EvaluationDependence;
use Illuminate\Support\Facades\Crypt;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ClosedImprovementPlanController extends AppBaseController {

    /** @var  ClosedImprovementPlanRepository */
    private $closedImprovementPlanRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ClosedImprovementPlanRepository $closedImprovementPlanRepo) {
        $this->closedImprovementPlanRepository = $closedImprovementPlanRepo;
    }

    /**
     * Muestra la vista para el CRUD de ClosedImprovementPlan.
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
    
        return view('improvementplans::closed_improvement_plans.index')->with("can_manage", $canManage)->with("can_generate_reports", $canGenerateReports);
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
        // $allowedRoles = Rol::whereIn('id', RolPermission::where('module', 'Evaluadores')->groupBy('role_id')->pluck('role_id'))->pluck('name')->toArray();
        $allowedRoles = ["Evaluadores - Gestión (crear, editar y eliminar registros)", "Evaluadores - Reportes", "Evaluadores - Solo consulta"];
        return $allowedRoles;
    }

    /**
     * Valida si el usuario tiene permisos para generar reportes y gestionar.
     * 
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     * 
     * @param $user
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
        //     ->where('module', 'Evaluadores')->first();

        return $user->hasRole('Evaluadores - Gestión (crear, editar y eliminar registros)') || $user->hasRole('Evaluadores - Reportes');
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
        $closed_improvement_plans = ClosedImprovementPlan::with(["evaluator","evaluated","EvaluationDependences","EvaluationCriteria"])->latest()->get()->map(function($closed_improvement_plan, $key){
            $closed_improvement_plan["encrypted_id"] = Crypt::encryptString($closed_improvement_plan["id"]);
            return $closed_improvement_plan;
        });;
        return $this->sendResponse($closed_improvement_plans->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateClosedImprovementPlanRequest $request
     *
     * @return Response
     */
    public function store(CreateClosedImprovementPlanRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si adjuntó documentos para el cierre del plan
            if (isset($input["evidencias_cierre_plan"])) {
                $input['evidencias_cierre_plan'] = implode(",", (array) $input["evidencias_cierre_plan"]);
            } 
            // Inserta el registro en la base de datos
            $closedImprovementPlan = $this->closedImprovementPlanRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($closedImprovementPlan->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ClosedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ClosedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateClosedImprovementPlanRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateClosedImprovementPlanRequest $request) {

        $input = $request->all();

        /** @var ClosedImprovementPlan $closedImprovementPlan */
        $closedImprovementPlan = $this->closedImprovementPlanRepository->find($id);

        if (empty($closedImprovementPlan)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si adjuntó documentos para el cierre del plan
            if (isset($input["evidencias_cierre_plan"])) {
                $input['evidencias_cierre_plan'] = implode(",", (array) $input["evidencias_cierre_plan"]);
            } 
            // Actualiza el registro
            $closedImprovementPlan = $this->closedImprovementPlanRepository->update($input, $id);
            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($closedImprovementPlan->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ClosedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ClosedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ClosedImprovementPlan del almacenamiento
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

        /** @var ClosedImprovementPlan $closedImprovementPlan */
        $closedImprovementPlan = $this->closedImprovementPlanRepository->find($id);

        if (empty($closedImprovementPlan)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $closedImprovementPlan->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ClosedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ClosedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('closed_improvement_plans').'.'.$fileType;

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

    public function exportImprovementPlan(int $improvementPlanId, Request $request) {
        $input = $request->all();
        $improvementPlan = ClosedImprovementPlan::where("id",$improvementPlanId)->with(["evaluator","evaluated","EvaluationDependences","EvaluationCriteria","EvaluationImprovementOpportunities"])->get()->toArray();
        // dd($improvementPlan);
        // Obtiene los id de los criterios no conformes
        $nonConformingCriterias = NonConformingCriteria::select("id")->where("evaluations_id",$improvementPlan[0]["id"])->where("status","No conforme")->get()->toArray();
        $nonConformingCriteriasId = array_column($nonConformingCriterias,"id");

        // Obtiene las metas de los criterios no conformes
        $goals = Goal::with(["GoalActivities","GoalDependencies"])->whereIn("pm_evaluation_criteria_id",$nonConformingCriteriasId)->get()->toArray();

        // Obtiene los avances de las meats
        $goalsProgress = GoalProgress::with(["GoalActivities","Goals"])->whereIn("pm_goals_id",array_column($goals,"id"))->get()->toArray();

        // $arreglo[0]["reporte"] = $input["reporte"];
        $arreglo[0]["evaluacion"] = $improvementPlan;
        $arreglo[0]["plan_mejoramiento"] = $improvementPlan;
        $arreglo[0]["cierre_final"] = $improvementPlan;
        $arreglo[0]["goals_progress"] = $goalsProgress;
        
        // Descarga el archivo generado
        return UtilController::exportReportToXlsxFile('improvementplans::closed_improvement_plans.exports.xlsx_improvement_plan_closed', $arreglo, 'g' ,'Reporte de la evaluación.xlsx');
    }

    /**
     * Actualiza un los adjuntos según la clave del objeto
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
        $input['evidencias_cierre_plan'] = isset($input["new_route"]) ? implode(",", $input["new_route"]) : null;
    
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $internal = $this->internalRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($internal->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }
}
