<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ImprovementPlans\Http\Requests\CreateApprovedImprovementPlanRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateApprovedImprovementPlanRequest;
use Modules\ImprovementPlans\Repositories\ApprovedImprovementPlanRepository;
use Modules\ImprovementPlans\Models\GoalActivity;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\ApprovedImprovementPlan;
use Modules\ImprovementPlans\Models\ImprovementPlan;
use Modules\ImprovementPlans\Models\ImprovementOpportunity;
use Modules\ImprovementPlans\Models\NonConformingCriteria;
use Modules\ImprovementPlans\Models\Goal;
use Modules\ImprovementPlans\Models\EvaluationHistory;
use Modules\ImprovementPlans\Models\User;
use Modules\ImprovementPlans\Models\GoalProgress;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\RolPermission;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\JwtController;
use Modules\ImprovementPlans\Models\UserOtherDependence;
use Illuminate\Http\Request;
use Flash;

use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\ImprovementPlans\Http\Controllers\UtilController;
use Modules\ImprovementPlans\Models\EvaluationCriterion;
use Modules\ImprovementPlans\Models\EvaluationDependence;
use Modules\ImprovementPlans\Models\Evaluation;

use Illuminate\Support\Facades\Crypt;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ApprovedImprovementPlanController extends AppBaseController {

    /** @var  ApprovedImprovementPlanRepository */
    private $approvedImprovementPlanRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ApprovedImprovementPlanRepository $approvedImprovementPlanRepo) {
        $this->approvedImprovementPlanRepository = $approvedImprovementPlanRepo;
    }

    /**
     * Muestra la vista para el CRUD de ApprovedImprovementPlan.
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
    
        return view('improvementplans::approved_improvement_plans.index')->with("can_manage", $canManage)->with("can_generate_reports", $canGenerateReports);
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
    public function all(Request $request) {
        // Decodifica y escapa el filtro solo una vez
        $filtroEscapado = '';
        $evaluation_improvement_opportunities = '';
        if (isset($request["f"]) && $request["f"] != "") {
            $filtroDecodificado = base64_decode($request["f"]);
            
            $filtroEscapado = str_replace('from', '`from`', $filtroDecodificado);

             // Valida si en los filtros realizados viene el filtro de pqrs_propios
            if(stripos($filtroDecodificado, "evaluation_improvement_opportunities") !== false) {
                // Se separan los filtros por el operador AND, obteniendo un array
                $filtro = explode(" AND ", $filtroDecodificado);
                // Se obtiene la posición del filtro de pqrs_propios en el array de filtros
                $posicion = array_keys(array_filter($filtro, function($value) {
                    return stripos($value, 'evaluation_improvement_opportunities') !== false;
                }))[0];
                // Se extrae el valor del filtro evaluation_improvement_opportunities
                $evaluation_improvement_opportunities = mb_strtolower(explode("%", $filtro[$posicion])[1]);
                // Se elimina el filtro de evaluation_improvement_opportunities del array de filtro
                unset($filtro[$posicion]);
                // Se convierte a cadena nuevamente los filtros a aplicar por el usuario
                $filtroEscapado = implode(" AND ", $filtro);
            }

        }
        // dd($filtroDecodificado,$evaluation_improvement_opportunities);
      
        
        // Definir variable para el conteo de registros
        $count_approved_improvement_plans = 0;
    
        if (isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
            //El valor de $request['f']="evaluated.dependencies.id"
            if (strpos($filtroEscapado, "dependence_id") !== false) {

                // Se encontró 'evaluated.dependencies.id' dentro de $request["f"]
                $approved_improvement_plans = ApprovedImprovementPlan::with(["evaluator", "evaluated", "EvaluationDependences", "EvaluationCriteria"])
                ->join('pm_users_others_dependences', 'pm_evaluations.evaluated_id', '=', 'pm_users_others_dependences.users_id')  // Relacionar con la tabla user_other_dependences
                ->select('pm_evaluations.*', 'pm_users_others_dependences.dependence_id') 
                ->whereNull('pm_users_others_dependences.deleted_at') 
                ->whereNotNull('no_improvement_plan')
                ->when($filtroEscapado!="",function($q) use($filtroEscapado){
                    $q->whereRaw($filtroEscapado);
                })
                ->when($evaluation_improvement_opportunities!="",function($query) use($evaluation_improvement_opportunities){
                    $query->whereHas("EvaluationCriteria.opportunities.goals.GoalActivities.GoalProgresses",function($q) use($evaluation_improvement_opportunities){
                        $q->where("status",$evaluation_improvement_opportunities);
                    });
                })
                ->latest('updated_at')
                ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
                ->take(base64_decode($request["pi"]))
                ->get()
                ->map(function($approved_improvement_plan, $key) {
                    // Encriptar el ID
                    $approved_improvement_plan["encrypted_id"] = Crypt::encryptString($approved_improvement_plan["id"]);
                    return $approved_improvement_plan;
                });
        
            
                // Realiza la consulta para contar los registros con el filtro aplicado
                $count_approved_improvement_plans = ApprovedImprovementPlan::join('pm_users_others_dependences', 'pm_evaluations.evaluated_id', '=', 'pm_users_others_dependences.users_id') // Incluye el JOIN
                ->whereNotNull("no_improvement_plan")
                ->whereNull('pm_users_others_dependences.deleted_at') 
                ->whereRaw($filtroEscapado)
                ->count();  // Método count() devuelve el número de registros que coinciden con la consulta
            
                // Responder con los resultados obtenidos
                return $this->sendResponseAvanzado($approved_improvement_plans, trans('data_obtained_successfully'), null, ["total_registros"=>$count_approved_improvement_plans]);
            }
             else{
                    // Realiza la consulta con paginación y relaciones
                    $approved_improvement_plans = ApprovedImprovementPlan::with(["evaluator", "evaluated", "EvaluationDependences", "EvaluationCriteria"])
                    ->whereNotNull("no_improvement_plan")
                    ->when($filtroEscapado!="",function($q) use($filtroEscapado){
                        $q->whereRaw($filtroEscapado);
                    })
                    ->when($evaluation_improvement_opportunities!="",function($query) use($evaluation_improvement_opportunities){
                        $query->whereHas("EvaluationCriteria.opportunities.goals.GoalActivities.GoalProgresses",function($q) use($evaluation_improvement_opportunities){
                            $q->where("status",$evaluation_improvement_opportunities);
                        });
                    })
                    ->latest("updated_at")
                    ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
                    ->take(base64_decode($request["pi"]))
                    ->get()
                    ->map(function($approved_improvement_plan, $key){
                        $approved_improvement_plan["encrypted_id"] = Crypt::encryptString($approved_improvement_plan["id"]);
                        return $approved_improvement_plan;
                    });
                    
                    $count_approved_improvement_plans = ApprovedImprovementPlan::whereNotNull("no_improvement_plan")
                    ->when($filtroEscapado!="",function($q) use($filtroEscapado){
                        $q->whereRaw($filtroEscapado);
                    }) 
                    ->when($evaluation_improvement_opportunities!="",function($query) use($evaluation_improvement_opportunities){
                        $query->whereHas("EvaluationCriteria.opportunities.goals.GoalActivities.GoalProgresses",function($q) use($evaluation_improvement_opportunities){
                            $q->where("status",$evaluation_improvement_opportunities);
                        });
                    })
                    ->count();
            }  
        } else  {
            // Si no hay filtro, solo paginación
            $approved_improvement_plans = ApprovedImprovementPlan::with(["evaluator", "evaluated", "EvaluationDependences", "EvaluationCriteria","EvaluationImprovementOpportunities"])              
                ->latest("updated_at")
                ->whereNotNull("no_improvement_plan")
                ->skip((base64_decode($request["cp"]) - 1) * base64_decode($request["pi"]))
                ->take(base64_decode($request["pi"]))
                ->get()
                ->map(function($approved_improvement_plan, $key){
                    $approved_improvement_plan["encrypted_id"] = Crypt::encryptString($approved_improvement_plan["id"]);
                    return $approved_improvement_plan;
                });

            // Conteo total sin filtro
            $count_approved_improvement_plans = ApprovedImprovementPlan::whereNotNull("no_improvement_plan")->count();
        }
        

    
        // Responder con los resultados obtenidos
        return $this->sendResponseAvanzado($approved_improvement_plans, trans('data_obtained_successfully'), null, ["total_registros"=>$count_approved_improvement_plans]);
    }
    

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateApprovedImprovementPlanRequest $request
     *
     * @return Response
     */
    public function store(CreateApprovedImprovementPlanRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $approvedImprovementPlan = $this->approvedImprovementPlanRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($approvedImprovementPlan->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ApprovedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ApprovedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateApprovedImprovementPlanRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateApprovedImprovementPlanRequest $request) {

        $input = $request->all();

        /** @var ApprovedImprovementPlan $approvedImprovementPlan */
        $approvedImprovementPlan = $this->approvedImprovementPlanRepository->find($id);

        if (empty($approvedImprovementPlan)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $history = new EvaluationHistory();
            $history->pm_evaluations_id = $id;
            $history->users_id = $user->id;
            $history->status = $input["status_improvement_plan"];
            $history->observation = $input["observation"];
            $history->user_name = $user->name;

            // Actualiza el registro
            $approvedImprovementPlan = $this->approvedImprovementPlanRepository->update($input, $id);

            $history->save();
            $approvedImprovementPlan->HistoryEvaluation;

            // Si el estado del plan de mejoramiento es "Declinado", se crea un nuevo plan con sus criterios
            if($approvedImprovementPlan->status_improvement_plan == "Declinado") {
                $input["status"] = "Cerrada";
                //estado del plan
                $input["is_accordance"] = "No";
                $input["no_improvement_plan"] = Evaluation::whereNotNull("no_improvement_plan")->count() + 1;
                $input["execution_percentage"] = "0";
                $input["status_improvement_plan"] = "Pendiente";
                // Inserta un nuevo registro en la base de datos
                $approvedImprovementPlanNuevo = $this->approvedImprovementPlanRepository->create($input);
                // Asigna los criterios de evaluacion
                foreach ($input["evaluation_criteria"] as $evaluation_criterion) {
                    $evaluationCriteria = json_decode($evaluation_criterion);
                    $this->_assignEvaluationCriteria($approvedImprovementPlanNuevo["id"], $evaluationCriteria->criteria_name,$evaluationCriteria->status);
                }
                // Asigna las dependencias de evaluacion
                foreach ($input["evaluation_dependences"] as $evaluation_dependence) {
                    $evaluationDependenceName = json_decode($evaluation_dependence)->dependence_name;
                    $this->_assignEvaluationDependence($approvedImprovementPlanNuevo["id"],$evaluationDependenceName);
                }

                $opportunities = ImprovementOpportunity::where("evaluations_id", $id)->get();

                foreach ($opportunities as $opportunity){
                    $improventOportunity = json_decode($opportunity);
                    $this->_assignImprovementOpportunities($approvedImprovementPlanNuevo["id"], $improventOportunity->source_information_id,$improventOportunity->type_oportunity_improvements_id,$improventOportunity->name_opportunity_improvement,$improventOportunity->description_opportunity_improvement,$improventOportunity->unit_responsible_improvement_opportunity,$improventOportunity->dependencia_id,$improventOportunity->official_responsible,$improventOportunity->official_responsible_id,$improventOportunity->deadline_submission,$improventOportunity->evidence,$improventOportunity->evaluation_criteria,$improventOportunity->evaluation_criteria_id);
                }
            }

            if($input["status_improvement_plan"] == "Aprobado"){
                $evaluado = User::select(['email','name'])->where("id",$input["evaluated_id"])->first();
                $input["evaluated_name"] = $evaluado->name;
                UtilController::sendMail([$evaluado->email],"improvementplans::approved_improvement_plans.mails.notification_approved",$input,"Aprobación del plan de mejoramiento");
            }

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($approvedImprovementPlan->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ApprovedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ApprovedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ApprovedImprovementPlan del almacenamiento
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

        /** @var ApprovedImprovementPlan $approvedImprovementPlan */
        $approvedImprovementPlan = $this->approvedImprovementPlanRepository->find($id);

        if (empty($approvedImprovementPlan)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $approvedImprovementPlan->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ApprovedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ApprovedImprovementPlanController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('approved_improvement_plans').'.'.$fileType;

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

    // public function exportImprovementPlan(int $improvementPlanId){
    //     $improvementPlan = ApprovedImprovementPlan::where("id",$improvementPlanId)->with(["evaluator","evaluated","EvaluationDependences","EvaluationCriteria","EvaluationImprovementOpportunities"])->get()->toArray();

    //     // Obtiene los id de los criterios no conformes
    //     $nonConformingCriterias = NonConformingCriteria::select("id")->where("evaluations_id",$improvementPlan[0]["id"])->where("status","No conforme")->get()->toArray();
    //     $nonConformingCriteriasId = array_column($nonConformingCriterias,"id");

    //     // Obtiene las metas de los criterios no conformes
    //     $goals = Goal::with(["GoalActivities","GoalDependencies"])->whereIn("pm_evaluation_criteria_id",$nonConformingCriteriasId)->get()->toArray();

    //     // Obtiene los avances de las meats
    //     $goalsProgress = GoalProgress::with(["GoalActivities","Goals"])->whereIn("pm_goals_id",array_column($goals,"id"))->get()->toArray();

    //     $improvementPlan[0]["goals"] = $goals;
    //     $improvementPlan[0]["goals_progress"] = $goalsProgress;
        
    //     // Descarga el archivo generado
    //     return UtilController::exportReportToXlsxFile('improvementplans::approved_improvement_plans.exports.xlsx_improvement_plan',$improvementPlan,'h','Reporte de la evaluaciÃ³n.xlsx');
    // }

    public function exportImprovementPlan(int $improvementPlanId){

        $inputFileType = 'Xlsx';
        $inputFileName = storage_path('app/public/improvement_plan/plantillas/Reporte plan mejoramiento.xlsx');

        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);
        $spreadsheet = $reader->load($inputFileName);
        $spreadsheet->setActiveSheetIndex(0);

        $contCeldas = 146;
        $goalsTotal = 0;



        $opportunities = ImprovementOpportunity::where("evaluations_id", $improvementPlanId)->get()->toArray();

        $opportunities_name = [];
        
        foreach ($opportunities as $opportunity){
            $opportunities_name = implode(",", array_map(function ($opportunity) {
                return $opportunity['name_opportunity_improvement'];
            }, $opportunities));
        }

        $improvementPlan = ApprovedImprovementPlan::where("id",$improvementPlanId)->with(["evaluator","evaluated","EvaluationDependences","EvaluationCriteria","EvaluationImprovementOpportunities"])->get()->first()->toArray();


        $dependenceNames = implode(', ', array_column($improvementPlan['evaluation_dependences'], 'dependence_name'));


        // Nombre del evaluado para ingresarlo en los responsables
        $evaluatedName = $improvementPlan["evaluated"]["name"];

        // Obtener la dependencia evaluada
        $unit_responsible_for_evaluation = Evaluation::select("unit_responsible_for_evaluation")->where("id",$opportunities[0]["evaluations_id"])->first()->unit_responsible_for_evaluation;

        $textoActividades = '';
        $textoActividadesFechaInicio = '';
        $textoActividadesFechaFin = '';
        $textoActividadesResponsables = '';

        $total = ($contCeldas + count($improvementPlan['evaluation_improvement_opportunities'])) - 1;

        //Centra el contenido en las celdas
        $spreadsheet->getActiveSheet()->getStyle('A146')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('A146')->getAlignment()->setVertical('center');
        $spreadsheet->getActiveSheet()->getStyle('T146')->getAlignment()->setHorizontal('center');
        $spreadsheet->getActiveSheet()->getStyle('T146')->getAlignment()->setVertical('center');

        //Aumenta el tamaño de la letra
        $spreadsheet->getActiveSheet()->getStyle('A146')->getFont()->setSize(24);
        $spreadsheet->getActiveSheet()->getStyle('T146')->getFont()->setSize(20);


        $spreadsheet->getActiveSheet()->setCellValue('A146', $improvementPlan['no_improvement_plan']);


        $spreadsheet->getActiveSheet()->setCellValue('C143', $dependenceNames);
        $spreadsheet->getActiveSheet()->setCellValue('D143', $dependenceNames);

        // dd($improvementPlan['evaluation_improvement_opportunities']);


        foreach ($improvementPlan['evaluation_improvement_opportunities'] as $improvementPlanIndx => $value) {
            $spreadsheet->getActiveSheet()->setCellValue('J'.$contCeldas, $improvementPlan['evaluated']['name']);
            $spreadsheet->getActiveSheet()->setCellValue('T'.$contCeldas, $improvementPlan['status_improvement_plan'].' - '.$improvementPlan['percentage_execution'].' %');
            if ($value['goals']) {
                $cell = $contCeldas;

                // Bucle para llenar la informacion de las actividades de cada meta
                foreach ($value['goals'] as $goalIndx => $goal) {
                    $goalsTotal++;

                    if(count($improvementPlan['evaluation_improvement_opportunities']) != ($improvementPlanIndx + 1)){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore($cell + 1);
                    }
                    else if(count($value['goals']) != ($goalIndx + 1)){
                        $spreadsheet->getActiveSheet()->insertNewRowBefore($cell + 1);
                    }

                    $spreadsheet->getActiveSheet()->setCellValue('Q'.$cell, $goal['goal_name']);
                    $spreadsheet->getActiveSheet()->setCellValue('R'.$cell, $goal['percentage_execution'].' %');


                    $goal_activities = implode("\n\n",array_column($goal['goal_activities'],"activity_name"));
                    $goal_activities_start_date = implode("\n\n",array_column($goal['goal_activities'],"start_date"));
                    $goal_activities_end_date = implode("\n\n",array_column($goal['goal_activities'],"end_date"));

                    $spreadsheet->getActiveSheet()->setCellValue('I'.$cell, $goal_activities);
                    $spreadsheet->getActiveSheet()->setCellValue('J'.$cell, $goal_activities);
                    $spreadsheet->getActiveSheet()->setCellValue('K'.$cell, $goal_activities_start_date);
                    $spreadsheet->getActiveSheet()->setCellValue('L'.$cell, $goal_activities_end_date);

                    foreach ($goal['goal_activities'] as $goalActivityKey => $data) {     
                        // Agrega la descripcion de los avances
                        $progress_descriptions = implode(",",array_column($data["goal_progresses"],"evidence_description"));
                        $spreadsheet->getActiveSheet()->setCellValue('S'.$contCeldas, $progress_descriptions);
                        
                        // $textoActividades = $textoActividades. "- ".$data['activity_name']."\n\n";
                        // $textoActividadesFechaInicio = "- ".$data['start_date']."\n\n".$textoActividadesFechaInicio;
                        // $textoActividadesFechaFin = "- ".$data['end_date']."\n\n".$textoActividadesFechaFin;
                        $textoActividadesResponsables = !empty($data["goal_responsibles"]) ? "- ".$data["goal_responsibles"][0]['responsibles_names']."\n\n".$textoActividadesResponsables : "- ".$evaluatedName."\n\n". $textoActividadesResponsables;
                    }

                    $spreadsheet->getActiveSheet()->setCellValue('J'.$cell, $textoActividadesResponsables);
                    $textoActividadesResponsables = '';

                    $cell++;
                }

                // Mergea las columnas
                $numberRowsToMerge = $contCeldas + (count($value['goals']) - 1);

                // dd($numberRowsToMerge);
                $spreadsheet->getActiveSheet()->mergeCells('B'. $contCeldas . ':B' .$numberRowsToMerge);
                $spreadsheet->getActiveSheet()->mergeCells('C'. $contCeldas . ':C' .$numberRowsToMerge);
                $spreadsheet->getActiveSheet()->mergeCells('D'. $contCeldas . ':D' .$numberRowsToMerge);
                $spreadsheet->getActiveSheet()->mergeCells('E'. $contCeldas . ':E' .$numberRowsToMerge);
                $spreadsheet->getActiveSheet()->mergeCells('F'. $contCeldas . ':F' .$numberRowsToMerge);
                $spreadsheet->getActiveSheet()->mergeCells('G'. $contCeldas . ':G' .$numberRowsToMerge);
                $spreadsheet->getActiveSheet()->mergeCells('H'. $contCeldas . ':H' .$numberRowsToMerge);
                $spreadsheet->getActiveSheet()->mergeCells('P'. $contCeldas . ':P' .$numberRowsToMerge);

                // Valores a ingresar en las celdas mergeadas de la oportunidad de mejora
                $spreadsheet->getActiveSheet()->setCellValue('B'.$contCeldas, $value['source_information']['name']);
                $spreadsheet->getActiveSheet()->setCellValue('C'.$contCeldas, $value['evaluation_criteria']);
                $spreadsheet->getActiveSheet()->setCellValue('D'.$contCeldas, $value['non_conforming_criterias']["criteria_name"]);
                $spreadsheet->getActiveSheet()->setCellValue('E'.$contCeldas, "");
                $spreadsheet->getActiveSheet()->setCellValue('F'.$contCeldas, $value['description_opportunity_improvement']);
                $spreadsheet->getActiveSheet()->setCellValue('G'.$contCeldas, $value['type_oportunity_improvements']['name']);
                $spreadsheet->getActiveSheet()->setCellValue('H'.$contCeldas, $value['description_cause_analysis']);
                $spreadsheet->getActiveSheet()->setCellValue('P'.$contCeldas, $value['weight'].' %');


            }





            $contCeldas += count($value["goals"]);
            $textoActividades = '';
            $textoActividadesResponsables = '';

        }

        $CELL_INITIAL_RECORD = 146;
        $cellLimit = $CELL_INITIAL_RECORD + ($goalsTotal - 1);

        //Combina la celdas del consecutivo y estado del plan
        $spreadsheet->getActiveSheet()->mergeCells('A146' . ':A' .$cellLimit);
        $spreadsheet->getActiveSheet()->mergeCells('T146' . ':T' .$cellLimit);


        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="Reporte plan mejoramiento.xlsx"');
        header('Cache-Control: max-age=0');

        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($spreadsheet, 'Xlsx');

        $writer->save('php://output');
        exit;

        return $this->sendResponse($writer, trans('data_obtained_successfully'));
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
    private function _assignEvaluationCriteria(int $evaluationId, string $criteriaName, string $status) : void{
        EvaluationCriterion::create(["evaluations_id" => $evaluationId, "criteria_name" => $criteriaName, "status" => $status, "observations" => null]);
    }

    /**
     * Asigna las oportunidades de mejora
     *
     * @author Kleverman Salazar Florez. - Ago. 04 - 2023
     * @version 1.0.0
     * 
     * @param int $evaluationId
     * @param string $criteriaName
     *
     * @return void
     */
    private function _assignImprovementOpportunities(int $evaluationId, int $sourceInformationId, int $typeOportunityImprovementsId, string $nameOpportunityImprovement, string $descriptionOpportunityImprovement, string $unitResponsibleImprovementOpportunity, int $dependenciaId, string $officialResponsible, int $officialResponsibleId, string $deadlineSubmission, string $evidence, string $evaluationCriteria, int $evaluationCriteriaId) : void{
        ImprovementOpportunity::create(["evaluations_id" => $evaluationId, "source_information_id" => $sourceInformationId, "type_oportunity_improvements_id" => $typeOportunityImprovementsId, "name_opportunity_improvement" => $nameOpportunityImprovement, "description_opportunity_improvement" => $descriptionOpportunityImprovement, "unit_responsible_improvement_opportunity" => $unitResponsibleImprovementOpportunity, "dependencia_id" => $dependenciaId, "official_responsible" => $officialResponsible, "official_responsible_id" => $officialResponsibleId, "deadline_submission" => $deadlineSubmission, "evidence" => $evidence, "evaluation_criteria" => $evaluationCriteria, "evaluation_criteria_id" => $evaluationCriteriaId]);
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
     * Obtiene todos los elementos existentes
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function show($id) {
        $approved_improvement_plans = ApprovedImprovementPlan::with(["evaluator","evaluated","EvaluationDependences","EvaluationCriteria","HistoryEvaluation","EvaluationImprovementOpportunities"])->where("id", $id)->first();
        return $this->sendResponse($approved_improvement_plans->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * consulta las actividades del plan
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function getActivitesPlanSelect($planId) {
        $opportunities = ImprovementOpportunity::where("evaluations_id", $planId)->get();
        $goals = Goal::whereIn("pm_improvement_opportunity_id", $opportunities->pluck("id"))->get();
        $activity = GoalActivity::where("status_modification", 'Si')->whereIn("pm_goals_id", $goals->pluck("id"))->get();
        return $this->sendResponse($activity->toArray(), trans('data_obtained_successfully'));
    }


    /**
     * Envia solicitud de modificacion del plan
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function modificationProcess(Request $request) {

        $user = Auth::user();
        $input = $request->all();

        foreach($input['activities_plans_processing'] as $activity){
            $dataActivity = json_decode($activity);
            Goal::where("id", $dataActivity->id)->update(["status_modification" => 'Aprobado']);
            GoalActivity::where("id", $dataActivity->pm_goals_id)->update(["status_modification" => 'Aprobado']);
        }

        $improvement_plans = $this->approvedImprovementPlanRepository->update(["status_improvement_plan" => "Aprobado"], $input['id']);

        $user = Auth::user();
        $history = new EvaluationHistory();
        $history->pm_evaluations_id = $input['id'];
        $history->users_id = $user->id;
        $history->status = "Aprobado";
        $history->observation = $input["observation"];
        $history->user_name = $user->name;
        $history->save();
        $improvement_plans->HistoryEvaluation;


        return $this->sendResponse($improvement_plans, trans('msg_success_update'));
    }
}
