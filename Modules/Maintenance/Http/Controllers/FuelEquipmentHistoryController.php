<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateFuelEquipmentHistoryRequest;
use Modules\Maintenance\Http\Requests\UpdateFuelEquipmentHistoryRequest;
use Modules\Maintenance\Repositories\FuelEquipmentHistoryRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Descripcion de la clase
 * Aqui va el historial de combustible de equipos menores
 *
 * @author Nicolas D. Ortiz Peña 17/02/2022
 * @version 1.0.0
 */
class FuelEquipmentHistoryController extends AppBaseController {

    /** @var  FuelEquipmentHistoryRepository */
    private $fuelEquipmentHistoryRepository;

    /**
     * Constructor de la clase
     *
     * @author Nicolas D. Ortiz Peña 17/02/2022
     * @version 1.0.0
     */
    public function __construct(FuelEquipmentHistoryRepository $fuelEquipmentHistoryRepo) {
        $this->fuelEquipmentHistoryRepository = $fuelEquipmentHistoryRepo;
    }

    /**
     * Muestra la vista para el CRUD de FuelEquipmentHistory.
     *
     * @author Nicolas D. Ortiz Peña 17/02/2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::fuel_equipment_histories.index');
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas D. Ortiz Peña 17/02/2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all() {
        $fuel_equipment_histories = $this->fuelEquipmentHistoryRepository->all();
        return $this->sendResponse($fuel_equipment_histories->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Nicolas D. Ortiz Peña 17/02/2022
     * @version 1.0.0
     *
     * @param CreateFuelEquipmentHistoryRequest $request
     *
     * @return Response
     */
    public function store(CreateFuelEquipmentHistoryRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $fuelEquipmentHistory = $this->fuelEquipmentHistoryRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($fuelEquipmentHistory->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelEquipmentHistoryController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelEquipmentHistoryController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Nicolas D. Ortiz Peña 17/02/2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateFuelEquipmentHistoryRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFuelEquipmentHistoryRequest $request) {

        $input = $request->all();

        /** @var FuelEquipmentHistory $fuelEquipmentHistory */
        $fuelEquipmentHistory = $this->fuelEquipmentHistoryRepository->find($id);

        if (empty($fuelEquipmentHistory)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $fuelEquipmentHistory = $this->fuelEquipmentHistoryRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($fuelEquipmentHistory->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelEquipmentHistoryController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelEquipmentHistoryController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un FuelEquipmentHistory del almacenamiento
     *
     * @author Nicolas D. Ortiz Peña 17/02/2022
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var FuelEquipmentHistory $fuelEquipmentHistory */
        $fuelEquipmentHistory = $this->fuelEquipmentHistoryRepository->find($id);

        if (empty($fuelEquipmentHistory)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $fuelEquipmentHistory->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelEquipmentHistoryController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelEquipmentHistoryController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
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
        $fileName = time().'-'.trans('fuel_equipment_histories').'.'.$fileType;

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
