<?php

namespace Modules\SuppliesAdequacies\Http\Controllers;

use App\Exports\GenericExport;
use Modules\SuppliesAdequacies\Http\Requests\CreateKnowledgeBaseRequest;
use Modules\SuppliesAdequacies\Http\Requests\UpdateKnowledgeBaseRequest;
use Modules\SuppliesAdequacies\Repositories\KnowledgeBaseRepository;
use Modules\SuppliesAdequacies\Models\KnowledgeBase;
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
class KnowledgeBaseController extends AppBaseController {

    /** @var  KnowledgeBaseRepository */
    private $knowledgeBaseRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(KnowledgeBaseRepository $knowledgeBaseRepo) {
        $this->knowledgeBaseRepository = $knowledgeBaseRepo;
    }

    /**
     * Muestra la vista para el CRUD de KnowledgeBase.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if (Auth::user()->hasRole(["Administrador requerimiento gestión recursos","Operador Infraestuctura","Operador Suministros de consumo","Operador Suministros devolutivo / Asignación"])) {
            return view('suppliesadequacies::knowledge_bases.index');
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
        $knowledge_bases = KnowledgeBase::with("userCreator")->latest()->get();
        return $this->sendResponse($knowledge_bases->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function store(Request $request) {
        $input = $request->all();
        $input["user_creator"] = Auth::user()->id;

        // Valida si se ingresa los archivos
        if (array_key_exists('url_attacheds', $input)) {
            $input['url_attacheds'] = implode(",", $input["url_attacheds"]);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $knowledgeBase = $this->knowledgeBaseRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            // Relaciones
            $knowledgeBase->userCreator;

            return $this->sendResponse($knowledgeBase->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\SuppliesAdequacies\Http\Controllers\KnowledgeBaseController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\SuppliesAdequacies\Http\Controllers\KnowledgeBaseController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $input = $request->all();

        // Valida si se ingresa los archivos
        if (array_key_exists('url_attacheds', $input)) {
            $input['url_attacheds'] = implode(",", $input["url_attacheds"]);
        }

        if(!empty($input["knowledge_subject"])){
            $input["subject"] = $input["knowledge_subject"];
        }

        /** @var KnowledgeBase $knowledgeBase */
        $knowledgeBase = $this->knowledgeBaseRepository->find($id);

        if (empty($knowledgeBase)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $knowledgeBase = $this->knowledgeBaseRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            // Relaciones
            $knowledgeBase->userCreator;
        
            return $this->sendResponse($knowledgeBase->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\SuppliesAdequacies\Http\Controllers\KnowledgeBaseController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\SuppliesAdequacies\Http\Controllers\KnowledgeBaseController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un KnowledgeBase del almacenamiento
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

        /** @var KnowledgeBase $knowledgeBase */
        $knowledgeBase = $this->knowledgeBaseRepository->find($id);

        if (empty($knowledgeBase)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $knowledgeBase->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\SuppliesAdequacies\Http\Controllers\KnowledgeBaseController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\SuppliesAdequacies\Http\Controllers\KnowledgeBaseController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('knowledge_bases').'.'.$fileType;

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
