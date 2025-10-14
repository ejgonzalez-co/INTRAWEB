<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreateExternalAnnotationRequest;
use Modules\Correspondence\Http\Requests\UpdateExternalAnnotationRequest;
use Modules\Correspondence\Repositories\ExternalAnnotationRepository;
use Modules\Correspondence\Models\ExternalAnnotation;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Correspondence\Models\External;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ExternalAnnotationController extends AppBaseController {

    /** @var  ExternalAnnotationRepository */
    private $externalAnnotationRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ExternalAnnotationRepository $externalAnnotationRepo) {
        $this->externalAnnotationRepository = $externalAnnotationRepo;
    }

    /**
     * Muestra la vista para el CRUD de ExternalAnnotation.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(!Auth::user()->hasRole(["Ciudadano"])){
            $external = External::where('id',$request->ci)->first();
            $externalId = $request->ci;
            $externalConsecutive = $external["consecutive"];
            return view('correspondence::external_annotations.index',compact(['externalId','externalConsecutive']));
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
    public function all($id) {
        // $external_annotations = $this->externalAnnotationRepository->all();
        // return $this->sendResponse($external_annotations->toArray(), trans('data_obtained_successfully'));

        $documents = ExternalAnnotation::where('correspondence_external_id',$id)->with('users')->latest()->get()->toArray();
        // dd($documents);
        return $this->sendResponse($documents, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateExternalAnnotationRequest $request
     *
     * @return Response
     */
    public function store(CreateExternalAnnotationRequest $request,$id) {

        $input = $request->all();

        // Validacion para quitar las etiquetas provenientes de un archivo docx
        if(strpos($input["content"],"<p") !== false){
            $input["content"] = preg_replace('/<p(.*?)>/i', '<span$1>', $input["content"]);
            $input["content"] = preg_replace('/<\/p>/i', '</span>', $input["content"]);
        }        

        $input["correspondence_external_id"]=$id;
        $input["users_name"]=Auth::user()->fullname;
        $input["users_id"]=Auth::user()->id;
        $input["leido_por"]=Auth::user()->id;

        // Valida si no seleccionó ningún adjunto
        if($input["attached"] ?? false) {
            $input['attached'] = $input["attached"];
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $externalAnnotation = $this->externalAnnotationRepository->create($input);
            $externalAnnotation->users;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($externalAnnotation->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExternalAnnotation', 'Modules\Correspondence\Http\Controllers\ExternalAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExternalAnnotation', 'Modules\Correspondence\Http\Controllers\ExternalAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$e->getMessage() . ' Linea: ' .$e->getLine()); 
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
     * @param UpdateExternalAnnotationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateExternalAnnotationRequest $request) {

        $input = $request->all();

        /** @var ExternalAnnotation $externalAnnotation */
        $externalAnnotation = $this->externalAnnotationRepository->find($id);

        if (empty($externalAnnotation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $externalAnnotation = $this->externalAnnotationRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($externalAnnotation->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExternalAnnotation', 'Modules\Correspondence\Http\Controllers\ExternalAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExternalAnnotation', 'Modules\Correspondence\Http\Controllers\ExternalAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$e->getMessage() . ' Linea: '. $e->getLine() . ' Error al actualizar el registro con ID: ' . ($id ??  'Indefinido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un ExternalAnnotation del almacenamiento
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

        /** @var ExternalAnnotation $externalAnnotation */
        $externalAnnotation = $this->externalAnnotationRepository->find($id);

        if (empty($externalAnnotation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $externalAnnotation->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExternalAnnotation', 'Modules\Correspondence\Http\Controllers\ExternalAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExternalAnnotation', 'Modules\Correspondence\Http\Controllers\ExternalAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido ').' -  Error: '.$e->getMessage() . ' Linea: '.$e->getLine().' - Error al eliminar el registro con ID: ' . ($id ?? 'Indefinido'));
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
        $fileName = time().'-'.trans('external_annotations').'.'.$fileType;

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
