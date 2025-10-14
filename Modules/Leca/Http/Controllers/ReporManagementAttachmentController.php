<?php

namespace Modules\Leca\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Leca\Http\Requests\CreateReporManagementAttachmentRequest;
use Modules\Leca\Http\Requests\UpdateReporManagementAttachmentRequest;
use Modules\Leca\Repositories\ReporManagementAttachmentRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Leca\Models\ReporManagementAttachment;
use Modules\Leca\Models\HistoryAttachmentReport;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ReporManagementAttachmentController extends AppBaseController {

    /** @var  ReporManagementAttachmentRepository */
    private $reporManagementAttachmentRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ReporManagementAttachmentRepository $reporManagementAttachmentRepo) {
        $this->reporManagementAttachmentRepository = $reporManagementAttachmentRepo;
    }

    /**
     * Muestra la vista para el CRUD de ReporManagementAttachment.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        return view('leca::repor_management_attachments.index')->with('report_id', $request['report_id']);
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
        $repor_management_attachments  = ReporManagementAttachment::with(['reportManagement','historyAttachments'])->where("lc_rm_report_management_id", $request['report_id'])->get();

        return $this->sendResponse($repor_management_attachments->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateReporManagementAttachmentRequest $request
     *
     * @return Response
     */
    public function store(CreateReporManagementAttachmentRequest $request) {

        $user = Auth::user();
        $input = $request->all();
        $input['user_name'] = $user->name;
        $input['users_id'] = $user->id;
        $input['lc_rm_report_management_id'] = $request->report_id;

        // Valida si ingresa un archivo.
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $name = time().$file->getClientOriginalName();
            $file = $name;
            $url = explode(".", $file);
            
                $input['attachment'] = substr($input['attachment']->store('public/leca/report_management/attachment'), 7);   
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $reporManagementAttachment = $this->reporManagementAttachmentRepository->create($input);
            $reporManagementAttachment->reportManagement;

            $history = new HistoryAttachmentReport();
            
            $history->users_id = $user->id;
            $history->user_name = $user->name;
            $history->status = $reporManagementAttachment->status;
            $history->lc_rm_attachment_id = $reporManagementAttachment->id;

    
            $history->save();
            $reporManagementAttachment-> historyAttachments;
           
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($reporManagementAttachment->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReporManagementAttachmentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReporManagementAttachmentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateReporManagementAttachmentRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateReporManagementAttachmentRequest $request) {
        $user = Auth::user();
        $input = $request->all();

         // Valida si ingresa un archivo.
         if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            $name = time().$file->getClientOriginalName();
            $file = $name;
            $url = explode(".", $file);
            
                $input['attachment'] = substr($input['attachment']->store('public/leca/report_management/attachment'), 7);   
        }

        /** @var ReporManagementAttachment $reporManagementAttachment */
        $reporManagementAttachment = $this->reporManagementAttachmentRepository->find($id);

        if (empty($reporManagementAttachment)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $reporManagementAttachment = $this->reporManagementAttachmentRepository->update($input, $id);

            $history = new HistoryAttachmentReport();
            
            $history->created_at = $reporManagementAttachment->updated_at;
            $history->users_id = $user->id;
            $history->user_name = $user->name;
            $history->status = $reporManagementAttachment->status;
            $history->lc_rm_attachment_id = $reporManagementAttachment->id;

           
            
            $history->save();
            $reporManagementAttachment-> historyAttachments;

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($reporManagementAttachment->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReporManagementAttachmentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReporManagementAttachmentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ReporManagementAttachment del almacenamiento
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

        /** @var ReporManagementAttachment $reporManagementAttachment */
        $reporManagementAttachment = $this->reporManagementAttachmentRepository->find($id);

        if (empty($reporManagementAttachment)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $reporManagementAttachment->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReporManagementAttachmentController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\leca\Http\Controllers\ReporManagementAttachmentController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('repor_management_attachments').'.'.$fileType;

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
