<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateDataAnalyticRequest;
use Modules\HelpTable\Http\Requests\UpdateDataAnalyticRequest;
use Modules\HelpTable\Repositories\DataAnalyticRepository;
use Modules\HelpTable\Models\DataAnalytic;
use Modules\HelpTable\Models\TicSatisfactionPoll;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use App\Http\Controllers\GoogleController;

/**
 * Descripcion de la clase
 *
 * @author Kleverman Salazar Florez. - Ene. 18 - 2023
 * @version 1.0.0
 */
class DataAnalyticController extends AppBaseController {

    /** @var  DataAnalyticRepository */
    private $dataAnalyticRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ene. 18 - 2023
     * @version 1.0.0
     */
    public function __construct(DataAnalyticRepository $dataAnalyticRepo) {
        $this->dataAnalyticRepository = $dataAnalyticRepo;
    }

    /**
     * Muestra la vista para el CRUD de DataAnalytic.
     *
     * @author Kleverman Salazar Florez. - Ene. 18 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('help_table::data_analytics.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar Florez. - Ene. 18 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {

        $count_tic_requests = 0;

        // * cp: currentPage
        // * pi: pageItems
        // * f: filtros
        
        // Valida si existen las variables del paginado y si esta filtrando
        if(isset($request["f"]) && $request["f"] != "" && isset($request["cp"]) && isset($request["pi"])) {
            $tic_requests = DataAnalytic::
                with(['ticTypeRequest', 'ticRequestStatus', 'assignedBy', 'assignedUser', 'ticMaintenances', 'ticKnowledgeBases'])
                ->with(['users' => function ($query) {
                    $query->with(['dependencies']);
                }])
                ->when(Auth::user()->id, function ($query) {
                    // Valida si el usuario logueado es un tecnico tic o un proveedor
                    if (Auth::user()->hasRole('Soporte TIC') || Auth::user()->hasRole('Proveedor TIC')) {
                        $query->where('assigned_user_id', Auth::user()->id);
                    }
                    // Valida si el usuario logueado usuario normal tic
                    else if (Auth::user()->hasRole('Usuario TIC')) {
                        $query->where('users_id', Auth::user()->id);
                    }
                    return $query;
                })
                ->whereRaw(base64_decode($request["f"]))->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))
                ->get()
                ->map(function($request, $key) {
                    return UtilController::stateTimeline($request);
                });

            $count_tic_requests= DataAnalytic::
            with(['ticTypeRequest', 'ticRequestStatus', 'assignedBy', 'assignedUser', 'ticMaintenances', 'ticKnowledgeBases'])
            ->with(['users' => function ($query) {
                $query->with(['dependencies']);
            }])
            ->when(Auth::user()->id, function ($query) {
                // Valida si el usuario logueado es un tecnico tic o un proveedor
                if (Auth::user()->hasRole('Soporte TIC') || Auth::user()->hasRole('Proveedor TIC')) {
                    $query->where('assigned_user_id', Auth::user()->id);
                }
                // Valida si el usuario logueado usuario normal tic
                else if (Auth::user()->hasRole('Usuario TIC')) {
                    $query->where('users_id', Auth::user()->id);
                }
                return $query;
            })
            ->whereRaw(base64_decode($request["f"]))->latest()->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))
            ->count();
        }else {
            $tic_requests = DataAnalytic::
                with(['ticTypeRequest', 'ticRequestStatus', 'assignedBy', 'assignedUser', 'ticMaintenances', 'ticKnowledgeBases'])
                ->with(['users' => function ($query) {
                    $query->with(['dependencies']);
                }])
                ->when(Auth::user()->id, function ($query) {
                    // Valida si el usuario logueado es un tecnico tic o un proveedor
                    if (Auth::user()->hasRole('Soporte TIC') || Auth::user()->hasRole('Proveedor TIC')) {
                        $query->where('assigned_user_id', Auth::user()->id);
                    }
                    // Valida si el usuario logueado usuario normal tic
                    else if (Auth::user()->hasRole('Usuario TIC')) {
                        $query->where('users_id', Auth::user()->id);
                    }
                    return $query;
                })
                ->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))
                ->latest()
                ->get()
                ->map(function($request, $key) {
                    return UtilController::stateTimeline($request);
                });

            $count_tic_requests= DataAnalytic::
            with(['ticTypeRequest', 'ticRequestStatus', 'assignedBy', 'assignedUser', 'ticMaintenances', 'ticKnowledgeBases'])
            ->with(['users' => function ($query) {
                $query->with(['dependencies']);
            }])
            ->when(Auth::user()->id, function ($query) {
                // Valida si el usuario logueado es un tecnico tic o un proveedor
                if (Auth::user()->hasRole('Soporte TIC') || Auth::user()->hasRole('Proveedor TIC')) {
                    $query->where('assigned_user_id', Auth::user()->id);
                }
                // Valida si el usuario logueado usuario normal tic
                else if (Auth::user()->hasRole('Usuario TIC')) {
                    $query->where('users_id', Auth::user()->id);
                }
                return $query;
            })
            ->skip((base64_decode($request["cp"])-1)*base64_decode($request["pi"]))->take(base64_decode($request["pi"]))
            ->latest()
            ->count();

        }
        // return $this->sendResponse($tic_requests->toArray(), trans('data_obtained_successfully'));
    return $this->sendResponseAvanzado($tic_requests, trans('data_obtained_successfully'), null, ["total_registros" => $count_tic_requests]);

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 18 - 2023
     * @version 1.0.0
     *
     * @param CreateDataAnalyticRequest $request
     *
     * @return Response
     */
    public function store(CreateDataAnalyticRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $dataAnalytic = $this->dataAnalyticRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($dataAnalytic->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\DataAnalyticController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\DataAnalyticController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ene. 18 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateDataAnalyticRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDataAnalyticRequest $request) {

        $input = $request->all();

        /** @var DataAnalytic $dataAnalytic */
        $dataAnalytic = $this->dataAnalyticRepository->find($id);

        if (empty($dataAnalytic)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $dataAnalytic = $this->dataAnalyticRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($dataAnalytic->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\DataAnalyticController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\DataAnalyticController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un DataAnalytic del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 18 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var DataAnalytic $dataAnalytic */
        $dataAnalytic = $this->dataAnalyticRepository->find($id);

        if (empty($dataAnalytic)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $dataAnalytic->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\DataAnalyticController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\DataAnalyticController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Kleverman Salazar Florez. - Ene. 18 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('data_analytics').'.'.$fileType;

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
            return $this->charts();
            // return Excel::download(new GenericExport($input['data']), $fileName);
        }
    }

    /**
     * Exporta el grafico de barras con la estadistica de las encuestas de satisfacción
     *
     * @author Kleverman Salazar Florez. - Ene. 18 - 2023
     * @version 1.0.0
     *
     * @param string $dateInitial
     * @param string $dateFinal
     *
     */
    public function exportGraphOfSatisfationPollByDateRange(string $dateInitial, string $dateFinal){
        $dateInitial .= "  00:00:00";
        $dateFinal .= "  23:59:59";
        $e = new GoogleController();
        $listSurveyCompletedId = DataAnalytic::select("id")->where("survey_status",2)->whereBetween('created_at',[$dateInitial,$dateFinal])->get();

        $surveyCompletedId = [];

        foreach($listSurveyCompletedId as $surveyCompleted){
            array_push($surveyCompletedId,$surveyCompleted["id"]);
        }

        $quantitySurveysCompleted = count($listSurveyCompletedId);

        $quantitySurveysNotCompleted = DataAnalytic::where("survey_status",1)->whereBetween('created_at',[$dateInitial,$dateFinal])->count();
        $quantityTotalSurveys = $quantitySurveysCompleted + $quantitySurveysNotCompleted;

        $quantityAnswersToItWasAttended = $this->_getQuantityAnswersPerQuestion("¿Fue atendido oportunamente el servicio?","Si",$surveyCompletedId);
        $quantityAnswersToNotAttended = $this->_getQuantityAnswersPerQuestion("¿Fue atendido oportunamente el servicio?","No",$surveyCompletedId);

        $quantityAnswersToTechnicianWasRespectful = $this->_getQuantityAnswersPerQuestion("¿El técnico fue cordial y respetuoso?","Si",$surveyCompletedId);
        $quantityAnswersToTechnicianWasNotRespectful = $this->_getQuantityAnswersPerQuestion("¿El técnico fue cordial y respetuoso?","No",$surveyCompletedId);

        $quantityAnswersToSolvedTheProblem = $this->_getQuantityAnswersPerQuestion("¿La atención brindada por el técnico solucionó completamente la falla?","Si",$surveyCompletedId);
        $quantityAnswersToDidNotSolvedTheProblem = $this->_getQuantityAnswersPerQuestion("¿La atención brindada por el técnico solucionó completamente la falla?","No",$surveyCompletedId);

        $data[] = ["range" => "C2", "values" => [[$quantityTotalSurveys]]];
        $data[] = ["range" => "D2", "values" => [[$quantitySurveysCompleted]]];
        $data[] = ["range" => "E2", "values" => [[$quantitySurveysNotCompleted]]];
        $data[] = ["range" => "F2", "values" => [[$quantityAnswersToItWasAttended]]];
        $data[] = ["range" => "G2", "values" => [[$quantityAnswersToNotAttended]]];

        $data[] = ["range" => "C3", "values" => [[$quantityTotalSurveys]]];
        $data[] = ["range" => "D3", "values" => [[$quantitySurveysCompleted]]];
        $data[] = ["range" => "E3", "values" => [[$quantitySurveysNotCompleted]]];
        $data[] = ["range" => "F3", "values" => [[$quantityAnswersToTechnicianWasRespectful]]];
        $data[] = ["range" => "G3", "values" => [[$quantityAnswersToTechnicianWasNotRespectful]]];

        $data[] = ["range" => "C4", "values" => [[$quantityTotalSurveys]]];
        $data[] = ["range" => "D4", "values" => [[$quantitySurveysCompleted]]];
        $data[] = ["range" => "E4", "values" => [[$quantitySurveysNotCompleted]]];
        $data[] = ["range" => "F4", "values" => [[$quantityAnswersToSolvedTheProblem]]];
        $data[] = ["range" => "G4", "values" => [[$quantityAnswersToDidNotSolvedTheProblem]]];

        $fileIdCopy = $e->editFileExcelBatch("1Bo3FoEzxsw2auntlPdV2ER-vAvQ_Fuv-aFCX_zJBSdY", $data, true);

        $e->downloadFileGoogleDrive($fileIdCopy, "Grafico Encuestas de satisfacción del ".$dateInitial." hasta ".$dateFinal, "excel", true);
    }

    /**
     * Exporta el grafico de barras con la estadistica de las encuestas de satisfacción
     *
     * @author Kleverman Salazar Florez. - Ene. 18 - 2023
     * @version 1.0.0
     *
     * @param string $question
     * @param string $answer
     * @param array $listSurveyCompletedId
     *
     * @return int
     */
    private function _getQuantityAnswersPerQuestion(string $question, string $answer,array $listSurveyCompletedId):int{
        $quantityAnswers = TicSatisfactionPoll::where("question",$question)->where("reply",$answer)->whereIn("ht_tic_requests_id",$listSurveyCompletedId)->count();
        return $quantityAnswers;
    }
}
