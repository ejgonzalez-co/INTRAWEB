<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ImprovementPlans\Http\Requests\CreateImprovementOpportunityRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateImprovementOpportunityRequest;
use Modules\ImprovementPlans\Repositories\NonConformingCriteriaRepository;
use Modules\ImprovementPlans\Models\NonConformingCriteria;
use App\Http\Controllers\AppBaseController;
use Modules\ImprovementPlans\Models\Evaluation;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\RolPermission;
use Modules\ImprovementPlans\Models\GoalResponsible;
use Modules\ImprovementPlans\Models\Goal;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Crypt;
use Modules\ImprovementPlans\Http\Controllers\UtilController;
use Modules\ImprovementPlans\Models\ImprovementOpportunity;
use Modules\ImprovementPlans\Repositories\ImprovementOpportunityRepository;


/**
 * Este controlador fue cambiado para que se alimente de ImprovementOpportunity
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */


class NonConformingCriteriaController extends AppBaseController {

    /** @var  NonConformingCriteriaRepository */
    private $improvementOpportunityRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ImprovementOpportunityRepository $ImprovementOpportunityRepo) {
        $this->improvementOpportunityRepository = $ImprovementOpportunityRepo;
    }

    /**
     * Muestra la vista para el CRUD de NonConformingCriteria.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        // $allowedRoles = array_column(Rol::select("name")->whereIn("id",array_column(RolPermission::select("role_id")->where("module","Planes de mejoramiento")->groupBy("role_id")->get()->toArray(),"role_id"))->get()->toArray(),"name");
        $allowedRoles = ["Planes de mejoramiento - Gestión (crear, editar y eliminar registros)", "Planes de mejoramiento - Reportes", "Planes de mejoramiento - Solo consulta"];

        $evaluationIdDecrypt = Crypt::decryptString($request["evaluation"]);
        $evaluation = Evaluation::select(["no_improvement_plan","name_improvement_plan"])->where("id",$evaluationIdDecrypt)->first();
        
        $text_header = "Plan de mejoramiento #".$evaluation["no_improvement_plan"]. " ". $evaluation["name_improvement_plan"];
        $execution_percentage = $this->getExecutionPercentajeImprovementPlan($evaluationIdDecrypt);
        $execution_percentage_pm = $this->getExecutionPercentajeImprovementPlanInicial($evaluationIdDecrypt);

        if(Auth::user()->hasRole("Registered")){
            return view('improvementplans::non_conforming_criterias.index',compact(["text_header","execution_percentage","execution_percentage_pm"]))->with("can_manage",true)->with("can_generate_reports",true)->with("decrypt_evaluation_id",$evaluationIdDecrypt);
        }
        if(Auth::user()->hasRole($allowedRoles)){
            // $idRol = Rol::select("id")->where("name",$allowedRoles[0])->first()->id;
            // $rolePermissions = RolPermission::select(["can_manage","can_generate_reports"])->where("role_id",$idRol)->where("module","Planes de mejoramiento")->first();
            return view('improvementplans::non_conforming_criterias.index',compact(["text_header","execution_percentage","execution_percentage_pm"]))->with("can_manage", Auth::user()->hasRole("Planes de mejoramiento - Gestión (crear, editar y eliminar registros)"))->with("can_generate_reports", Auth::user()->hasRole("Planes de mejoramiento - Reportes"))->with("decrypt_evaluation_id",$evaluationIdDecrypt);
        }
        return abort(403,"No autorizado");
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
    public function hasPermissions(User $user): bool
    {
        if ($user->hasRole('Registered')) {
            return true;
        }

        $allowedRoles = getAllowedRoles();
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
    public function all(Request $request) {
        $goalResponsible = GoalResponsible::whereRaw('FIND_IN_SET("' . Auth::user()->id.'", responsibles_id)')->get()->toArray();

        $improvementOpportunity = [];

        if(count($goalResponsible) > 0){
            $goalsId = array_column($goalResponsible,"pm_goals_id");

            $goals = Goal::with("opportunity")->whereIn("id",$goalsId)->get()->toArray();
            $improvementOpportunity = array_column($goals,"pm_improvement_opportunity_id");

            $ownImprovementOpportunities = ImprovementOpportunity::where("evaluations_id",$request["evaluation"])->whereHas("evaluation",function($query){
                $query->where("evaluated_id",Auth::user()->id);
            })->pluck("id")->toArray();

            $improvementOpportunity = count($ownImprovementOpportunities) > 0 ? array_merge($improvementOpportunity,$ownImprovementOpportunities) : $improvementOpportunity;

            $non_conforming_criterias = ImprovementOpportunity::where("evaluations_id",$request["evaluation"])->whereIn("id",$improvementOpportunity)->latest()->get()->map(function($improvementOpportunity, $key){
                $improvementOpportunity["encrypted_id"] = Crypt::encryptString($improvementOpportunity["id"]);
                return $improvementOpportunity;
            });

            return $this->sendResponse($non_conforming_criterias->toArray(), trans('data_obtained_successfully'));
        }

        $non_conforming_criterias = ImprovementOpportunity::where("evaluations_id",$request["evaluation"])->latest()->get()->map(function($improvementOpportunity, $key){
            $improvementOpportunity["encrypted_id"] = Crypt::encryptString($improvementOpportunity["id"]);
            return $improvementOpportunity;
        });
        // $non_conforming_criterias = ImprovementOpportunity::where("evaluations_id",$request["evaluation"])->latest()->get();
        return $this->sendResponse($non_conforming_criterias->toArray(), trans('data_obtained_successfully'));
    }

    public function getExecutionPercentajeImprovementPlan(int $evaluationId) : float{
        $improvementOpportunities = ImprovementOpportunity::where("evaluations_id",$evaluationId)->get()->toArray();
        $criteriasTotalPercentajeExecution = array_sum(array_column($improvementOpportunities,"percentage_execution"));
        return $criteriasTotalPercentajeExecution;
    }

        public function getExecutionPercentajeImprovementPlanInicial(int $evaluationId) : float{
        $improvementOpportunities = ImprovementOpportunity::where("evaluations_id",$evaluationId)->get()->toArray();
        $criteriasTotalPercentajeExecution = 0;
        foreach ($improvementOpportunities as $improvementOpportunity) {
            $criteriasTotalPercentajeExecution += $improvementOpportunity["weight"] * ($improvementOpportunity["percentage_execution"] / 100);
        }
        return $criteriasTotalPercentajeExecution;
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateImprovementOpportunityRequest $request
     *
     * @return Response
     */
    public function store(CreateImprovementOpportunityRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $improvementOpportunity = $this->improvementOpportunityRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($improvementOpportunity->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\NonConformingCriteriaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\NonConformingCriteriaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateImprovementOpportunityRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateImprovementOpportunityRequest $request) {

        $input = $request->all();

        $WEIGHT_LIMIT = 100;

        //valida si solo hay un criterio
        $totalCriterials = ImprovementOpportunity::where("evaluations_id", $input["evaluations_id"])
        ->get()->toArray();

        $quantityImprovementOpportunitiesPending = ImprovementOpportunity::where("evaluations_id", $input["evaluations_id"])->whereNull("weight")->count();

        if(count($totalCriterials) > 1){
            //suma los peso de los criterios excepto el actual
            $weightTotal = ImprovementOpportunity::where("evaluations_id", $input["evaluations_id"])
            ->where("id", "!=", $input["id"])
            ->sum("weight");
    
            $weightAvailable = $WEIGHT_LIMIT - $weightTotal;
    
            // Si es igual a 1 entonces es porque falta el ultimo
            if($quantityImprovementOpportunitiesPending == 1){
                if($input["weight"] != $weightAvailable){
                    return $this->sendSuccess("El peso asignado a esta oportunidad de mejora en el plan debe ser del ". $weightAvailable. "%, ya que es el porcentaje disponible.",'info');
                }
            }
            else{
                $weightAvailable = ($WEIGHT_LIMIT - $weightTotal) - ($quantityImprovementOpportunitiesPending - 1);

                if($input["weight"] != $weightAvailable && $quantityImprovementOpportunitiesPending == 1){
                    return $this->sendSuccess("El peso asignado a esta oportunidad de mejora en el plan debe ser de un rango de 1% a el ". $weightAvailable. "%, ya que hay mas oportunidades de mejora pendiente.",'info');
                }

                if(($input["weight"] > $weightAvailable || $input["weight"] <= 0) && $quantityImprovementOpportunitiesPending > 1){
                    return $this->sendSuccess("El peso asignado a esta oportunidad de mejora en el plan debe ser de un rango de 1% a el ". $weightAvailable. "%, ya que hay mas oportunidades de mejora pendiente.",'info');
                }
            }
        }

        if(count($totalCriterials) == 1 && $input["weight"] < 100){
            return $this->sendSuccess("El peso asignado a esta oportunidad de mejora en el plan debe ser del 100%, ya que es la única oportunidad de mejora dentro de la evaluación.", 'info');
        }
        
        // dd($totalCriterials,$quantityImprovementOpportunitiesPending,array_sum(array_column($totalCriterials,"weight")));
        //suma los peso de los criterios excepto el actual
        $sumOportunidades = ImprovementOpportunity::where("evaluations_id", $input["evaluations_id"])
        ->where("id", "!=", $input["id"])
        ->sum("weight");
        
        //calcula lo disponible
        $disponible = 100 - $sumOportunidades;

        if (($sumOportunidades + $input["weight"]) > 100) {
            $mensaje = "La suma de los porcentajes de las oportunidades de mejora no debe superar el 100%. En la actualidad, el total es <b>{$sumOportunidades}%</b>, siendo el máximo permitido para esta oportunidad de mejora <b>{$disponible}%</b>.";
            return $this->sendSuccess($mensaje, 'info');
        }
    

            $improvementOpportunity = $this->improvementOpportunityRepository->find($id);

            if (empty($improvementOpportunity)) {
                return $this->sendError(trans('not_found_element'), 200);
            }
            
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $improvementOpportunity = $this->improvementOpportunityRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($improvementOpportunity->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\NonConformingCriteriaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\NonConformingCriteriaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un NonConformingCriteria del almacenamiento
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

        $improvementOpportunity = $this->improvementOpportunityRepository->find($id);

        if (empty($improvementOpportunity)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $improvementOpportunity->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\NonConformingCriteriaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\NonConformingCriteriaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('non_conforming_criterias').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $data = JwtController::decodeToken($input["data"]);
            array_walk($data, fn(&$object) => $object = (array) $object);
            return UtilController::exportReportToXlsxFile('improvementplans::non_conforming_criterias.exports.xlsx',$data,'C','Oportunidades_de_mejora.xlsx');
        }
    }
}
