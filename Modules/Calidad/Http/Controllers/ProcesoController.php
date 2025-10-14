<?php

namespace Modules\Calidad\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Calidad\Http\Requests\CreateProcesoRequest;
use Modules\Calidad\Http\Requests\UpdateProcesoRequest;
use Modules\Calidad\Repositories\ProcesoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Calidad\Models\Proceso;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ProcesoController extends AppBaseController {

    /** @var  ProcesoRepository */
    private $procesoRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ProcesoRepository $procesoRepo) {
        $this->procesoRepository = $procesoRepo;
    }

    /**
     * Muestra la vista para el CRUD de Proceso.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        return view('calidad::procesos.index');

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
        $procesos = Proceso::select(DB::raw("*, COALESCE(calidad_proceso_id, id) AS orden_principal"))
            ->with(['tipoProceso', 'proceso', 'tipoSistema', 'dependencia'])
            ->orderBy('orden_principal')
            ->orderByRaw('(calidad_proceso_id IS NOT NULL) ASC')
            ->orderBy('orden')
            ->get();
        return $this->sendResponse($procesos, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateProcesoRequest $request
     *
     * @return Response
     */
    public function store(CreateProcesoRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Usuario en sesión
            $user = Auth::user();
            $input["users_id"] = $user->id;
            $input["usuario_creador"] = $user->name;
            $input["orden"] = Proceso::Max("orden")->pluck("orden")->first() + 1;
            $tipo_responsable = explode("_", $input["id_responsable"]);
            $input["tipo_responsable"] = reset($tipo_responsable);
            // Inserta el registro en la base de datos
            $proceso = $this->procesoRepository->create($input);
            // Carga las relaciones
            $proceso->tipoProceso;
            $proceso->proceso;
            $proceso->tipoSistema;
            $proceso->dependencia;

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($proceso->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\ProcesoController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\ProcesoController - '. Auth::user()->name.' -  Error: '.$e->getMessage().' -  Línea: '.$e->getLine());
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
     * @param UpdateProcesoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateProcesoRequest $request) {

        $input = $request->all();

        /** @var Proceso $proceso */
        $proceso = $this->procesoRepository->find($id);

        if (empty($proceso)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Si no fue seleccionado un proceso padre, se asigna null al campo calidad_proceso_id para remover el padre en caso de haberlo tenido
            if(empty($input["calidad_proceso_id"])) $input["calidad_proceso_id"] = null;
            // Actualiza el registro
            $proceso = $this->procesoRepository->update($input, $id);
            // Carga las relaciones
            $proceso->tipoProceso;
            $proceso->proceso;
            $proceso->tipoSistema;
            $proceso->dependencia;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($proceso->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\ProcesoController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\ProcesoController - '. Auth::user()->name.' -  Error: '.$e->getMessage().' -  Línea: '.$e->getLine());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un Proceso del almacenamiento
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

        /** @var Proceso $proceso */
        $proceso = $this->procesoRepository->find($id);

        if (empty($proceso)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $proceso->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\ProcesoController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('Calidad', 'Modules\Calidad\Http\Controllers\ProcesoController - '. Auth::user()->name.' -  Error: '.$e->getMessage().' -  Línea: '.$e->getLine());
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
        $fileName = time().'-'.trans('procesos').'.'.$fileType;

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

    /**
     * Realiza una consulta SQL para obtener y combinar información de cargos y usuarios
     * @return array $cargos_usuarios - Usuarios y cargos, agrupados por cargos
     */
    public function obtenerResponsables() {
        // Realiza una consulta SQL cruda utilizando DB::select() y DB::raw() para obtener una lista combinada de cargos y usuarios.
        $cargos_usuarios = DB::select(DB::raw("SELECT id, usuario_cargo
            FROM (
                SELECT CONCAT('Cargo_', id) AS id,
                    nombre AS cargo,
                    CONCAT('Cargo ', nombre) AS usuario_cargo,
                    0 AS is_cargo
                FROM cargos

                UNION ALL

                SELECT CONCAT('Usuario_', u.id) AS id,
                    c.nombre AS cargo,
                    CONCAT('     -- Usuario ', u.name) AS usuario_cargo,
                    1 AS is_cargo
                FROM users AS u
                JOIN cargos AS c ON u.id_cargo = c.id
            ) AS combined
            ORDER BY cargo, is_cargo, usuario_cargo"));
        // Retorna una respuesta estandarizada con la lista de cargos y usuarios obtenidos.
        return $this->sendResponse($cargos_usuarios, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene una lista de procesos y subprocesos.
     *
     * @return array La respuesta con la lista de procesos y subprocesos.
     */
    public function obtenerProcesos() {
        // Realiza una consulta SQL cruda utilizando DB::select() y DB::raw() para obtener una lista de procesos y subprocesos.
        $procesos = DB::select(DB::raw("SELECT
                p.id,
                CONCAT(IF(p.calidad_proceso_id, ' -- Subproceso ', 'Proceso '), p.nombre) AS nombre,
                p.calidad_proceso_id,
                COALESCE(p.calidad_proceso_id, p.id) AS orden_principal,
                p.calidad_proceso_id IS NULL AS es_principal
            FROM
                calidad_proceso p
            LEFT JOIN
                calidad_proceso p2 ON p.calidad_proceso_id = p2.id
            ORDER BY
                orden_principal, es_principal DESC"));
        // Retorna una respuesta estandarizada con la lista de procesos y subprocesos obtenidos.
        return $this->sendResponse($procesos, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene una lista de procesos y subprocesos en estado activo.
     *
     * @author Desarrollador Seven - Jul. 19. 2024
     * @version 1.0.0
     *
     * @return array La respuesta con la lista de procesos y subprocesos activos.
     */
    public function obtenerProcesosActivos() {
        // Realiza una consulta SQL cruda utilizando DB::select() y DB::raw() para obtener una lista de procesos y subprocesos.
        $procesos = DB::select(DB::raw("SELECT
                p.id,
                CONCAT(IF(p.calidad_proceso_id, ' -- Subproceso ', 'Proceso '), p.nombre) AS nombre,
                p.calidad_proceso_id,
                COALESCE(p.calidad_proceso_id, p.id) AS orden_principal,
                p.calidad_proceso_id IS NULL AS es_principal
            FROM
                calidad_proceso p
            LEFT JOIN
                calidad_proceso p2 ON p.calidad_proceso_id = p2.id WHERE p.estado = 'Activo'
            ORDER BY
                orden_principal, es_principal DESC"));
        // Retorna una respuesta estandarizada con la lista de procesos y subprocesos obtenidos.
        return $this->sendResponse($procesos, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene una lista de procesos y subprocesos en estado activo, esto para usar el formulario de documentos.
     *
     * @author Desarrollador Seven - Jul. 19. 2024
     * @version 1.0.0
     *
     * @param Int $tipo_sistema - Id del tipo de sistema
     *
     * @return array La respuesta con la lista de procesos y subprocesos activos.
     */
    public function obtenerProcesosActivosDoc($tipo_sistema) {
        // Realiza una consulta SQL cruda utilizando DB::select() y DB::raw() para obtener una lista de procesos y subprocesos.
        $procesos = DB::select(DB::raw("SELECT
                p.id,
                CONCAT(IF(p.calidad_proceso_id, ' -- Subproceso ', 'Proceso '), p.nombre) AS nombre,
                p.calidad_proceso_id,
                COALESCE(p.calidad_proceso_id, p.id) AS orden_principal,
                p.calidad_proceso_id IS NULL AS es_principal,
                p.prefijo,
                p.orden
            FROM
                calidad_proceso p
            LEFT JOIN
                calidad_proceso p2 ON p.calidad_proceso_id = p2.id WHERE p.estado = 'Activo' AND p.calidad_tipo_sistema_id = ".$tipo_sistema."
            ORDER BY
                orden_principal, es_principal DESC"));
        // Retorna una respuesta estandarizada con la lista de procesos y subprocesos obtenidos.
        return $this->sendResponse($procesos, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene una lista de procesos y subprocesos en estado activo.
     *
     * @author Desarrollador Seven - Jul. 19. 2024
     * @version 1.0.0
     *
     * @return array La respuesta con la lista de procesos y subprocesos activos.
     */
    public function obtenerSoloProcesosActivos() {
        // Realiza una consulta SQL cruda utilizando DB::select() y DB::raw() para obtener una lista de procesos y subprocesos.
        $procesos = Proceso::whereNull("calidad_proceso_id")->latest("nombre")->get();
        // Retorna una respuesta estandarizada con la lista de procesos y subprocesos obtenidos.
        return $this->sendResponse($procesos, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene una lista de subprocesos en estado activo.
     *
     * @author Desarrollador Seven - Jul. 23. 2024
     * @version 1.0.0
     *
     * @return array La respuesta con la lista de subprocesos activos según el id del macroproceso.
     */
    public function obtenerSubProcesosActivos($macroproceso_id) {
        // Realiza una consulta SQL cruda utilizando DB::select() y DB::raw() para obtener una lista de subprocesos según el id del macroproceso.
        $procesos = Proceso::where("calidad_proceso_id", $macroproceso_id)->latest("nombre")->get();
        // Retorna una respuesta estandarizada con la lista de subprocesos obtenidos.
        return $this->sendResponse($procesos, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene una lista de procesos y subprocesos en estado activo, esto para usar el formulario de documentos en el listado de distribución.
     *
     * @author Desarrollador Seven - Jul. 19. 2024
     * @version 1.0.0
     *
     * @return array La respuesta con la lista de procesos y subprocesos activos.
     */
    public function obtenerProcesosActivosDis() {
        // Realiza una consulta SQL cruda utilizando DB::select() y DB::raw() para obtener una lista de procesos y subprocesos.
        $procesos = DB::select(DB::raw("SELECT
                p.id,
                CONCAT(IF(p.calidad_proceso_id, ' -- Subproceso ', 'Proceso '), p.nombre) AS nombre,
                p.calidad_proceso_id,
                COALESCE(p.calidad_proceso_id, p.id) AS orden_principal,
                p.calidad_proceso_id IS NULL AS es_principal
            FROM
                calidad_proceso p
            LEFT JOIN
                calidad_proceso p2 ON p.calidad_proceso_id = p2.id WHERE p.estado = 'Activo'
            ORDER BY
                orden_principal, es_principal DESC"));
        // Opción inicial para seleccionar en la distribución del documento
        $estructura =
            (object) [
                'id' => 'Todos',
                'nombre' => 'Todos'
            ];
        // Se agrega al inicio de los procesos la opcuón de Todos
        array_unshift($procesos, $estructura);
        // Retorna una respuesta estandarizada con la lista de procesos y subprocesos obtenidos.
        return $this->sendResponse($procesos, trans('data_obtained_successfully'));
    }
}
