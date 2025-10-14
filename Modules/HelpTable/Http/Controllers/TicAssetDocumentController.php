<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateTicAssetDocumentRequest;
use Modules\HelpTable\Http\Requests\UpdateTicAssetDocumentRequest;
use Modules\HelpTable\Repositories\TicAssetDocumentRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\HelpTable\Models\TicAssetDocument;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class TicAssetDocumentController extends AppBaseController {

    /** @var  TicAssetDocumentRepository */
    private $ticAssetDocumentRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(TicAssetDocumentRepository $ticAssetDocumentRepo) {
        $this->ticAssetDocumentRepository = $ticAssetDocumentRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicAssetDocument.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('help_table::tic_asset_documents.index')->with("asset_id",$request['asset']);
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
        $tic_asset_documents = TicAssetDocument::where("ht_tic_assets_id",$request["asset"])->whereNull("deleted_at")->get();
        return $this->sendResponse($tic_asset_documents->toArray(), trans('data_obtained_successfully'));
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

        $input["ht_tic_assets_documents"] = $input["asset_id"];
        $input["users_id"] = Auth::user()->id;

        if(!empty($input["url_attachments"])){
            $input["url_attachments"] = implode(",",$input["url_attachments"]);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $ticAssetDocument = $this->ticAssetDocumentRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($ticAssetDocument->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicAssetDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicAssetDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param Request $request
     *
     * @return Response
     */
    public function update($id, Request $request) {

        $input = $request->all();

        if(!empty($input["url_attachments"])){
            $input["url_attachments"] = implode(",",$input["url_attachments"]);
        }

        /** @var TicAssetDocument $ticAssetDocument */
        $ticAssetDocument = $this->ticAssetDocumentRepository->find($id);

        if (empty($ticAssetDocument)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $ticAssetDocument = $this->ticAssetDocumentRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($ticAssetDocument->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicAssetDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicAssetDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un TicAssetDocument del almacenamiento
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

        /** @var TicAssetDocument $ticAssetDocument */
        $ticAssetDocument = $this->ticAssetDocumentRepository->find($id);

        if (empty($ticAssetDocument)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            TicAssetDocument::where("id",$id)->update(["deleted_at" => date("Y-m-d H:i:s")]);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicAssetDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicAssetDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('tic_asset_documents').'.'.$fileType;

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
