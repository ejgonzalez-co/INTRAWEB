<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ImprovementPlans\Http\Requests\CreateImprovementOpportunityRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateImprovementOpportunityRequest;
use Modules\ImprovementPlans\Repositories\ImprovementOpportunityRepository;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\ImprovementOpportunity;
use Modules\ImprovementPlans\Models\EvaluationCriterion;
use App\Http\Controllers\AppBaseController;
use App\Http\Controllers\JwtController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\ImprovementPlans\Http\Controllers\UtilController;
use Modules\ImprovementPlans\Models\Dependence;
use Modules\ImprovementPlans\Models\GoalActivity;
use Illuminate\Support\Facades\Validator;
use Modules\ImprovementPlans\Models\Rol;
use Modules\ImprovementPlans\Models\RolPermission;
use App\User;
use Modules\ImprovementPlans\Models\Evaluation;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ImprovementOpportunityController extends AppBaseController {

    /** @var  ImprovementOpportunityRepository */
    private $improvementOpportunityRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ImprovementOpportunityRepository $improvementOpportunityRepo) {
        $this->improvementOpportunityRepository = $improvementOpportunityRepo;
    }

    /**
     * Muestra la vista para el CRUD de ImprovementOpportunity.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $evaluationId = $request["evaluation"];
        $evaluationInfo = Evaluation::select(["evaluation_name","type_evaluation"])->where("id",$evaluationId)->first();

        // $allowedRoles = array_column(Rol::select("name")->whereIn("id",array_column(RolPermission::select("role_id")->where("module","Evaluadores")->groupBy("role_id")->get()->toArray(),"role_id"))->get()->toArray(),"name");
        $allowedRoles = ["Evaluadores - Gestión (crear, editar y eliminar registros)", "Evaluadores - Reportes", "Evaluadores - Solo consulta"];

        if(Auth::user()->hasRole("Registered")){
            return view('improvementplans::improvement_opportunities.index')->with("can_manage", true)->with("can_generate_reports", true)->with("evaluation", $evaluationId)->with("evaluationInfo", $evaluationInfo);
        }
        if(Auth::user()->hasRole($allowedRoles)){
            // $idRol = Rol::select("id")->where("name",$allowedRoles[0])->first()->id;
            // $rolePermissions = RolPermission::select(["can_manage","can_generate_reports"])->where("role_id",$idRol)->where("module","Evaluadores")->first();
            return view('improvementplans::improvement_opportunities.index')->with("can_manage", Auth::user()->hasRole("Evaluadores - Gestión (crear, editar y eliminar registros)"))->with("can_generate_reports", Auth::user()->hasRole("Evaluadores - Reportes"))->with("evaluation", $evaluationId)->with("evaluationInfo", $evaluationInfo);
        }
        return abort(403,"No autorizado");
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
        $improvement_opportunities = ImprovementOpportunity::where("evaluations_id",$request["evaluation"])->with(["sourceInformation","typeOportunityImprovements"])->latest()->get();
        return $this->sendResponse($improvement_opportunities->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los criterios de evaluacion que sean no conformes de una evaluacion
     *
     * @author Kleverman Salazar Florez. - Ago. 10 - 2023
     * @version 1.0.0
     *
     * @param int $evaluationId
     * 
     * @return Response
     */
    public function getNonCompliantEvaluationCriteria(int $evaluationId){
        $nonCompliantEvaluationCriteria = EvaluationCriterion::select("criteria_name","id")->where("evaluations_id",$evaluationId)->where("status","No conforme")->get()->toArray();
        return $this->sendResponse($nonCompliantEvaluationCriteria, trans('data_obtained_successfully'));
    }

    public function getActivitiesByGoal(int $goalId) : array{
        $goalActivities = GoalActivity::select(["pm_goals_id","id","activity_name","activity_weigth","start_date","end_date"])->where("pm_goals_id",$goalId)->get()->toArray();
        return $this->sendResponse($goalActivities, trans('data_obtained_successfully'));
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

        // Validar la existencia de un archivo para realizar las validaciones de extension y peso
        if($request->hasFile('evidence')){
            $validatorMimes = Validator::make($request->all(), [
                'evidence' => 'required|file|mimes:jpeg,jpg,png,doc,docx,pdf,ppt,pptx,xls,xlsx',
            ]);
    
            $validatorWeight = Validator::make($request->all(), [
                'evidence' => 'required|file|max:5024',
            ]);
    
            if ($validatorMimes->fails()) {
                return $this->sendSuccess("El archivo no es de extensión doc, docx, pdf, ppt, pptx, xls, xlsx, png, jpg o jpeg.", 'error');
            }
    
            if ($validatorWeight->fails()) {
                return $this->sendSuccess("El archivo debe tener un tamaño menor a 5MB.", 'error');
            }
            // Se crea una instancia de la clase principal para enviarla como parámetro y usar las funciones no estáticas
            $app_base_controller = new AppBaseController();
            $input["evidence"] = UtilController::uploadFile($input["evidence"],'public/improvement_plan/improvement_opportunity_evidences', $app_base_controller);

        }


        if($input["dependencia_id"]){
            $input["unit_responsible_improvement_opportunity"]  = Dependence::where("id", $input["dependencia_id"])->value('nombre');
        }
                
        if($input["official_responsible_id"]){
            $input["official_responsible"]  = User::where("id", $input["official_responsible_id"])->value('name');
        }

        if($input["evaluation_criteria_id"]){
            $input["evaluation_criteria"]  = EvaluationCriterion::where("id", $input["evaluation_criteria_id"])->value('criteria_name');
        }
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $improvementOpportunity = $this->improvementOpportunityRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            // Obtiene relaciones
            $improvementOpportunity->sourceInformation;
            $improvementOpportunity->typeOportunityImprovements;

            return $this->sendResponse($improvementOpportunity->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementOpportunityController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementOpportunityController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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

        // Validar la existencia de un archivo para realizar las validaciones de extension y peso
        if($request->hasFile('evidence')){
            $validatorMimes = Validator::make($request->all(), [
                'evidence' => 'required|file|mimes:jpeg,jpg,png,doc,docx,pdf,ppt,pptx,xls,xlsx',
            ]);
    
            $validatorWeight = Validator::make($request->all(), [
                'evidence' => 'required|file|max:5024',
            ]);
    
            if ($validatorMimes->fails()) {
                return $this->sendSuccess("El archivo no es de extensión doc, docx, pdf, ppt, pptx, xls, xlsx, png, jpg o jpeg.", 'error');
            }
    
            if ($validatorWeight->fails()) {
                return $this->sendSuccess("El archivo debe tener un tamaño menor a 5MB.", 'error');
            }
            // Se crea una instancia de la clase principal para enviarla como parámetro y usar las funciones no estáticas
            $app_base_controller = new AppBaseController();
            $input["evidence"] = UtilController::uploadFile($input["evidence"],'public/improvement_plan/improvement_opportunity_evidences', $app_base_controller);

        }
        

        if($input["dependencia_id"]){
            $input["unit_responsible_improvement_opportunity"]  = Dependence::where("id", $input["dependencia_id"])->value('nombre');
        }

                
        if($input["official_responsible_id"]){
            $input["official_responsible"]  = User::where("id", $input["official_responsible_id"])->value('name');
        }
        if($input["evaluation_criteria_id"]){
            $input["evaluation_criteria"]  = EvaluationCriterion::where("id", $input["evaluation_criteria_id"])->value('criteria_name');
        }
        /** @var ImprovementOpportunity $improvementOpportunity */
        $improvementOpportunity = $this->improvementOpportunityRepository->find($input["id"]);
        
        
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

            // Obtiene relaciones
            $improvementOpportunity->sourceInformation;
            $improvementOpportunity->typeOportunityImprovements;
        
            return $this->sendResponse($improvementOpportunity->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementOpportunityController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementOpportunityController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ImprovementOpportunity del almacenamiento
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

        /** @var ImprovementOpportunity $improvementOpportunity */
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
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementOpportunityController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\ImprovementOpportunityController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('improvement_opportunities').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $data = JwtController::decodeToken($input["data"]);
            array_walk($data, fn(&$object) => $object = (array) $object);
            $improvementOpportunitiesId = array_column($data, "id");
            $improvementOpportunities = ImprovementOpportunity::whereIn("id",$improvementOpportunitiesId)->with(["sourceInformation","typeOportunityImprovements"])->latest()->get()->toArray();
            return UtilController::exportReportToXlsxFile('improvementplans::improvement_opportunities.exports.xlsx',$improvementOpportunities,'H','Reporte de las oportunidades de mejora.xlsx');
        }
    }
}
