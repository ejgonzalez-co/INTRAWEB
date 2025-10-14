<?php

namespace Modules\ExpedientesElectronicos\Http\Controllers;

use App\Exports\GenericExport;
use Modules\ExpedientesElectronicos\Http\Requests\CreateTiposDocumentalRequest;
use Modules\ExpedientesElectronicos\Http\Requests\UpdateTiposDocumentalRequest;
use Modules\ExpedientesElectronicos\Repositories\TiposDocumentalRepository;
use App\Http\Controllers\AppBaseController;
use Modules\ExpedientesElectronicos\Models\TiposDocumental;
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
class TiposDocumentalController extends AppBaseController {

    /** @var  TiposDocumentalRepository */
    private $tiposDocumentalRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(TiposDocumentalRepository $tiposDocumentalRepo) {
        $this->tiposDocumentalRepository = $tiposDocumentalRepo;
    }

    /**
     * Muestra la vista para el CRUD de TiposDocumental.
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
        if(Auth::user()->hasRole('Operador Expedientes Electrónicos')) {
            return view('expedienteselectronicos::tipos_documentals.index')->with('c', $request['c']);
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
        $tipos_documentals = TiposDocumental::latest()->get();
        return $this->sendResponse($tipos_documentals->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateTiposDocumentalRequest $request
     *
     * @return Response
     */
    public function store(CreateTiposDocumentalRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // ID del usuario que crea el registro
            $input["users_id"] = Auth::user()->id;
            // nombre del usuario que crea el registro
            $input["user_name"] = Auth::user()->fullname;
            // Inserta el registro en la base de datos
            $tiposDocumental = $this->tiposDocumentalRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($tiposDocumental->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\TiposDocumentalController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
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
     * @param UpdateTiposDocumentalRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTiposDocumentalRequest $request) {

        $input = $request->all();

        /** @var TiposDocumental $tiposDocumental */
        $tiposDocumental = $this->tiposDocumentalRepository->find($id);

        if (empty($tiposDocumental)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $tiposDocumental = $this->tiposDocumentalRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($tiposDocumental->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\TiposDocumentalController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
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
     * Elimina un TiposDocumental del almacenamiento
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

        /** @var TiposDocumental $tiposDocumental */
        $tiposDocumental = $this->tiposDocumentalRepository->find($id);

        if (empty($tiposDocumental)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $tiposDocumental->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('ExpedientesElectronicos', 'Modules\ExpedientesElectronicos\Http\Controllers\TiposDocumentalController - ' . Auth::user()->name . ' -  Error: ' . $error->getMessage());
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
        $fileName = time().'-'.trans('tipos_documentals').'.'.$fileType;

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
