<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ImprovementPlans\Http\Requests\CreateEvaluationEvaluatedRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateEvaluationEvaluatedRequest;
use Modules\ImprovementPlans\Repositories\EvaluationEvaluatedRepository;
use Modules\ImprovementPlans\Models\EvaluationEvaluated;
use Modules\ImprovementPlans\Models\Evaluation;
use Modules\ImprovementPlans\Models\ContentManagement;
use Modules\ImprovementPlans\Models\RolPermission;
use Modules\ImprovementPlans\Models\Rol;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\ImprovementPlans\Http\Controllers\UtilController;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class EvaluationEvaluatedController extends AppBaseController {

    /** @var  EvaluationEvaluatedRepository */
    private $evaluationEvaluatedRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(EvaluationEvaluatedRepository $evaluationEvaluatedRepo) {
        $this->evaluationEvaluatedRepository = $evaluationEvaluatedRepo;
    }

    /**
     * Muestra la vista para el CRUD de EvaluationEvaluated.
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

        if(Auth::user()->hasRole($allowedRoles)){
            // $idRol = Rol::select("id")->where("name",$allowedRoles[0])->first()->id;
            // $rolePermissions = RolPermission::select(["can_manage","can_generate_reports"])->where("role_id",$idRol)->where("module","Planes de mejoramiento")->first();
            return view('improvementplans::evaluation_evaluateds.index')->with("can_manage", Auth::user()->hasRole("Planes de mejoramiento - Gestión (crear, editar y eliminar registros)"))->with("can_generate_reports", Auth::user()->hasRole("Planes de mejoramiento - Reportes"));
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
    public function all() {
        $evaluation_evaluateds = EvaluationEvaluated::with(["evaluator","EvaluationDependences","EvaluationCriteria"])->where("evaluated_id",Auth::user()->id)->latest()->get();
        return $this->sendResponse($evaluation_evaluateds->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateEvaluationEvaluatedRequest $request
     *
     * @return Response
     */
    public function store(CreateEvaluationEvaluatedRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $evaluationEvaluated = $this->evaluationEvaluatedRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($evaluationEvaluated->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationEvaluatedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationEvaluatedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateEvaluationEvaluatedRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEvaluationEvaluatedRequest $request) {

        $input = $request->all();

        /** @var EvaluationEvaluated $evaluationEvaluated */
        $evaluationEvaluated = $this->evaluationEvaluatedRepository->find($id);

        if (empty($evaluationEvaluated)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $evaluationEvaluated = $this->evaluationEvaluatedRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($evaluationEvaluated->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationEvaluatedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationEvaluatedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un EvaluationEvaluated del almacenamiento
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

        /** @var EvaluationEvaluated $evaluationEvaluated */
        $evaluationEvaluated = $this->evaluationEvaluatedRepository->find($id);

        if (empty($evaluationEvaluated)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $evaluationEvaluated->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationEvaluatedController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\EvaluationEvaluatedController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('evaluation_evaluateds').'.'.$fileType;

        // Valida si el tipo de archivo es pdf
        if (strcmp($fileType, 'pdf') == 0) {
            // Guarda el archivo pdf en ubicacion temporal
            // Excel::store(new GenericExport($input['data']), $fileName, 'temp', \Maatwebsite\Excel\Excel::DOMPDF);

            // Descarga el archivo generado
            return Excel::download(new GenericExport($input['data']), $fileName, \Maatwebsite\Excel\Excel::DOMPDF);
        } else if (strcmp($fileType, 'xlsx') == 0) {
            $evaluations = Evaluation::where("evaluated_id",Auth::user()->id)->with(["evaluator","evaluated","EvaluationDependences","EvaluationCriteria"])->latest()->get();
            
            // Descarga el archivo generado
            return UtilController::exportReportToXlsxFile('improvementplans::evaluations.exports.evaluations',$evaluations,'M','Reporte de las evaluaciones.xlsx');
        }
    }
}
