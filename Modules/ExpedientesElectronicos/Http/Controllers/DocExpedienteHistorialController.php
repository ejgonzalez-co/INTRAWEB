<?php

namespace Modules\ExpedientesElectronicos\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ExpedientesElectronicos\Http\Requests\CreateDocExpedienteHistorialRequest;
use Modules\ExpedientesElectronicos\Http\Requests\UpdateDocExpedienteHistorialRequest;
use Modules\ExpedientesElectronicos\Repositories\DocExpedienteHistorialRepository;
use App\Http\Controllers\AppBaseController;
use Modules\ExpedientesElectronicos\Models\DocExpedienteHistorial;
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
class DocExpedienteHistorialController extends AppBaseController {

    /** @var  DocExpedienteHistorialRepository */
    private $docExpedienteHistorialRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(DocExpedienteHistorialRepository $docExpedienteHistorialRepo) {
        $this->docExpedienteHistorialRepository = $docExpedienteHistorialRepo;
    }

    /**
     * Muestra la vista para el CRUD de DocExpedienteHistorial.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        // Valida si tiene permisos de administrador
        // if(Auth::user()->hasRole('Operador Expedientes Electrónicos') || Auth::user()->hasRole('Consulta Expedientes Electrónicos')) {
            return view('expedienteselectronicos::doc_expediente_historials.index')->with('c', $request['c']);
        // }

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
    public function all(Request $request) {
        $doc_expediente_historials = DocExpedienteHistorial::where('ee_expediente_id', base64_decode($request['c']))->latest()->get();
        return $this->sendResponse($doc_expediente_historials->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateDocExpedienteHistorialRequest $request
     *
     * @return Response
     */
    public function store(CreateDocExpedienteHistorialRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $docExpedienteHistorial = $this->docExpedienteHistorialRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($docExpedienteHistorial->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocExpedienteHistorialController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
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
     * @param UpdateDocExpedienteHistorialRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateDocExpedienteHistorialRequest $request) {

        $input = $request->all();

        /** @var DocExpedienteHistorial $docExpedienteHistorial */
        $docExpedienteHistorial = $this->docExpedienteHistorialRepository->find($id);

        if (empty($docExpedienteHistorial)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $docExpedienteHistorial = $this->docExpedienteHistorialRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($docExpedienteHistorial->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocExpedienteHistorialController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un DocExpedienteHistorial del almacenamiento
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

        /** @var DocExpedienteHistorial $docExpedienteHistorial */
        $docExpedienteHistorial = $this->docExpedienteHistorialRepository->find($id);

        if (empty($docExpedienteHistorial)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $docExpedienteHistorial->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\DocExpedienteHistorialController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', $e->getFile() . ' - ' . Auth::user()->name . ' -  Error: ' . $e->getMessage() . '. Linea: ' . $e->getLine());
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
        $fileName = time().'-'.trans('doc_expediente_historials').'.'.$fileType;

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
