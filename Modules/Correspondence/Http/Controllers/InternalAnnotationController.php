<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreateInternalAnnotationRequest;
use Modules\Correspondence\Http\Requests\UpdateInternalAnnotationRequest;
use Modules\Correspondence\Repositories\InternalAnnotationRepository;
use Modules\Correspondence\Models\InternalAnnotation;

use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Correspondence\Models\Internal;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class InternalAnnotationController extends AppBaseController {

    /** @var  InternalAnnotationRepository */
    private $internalAnnotationRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(InternalAnnotationRepository $internalAnnotationRepo) {
        $this->internalAnnotationRepository = $internalAnnotationRepo;
    }

    /**
     * Muestra la vista para el CRUD de InternalAnnotation.
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
            $internal = Internal::where('id',$request->ci)->first();
            $internalId = $request->ci;
            $internalConsecutive = $internal["consecutive"];
            return view('correspondence::internal_annotations.index',compact(['internalId','internalConsecutive']));
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
        // $internal_annotations = $this->internalAnnotationRepository->all();
        $anotaciones = InternalAnnotation::where('correspondence_internal_id',$id)->with('users')->latest()->get()->toArray();
        return $this->sendResponse($anotaciones, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateInternalAnnotationRequest $request
     *
     * @return Response
     */
    public function store(CreateInternalAnnotationRequest $request,$id) {

        $input = $request->all();

        // Validacion para quitar las etiquetas provenientes de un archivo docx
        if(strpos($input["content"],"<p") !== false){
            $input["content"] = preg_replace('/<p(.*?)>/i', '<span$1>', $input["content"]);
            $input["content"] = preg_replace('/<\/p>/i', '</span>', $input["content"]);
        } 

        // dd($input);
        $input["correspondence_internal_id"]=$id;
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
            $internalAnnotation = $this->internalAnnotationRepository->create($input);
            $internalAnnotation->users;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($internalAnnotation->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalAnnotationController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalAnnotationController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . ' Linea: ' . $e->getLine());
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
     * @param UpdateInternalAnnotationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateInternalAnnotationRequest $request) {

        $input = $request->all();

        /** @var InternalAnnotation $internalAnnotation */
        $internalAnnotation = $this->internalAnnotationRepository->find($id);

        if (empty($internalAnnotation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $internalAnnotation = $this->internalAnnotationRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($internalAnnotation->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalAnnotationController - '. Auth::user()->fullname.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalAnnotationController - '. Auth::user()->fullname.' -  Error: '.$e->getMessage().' Linea: '.$e->getLine().' Id: '. ($internalAnnotation['id']) ?? 'Desconocido');
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un InternalAnnotation del almacenamiento
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

        /** @var InternalAnnotation $internalAnnotation */
        $internalAnnotation = $this->internalAnnotationRepository->find($id);

        if (empty($internalAnnotation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $internalAnnotation->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalAnnotationController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\InternalAnnotationController - '. (Auth::user()->fullname ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage().' Linea: '.$e->getLine().' Id: '. ($internalAnnotation['id'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('internal_annotations').'.'.$fileType;

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
