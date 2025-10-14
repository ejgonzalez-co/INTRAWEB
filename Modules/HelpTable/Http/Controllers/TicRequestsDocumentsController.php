<?php

namespace Modules\HelpTable\Http\Controllers;

use App\Exports\GenericExport;
use Modules\HelpTable\Http\Requests\CreateTicRequestsDocumentsRequest;
use Modules\HelpTable\Http\Requests\UpdateTicRequestsDocumentsRequest;
use Modules\HelpTable\Repositories\TicRequestsDocumentsRepository;
use App\Http\Controllers\AppBaseController;
use Modules\HelpTable\Models\TicRequestsDocuments;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Illuminate\Support\Facades\Storage;
use Modules\HelpTable\Models\TicRequest;


/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class TicRequestsDocumentsController extends AppBaseController {

    /** @var  TicRequestsDocumentsRepository */
    private $ticRequestsDocumentsRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(TicRequestsDocumentsRepository $ticRequestsDocumentsRepo) {
        $this->ticRequestsDocumentsRepository = $ticRequestsDocumentsRepo;
    }

    /**
     * Muestra la vista para el CRUD de TicRequestsDocuments.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $input = $request->toArray();
        $data = urldecode($input['requests']); // Decode from URL
        $data = base64_decode($data); // Decode from base64
        $idDecrypter =  openssl_decrypt($data, 'aes-256-cbc', 'jdjc110274', 0, '1234567890123456');

        $tic_requests_status = TicRequest:: select(['request_status'])->where('id',  $idDecrypter)->first()->request_status;

        // Usar un operador ternario para asignar el estado
        $estado = (strpos($tic_requests_status, 'Cerrada') !== false) ? 'Cerrada' : 'Abierta';

        
        return view('help_table::tic_requests_documents.index', compact(['estado', 'idDecrypter']))->with("id",$input['requests']);
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

        $input = $request->toArray();

        $data = urldecode($input['request']); // Decode from URL
        $data = base64_decode($data); // Decode from base64
        $id =  openssl_decrypt($data, 'aes-256-cbc', 'jdjc110274', 0, '1234567890123456');

        $tic_requests_documents = TicRequestsDocuments::where('ht_tic_requests_id',$id)->get();
        return $this->sendResponse($tic_requests_documents->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateTicRequestsDocumentsRequest $request
     *
     * @return Response
     */
    public function store(CreateTicRequestsDocumentsRequest $request) {

        $input = $request->all();

        $data = urldecode($input['request_id']); // Decode from URL
        $data = base64_decode($data); // Decode from base64
        $request_id =  openssl_decrypt($data, 'aes-256-cbc', 'jdjc110274', 0, '1234567890123456');

        $input['ht_tic_requests_id'] = $request_id;

        if ($request->hasFile('url')) {

            $attached = $input['url'];
            $file = $request->file('url');
            //Se genera un nombre unico
            $name = time() . $file->getClientOriginalName();
            //Especifica la ruta donde se almacenara el documento
            $attached = substr($input['url']->store('public/request-document'), 7);

            $input['url'] = $attached;

            // Inicia la transaccion
            DB::beginTransaction();
            try {
                // Inserta el registro en la base de datos
                $ticRequestsDocuments = $this->ticRequestsDocumentsRepository->create($input);

                // Efectua los cambios realizados
                DB::commit();

                return $this->sendResponse($ticRequestsDocuments->toArray(), trans('msg_success_save'));
            } catch (\Illuminate\Database\QueryException $error) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestsDocumentsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
                // Retorna mensaje de error de base de datos
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
            } catch (\Exception $e) {
                // Devuelve los cambios realizados
                DB::rollback();
                // Inserta el error en el registro de log
                $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestsDocumentsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
                // Retorna error de tipo logico
                return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
            }
        }else{
            return $this->sendSuccess('Por favor adjunte un documento', 'error');

        }


    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateTicRequestsDocumentsRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTicRequestsDocumentsRequest $request) {

        $input = $request->all();

        if ($request->hasFile('url')) {
            $file = $request->file('url');

            //Consulta todos los documentos que tenga el id de la solicitud creada
            $documents_find = TicRequestsDocuments::where('id', $input['id'])->get()->first()->toArray();

                //Valida si ya existe un archivo con esa url
                if (Storage::disk('public')->exists($documents_find['url'])) {
                    //Si el archivo existe, lo elimina
                    Storage::disk('public')->delete($documents_find['url']);
                }

            $name = time() . $file->getClientOriginalName();
            //Especifica la ruta donde se almacenara el documento
            $attached = substr($input['url']->store('public/request-document'), 7);
        

        $input['url'] = $attached;
        }

        /** @var TicRequestsDocuments $ticRequestsDocuments */
        $ticRequestsDocuments = $this->ticRequestsDocumentsRepository->find($id);

        if (empty($ticRequestsDocuments)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $ticRequestsDocuments = $this->ticRequestsDocumentsRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($ticRequestsDocuments->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestsDocumentsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestsDocumentsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un TicRequestsDocuments del almacenamiento
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

        /** @var TicRequestsDocuments $ticRequestsDocuments */
        $ticRequestsDocuments = $this->ticRequestsDocumentsRepository->find($id);

        if (empty($ticRequestsDocuments)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $ticRequestsDocuments->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestsDocumentsController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('help_table', 'Modules\HelpTable\Http\Controllers\TicRequestsDocumentsController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('tic_requests_documents').'.'.$fileType;

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
