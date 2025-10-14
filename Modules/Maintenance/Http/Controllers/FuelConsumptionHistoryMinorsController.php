<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateFuelConsumptionHistoryMinorsRequest;
use Modules\Maintenance\Http\Requests\UpdateFuelConsumptionHistoryMinorsRequest;
use Modules\Maintenance\Repositories\FuelConsumptionHistoryMinorsRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\FuelConsumptionHistoryMinors;
use Modules\Maintenance\Models\MinorEquipmentFuel;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Descripcion de la clase
 * En esta clase va todo lo que tiene que ver con el historiasl de equipos menores
 *
 * @author Nicolas D. Ortiz Peña 17/02/2022
 * @version 1.0.0
 */
class FuelConsumptionHistoryMinorsController extends AppBaseController {

    /** @var  FuelConsumptionHistoryMinorsRepository */
    private $fuelConsumptionHistoryMinorsRepository;

    /**
     * Constructor de la clase
     *
     * @author Nicolas D. Ortiz Peña 17/02/2022
     * @version 1.0.0
     */
    public function __construct(FuelConsumptionHistoryMinorsRepository $fuelConsumptionHistoryMinorsRepo) {
        $this->fuelConsumptionHistoryMinorsRepository = $fuelConsumptionHistoryMinorsRepo;
    }

    /**
     * Muestra la vista para el CRUD de FuelConsumptionHistoryMinors.
     *
     * @author Nicolas D. Ortiz Peña 17/02/2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $minorEquipment=MinorEquipmentFuel::find($request['equipment']);
        
        
        return view('maintenance::fuel_consumption_history_minors.index')->with("equipment", $request['equipment'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Nicolas D. Ortiz Peña 17/02/2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {

        $fuel_consumption_history_minors = FuelConsumptionHistoryMinors::where('id_equipment_minor',$request['equipment'])->latest()->get();
        
        return $this->sendResponse($fuel_consumption_history_minors->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Nicolas D. Ortiz Peña 17/02/2022
     * @version 1.0.0
     *
     * @param CreateFuelConsumptionHistoryMinorsRequest $request
     *
     * @return Response
     */
    public function store(CreateFuelConsumptionHistoryMinorsRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $fuelConsumptionHistoryMinors = $this->fuelConsumptionHistoryMinorsRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($fuelConsumptionHistoryMinors->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelConsumptionHistoryMinorsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelConsumptionHistoryMinorsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateFuelConsumptionHistoryMinorsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFuelConsumptionHistoryMinorsRequest $request) {

        $input = $request->all();

        /** @var FuelConsumptionHistoryMinors $fuelConsumptionHistoryMinors */
        $fuelConsumptionHistoryMinors = $this->fuelConsumptionHistoryMinorsRepository->find($id);

        if (empty($fuelConsumptionHistoryMinors)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $fuelConsumptionHistoryMinors = $this->fuelConsumptionHistoryMinorsRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($fuelConsumptionHistoryMinors->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelConsumptionHistoryMinorsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelConsumptionHistoryMinorsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un FuelConsumptionHistoryMinors del almacenamiento
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

        /** @var FuelConsumptionHistoryMinors $fuelConsumptionHistoryMinors */
        $fuelConsumptionHistoryMinors = $this->fuelConsumptionHistoryMinorsRepository->find($id);

        if (empty($fuelConsumptionHistoryMinors)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $fuelConsumptionHistoryMinors->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelConsumptionHistoryMinorsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\maintenance\Http\Controllers\FuelConsumptionHistoryMinorsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('fuel_consumption_history_minors').'.'.$fileType;

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
