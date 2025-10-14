<?php

namespace Modules\Maintenance\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Maintenance\Http\Requests\CreateOilDocumentRequest;
use Modules\Maintenance\Http\Requests\UpdateOilDocumentRequest;
use Modules\Maintenance\Repositories\OilDocumentRepository;
use App\Http\Controllers\AppBaseController;
use App\Exports\Maintenance\RequestExport;
use Modules\Maintenance\Models\OilDocument;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

/**
 * Descripcion de la clase
 *
 * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
 * @version 1.0.0
 */
class OilDocumentController extends AppBaseController {

    /** @var  OilDocumentRepository */
    private $oilDocumentRepository;

    /**
     * Constructor de la clase
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     */
    public function __construct(OilDocumentRepository $oilDocumentRepo) {
        $this->oilDocumentRepository = $oilDocumentRepo;
    }

    /**
     * Muestra la vista para el CRUD de OilDocument.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('maintenance::oil_documents.index')->with("mant_oils_id", $request['mant_oils_id'] ?? null);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        // $oil_documents = $this->oilDocumentRepository->all();
        $oilDocuments=OilDocument::with(['Oils'])->where('mant_oils_id','=',$request['mant_oils_id'])->latest()->get();
        return $this->sendResponse($oilDocuments->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param CreateOilDocumentRequest $request
     *
     * @return Response
     */
    public function store(CreateOilDocumentRequest $request) {

        $input = $request->all();
        $url = implode(",", $input["url_attachment"]);
        $input['url_attachment'] = $url;

        // Inicia la transaccion
        DB::beginTransaction();
        // try {
            // Inserta el registro en la base de datos
            $oilDocument = $this->oilDocumentRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($oilDocument->toArray(), trans('msg_success_save'));
        // } catch (\Illuminate\Database\QueryException $error) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
        //     // Retorna mensaje de error de base de datos
        //     return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        // } catch (\Exception $e) {
        //     // Devuelve los cambios realizados
        //     DB::rollback();
        //     // Inserta el error en el registro de log
        //     $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
        //     // Retorna error de tipo logico
        //     return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        // }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateOilDocumentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateOilDocumentRequest $request) {

        $input = $request->all();

        /** @var OilDocument $oilDocument */
        $oilDocument = $this->oilDocumentRepository->find($id);

        if (empty($oilDocument)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        $url = implode(",", $input["url_attachment"]);
        $input['url_attachment'] = $url;
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $oilDocument = $this->oilDocumentRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($oilDocument->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un OilDocument del almacenamiento
     *
     * @author Jhoan Sebastian Chilito S. - Abr. 06 - 2020
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var OilDocument $oilDocument */
        $oilDocument = $this->oilDocumentRepository->find($id);

        if (empty($oilDocument)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $oilDocument->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Maintenance\Http\Controllers\OilDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

   /**
     * Genera el reporte de gestion de combustibles en hoja de calculo
     *
     * @author Andres Stiven Pinzon G. - Ago. 18 - 2021
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();
        // dd($input['data']);
        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];

        $fileName = date('Y-m-d H:i:s').'-'.trans('vehicle_fuel').'.'.$fileType;
        
        return Excel::download(new RequestExport('maintenance::oil_documents.report_excel', $input['data'],'b'), $fileName);
    }
}
