<?php

namespace Modules\DocumentaryClassification\Http\Controllers;

use App\Exports\GenericExport;
use Modules\DocumentaryClassification\Http\Requests\CreatedocumentarySerieSubseriesRequest;
use Modules\DocumentaryClassification\Http\Requests\UpdatedocumentarySerieSubseriesRequest;
use Modules\DocumentaryClassification\Repositories\documentarySerieSubseriesRepository;
use Modules\DocumentaryClassification\Models\documentarySerieSubseries;
use Modules\DocumentaryClassification\Models\typeDocumentaries;
use Modules\DocumentaryClassification\Models\seriesSubSeries;
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
class documentarySerieSubseriesController extends AppBaseController {

    /** @var  documentarySerieSubseriesRepository */
    private $documentarySerieSubseriesRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(documentarySerieSubseriesRepository $documentarySerieSubseriesRepo) {
        $this->documentarySerieSubseriesRepository = $documentarySerieSubseriesRepo;
    }

    /**
     * Muestra la vista para el CRUD de documentarySerieSubseries.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */

    public function all_type_documentaries(){
        $type_documentaries = documentarySerieSubseries::select('cd_type_documentaries_has_cl_series_subseries.id','cd_type_documentaries.name')
        ->join(' cd_type_documentaries',' cd_type_documentaries.id','=','cd_type_documentaries_has_cl_series_subseries.id_type_documentaries')
        ->get();

        return  $this->sendResponse($type_documentaries->toArray(), trans('data_obtained_successfully'));
    }


    public function index(Request $request) {
        if(Auth::user()->hasRole("Gestión Documental Admin")){
            $consult = seriesSubSeries::select('type','no_serie','name_serie','no_subserie','name_subserie')->where('id', $request['did_series_subseries'])->get();
            $request_data= $consult[0]->toArray();
            if($request_data['no_serie'] === NULL && $request_data['name_serie'] === NULL){
                unset($request_data['no_serie']);
                unset($request_data['name_serie']);
    
            }elseif($request_data['no_subserie'] === NULL && $request_data['name_subserie'] === NULL){
                unset($request_data['no_subserie']);
                unset($request_data['name_subserie']);
            }
            
            return view('documentaryclassification::documentary_serie_subseries.index',compact('request_data'))->with('did_series_subseries',$request['did_series_subseries']);
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
        $documentary_serie_subseries = $this->documentarySerieSubseriesRepository->all();
        return $this->sendResponse($documentary_serie_subseries->toArray(), trans('data_obtained_successfully'));
    }

    public function all_specific(Request $request) {
        $data_request = $request->all();

        foreach($data_request as $item){
            $data = (int) $item;
        }

        $documentary_serie_subseries = documentarySerieSubseries::select('cd_type_documentaries.*','cd_type_documentaries_has_cl_series_subseries.id')
        ->join('cd_type_documentaries', 'cd_type_documentaries_has_cl_series_subseries.id_type_documentaries', '=', 'cd_type_documentaries.id')
        ->where('id_series_subseries',$data)
        // ->where('deleted_at',NULL)
        ->get();


        return $this->sendResponse($documentary_serie_subseries->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatedocumentarySerieSubseriesRequest $request
     *
     * @return Response
     */
    public function store(CreatedocumentarySerieSubseriesRequest $request) {

        $input = $request->all();


        $input['id_series_subseries'] = (int) $input['did_series_subseries'];
        $input['id_type_documentaries'] = (int) $input['id_type_documentaries'];

        unset($input['did_series_subseries']);
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $documentarySerieSubseries = $this->documentarySerieSubseriesRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            $documentarySerieSubseries = typeDocumentaries::select('*')
            ->join('cd_type_documentaries_has_cl_series_subseries','cd_type_documentaries_has_cl_series_subseries.id_type_documentaries', '=', 'cd_type_documentaries.id' )
            ->where('cd_type_documentaries_has_cl_series_subseries.id',$documentarySerieSubseries['id'])->first();

            // $documentarySerieSubseries->idTypeDocumentaries;
            // $documentarySerieSubseries->seriesOsubseries;

            return $this->sendResponse($documentarySerieSubseries, trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\documentarySerieSubseriesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\documentarySerieSubseriesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdatedocumentarySerieSubseriesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatedocumentarySerieSubseriesRequest $request) {

        $input = $request->all();

        /** @var documentarySerieSubseries $documentarySerieSubseries */
        $documentarySerieSubseries = $this->documentarySerieSubseriesRepository->find($id);

        if (empty($documentarySerieSubseries)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $documentarySerieSubseries = $this->documentarySerieSubseriesRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($documentarySerieSubseries->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\documentarySerieSubseriesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\documentarySerieSubseriesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un documentarySerieSubseries del almacenamiento
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

        /** @var documentarySerieSubseries $documentarySerieSubseries */
        $documentarySerieSubseries = $this->documentarySerieSubseriesRepository->find($id);


        if (empty($documentarySerieSubseries)) {
            return $this->sendError(trans('not_found_element'), 200);
        }


        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $documentarySerieSubseries->delete();

            // Efectua los cambios realizados
            DB::commit();

            // $documentarySerieSubseries->idTypeDocumentaries;
            // $documentarySerieSubseries->seriesOsubseries;

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\documentarySerieSubseriesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\documentarySerieSubseriesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('documentary_serie_subseries').'.'.$fileType;

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
