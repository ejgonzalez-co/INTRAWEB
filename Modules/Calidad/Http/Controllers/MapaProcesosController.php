<?php

namespace Modules\Calidad\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Calidad\Http\Requests\CreateMapaProcesosRequest;
use Modules\Calidad\Http\Requests\UpdateMapaProcesosRequest;
use Modules\Calidad\Repositories\MapaProcesosRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Calidad\Models\MapaProcesos;
use Modules\Calidad\Models\MapaProcesosLinks;
use Modules\Calidad\Models\TipoProceso;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class MapaProcesosController extends AppBaseController {

    /** @var  MapaProcesosRepository */
    private $mapaProcesosRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(MapaProcesosRepository $mapaProcesosRepo) {
        $this->mapaProcesosRepository = $mapaProcesosRepo;
    }

    /**
     * Muestra la vista para el CRUD de MapaProcesos.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        return view('calidad::mapa_procesos.index');

    }

    /**
     * Muestra la vista para el CRUD de MapaProcesos.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index_publico(Request $request) {

        return view('calidad::mapa_procesos.index_mapa_procesos_publico');

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
        $mapa_procesos = MapaProcesos::with(['mapaProcesosLinks'])->latest()->get();
        $procesos = TipoProceso::with(["procesosMapa"])->get()->toArray();
        // Realiza una consulta SQL cruda utilizando DB::select() y DB::raw() para obtener una lista de procesos y subprocesos formateando el nombre.
        $procesos = DB::select(DB::raw("SELECT
                p.id,
                CONCAT(IF(p.calidad_proceso_id, ' -- Subproceso ', 'Proceso '), p.nombre) AS nombre,
                CONCAT('".config('app.url')."', '/calidad/documentos-calidad/', p.nombre) AS enlace,
                p.calidad_proceso_id,
                COALESCE(p.calidad_proceso_id, p.id) AS orden_principal,
                p.calidad_proceso_id IS NULL AS es_principal
            FROM
                calidad_proceso p
            LEFT JOIN
                calidad_proceso p2 ON p.calidad_proceso_id = p2.id
            ORDER BY
                orden_principal, es_principal DESC"));
        $mapa_procesos[0]["procesos"] = $procesos;
        return $this->sendResponse($mapa_procesos->toArray(), trans('data_obtained_successfully'));
    }

    public function all_publico() {
        $mapa_procesos_links = MapaProcesos::with('mapaProcesosLinks')->latest()->get()->toArray();
        $procesos = TipoProceso::with(["procesosMapa"])->get()->toArray();
        $mapa_procesos["mapa_procesos_links"] = $mapa_procesos_links;
        $mapa_procesos["macroprocesos_mapa"] = $procesos;
        return $this->sendResponseAvanzado([], trans('data_obtained_successfully'), null, $mapa_procesos);
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateMapaProcesosRequest $request
     *
     * @return Response
     */
    public function store(CreateMapaProcesosRequest $request) {

        $input = $request->all();

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $usuario = Auth::user();
            $input["users_id"] = $usuario->id;
            $input["nombre_usuario"] = $usuario->fullname;
            $input["vigencia"] = date("Y");
            // Valida si se adjunto un documento
            if ($request->hasFile('adjunto')) {
                $input['adjunto'] = substr($input['adjunto']->store('public/container/calidad_documentos_' . date("Y")), 7);
            }
            // Inserta el registro en la base de datos
            $mapaProcesos = $this->mapaProcesosRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($mapaProcesos->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Calidad\Http\Controllers\MapaProcesosController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Calidad\Http\Controllers\MapaProcesosController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
     * @param UpdateMapaProcesosRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateMapaProcesosRequest $request) {

        $input = $request->all();

        /** @var MapaProcesos $mapaProcesos */
        $mapaProcesos = $this->mapaProcesosRepository->find($id);

        if (empty($mapaProcesos)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Valida si se adjunto un documento
            if ($request->hasFile('adjunto')) {
                $input['adjunto'] = substr($input['adjunto']->store('public/container/calidad_documentos_' . date("Y")), 7);
            }
            // Actualiza el registro
            $mapaProcesos = $this->mapaProcesosRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($mapaProcesos->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Calidad\Http\Controllers\MapaProcesosController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Calidad\Http\Controllers\MapaProcesosController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un MapaProcesos del almacenamiento
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

        /** @var MapaProcesos $mapaProcesos */
        $mapaProcesos = $this->mapaProcesosRepository->find($id);

        if (empty($mapaProcesos)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            MapaProcesosLinks::where("calidad_mapa_procesos_id", $id)->delete();
            // Elimina el registro
            $mapaProcesos->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Calidad\Http\Controllers\MapaProcesosController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Calidad\Http\Controllers\MapaProcesosController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('mapa_procesos').'.'.$fileType;

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

    public function guardarLinksMapaProcesos(Request $request, $imageId) {
        /** @var MapaProcesos $mapaProcesos */
        $mapaProcesos = $this->mapaProcesosRepository->find($imageId);

        if (empty($mapaProcesos)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            $links = $request->all();
            // dd($links);
            // Valida si tiene links registrados
            if (!empty($links)) {
                $usuario = Auth::user();
                $id_usuario = $usuario->id;
                $nombre_usuario = $usuario->name;
                // Borra todos los registros de links para volver a insertarlos
                MapaProcesosLinks::where('calidad_mapa_procesos_id', $imageId)->delete();
                // Recorre los links
                foreach ($links as $link) {
                    // Array de links
                    $registroLink = [];
                    $registroLink["desplazamiento_x"] = $link["desplazamiento_x"];
                    $registroLink["desplazamiento_y"] = $link["desplazamiento_y"];
                    $registroLink["porcentaje_x"] = $link["porcentaje_x"];
                    $registroLink["porcentaje_y"] = $link["porcentaje_y"];
                    $registroLink["porcentaje_w"] = $link["porcentaje_w"];
                    $registroLink["porcentaje_h"] = $link["porcentaje_h"];
                    $registroLink["ancho"] = $link["ancho"];
                    $registroLink["alto"] = $link["alto"];
                    $registroLink["link_id"] = $link["link_id"];
                    $registroLink["url"] = $link["url"];
                    $registroLink["nombre_usuario"] = $nombre_usuario;
                    $registroLink["users_id"] = $id_usuario;
                    $registroLink["calidad_mapa_procesos_id"] = $imageId;
                    // Inserta los valores de los links relacionado al metadato y al documento
                    MapaProcesosLinks::create($registroLink);
                }
            } else {
                // Elimina todos los registros de links relacionados al documento
                MapaProcesosLinks::where('calidad_mapa_procesos_id', $imageId)->delete();
            }
            // Carga la relación de los links
            $mapaProcesos->mapaProcesosLinks;
            DB::commit();

            return $this->sendResponse($mapaProcesos->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Calidad\Http\Controllers\MapaProcesosController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Calidad\Http\Controllers\MapaProcesosController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }
}
