<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateEquipmentResumeDocumentRequest;
use Modules\HelpTable\Http\Requests\UpdateEquipmentResumeDocumentRequest;
use Modules\HelpTable\Repositories\EquipmentResumeDocumentRepository;
use Modules\HelpTable\Models\EquipmentResume;
use Modules\HelpTable\Models\EquipmentResumeDocument;
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
 * @author Kleverman Salazar Florez. - Ene. 24 - 2023
 * @version 1.0.0
 */
class EquipmentResumeDocumentController extends AppBaseController {

    /** @var  EquipmentResumeDocumentRepository */
    private $equipmentResumeDocumentRepository;

    /**
     * Constructor de la clase
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     */
    public function __construct(EquipmentResumeDocumentRepository $equipmentResumeDocumentRepo) {
        $this->equipmentResumeDocumentRepository = $equipmentResumeDocumentRepo;
    }

    /**
     * Muestra la vista para el CRUD de EquipmentResumeDocument.
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        $equipment_resume = EquipmentResume::select('id')->where('id',$request['equipment_resume_id'])->get()->first();
        return view('help_table::equipment_resume_documents.index')->with('equipment_resume_id',$equipment_resume['id']);
    }

    /**
     * Obtiene todos los elementos existentes
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function all(Request $request) {
        $equipment_resume_documents = EquipmentResumeDocument::where('ht_tic_equipment_resume_id',$request['equipment_resume_id'])->latest()->get();
        return $this->sendResponse($equipment_resume_documents->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param CreateEquipmentResumeDocumentRequest $request
     *
     * @return Response
     */
    public function store(Request $request) {

        $input = $request->all();
        $input['ht_tic_equipment_resume_id'] = $request['equipment_resume_id'];

        if($request->hasFile('url_new')) {
            $input['url'] = substr($input['url_new']->store('public/help_table/equipment_resume_documents'), 7);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $equipmentResumeDocument = $this->equipmentResumeDocumentRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($equipmentResumeDocument->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateEquipmentResumeDocumentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateEquipmentResumeDocumentRequest $request) {

        $input = $request->all();

        /** @var EquipmentResumeDocument $equipmentResumeDocument */
        $equipmentResumeDocument = $this->equipmentResumeDocumentRepository->find($id);

        if (empty($equipmentResumeDocument)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        if($request->hasFile('url_new')) {
            $input['url'] = substr($input['url_new']->store('public/help_table/equipment_resume_documents'), 7);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $equipmentResumeDocument = $this->equipmentResumeDocumentRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($equipmentResumeDocument->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un EquipmentResumeDocument del almacenamiento
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param int $id
     *
     * @throws \Exception
     *
     * @return Response
     */
    public function destroy($id) {

        /** @var EquipmentResumeDocument $equipmentResumeDocument */
        $equipmentResumeDocument = $this->equipmentResumeDocumentRepository->find($id);

        if (empty($equipmentResumeDocument)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $equipmentResumeDocument->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeDocumentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\HelpTable\Http\Controllers\EquipmentResumeDocumentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Organiza la exportacion de datos
     *
     * @author Kleverman Salazar Florez. - Ene. 24 - 2023
     * @version 1.0.0
     *
     * @param Request $request datos recibidos
     */
    public function export(Request $request) {
        $input = $request->all();

        // Tipo de archivo (extencion)
        $fileType = $input['fileType'];
        // Nombre de archivo con tiempo de creacion
        $fileName = time().'-'.trans('equipment_resume_documents').'.'.$fileType;

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
