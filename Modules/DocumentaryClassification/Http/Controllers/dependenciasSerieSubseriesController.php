<?php

namespace Modules\DocumentaryClassification\Http\Controllers;

use App\Exports\GenericExport;
use Modules\DocumentaryClassification\Http\Requests\CreatedependenciasSerieSubseriesRequest;
use Modules\DocumentaryClassification\Http\Requests\UpdatedependenciasSerieSubseriesRequest;
use Modules\DocumentaryClassification\Repositories\dependenciasSerieSubseriesRepository;
use App\Http\Controllers\AppBaseController;
use Modules\DocumentaryClassification\Models\dependencias;
use Modules\DocumentaryClassification\Models\dependenciasSerieSubseries;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
use Modules\DocumentaryClassification\Models\inventoryDocumental;
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
class dependenciasSerieSubseriesController extends AppBaseController {

    /** @var  dependenciasSerieSubseriesRepository */
    private $dependenciasSerieSubseriesRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(dependenciasSerieSubseriesRepository $dependenciasSerieSubseriesRepo) {
        $this->dependenciasSerieSubseriesRepository = $dependenciasSerieSubseriesRepo;
    }

    /**
     * Muestra la vista para el CRUD de dependenciasSerieSubseries.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole("Gestión Documental Admin")){
            $consult = dependencias::select('nombre')->where('id', (int) $request['id_dependencia'])->first();
    
            $request_name = $consult['nombre'];
    
            return view('documentaryclassification::dependencias_serie_subseries.index')->with('name_dependencia',$request_name)->with('id_dependencia',$request['id_dependencia']);
        }
        return view("auth.forbidden");
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
        $dependencias_serie_subseries = $this->dependenciasSerieSubseriesRepository->all();
        return $this->sendResponse($dependencias_serie_subseries->toArray(), trans('data_obtained_successfully'));
    }

    public function dependencias_request_all(Request $request){
        $input = $request['id_dependencia'];
        
        $dependencias_serie_subseries = dependenciasSerieSubseries::select('cd_series_subseries.*','cd_dependecias_has_cd_series_subseries.id')
        ->join('cd_series_subseries', 'cd_series_subseries.id', '=' , 'cd_dependecias_has_cd_series_subseries.id_series_subseries')
        ->where('id_dependencia', (int) $request['id_dependencia'])->latest()->get();

        $request_code_oficine = dependencias::select('codigo_oficina_productora')
        ->where('id', (int) $request['id_dependencia'])->get();


        foreach($dependencias_serie_subseries as $item){
            $item['oficine_code'] = $request_code_oficine[0]['codigo_oficina_productora'];
        }


        return $this->sendResponse($dependencias_serie_subseries->toArray(),trans('data_obtained_successfully'));

    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatedependenciasSerieSubseriesRequest $request
     *
     * @return Response
     */
    public function store(CreatedependenciasSerieSubseriesRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $dependenciasSerieSubseries = $this->dependenciasSerieSubseriesRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($dependenciasSerieSubseries->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasSerieSubseriesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasSerieSubseriesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdatedependenciasSerieSubseriesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatedependenciasSerieSubseriesRequest $request) {

        $input = $request->all();

        /** @var dependenciasSerieSubseries $dependenciasSerieSubseries */
        $dependenciasSerieSubseries = $this->dependenciasSerieSubseriesRepository->find($id);

        if (empty($dependenciasSerieSubseries)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $dependenciasSerieSubseries = $this->dependenciasSerieSubseriesRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($dependenciasSerieSubseries->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasSerieSubseriesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasSerieSubseriesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un dependenciasSerieSubseries del almacenamiento
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

        function validate_delete($identification){
            //consulta para encontrar el id de la serie o subserie
            $consult_serie_subserie = dependenciasSerieSubseries::select('id_dependencia','id_series_subseries')->where('id',(int) $identification)->first();
            //consulta de validacion de que se encuentre registrada la serie o subserie en inventario

            $consult_inventory = inventoryDocumental::where('id_series_subseries', $consult_serie_subserie["id_series_subseries"])
            ->where('id_dependencias', $consult_serie_subserie["id_dependencia"])
            ->count();
            
            return $consult_inventory;
        }

        if(validate_delete($id) > 0){
            return $this->sendSuccess('La Serie o Subserie Documental NO fué borrada porque está asociada a Inventarios documentales.', 'error');
        }

        /** @var dependenciasSerieSubseries $dependenciasSerieSubseries */
        $dependenciasSerieSubseries = $this->dependenciasSerieSubseriesRepository->find($id);

        if (empty($dependenciasSerieSubseries)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $dependenciasSerieSubseries->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasSerieSubseriesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\dependenciasSerieSubseriesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
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
        $fileName = time().'-'.trans('dependencias_serie_subseries').'.'.$fileType;

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
