<?php

namespace Modules\NotificacionesIntraweb\Http\Controllers;

use App\Exports\GenericExport;
use Modules\NotificacionesIntraweb\Http\Requests\CreateListadoCorreosCheckeosRequest;
use Modules\NotificacionesIntraweb\Http\Requests\UpdateListadoCorreosCheckeosRequest;
use Modules\NotificacionesIntraweb\Repositories\ListadoCorreosCheckeosRepository;
use App\Http\Controllers\AppBaseController;
use Modules\NotificacionesIntraweb\Models\ListadoCorreosCheckeos;
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
class ListadoCorreosCheckeosController extends AppBaseController {

    /** @var  ListadoCorreosCheckeosRepository */
    private $listadoCorreosCheckeosRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ListadoCorreosCheckeosRepository $listadoCorreosCheckeosRepo) {
        $this->listadoCorreosCheckeosRepository = $listadoCorreosCheckeosRepo;
    }

    /**
     * Muestra la vista para el CRUD de ListadoCorreosCheckeos.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        return view('notificacionesintraweb::listado_correos_checkeos.index');

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
        if (Auth::user()->hasRole('Administrador intranet')) {
            $listado_correos_checkeos = $this->listadoCorreosCheckeosRepository->all();
        }else {
            $listado_correos_checkeos = ListadoCorreosCheckeos::where('user_id', Auth::user()->id)->get();
        }

        return $this->sendResponse($listado_correos_checkeos->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateListadoCorreosCheckeosRequest $request
     *
     * @return Response
     */
    public function store(CreateListadoCorreosCheckeosRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $listadoCorreosCheckeos = $this->listadoCorreosCheckeosRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($listadoCorreosCheckeos->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\ListadoCorreosCheckeosController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\ListadoCorreosCheckeosController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateListadoCorreosCheckeosRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateListadoCorreosCheckeosRequest $request) {

        $input = $request->all();

        /** @var ListadoCorreosCheckeos $listadoCorreosCheckeos */
        $listadoCorreosCheckeos = $this->listadoCorreosCheckeosRepository->find($id);

        if (empty($listadoCorreosCheckeos)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $listadoCorreosCheckeos = $this->listadoCorreosCheckeosRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($listadoCorreosCheckeos->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\ListadoCorreosCheckeosController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\ListadoCorreosCheckeosController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un ListadoCorreosCheckeos del almacenamiento
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

        /** @var ListadoCorreosCheckeos $listadoCorreosCheckeos */
        $listadoCorreosCheckeos = $this->listadoCorreosCheckeosRepository->find($id);

        if (empty($listadoCorreosCheckeos)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $listadoCorreosCheckeos->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\ListadoCorreosCheckeosController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('notificacionesintraweb', 'Modules\NotificacionesIntraweb\Http\Controllers\ListadoCorreosCheckeosController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('listado_correos_checkeos').'.'.$fileType;

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
