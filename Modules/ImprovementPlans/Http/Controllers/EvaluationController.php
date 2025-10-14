<?php

namespace Modules\ImprovementPlans\Http\Controllers;
use App\Exports\GenericExport;
use App\Exports\ImprovementPlans\RequestExport;
use Modules\ImprovementPlans\Http\Requests\CreateEvaluationRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateEvaluationRequest;
use Modules\ImprovementPlans\Repositories\EvaluationRepository;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\Evaluation;
use Modules\ImprovementPlans\Models\Dependence;
use Modules\ImprovementPlans\Models\EvaluationCriterion;
use Modules\ImprovementPlans\Models\EvaluationDependence;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\JwtController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Flash;
use Auth;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\RolPermission;
use DB;


/**
 * DEscripcion de la clase
 *
 * @author Kleverman Salazar Florez. - Ago. 06 - 2023
 * @version 1.0.0
 */
class EvaluationController extends AppBaseController {

    /** @var  EvaluationRepository */
    private $evaluationRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     */
    public function __construct(EvaluationRepository $evaluationRepo) {
        $this->evaluationRepository = $evaluationRepo;
    }

    /**
     * Muestra la vista para el CRUD de Evaluation.
     *
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
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
            $canManage = true;
            $canGenerateReports = true;
        } elseif ($userLogged->hasRole($allowedRoles)) {
            $canManage = $canGenerateReports = $this->hasPermissions($userLogged);
        }
        else{
            return abort(403,"No se encuentra autorizado.");
        }
    
        return view('improvementplans::evaluations.index')->with("can_manage", $canManage)->with("can_generate_reports", $canGenerateReports);
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
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request)
{
    // Definir variable para el conteo de registros
    $count_evaluations = 0;

    // Comprobar si existen filtros y parámetros de paginación en la solicitud
    if (isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
        
        // Consulta para contar los registros con el filtro aplicado
        $count_evaluations = Evaluation::where('status_improvement_plan', '<>', 'Declinado')
            ->whereRaw(base64_decode($request["f"]))
            ->latest("updated_at")
            ->count();

        // Consulta con paginación y relaciones cargadas
        $evaluations = Evaluation::with([
                "evaluator", 
                "evaluated", 
                "EvaluationDependences", 
                "EvaluationCriteria"
            ])
            ->where('status_improvement_plan', '<>', 'Declinado')
            ->whereRaw(base64_decode($request["f"]))
            ->latest("updated_at")
            ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))  // Paginación: salto de registros
            ->take(base64_decode($request["pi"]))  // Paginación: cantidad de registros a tomar
            ->get();

    } else {
        
        // Contar los registros sin filtro (incluyendo los nulos en 'status_improvement_plan')
        $count_evaluations = Evaluation::where('status_improvement_plan', '<>', 'Declinado')
            ->orWhereNull('status_improvement_plan')
            ->count();

        // Consulta sin filtros, solo paginación
        $evaluations = Evaluation::with([
                "evaluator", 
                "evaluated", 
                "EvaluationDependences", 
                "EvaluationCriteria"
            ])
            ->where('status_improvement_plan', '<>', 'Declinado')
            ->orWhereNull('status_improvement_plan')
            ->latest("updated_at")
            ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))  // Paginación: salto de registros
            ->take(base64_decode($request["pi"]))  // Paginación: cantidad de registros a tomar
            ->get();
    }

    // Responder con los resultados obtenidos
    return $this->sendResponseAvanzado($evaluations, trans('data_obtained_successfully'), null, ["total_registros"=>$count_evaluations]);
}

    

    /**
     * Obtiene todos las dependencias
     *
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function getDependeces(){
        $dependences = Dependence::select("nombre")->get()->toArray();
        return $this->sendResponse($dependences, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     *
     * @param CreateEvaluationRequest $request
     *
     * @return Response
     */

     public function store(CreateEvaluationRequest $request) {
         $input = $request->all();
         // Inicia la transaccion
         try {
             DB::beginTransaction();
             
             $dateFormatted = Carbon::parse($input["evaluation_start_date"])->format('Y-m-d');

            $initialDateToCalendar = $dateFormatted."T".$input["evaluation_start_time"].":00";
            $finalDateToCalendar = $dateFormatted."T".$input["evaluation_end_time"].":00";

            $userLogged = Auth::user();

            $input["evaluator_id"] = $userLogged->id;
            $input["status"]= "Programada";
            if($request->hasFile("attached")){
                $input["attached"] = $this->_uploadFile($input["attached"],'public/improvement_plan/attacheds');
            }
            
            // Validacion para evitar el error de the specified time range is empty
            if($input["evaluation_start_time"] >= $input["evaluation_end_time"]){
                return $this->sendSuccess("Por favor, la hora final(<b>".date("h:i A",strtotime($input["evaluation_end_time"]))."</b>) de la evaluación debe ser mayor a la hora inicial(<b>".date("h:i A",strtotime($input["evaluation_start_time"]))."</b>).", 'info');

            }

            // validacion de criterios debe existir al menos uno
            if (!isset($input["evaluation_criteria"])) {
                return $this->sendSuccess("Por favor, seleccione al menos un Criterio de evaluación en el campo <b>'Criterios de evaluación'</b> y haga clic en el botón <b>'Agregar Criterios de evaluación'</b>.", 'info');
            }
            
            // validacion de dependencias debe existir al menos una
            if (!isset($input["evaluation_dependences"])) {
                return $this->sendSuccess("Por favor, seleccione al menos una dependencia en el campo <b>'Dependencia'</b> y haga clic en el botón <b>'Agregar Dependencia'</b>.", 'info');
            }
            
            $evaluation = $this->evaluationRepository->create($input);

            // Asigna los criterios de evaluacion
            foreach ($input["evaluation_criteria"] as $evaluation_criterion) {
                $evaluationCriterionName = json_decode($evaluation_criterion)->criteria_name;
                $this->_assignEvaluationCriteria($evaluation["id"],$evaluationCriterionName);
            }

            // Asigna las dependencias de evaluacion
            foreach ($input["evaluation_dependences"] as $evaluation_dependence) {
                $evaluationDependenceName = json_decode($evaluation_dependence)->dependence_name;
                $this->_assignEvaluationDependence($evaluation["id"],$evaluationDependenceName);
            }
             // Efectua los cambios realizados
             DB::commit();
 
            // Relaciones
            $evaluation->EvaluationCriteria;
            $evaluation->EvaluationDependences;
            $evaluation->evaluator;
            $evaluation->evaluated;
            
            $googleCalendar = new GoogleController(); // Crea la instancia de la conexión a Google
            
            // Agenda el evento a los usuarios correspondientes
            $googleCalendar->createEventToCalendar($input["evaluation_name"],$initialDateToCalendar,$finalDateToCalendar,$input["evaluation_scope"],[["email" => $userLogged->email],["email" => $evaluation->evaluated->email]]);
            
 
             return $this->sendResponse($evaluation->toArray(), trans('msg_success_save'));
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

    public function hourToMilitaryHour(string $hour) : string{
        
    }
    
    
    /**
     * Asigna los criterios de evaluacion
     *
     * @author Kleverman Salazar Florez. - Ago. 04 - 2023
     * @version 1.0.0
     * 
     * @param int $evaluationId
     * @param string $criteriaName
     *
     * @return void
     */
    private function _assignEvaluationCriteria(int $evaluationId, string $criteriaName) : void{
        EvaluationCriterion::create(["evaluations_id" => $evaluationId, "criteria_name" => $criteriaName, "status" => null, "observations" => null]);
    }
    
    /**
     * Asigna los criterios de evaluacion
     *
     * @author Kleverman Salazar Florez. - Ago. 04 - 2023
     * @version 1.0.0
     * 
     * @param int $evaluationId
     * @param string $criteriaName
     *
     * @return void
     */
    private function _updateEvaluationCriteria(int $evaluationCriteriaId, string $criteriaName, string $status, string $observation) : void{
        EvaluationCriterion::where("id",$evaluationCriteriaId)->update(["criteria_name" => $criteriaName, "status" => $status, "observations" => $observation]);
    }

    /**
     * Asigna las dependencias de evaluacion
     *
     * @author Kleverman Salazar Florez. - Ago. 04 - 2023
     * @version 1.0.0
     * 
     * @param int $evaluationId
     * @param string $dependenceName
     *
     * @return void
     */
    private function _assignEvaluationDependence(int $evaluationId, string $dependenceName) : void{
        EvaluationDependence::create(["evaluations_id" => $evaluationId, "dependence_name" => $dependenceName]);
    }
    
    /**
     * Sube un archivo a una ubicacion en especifico
     *
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     * 
     * @param object $file
     * @param string $storageLocation
     *
     * @return string
     */
    private function _uploadFile(object $file, string $storageLocation) : string{
        $fileLocation = substr($file->store($storageLocation), 7);
        return $fileLocation;
    }
    
    
    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateEvaluationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEvaluationRequest $request) {
        $input = $request->all();
        if($request->hasFile("evaluation_process_attachment")){
            $input["evaluation_process_attachment"] = $this->_uploadFile($input["evaluation_process_attachment"],'public/improvement_plan/evaluation_process_attachment');
        }
        if($request->hasFile("attached")){
            $input["attached"] = $this->_uploadFile($input["attached"],'public/improvement_plan/attacheds');
        }

        if($input["status"] != "Cerrada"){
            $input["status"]= "Programada";
    
            // Elimina los criterios y dependencias
            EvaluationCriterion::where("evaluations_id",$id)->delete();
            EvaluationDependence::where("evaluations_id",$id)->delete();
            
            // Asigna los criterios de evaluacion
            foreach ($input["evaluation_criteria"] as $evaluation_criterion) {
                $evaluationCriterion = json_decode($evaluation_criterion);
                $this->_assignEvaluationCriteria($id,$evaluationCriterion->criteria_name);
            }
    
            // Asigna las dependencias de evaluacion
            foreach ($input["evaluation_dependences"] as $evaluation_dependence) {
                $evaluationDependenceName = json_decode($evaluation_dependence)->dependence_name;
                $this->_assignEvaluationDependence($id,$evaluationDependenceName);
            }
        }
        
        /** @var Evaluation $evaluation */
        $evaluation = $this->evaluationRepository->find($id);
        
        if (empty($evaluation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        $evaluation = $this->evaluationRepository->update($input, $id);

        // Relaciones
        $evaluation->EvaluationCriteria;
        $evaluation->EvaluationDependences;
        $evaluation->evaluator;
        $evaluation->evaluated;

        return $this->sendResponse($evaluation->toArray(), trans('msg_success_update'));
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     *
     * @param $request
     *
     * @return Response
     */
    public function executeEvaluationProcess(Request $request) {
        
        $input = $request->all();
        if($request->hasFile("evaluation_process_attachment")){
            $input["evaluation_process_attachment"] = $this->_uploadFile($input["evaluation_process_attachment"],'public/improvement_plan/evaluation_process_attachment');
        }

        $nonConformeCount = 0;
        foreach ($input["evaluation_criteria"] as $evaluation) {
        $evaluationCriterion = json_decode($evaluation);
            if ($evaluationCriterion->status == 'No conforme') {
                $nonConformeCount++;
            }
        }
        
        foreach ($input["evaluation_criteria"] as $evaluation_criterion) {
            $evaluationCriterion = json_decode($evaluation_criterion);
            if($nonConformeCount == 1 && $evaluationCriterion->status == 'No conforme'){
                    EvaluationCriterion::where("id", $evaluationCriterion->id)->where("criteria_name", $evaluationCriterion->criteria_name)->update(["criteria_name" => $evaluationCriterion->criteria_name, "status" => $evaluationCriterion->status, "observations" => $evaluationCriterion->observations, "weight" => "100"]);
            }else {
                $this->_updateEvaluationCriteria($evaluationCriterion->id,$evaluationCriterion->criteria_name,$evaluationCriterion->status,$evaluationCriterion->observations);
            }
        }

        //crear el plan de mejoramiento
        // if($nonConformeCount > 0){
        //     $input["is_accordance"] = "No";
        //     $input["no_improvement_plan"] = Evaluation::whereNotNull("no_improvement_plan")->count() + 1;
        //     $input["execution_percentage"] = "0";
        //     $input["status_improvement_plan"] = "Pendiente";
        // }
      
        $input["status"] = "En proceso";
        
        /** @var Evaluation $evaluation */
        $evaluation = $this->evaluationRepository->find($request['id']);
        
        if (empty($evaluation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        $evaluation = $this->evaluationRepository->update($input, $request['id']);

        // Relaciones
        $evaluation->EvaluationCriteria;
        
        return $this->sendResponse($evaluation->toArray(), trans('msg_success_update'));
    }

    /**
     * Elimina un Evaluation del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ago. 06 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var Evaluation $evaluation */
        $evaluation = $this->evaluationRepository->find($id);

        if (empty($evaluation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        $evaluation->delete();

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
        $fileName = time().'-'.trans('evaluations').'.'.$fileType;

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
            $evaluationsId = array_column($data, "id");
            $evaluations = Evaluation::whereIn("id",$evaluationsId)->with(["evaluator","evaluated","EvaluationDependences","EvaluationCriteria"])->latest()->get();
            // Descarga el archivo generado
            return $this->_exportReportToXlsxFile('improvementplans::evaluations.exports.evaluations',$evaluations,'M','Reporte de las evaluaciones.xlsx');
        }

    }

    /**
     * Exporta un certificado en formato xlsx.
     *
     * @author Kleverman Salazar Florez - Dic. 01 - 2022
     * @version 1.0.0
     *
     * @param int $locationOfTheTemplate
     * @param object $data
     * @param string $finalColum
     * @param string $archiveName
     * 
     * @return object
     *
     */
    private function _exportReportToXlsxFile(string $locationOfTheTemplate,object $data,string $finalColum, string $archiveName) : object{
        return Excel::download(new RequestExport($locationOfTheTemplate, $data, $finalColum), $archiveName);
    }

}
