<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ImprovementPlans\Http\Requests\CreateGoalRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateGoalRequest;
use Modules\ImprovementPlans\Repositories\GoalRepository;
use Modules\ImprovementPlans\Models\Goal;
use Modules\ImprovementPlans\Models\GoalActivity;
use Modules\ImprovementPlans\Models\GoalProgress;
use Modules\ImprovementPlans\Models\GoalDependencies;
use Modules\ImprovementPlans\Models\Evaluation;
use Modules\ImprovementPlans\Models\EvaluationCriterion;
use Modules\ImprovementPlans\Models\ImprovementOpportunity;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\RolPermission;
use Modules\ImprovementPlans\Models\GoalResponsible;
use App\User;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\JwtController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Crypt;
use Modules\ImprovementPlans\Http\Controllers\UtilController;
use Maatwebsite\Excel\Concerns\ToArray;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class GoalController extends AppBaseController
{

    /** @var  GoalRepository */
    private $goalRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(GoalRepository $goalRepo)
    {
        $this->goalRepository = $goalRepo;
    }

    /**
     * Muestra la vista para el CRUD de Goal.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request)
    {
        $improvementIdDecrypt = Crypt::decryptString($request["improvement-opportunity"]);
        // $improvement_opportunity = EvaluationCriterion::select(["evaluations_id","description_cause_analysis","weight","possible_causes","criteria_name"])->where("id",$improvementIdDecrypt)->first();

        $improvement_opportunity = ImprovementOpportunity::where("id",$improvementIdDecrypt)->first();

        $evaluation = Evaluation::select(["no_improvement_plan", "name_improvement_plan"])->where("id", $improvement_opportunity->evaluations_id)->first();

        $text_header = "Plan de mejoramiento #" . $evaluation["no_improvement_plan"] . " " . $evaluation["name_improvement_plan"];

        $execution_percentage = $this->getExecutionPercentageGoal($request);

        $totalPercentageCompliance = Goal::where("pm_improvement_opportunity_id",$improvementIdDecrypt)->sum("goal_weight");
    
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
    
        return view('improvementplans::goals.index', compact(["text_header", "execution_percentage", "improvement_opportunity", "totalPercentageCompliance"
        ]))->with("can_manage", $canManage)->with("can_generate_reports", $canGenerateReports)->with("decrypt_improvement_opportunity_id", $improvementIdDecrypt);
    }

    public function getExecutionPercentageGoal(Request $request) : float{
        $criteriaExecutionPercentageGoal = ImprovementOpportunity::where("id",Crypt::decryptString($request["improvement-opportunity"]))->first()->percentage_execution;
        return $criteriaExecutionPercentageGoal;
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
    public function all(Request $request)
    {
        $goalResponsible = GoalResponsible::whereRaw('FIND_IN_SET("' . Auth::user()->id.'", responsibles_id)')->get()->toArray();

        $improvementOpportunity = [];

        if(count($goalResponsible) > 0){
            $goalsResponsibleId = array_column($goalResponsible,"pm_goals_id");

            $goals = Goal::with("opportunity")->where("pm_improvement_opportunity_id",$request["improvement-opportunity"])->get()->toArray();

            foreach ($goals as $goal) {
                if($goal["opportunity"]["evaluation"]["evaluated_id"] == Auth::user()->id){
                    $goalsResponsibleId[]= $goal["id"];
                }
            }

            $goals = Goal::with(["GoalActivitiesCuantitativas","GoalResponsibles", "GoalActivitiesCualitativas", "GoalDependencies"])
            ->where('pm_improvement_opportunity_id',"=", $request["improvement-opportunity"])
            ->whereIn('id', $goalsResponsibleId)
            ->orderBy('created_at', 'desc') // Ordenar por created_at de forma descendente
            ->get()
            ->map(function($goal, $key) {
                $goal["encrypted_id"] = Crypt::encryptString($goal["id"]);
                return $goal;
            });

            return $this->sendResponse($goals->toArray(), trans('data_obtained_successfully'));
        }

        $goals = Goal::with(["GoalActivitiesCuantitativas","GoalResponsibles", "GoalActivitiesCualitativas", "GoalDependencies"])
            ->where("pm_improvement_opportunity_id", $request["improvement-opportunity"])
            ->orderBy('created_at', 'desc') // Ordenar por created_at de forma descendente
            ->get()
            ->map(function($goal, $key) {
                $goal["encrypted_id"] = Crypt::encryptString($goal["id"]);
                return $goal;
            });
        

                
        return $this->sendResponse($goals->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateGoalRequest $request
     *
     * @return Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        // Inicia la transaccion
        try {

             /*Explicacion. Se tuvo que separar goal_activities y goal_activities_cualitativas y goal_activities_cuantitativas porque el componente de dinamic-list no podia limpiar el listado ya ingresado entonces si cambiaba de tipo de meta por ej. de Cuantitativa a Cualitativa al ser formularios diferentes quedaba el listado mal  */
            if ($input['goal_type'] == "Cualitativa") {
                $input["goal_activities"] = isset($input["goal_activities_cualitativas"]) ? $input["goal_activities_cualitativas"] : null;
            }
            if ($input['goal_type'] == "Cuantitativa") {
                $input["goal_activities"] = isset($input["goal_activities_cuantitativas"]) ? $input["goal_activities_cuantitativas"] : null;
            }
             // Realiza las validaciones
            $validacionResult = $this->_validacionesMeta($input);

            // Verifica si hubo problemas en las validaciones
            if ($validacionResult !== null) {
                // Hubo problemas, por lo tanto, retorna el mensaje de validación
                return $this->sendSuccess($validacionResult, 'info');
            }
        
            DB::beginTransaction();

            // Inserta el registro en la base de datos
            $goal = $this->goalRepository->create($input);

            //crea las actividades
            foreach ($input["goal_activities"] as $goalActivity) {
                $activity = json_decode($goalActivity);
                $this->_addGoalActivity(
                    $goal["id"],
                    $activity->activity_name,
                    $activity->status_modification = "Creado",
                    $activity->activity_quantity ?? 0, // Utiliza 0 como valor predeterminado si activity_quantity es null
                    $activity->activity_weigth,
                    $activity->baseline_for_goal ?? 0,
                    $activity->gap_meet_goal ?? 0,
                    $activity->goal_type = $input['goal_type'],
                    $activity->start_date ?? null,
                    $activity->end_date ?? null
                ); 
            }


            $exist_goal_dependencies = array_key_exists("goal_dependencies", $input);

            if($exist_goal_dependencies != false) {
                // Asigna las dependencias y las crea
                foreach ($input["goal_dependencies"] as $goalDependence) {
                    $dependence = json_decode($goalDependence);
                    $this->_addGoalDependence($goal["id"], $dependence->dependence_name);
                }
            }


            // UtilController::sendMail(["klevermansalazar23@gmail.com"],"improvementplans::goals.mails.dependencies_notification",$goal,"Notificación PMI"); // Envia las notificaciones a los usuarios de las dependencias

            // Efectua los cambios realizados
            DB::commit();

            // Relaciones
            $goal->GoalActivities;
            $goal->GoalActivitiesCuantitativas;
            $goal->GoalActivitiesCualitativas;
            $goal->GoalDependencies;
            $goal->GoalResponsibles;

            return $this->sendResponse($goal->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $goalId id de la meta
     * @param string $activityName nombre de la actividad
     * @param int $activityQuantity cantidad de la actividad
     * @param string $activityWeigth peso de la actividad
     * @param string $baselineForGoal linea base para la meta
     * @param string $gapMeetGoal brecha para cumplimiento de la meta
     *
     * @return Response
     */
    private function _addGoalActivity(int $goalId, string $activityName, string $statusModification, int $activityQuantity, string $activityWeigth, string $baselineForGoal, string $gapMeetGoal, string $goalType,?string $startDate,?string $endDate ): void
    {
        GoalActivity::create(["pm_goals_id" => $goalId, "activity_name" => $activityName, "status_modification" => $statusModification, "activity_quantity" => $activityQuantity, "activity_weigth" => $activityWeigth, "baseline_for_goal" => $baselineForGoal, "gap_meet_goal" => $gapMeetGoal, "goal_type"  =>$goalType,"start_date" => $startDate, "end_date" => $endDate]);
    }

    /**
     * Realiza validaciones específicas para la creación de metas.
     *
     * Esta función realiza varias validaciones para garantizar que los datos ingresados
     * cumplan con los requisitos establecidos para la creación de metas. Verifica la suma
     * de pesos de las metas, la existencia de actividades y dependencias, y la validez de los
     * pesos de las actividades ingresadas.
     *
     * @param array $input Los datos de entrada que contienen información sobre la meta.
     *                    Debe contener al menos los siguientes elementos:
     *                    - pm_improvement_opportunity_id: ID del criterio de evaluación.
     *                    - goal_weight: Peso de la meta.
     *                    - goal_activities: Lista de actividades asociadas a la meta.
     *                    - goal_dependencies: Lista de dependencias asociadas a la meta.
     *
     * @return string|null Si se encuentran problemas durante las validaciones, la función
     *                    devuelve un mensaje descriptivo del problema. Si no hay problemas,
     *                    devuelve null.
     *
     * @throws \Exception En caso de error o condiciones no previstas durante las validaciones.
     */
    private function _validacionesMeta($input){
        /** Inicia Validaciones */
        // 1. Consulta las metas que existen para el criterio y valida que aún tenga disponible espacio

        if(isset($input["id"])){
            $CantGoals = Goal::where("pm_improvement_opportunity_id", $input["pm_improvement_opportunity_id"])
            ->where("id", "!=", $input["id"]) // Excluye la meta actual
            ->get()
            ->sum("goal_weight");

        }else{
            $CantGoals = Goal::where("pm_improvement_opportunity_id", $input["pm_improvement_opportunity_id"])
            ->get()
            ->sum("goal_weight");
        }

        $disponible = 100 - $CantGoals;
    
        if (($CantGoals + $input["goal_weight"]) > 100) {
            return "La suma de los porcentajes de las metas no debe ser superior al 100%. Actualmente cuenta con <b>{$CantGoals}%</b>, el máximo permitido para esta meta sería de <b>{$disponible}%</b>.";
        }
    
        // 2. Validacion de actividades y dependencias, valida que existan, que haya ingresado al menos 1
        // validacion de actividades debe existir al menos una
        if (!isset($input["goal_activities"])) {
            return "Por favor, ingrese al menos una actividad. Complete el formulario de <b>'Actividades que componen la meta'</b> y haga clic en el botón <b>'Agregar actividad'</b>.";
        }
        
        // validacion de dependencias debe existir al menos una
        // if (!isset($input["goal_dependencies"])) {
        //     return "Por favor, seleccione al menos una dependencia en el campo <b>'Nombre de la dependencia'</b> y haga clic en el botón <b>'Agregar Dependencia'</b>.";
        // }
    
        // 3. Validacion de actividades por peso, valida que el peso de las actividades que ingreso sea 100
        // recorre las actividades y suma el peso de todas en $totalPesoActividades


        $totalPesoActividades= 0;
    
        foreach ($input["goal_activities"] as $goalActivity) {
            $activity = json_decode($goalActivity);
            $totalPesoActividades += $activity->activity_weigth;  
        }

        if(isset($input["id"])){
            $sumActivity = GoalActivity::where("pm_goals_id", $input['id'])
            ->whereNull("status_modification")
            ->orWhere("status_modification", "Si")
            ->get()->sum("activity_weigth");
    
            $totalPesoActividades = $totalPesoActividades + $sumActivity;
        }
    
        // si el peso de la actividades es mayor a 100 o menos a 100 entonces retorna y muestra mensaje
        if ($totalPesoActividades < 100) {
            return "Por favor, cree más actividades. La suma de los porcentajes de las actividades ingresadas no alcanza el 100%. Actualmente tiene {$totalPesoActividades}%, le falta: <b>" . (100 - $totalPesoActividades) . "%</b>.";
        }
    
        return null;
        /** Fin de las validaciones */
    }
    
    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $goalId id de la meta
     * @param string $dependenceName nombre de la dependencia
     *
     * @return Response
     */
    private function _addGoalDependence(int $goalId, string $dependenceName): void
    {
        GoalDependencies::create(["pm_goals_id" => $goalId, "dependence_name" => $dependenceName]);
    }
    
    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request)
    {

        $input = $request->all();
        try {

            /*Explicacion. Se tuvo que separar goal_activities y goal_activities_cualitativas y goal_activities_cuantitativas porque el componente de dinamic-list no podia limpiar el listado ya ingresado entonces si cambiaba de tipo de meta por ej. de Cuantitativa a Cualitativa al ser formularios diferentes quedaba el listado mal  */
            if ($input['goal_type'] == "Cualitativa") {
                $input["goal_activities"] = isset($input["goal_activities_cualitativas"]) ? $input["goal_activities_cualitativas"] : null;
            }
            if ($input['goal_type'] == "Cuantitativa") {
                $input["goal_activities"] = isset($input["goal_activities_cuantitativas"]) ? $input["goal_activities_cuantitativas"] : null;
            }
            // Realiza las validaciones
            $validacionResult = $this->_validacionesMeta($input);

            // Verifica si hubo problemas en las validaciones
            if ($validacionResult !== null) {
                // Hubo problemas, por lo tanto, retorna el mensaje de validación
                return $this->sendSuccess($validacionResult, 'info');
            }
        

            /** @var Goal $goal */
            $goal = $this->goalRepository->find($id);

            if (empty($goal)) {
                return $this->sendError(trans('not_found_element'), 200);
            }

            // Inicia la transaccion
            DB::beginTransaction();
            // Elimina los criterios y dependencias
            GoalActivity::where("pm_goals_id",$id)->delete();
            GoalDependencies::where("pm_goals_id",$id)->delete();

            
            // Actualiza el registro
            $goal = $this->goalRepository->update($input, $id);

            //crea las actividades
            foreach ($input["goal_activities"] as $goalActivity) {
                $activity = json_decode($goalActivity);
                $this->_addGoalActivity(
                    $goal["id"],
                    $activity->activity_name,
                    $activity->status_modification = "Creado",
                    $activity->activity_quantity ?? 0, // Utiliza 0 como valor predeterminado si activity_quantity es null
                    $activity->activity_weigth,
                    $activity->baseline_for_goal ?? 0,
                    $activity->gap_meet_goal ?? 0,
                    $activity->goal_type = $input['goal_type'],
                    $activity->start_date ?? null,
                    $activity->end_date ?? null
                ); 
            }

            $exist_goal_dependencies = array_key_exists("goal_dependencies", $input);

            if ($exist_goal_dependencies != false) {
                // Asigna las dependencias y las crea
                foreach ($input["goal_dependencies"] as $goalDependence) {
                    $dependence = json_decode($goalDependence);
                    $this->_addGoalDependence($goal["id"], $dependence->dependence_name);
                }
            }


            // Efectua los cambios realizados
            DB::commit();

            // Relaciones
            $goal->GoalActivities;
            $goal->GoalActivitiesCuantitativas;
            $goal->GoalActivitiesCualitativas;
            $goal->GoalDependencies;
            $goal->GoalResponsibles;

            return $this->sendResponse($goal->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
        }
    }

    public function assignResponsibleToActivity(Request $request){
        $input = $request->all();

        $userLogged = Auth::user();

        if(empty($input["goal_responsibles"])){
            return $this->sendSuccess("Por favor seleccione una actividad y los responsables y de click en el botón <strong>Agregar responsable</strong>", 'info');
        }

        $activity = json_decode($input["goal_responsibles"][0],true);
        
        // Elimina los responsables que estaban antes
        GoalResponsible::where("pm_goals_id",$activity["activity_object"]["pm_goals_id"])->delete();

        // Asigna los criterios de evaluacion
        foreach ($input["goal_responsibles"] as $responsible) {
            $responsible_data = json_decode($responsible,true);

            // Realiza la consulta para posterior obtener el nombre del plan de mejoramiento
            $goal = Goal::select(["goal_name","pm_improvement_opportunity_id"])->with("opportunity")->where("id",$responsible_data["activity_object"]["pm_goals_id"])->first()->toArray();

            // Obtiene la informacion del plan de mejoramiento
            $improvement_plan_name = $goal["opportunity"]["evaluation"]["name_improvement_plan"];
            $no_improvement_plan = $goal["opportunity"]["evaluation"]["no_improvement_plan"];

            // Obtiene el nombre del evaluador
            $evaluator_name = User::where("id",$goal["opportunity"]["evaluation"]["evaluator_id"])->first()->name;

            $responsibles_id = $responsible_data["responsibles"]["id"];
            $responsibles_names = $responsible_data["responsibles"]["name"];
            $responsibles_mails = [$responsible_data["responsibles"]["email"]];
            
            GoalResponsible::create(["pm_goals_id" => $responsible_data["activity_object"]["pm_goals_id"],"activity" => $responsible_data["activity"],"responsibles_names" => $responsibles_names, "responsibles_id" => $responsibles_id]);

            // Notificacion para los responsables
            $notification_data = ["functionary_name" => $userLogged->name, "activity_name" => $responsible_data["activity_object"]["activity_name"],"improvement_plan_name" => $improvement_plan_name,"no_improvement_plan" => $no_improvement_plan,"activity_percentage" => $responsible_data["activity_object"]["activity_weigth"],"evaluator_name" => $evaluator_name,"goal_name" => $goal["goal_name"],"activity_start_date" => $responsible_data["activity_object"]["start_date"], "activity_end_date" => $responsible_data["activity_object"]["end_date"]];
            UtilController::sendMail($responsibles_mails,"improvementplans::goals.mails.responsibles_notificacion",$notification_data,"Notificación PMI");
        }

        $goals = Goal::with(["GoalActivitiesCuantitativas","GoalResponsibles", "GoalActivitiesCualitativas", "GoalDependencies"])->where("id",$responsible_data["activity_object"]["pm_goals_id"])->first();

        return $this->sendResponse($goals->toArray(), trans('msg_success_update'));
    }

    /**
     * Elimina un Goal del almacenamiento
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
    public function destroy($id)
    {

        /** @var Goal $goal */
        $goal = $this->goalRepository->find($id);

        if (empty($goal)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $goal->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalController - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message') . '<br>' . $e->getMessage(), 'error');
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
    public function export(Request $request)
    {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time() . '-' . trans('goals') . '.' . $fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) { // Valida si el tipo de archivo es excel
            // Guarda el archivo excel en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp');
            $data = JwtController::decodeToken($input["data"]);
            array_walk($data, fn(&$object) => $object = (array) $object);
            // Descarga el archivo generado
            return Excel::download(new GenericExport($data), $fileName);
        }
    }
}
