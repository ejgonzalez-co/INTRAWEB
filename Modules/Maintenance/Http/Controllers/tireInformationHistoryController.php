<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreatetireInformationHistoryRequest;
use Modules\Maintenance\Http\Requests\UpdatetireInformationHistoryRequest;
use Modules\Maintenance\Repositories\tireInformationHistoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Modules\Maintenance\Models\tireInformationHistory;
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
class tireInformationHistoryController extends AppBaseController {

    /** @var  tireInformationHistoryRepository */
    private $tireInformationHistoryRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(tireInformationHistoryRepository $tireInformationHistoryRepo) {
        $this->tireInformationHistoryRepository = $tireInformationHistoryRepo;
    }

    /**
     * Muestra la vista para el CRUD de tireInformationHistory.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::tire_information_histories.index')->with('machinery', $request['machinery']);
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
        
        if($request['machinery']){
            $tire_information_histories = tireInformationHistory::where('mant_resume_machinery_vehicles_yellow_id',$request['machinery'])->latest()->get();
        }else{
            $tire_information_histories = tireInformationHistory::latest()->get();
        }
        
        
        return $this->sendResponse($tire_information_histories->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Cambia el estado del historial
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function changeStatus($id)
    {
        //Consulta el estado actual del registro
        $status = tireInformationHistory::where('id',$id)->first()->toArray();
        
        //Valida el estado actual
        if ($status['status'] == 'Activo') {
            $input['status'] = 'Oculto';
        }else{
            $input['status'] = 'Activo';
        }

        // Actualiza el registro
        $tireInformationHistory = $this->tireInformationHistoryRepository->update($input, $id);

        return $this->sendResponse($tireInformationHistory->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatetireInformationHistoryRequest $request
     *
     * @return Response
     */
    public function store(CreatetireInformationHistoryRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $tireInformationHistory = $this->tireInformationHistoryRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($tireInformationHistory->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\tireInformationHistoryController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\tireInformationHistoryController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdatetireInformationHistoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatetireInformationHistoryRequest $request) {

        $input = $request->all();

        /** @var tireInformationHistory $tireInformationHistory */
        $tireInformationHistory = $this->tireInformationHistoryRepository->find($id);

        if (empty($tireInformationHistory)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $tireInformationHistory = $this->tireInformationHistoryRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($tireInformationHistory->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\tireInformationHistoryController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\tireInformationHistoryController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un tireInformationHistory del almacenamiento
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

        /** @var tireInformationHistory $tireInformationHistory */
        $tireInformationHistory = $this->tireInformationHistoryRepository->find($id);

        if (empty($tireInformationHistory)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $tireInformationHistory->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\tireInformationHistoryController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\tireInformationHistoryController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('tire_information_histories').'.'.$fileType;

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
