<?php

namespace Modules\Calidad\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Calidad\Http\Requests\CreateTipoSistemaRequest;
use Modules\Calidad\Http\Requests\UpdateTipoSistemaRequest;
use Modules\Calidad\Repositories\TipoSistemaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Calidad\Models\TipoSistema;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class TipoSistemaController extends AppBaseController {

    /** @var  TipoSistemaRepository */
    private $tipoSistemaRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(TipoSistemaRepository $tipoSistemaRepo) {
        $this->tipoSistemaRepository = $tipoSistemaRepo;
    }

    /**
     * Muestra la vista para el CRUD de TipoSistema.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        return view('calidad::tipo_sistemas.index');

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
        $tipo_sistemas = $this->tipoSistemaRepository->all();
        return $this->sendResponse($tipo_sistemas->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes en estado activo
     *
     * @author Desarrollador Seven - 2024
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtenerTiposActivos() {
        $tipo_sistemas = TipoSistema::where("estado", "Activo")->get();
        return $this->sendResponse($tipo_sistemas->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateTipoSistemaRequest $request
     *
     * @return Response
     */
    public function store(CreateTipoSistemaRequest $request) {

        $input = $request->all();
        $user = Auth::user();
        $input["users_id"] = $user->id;
        $input["usuario_creador"] = $user->name;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $tipoSistema = $this->tipoSistemaRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($tipoSistema->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\TipoSistemaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\TipoSistemaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateTipoSistemaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateTipoSistemaRequest $request) {

        $input = $request->all();

        /** @var TipoSistema $tipoSistema */
        $tipoSistema = $this->tipoSistemaRepository->find($id);

        if (empty($tipoSistema)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $tipoSistema = $this->tipoSistemaRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($tipoSistema->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\TipoSistemaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\TipoSistemaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un TipoSistema del almacenamiento
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

        /** @var TipoSistema $tipoSistema */
        $tipoSistema = $this->tipoSistemaRepository->find($id);

        if (empty($tipoSistema)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Verifica si el tipo de sistema tiene registros asociados
            $relacionDocumentos = $tipoSistema->documentos()->exists();
            $relacionTipoDocumentos = $tipoSistema->documentoTipoDocumentos()->exists();
            $relacionProcesos = $tipoSistema->procesos()->exists();
            // Verifica si el tipo de sistema a eliminar tiene relación con alguno de los demás componentes (valida si ya esta siendo usado)
            if($relacionTipoDocumentos || $relacionProcesos) {
                // Retorna mensaje al usuario indicándole el porqué no se puede eliminar dicho registro
                return $this->sendSuccess("No se puede eliminar este tipo de sistema porque está en uso", "warning");
            }
            // Elimina el registro
            $tipoSistema->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\TipoSistemaController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('calidad', 'Modules\Calidad\Http\Controllers\TipoSistemaController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('tipo_sistemas').'.'.$fileType;

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
