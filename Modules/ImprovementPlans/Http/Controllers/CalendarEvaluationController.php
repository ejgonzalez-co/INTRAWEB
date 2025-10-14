<?php

namespace Modules\ImprovementPlans\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ImprovementPlans\Http\Requests\CreateCalendarEvaluationRequest;
use Modules\ImprovementPlans\Http\Requests\UpdateCalendarEvaluationRequest;
use Modules\ImprovementPlans\Repositories\CalendarEvaluationRepository;
use Modules\ImprovementPlans\Models\CalendarEvaluation;
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

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class CalendarEvaluationController extends AppBaseController {

    /** @var  CalendarEvaluationRepository */
    private $calendarEvaluationRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(CalendarEvaluationRepository $calendarEvaluationRepo) {
        $this->calendarEvaluationRepository = $calendarEvaluationRepo;
    }

    /**
     * Muestra la vista para el CRUD de CalendarEvaluation.
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
            return view('improvementplans::calendar_evaluations.index');
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
        $calendar_evaluations = CalendarEvaluation::where("evaluated_id",Auth::user()->id)->get();
        return $this->sendResponse($calendar_evaluations->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateCalendarEvaluationRequest $request
     *
     * @return Response
     */
    public function store(CreateCalendarEvaluationRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $calendarEvaluation = $this->calendarEvaluationRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($calendarEvaluation->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\CalendarEvaluationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\CalendarEvaluationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateCalendarEvaluationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateCalendarEvaluationRequest $request) {

        $input = $request->all();

        /** @var CalendarEvaluation $calendarEvaluation */
        $calendarEvaluation = $this->calendarEvaluationRepository->find($id);

        if (empty($calendarEvaluation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $calendarEvaluation = $this->calendarEvaluationRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($calendarEvaluation->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\CalendarEvaluationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\CalendarEvaluationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un CalendarEvaluation del almacenamiento
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

        /** @var CalendarEvaluation $calendarEvaluation */
        $calendarEvaluation = $this->calendarEvaluationRepository->find($id);

        if (empty($calendarEvaluation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $calendarEvaluation->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\CalendarEvaluationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\ImprovementPlans\Http\Controllers\CalendarEvaluationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('calendar_evaluations').'.'.$fileType;

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
