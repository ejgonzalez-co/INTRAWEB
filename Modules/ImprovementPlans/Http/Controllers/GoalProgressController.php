<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ImprovementPlans\Http\Requests\CreateGoalProgressRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateGoalProgressRequest;
use Modules\ImprovementPlans\Repositories\GoalProgressRepository;
use Modules\ImprovementPlans\Models\GoalProgress;
use Modules\ImprovementPlans\Models\ImprovementOpportunity;
use Modules\ImprovementPlans\Models\Evaluation;
use Modules\ImprovementPlans\Models\Goal;
use Modules\ImprovementPlans\Models\GoalActivity;
use Modules\ImprovementPlans\Models\GoalResponsible;
use App\Models\User;
use Modules\ImprovementPlans\Http\Controllers\UtilController;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Crypt;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\RolPermission;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class GoalProgressController extends AppBaseController {

    /** @var  GoalProgressRepository */
    private $goalProgressRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(GoalProgressRepository $goalProgressRepo) {
        $this->goalProgressRepository = $goalProgressRepo;
    }

    /**
     * Muestra la vista para el CRUD de GoalProgress.
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

        // Realiza la consulta para encontrar al id del evaluador
        $goal = Goal::select(["pm_improvement_opportunity_id","id","goal_name","goal_type"])->where("id",Crypt::decryptString($request["goal"]))->first();
        $evaluationId = ImprovementOpportunity::select("evaluations_id")->where("id",$goal["pm_improvement_opportunity_id"])->first()->evaluations_id;
        $evaluatorId = Evaluation::select("evaluator_id")->where("id",$evaluationId)->first()->evaluator_id;
    
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
    
        return view('improvementplans::goal_progresses.index', compact(["goal", "evaluatorId"
        ]))->with("can_manage", $canManage)->with("can_generate_reports", $canGenerateReports)->with("goalId", $request["goal"]);

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
    public function all(Request $request) {
        // Realiza la consulta para posterior obtener el nombre del plan de mejoramiento
        $goal = Goal::select(["goal_name","pm_improvement_opportunity_id"])->with("opportunity")->where("id",Crypt::decryptString($request["goal"]))->first()->toArray();

        // Obtiene la informacion del plan de mejoramiento
        $evaluated_id = $goal["opportunity"]["evaluation"]["evaluated_id"];

        $userLogged = Auth::user();

        // Validacion para mostrar todos los avances o no siempre y cuando el usuario sea el evaluado principal
        if($evaluated_id == $userLogged->id || Auth::user()->hasRole("Evaluador")){
            $goal_progresses = GoalProgress::with(["GoalActivities","CreatorUser"])->where("pm_goals_id",Crypt::decryptString($request["goal"]))->latest()->get();
            return $this->sendResponse($goal_progresses->toArray(), trans('data_obtained_successfully'));
        }

        $goal_progresses = GoalProgress::with(["GoalActivities","CreatorUser"])->where("pm_goals_id",Crypt::decryptString($request["goal"]))->where("user_id",$userLogged->id)->latest()->get();
        return $this->sendResponse($goal_progresses->toArray(), trans('data_obtained_successfully'));

    }

    public function getActivitesGoal(int $goalId){
        $goalResponsible = GoalResponsible::whereRaw('FIND_IN_SET("' . Auth::user()->id.'", responsibles_id)')->get()->toArray();

        if(count($goalResponsible) > 0){
            $activityId = array_column($goalResponsible,"activity");

            $goal = GoalActivity::select(["id","activity_name"])->whereIn("id",$activityId)->where("pm_goals_id",$goalId)->orderBy("activity_name")->get()->toArray();
            
            // Si no existe datos es porque el usuario en sesion es el evaluador
            if($goal == []){
                $goal = GoalActivity::select(["id","activity_name"])->where("pm_goals_id",$goalId)->orderBy("activity_name")->get()->toArray();
            }
            return $this->sendResponse($goal, trans('data_obtained_successfully'));
        }
        else{
            $goal = GoalActivity::select(["id","activity_name"])->where("pm_goals_id",$goalId)->orderBy("activity_name")->get();
            return $this->sendResponse($goal->toArray(), trans('data_obtained_successfully'));
        }
    }

    public function getActivityWeigth(int $activityId){
        $goalActivityWeigth = GoalActivity::select("activity_weigth","gap_meet_goal")->where("id",$activityId)->first();
        return $this->sendResponse($goalActivityWeigth, trans('data_obtained_successfully'));
    }

    public function sendReviewGoalProgress(int $goalProgressId){
        $input = [];
        $input["status"] = "Revisión";
        $input["observation"] = null;
        $goalId = GoalProgress::select("pm_goals_id")->where("id",$goalProgressId)->first()->pm_goals_id;

        // Obtiene el correo del evaluador
        $evaluator= User::select(['email','name'])
        ->join('pm_evaluations', 'users.id', '=', 'pm_evaluations.evaluator_id')
        ->join('pm_improvement_opportunities', 'pm_evaluations.id', '=', 'pm_improvement_opportunities.evaluations_id')
        ->join('pm_goals', 'pm_improvement_opportunities.id', '=', 'pm_goals.pm_improvement_opportunity_id')
        ->where('pm_goals.id', '=', $goalId)
        ->first();

        UtilController::sendMail([$evaluator->email],"improvementplans::goal_progresses.mails.evaluator_notification_progress_review",$evaluator,"Notificación PMI");

        
        $goalProgress = $this->goalProgressRepository->update($input, $goalProgressId);
        return $this->sendResponse($goalProgress->toArray(), trans('Avance enviado exitosamente a revisión'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $input["pm_goals_id"] = Crypt::decryptString($request["goal_id"]);
        $input["status"] = "Pendiente";
        $input["user_id"] = Auth::user()->id;

        $progressTotal = GoalProgress::where("pm_goal_activities_id",$input["pm_goal_activities_id"])->sum("progress_weigth");

        $actividad = GoalActivity::select(["gap_meet_goal","goal_type"])->where("id",$input["pm_goal_activities_id"])->first();

        $quantityCharacters = $this->space_counter($input["evidence_description"]);
        if($quantityCharacters <= 20){
            return $this->sendSuccess("El campo <strong>Descripción de la evidencia</strong> debe contener más de 20 palabras y por el momento contiene {$quantityCharacters} palabras", 'info');
            
        }
        
        if($actividad->goal_type == "Cuantitativa"){

            // Valida si los porcentajes de los avances superaron a el porcentaje limite de la actividad
            if(($progressTotal + $input["progress_weigth"]) > $actividad->gap_meet_goal){
                $percentajeActivityRemaining = $actividad->gap_meet_goal - $progressTotal + $input["progress_weigth"];
                return $this->sendSuccess("El avance actual tiene un peso de " . $input["progress_weigth"] . ", superando la brecha para alcanzar el cumplimiento de la meta. Hasta ahora, has acumulado un total de avances equivalente a (" . $progressTotal . ").", 'info');
            } else if($input["progress_weigth"] == 0){
                return $this->sendSuccess("El avance actual tiene un peso de " . $input["progress_weigth"] . ", ese valor no esta disponible para un avance. Hasta ahora, has acumulado un total de avances equivalente a (" . $progressTotal . ").", 'info');
            }
        }else{

            if($progressTotal == 100){
                return $this->sendSuccess("El avance de esta actividad ya ha sido registrado y se encuentra al 100%. No es necesario realizar cambios.", 'info');
            }

        }
      

        if($request->hasFile("url_progress_evidence")){
            if(UtilController::validateArchive($input,'url_progress_evidence','file|mimes:pdf,docx,xlsx,png,jpeg,jpg|max:1024')){
                return $this->sendSuccess("El archivo no cuenta con las especificaciones dadas.", 'info');
            }
            // Se crea una instancia de la clase principal para enviarla como parámetro y usar las funciones no estáticas
            $app_base_controller = new AppBaseController();
            $input["url_progress_evidence"] =  UtilController::uploadFile($input["url_progress_evidence"],'public/improvement_plan/progress_evidence', $app_base_controller);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $goalProgress = $this->goalProgressRepository->create($input);

            // Relaciones
            $goalProgress->GoalActivities;
            $goalProgress->CreatorUser;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($goalProgress->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalProgressController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalProgressController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    public function space_counter($cadena) {
        $contador = 0;
        for ($i = 0; $i < strlen($cadena); $i++) {
            if (ctype_space($cadena[$i])) {
                $contador++;
            }
        }
        return $contador+1;
    }    

    public function approvedProgress(Request $request){
        $goal = Goal::select(["goal_name","pm_improvement_opportunity_id"])->with("opportunity")->where("id",$request["pm_goals_id"])->first()->toArray();
        $mail_to_notify = User::where("id",$request["user_id"])->first()->email;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $goalProgress = $this->goalProgressRepository->update($request->all(), $request["id"]);

            if($goalProgress["status"] == "Aprobado"){
                $notification_data = ["goal_name" => $goal["goal_name"],"name_opportunity_improvement" => $goal["opportunity"]["name_opportunity_improvement"],"name_improvement_plan" => $goal["opportunity"]["evaluation"]["name_improvement_plan"], "no_improvement_plan" => $goal["opportunity"]["evaluation"]["no_improvement_plan"]];
                UtilController::sendMail([$mail_to_notify],"improvementplans::goal_progresses.mails.approved_progress_notification",$notification_data,"Notificación PMI");
            }
            if($goalProgress["status"] == "Devuelto"){
                $goalActivity = GoalActivity::select("activity_name")->where("id",$request["pm_goal_activities_id"])->first()->activity_name;
                $notification_data = ["goal_activity_name" => $goalActivity,"name_opportunity_improvement" => $goal["opportunity"]["name_opportunity_improvement"],"name_improvement_plan" => $goal["opportunity"]["evaluation"]["name_improvement_plan"], "no_improvement_plan" => $goal["opportunity"]["evaluation"]["no_improvement_plan"],"observation" => $request["observation"]];
                UtilController::sendMail([$mail_to_notify],"improvementplans::goal_progresses.mails.returned_progress_notification",$notification_data,"Notificación PMI");
            }

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($goalProgress->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalProgressController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalProgressController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {
        $input = $request->all();
        $input["status"] = "Pendiente";

        $progressTotal = GoalProgress::where("pm_goal_activities_id",$input["pm_goal_activities_id"])->where("id","!=",$id)->sum("progress_weigth");

        $actividad = GoalActivity::select(["gap_meet_goal","goal_type"])->where("id",$input["pm_goal_activities_id"])->first();
        
        if($actividad->goal_type == "Cuantitativa"){

            // Valida si los porcentajes de los avances superaron a el porcentaje limite de la actividad
            if(($progressTotal + $input["progress_weigth"]) > $actividad->gap_meet_goal){
                $percentajeActivityRemaining = $actividad->gap_meet_goal - $progressTotal + $input["progress_weigth"];
                return $this->sendSuccess("El avance actual tiene un peso de " . $input["progress_weigth"] . ", superando la brecha para alcanzar el cumplimiento de la meta. Hasta ahora, has acumulado un total de avances equivalente a (" . $progressTotal . ").", 'info');
            }else if($input["progress_weigth"] == 0){
                return $this->sendSuccess("El avance actual tiene un peso de " . $input["progress_weigth"] . ", ese valor no esta disponible para un avance. Hasta ahora, has acumulado un total de avances equivalente a (" . $progressTotal . ").", 'info');
            }
        }else{

            if($progressTotal == 100){
                return $this->sendSuccess("El avance de esta actividad ya ha sido registrado y se encuentra al 100%. No es necesario realizar cambios.", 'info');
            }

        }
      
        if($request->hasFile("url_progress_evidence")){
            if(UtilController::validateArchive($input,'url_progress_evidence','file|mimes:pdf,docx,xlsx,png,jpeg,jpg|max:1024')){
                return $this->sendSuccess("El archivo no cuenta con las especificaciones dadas.", 'info');
            }
            // Se crea una instancia de la clase principal para enviarla como parámetro y usar las funciones no estáticas
            $app_base_controller = new AppBaseController();
            $input["url_progress_evidence"] =  UtilController::uploadFile($input["url_progress_evidence"],'public/improvement_plan/progress_evidence', $app_base_controller);
        }


        /** @var GoalProgress $goalProgress */
        $goalProgress = $this->goalProgressRepository->find($id);

        if (empty($goalProgress)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $goalProgress = $this->goalProgressRepository->update($input, $id);

            // Relaciones
            $goalProgress->GoalActivities;
            $goalProgress->CreatorUser;

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($goalProgress->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalProgressController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalProgressController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un GoalProgress del almacenamiento
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

        /** @var GoalProgress $goalProgress */
        $goalProgress = $this->goalProgressRepository->find($id);

        if (empty($goalProgress)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $goalProgress->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalProgressController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\GoalProgressController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('goal_progresses').'.'.$fileType;

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
}
