<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateHistoryBudgetAssignationRequest;
use Modules\Maintenance\Http\Requests\UpdateHistoryBudgetAssignationRequest;
use Modules\Maintenance\Repositories\HistoryBudgetAssignationRepository;
use Modules\Maintenance\Models\HistoryBudgetAssignation;
use Modules\Maintenance\Models\ProviderContract;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Esta es la clase del historial de asignacion de presupuesto
 *
 * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
 * @version 1.0.0
 */
class HistoryBudgetAssignationController extends AppBaseController {

    /** @var  HistoryBudgetAssignationRepository */
    private $historyBudgetAssignationRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(HistoryBudgetAssignationRepository $historyBudgetAssignationRepo) {
        $this->historyBudgetAssignationRepository = $historyBudgetAssignationRepo;
    }

    /**
     * Muestra la vista para el CRUD de HistoryBudgetAssignation.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        
        $providerContract=ProviderContract::with('providers')->find($request['mpc']);
        
        return view('maintenance::history_budget_assignations.index', compact('providerContract'))->with("mpc", $request['mpc'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $history_budget_assignations=HistoryBudgetAssignation::where('mant_provider_contract_id', $request['mpc'])->latest()->get();
        return $this->sendResponse($history_budget_assignations->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param CreateHistoryBudgetAssignationRequest $request
     *
     * @return Response
     */
    public function store(CreateHistoryBudgetAssignationRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $historyBudgetAssignation = $this->historyBudgetAssignationRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($historyBudgetAssignation->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\HistoryBudgetAssignationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\HistoryBudgetAssignationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateHistoryBudgetAssignationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateHistoryBudgetAssignationRequest $request) {

        $input = $request->all();

        /** @var HistoryBudgetAssignation $historyBudgetAssignation */
        $historyBudgetAssignation = $this->historyBudgetAssignationRepository->find($id);

        if (empty($historyBudgetAssignation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $historyBudgetAssignation = $this->historyBudgetAssignationRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($historyBudgetAssignation->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\HistoryBudgetAssignationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\HistoryBudgetAssignationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un HistoryBudgetAssignation del almacenamiento
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var HistoryBudgetAssignation $historyBudgetAssignation */
        $historyBudgetAssignation = $this->historyBudgetAssignationRepository->find($id);

        if (empty($historyBudgetAssignation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $historyBudgetAssignation->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\HistoryBudgetAssignationController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\HistoryBudgetAssignationController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Nicolas Dario Ortiz Peña. - Septiembre. 08 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('history_budget_assignations').'.'.$fileType;

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
