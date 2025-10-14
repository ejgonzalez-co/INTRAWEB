<?php

namespace Modules\DocumentaryClassification\Http\Controllers;

use App\Exports\GenericExport;
use Modules\DocumentaryClassification\Http\Requests\CreatetypeDocumentariesRequest;
use Modules\DocumentaryClassification\Http\Requests\UpdatetypeDocumentariesRequest;
use Modules\DocumentaryClassification\Repositories\typeDocumentariesRepository;
use Modules\DocumentaryClassification\Models\typeDocumentaries;
use Modules\DocumentaryClassification\Models\documentarySerieSubseries;
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
class typeDocumentariesController extends AppBaseController {

    /** @var  typeDocumentariesRepository */
    private $typeDocumentariesRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(typeDocumentariesRepository $typeDocumentariesRepo) {
        $this->typeDocumentariesRepository = $typeDocumentariesRepo;
    }

    /**
     * Muestra la vista para el CRUD de typeDocumentaries.
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
            return view('documentaryclassification::type_documentaries.index');
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
    public function all() {
        $type_documentaries = $this->typeDocumentariesRepository->all();
        return $this->sendResponse($type_documentaries->toArray(), trans('data_obtained_successfully'));
    }

    public function all_request(request $request){
        // $type_documentaries = $this->typeDocumentariesRepository->all();
        $resultSearch = typeDocumentaries::where('name','like',"%".$request['query']."%")->select('id','name')->latest()->get();
        return $this->sendResponse($resultSearch->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatetypeDocumentariesRequest $request
     *
     * @return Response
     */
    public function store(CreatetypeDocumentariesRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos

            if(isset($input['description'])){
                //entro pero no realiz nada en la validacion
            }else{
                $input['description'] = null;
            }

            $typeDocumentaries = $this->typeDocumentariesRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($typeDocumentaries->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\typeDocumentariesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\typeDocumentariesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdatetypeDocumentariesRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatetypeDocumentariesRequest $request) {

        $input = $request->all();

        /** @var typeDocumentaries $typeDocumentaries */
        $typeDocumentaries = $this->typeDocumentariesRepository->find($id);

        if (empty($typeDocumentaries)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $typeDocumentaries = $this->typeDocumentariesRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($typeDocumentaries->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\typeDocumentariesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\typeDocumentariesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un typeDocumentaries del almacenamiento
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

        /** @var typeDocumentaries $typeDocumentaries */
        $typeDocumentaries = $this->typeDocumentariesRepository->find($id);

        if (empty($typeDocumentaries)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {

            $consult_delete_type = documentarySerieSubseries::join('cd_type_documentaries','cd_type_documentaries.id','=','cd_type_documentaries_has_cl_series_subseries.id_type_documentaries')->where('id_type_documentaries',(int) $id)->count();

            if($consult_delete_type > 0){
                return $this->sendSuccess('EL Tipo documental NO fué borrada porque está asociada a una Serie o Subserie.', 'error');
            }else{
                 // Elimina el registro
                $typeDocumentaries->delete();

                // Efectua los cambios realizados
                DB::commit();

                return $this->sendSuccess(trans('msg_success_drop'));
            }

        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\typeDocumentariesController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\DocumentaryClassification\Http\Controllers\typeDocumentariesController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('type_documentaries').'.'.$fileType;

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
