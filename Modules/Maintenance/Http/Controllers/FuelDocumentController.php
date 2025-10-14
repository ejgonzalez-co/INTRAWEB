<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateFuelDocumentRequest;
use Modules\Maintenance\Http\Requests\UpdateFuelDocumentRequest;
use Modules\Maintenance\Repositories\FuelDocumentRepository;
use App\Http\Controllers\AppBaseController;
use Modules\Maintenance\Models\FuelDocument;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Andres Stiven Pinzon G. - Ago. 20 - 2021
 * @version 1.0.0
 */
class FuelDocumentController extends AppBaseController {

    /** @var  FuelDocumentRepository */
    private $fuelDocumentRepository;

    /**
     * Constructor de la clase
     *
     * @author Andres Stiven Pinzon G. - Ago. 20 - 2021
     * @version 1.0.0
     */
    public function __construct(FuelDocumentRepository $fuelDocumentRepo) {
        $this->fuelDocumentRepository = $fuelDocumentRepo;
    }

    /**
     * Muestra la vista para el CRUD de FuelDocument.
     *
     * @author Andres Stiven Pinzon G. - Ago. 20 - 2021
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::fuel_documents.index')->with("fuel_id", $request['vehicle_fuel_id'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Andres Stiven Pinzon G. - Ago. 20 - 2021
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $fuelDocuments=FuelDocument::with(['vehicleFuels'])->where('mant_vehicle_fuels_id',$request['fuel_id'])->latest()->get();
        // $fuel_documents = $this->fuelDocumentRepository->all();
        return $this->sendResponse($fuelDocuments->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Andres Stiven Pinzon G. - Ago. 20 - 2021
     * @version 1.0.0
     *
     * @param CreateFuelDocumentRequest $request
     *
     * @return Response
     */
    public function store(CreateFuelDocumentRequest $request) {
        // dd($request);

        $info = $request->all();
        $url = implode(",", $info["url_document_fuel"]);
        
        $info['mant_vehicle_fuels_id'] = $request->fuel_id;
        $info['url_document_fuel'] = $url;
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            
            $fuelDocument = $this->fuelDocumentRepository->create($info);     
            
            // Inserta el registro en la base de datos

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($fuelDocument->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\FuelDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\FuelDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Andres Stiven Pinzon G. - Ago. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateFuelDocumentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateFuelDocumentRequest $request) {

        $input = $request->all();

        /** @var FuelDocument $fuelDocument */
        $fuelDocument = $this->fuelDocumentRepository->find($id);

        if (empty($fuelDocument)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        $url = implode(",", $input["url_document_fuel"]);
        
        $input['url_document_fuel'] = $url;
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $fuelDocument = $this->fuelDocumentRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($fuelDocument->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\FuelDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\FuelDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un FuelDocument del almacenamiento
     *
     * @author Andres Stiven Pinzon G. - Ago. 20 - 2021
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var FuelDocument $fuelDocument */
        $fuelDocument = $this->fuelDocumentRepository->find($id);

        if (empty($fuelDocument)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $fuelDocument->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\FuelDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\FuelDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('fuel_documents').'.'.$fileType;

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
