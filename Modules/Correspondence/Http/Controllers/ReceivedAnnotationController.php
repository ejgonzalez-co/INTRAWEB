<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreateReceivedAnnotationRequest;
use Modules\Correspondence\Http\Requests\UpdateReceivedAnnotationRequest;
use Modules\Correspondence\Repositories\ReceivedAnnotationRepository;
use Modules\Correspondence\Models\ExternalReceived;
use Modules\Correspondence\Models\ReceivedAnnotation;
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
class ReceivedAnnotationController extends AppBaseController {

    /** @var  ReceivedAnnotationRepository */
    private $receivedAnnotationRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ReceivedAnnotationRepository $receivedAnnotationRepo) {
        $this->receivedAnnotationRepository = $receivedAnnotationRepo;
    }

    /**
     * Muestra la vista para el CRUD de ReceivedAnnotation.
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
            $id=decrypt($request["cr"]);
            $received = ExternalReceived::where('id', $id)->first();
            $receivedId = $id;
            $receivedConsecutive = $received["consecutive"];
            return view('correspondence::received_annotations.index',compact(['receivedId','receivedConsecutive']));
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

        $annotations = ReceivedAnnotation::where('external_received_id', $id)->with('users')->latest()->get()->toArray();
        return $this->sendResponse($annotations, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateReceivedAnnotationRequest $request
     *
     * @return Response
     */
    public function store(CreateReceivedAnnotationRequest $request, $id) {

        $input = $request->all();

        // Validacion para quitar las etiquetas provenientes de un archivo docx
        if(strpos($input["annotation"],"<p") !== false){
            $input["annotation"] = preg_replace('/<p(.*?)>/i', '<span$1>', $input["annotation"]);
            $input["annotation"] = preg_replace('/<\/p>/i', '</span>', $input["annotation"]);
        }

         // Formatea separado por coma los enlaces de los anexos de la correspondencia
        // Valida si no seleccionó ningún adjunto
        if($input["attached"] ?? false) {
            $input['attached'] = $input["attached"];
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $input['external_received_id'] = $id;
            $input["users_name"]=Auth::user()->fullname;
            $input["users_id"]=Auth::user()->id;
            $input["leido_por"]=Auth::user()->id;

            // Inserta el registro en la base de datos
            $receivedAnnotation = $this->receivedAnnotationRepository->create($input);

            $receivedAnnotation->users;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($receivedAnnotation->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ReceivedAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ReceivedAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage() . '- ID: ' . ($id ?? 'Desconocido'));
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
     * @param UpdateReceivedAnnotationRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateReceivedAnnotationRequest $request) {

        $input = $request->all();

         // Formatea separado por coma los enlaces de los anexos de la correspondencia
         $input['attached'] = isset($input["attached"]) ? implode(",", $input["attached"]) : null;

        /** @var ReceivedAnnotation $receivedAnnotation */
        $receivedAnnotation = $this->receivedAnnotationRepository->find($id);

        if (empty($receivedAnnotation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $receivedAnnotation = $this->receivedAnnotationRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($receivedAnnotation->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ReceivedAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ReceivedAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine().'  Id: '.($receivedAnnotation['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un ReceivedAnnotation del almacenamiento
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

        /** @var ReceivedAnnotation $receivedAnnotation */
        $receivedAnnotation = $this->receivedAnnotationRepository->find($id);

        if (empty($receivedAnnotation)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $receivedAnnotation->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ReceivedAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\ReceivedAnnotationController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine().'  Id: '.($receivedAnnotation['id'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('received_annotations').'.'.$fileType;

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
