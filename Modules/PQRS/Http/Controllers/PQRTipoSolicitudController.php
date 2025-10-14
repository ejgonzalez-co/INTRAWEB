<?php

namespace Modules\PQRS\Http\Controllers;

use App\Exports\GenericExport;
use Modules\PQRS\Http\Requests\CreatePQRTipoSolicitudRequest;
use Modules\PQRS\Http\Requests\UpdatePQRTipoSolicitudRequest;
use Modules\PQRS\Repositories\PQRTipoSolicitudRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\PQRS\Models\PQRTipoSolicitud;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class PQRTipoSolicitudController extends AppBaseController {

    /** @var  PQRTipoSolicitudRepository */
    private $pQRTipoSolicitudRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(PQRTipoSolicitudRepository $pQRTipoSolicitudRepo) {
        $this->pQRTipoSolicitudRepository = $pQRTipoSolicitudRepo;
    }

    /**
     * Muestra la vista para el CRUD de PQRTipoSolicitud.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {
        if(Auth::user()->hasRole(["Administrador de requerimientos","Operadores","Consulta de requerimientos"])){
            return view('pqrs::p_q_r_tipo_solicituds.index');
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
        // Obtiene todos los tipos de solicitudes
        $p_q_r_tipo_solicituds = $this->pQRTipoSolicitudRepository->all();
        return $this->sendResponse($p_q_r_tipo_solicituds->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes en estado activo, esto para el formulario de PQRS
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function allRadicacion() {
        // Obtiene las solicitudes en estado activo, esto aplica para el listado de radicación de PQRS
        $p_q_r_tipo_solicituds = PQRTipoSolicitud::where("estado", "Activo")->get()->toArray();
        return $this->sendResponse($p_q_r_tipo_solicituds, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatePQRTipoSolicitudRequest $request
     *
     * @return Response
     */
    public function store(CreatePQRTipoSolicitudRequest $request) {

        $input = $request->all();
        $input["users_id"] = Auth::user()->id;
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $pQRTipoSolicitud = $this->pQRTipoSolicitudRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($pQRTipoSolicitud->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRTipoSolicitudController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRTipoSolicitudController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
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
     * @param UpdatePQRTipoSolicitudRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePQRTipoSolicitudRequest $request) {

        $input = $request->all();

        /** @var PQRTipoSolicitud $pQRTipoSolicitud */
        $pQRTipoSolicitud = $this->pQRTipoSolicitudRepository->find($id);

        if (empty($pQRTipoSolicitud)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $pQRTipoSolicitud = $this->pQRTipoSolicitudRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($pQRTipoSolicitud->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRTipoSolicitudController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRTipoSolicitudController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '. $e->getLine().' Id: '.($pQRTipoSolicitud['id'] ?? 'Desconocido' ));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un PQRTipoSolicitud del almacenamiento
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

        /** @var PQRTipoSolicitud $pQRTipoSolicitud */
        $pQRTipoSolicitud = $this->pQRTipoSolicitudRepository->find($id);

        if (empty($pQRTipoSolicitud)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $pQRTipoSolicitud->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRTipoSolicitudController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQRTipoSolicitudController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '. $e->getLine().' Id: '.($pQRTipoSolicitud['id'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('p_q_r_tipo_solicituds').'.'.$fileType;

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
