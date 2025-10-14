<?php

namespace Modules\PQRS\Http\Controllers;

use App\Exports\GenericExport;
use Modules\PQRS\Http\Requests\CreatePQREjeTematicoRequest;
use Modules\PQRS\Http\Requests\UpdatePQREjeTematicoRequest;
use Modules\PQRS\Repositories\PQREjeTematicoRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;

use Modules\PQRS\Models\PQREjeTematico;
use Modules\PQRS\Models\PQREjeTematicoDependencias;
use Modules\Intranet\Models\Dependency;
use Modules\PQRS\Models\PQREjeTematicoHistorial;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class PQREjeTematicoController extends AppBaseController {

    /** @var  PQREjeTematicoRepository */
    private $pQREjeTematicoRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(PQREjeTematicoRepository $pQREjeTematicoRepo) {
        $this->pQREjeTematicoRepository = $pQREjeTematicoRepo;
    }

    /**
     * Muestra la vista para el CRUD de PQREjeTematico.
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
            return view('pqrs::p_q_r_eje_tematicos.index');
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
        // Obtiene todos los ejes temáticos con sus relaciones de dependencias e historial
        $p_q_r_eje_tematicos = PQREjeTematico::with(["ejetematicoHasDependencias", "pqrEjeTematicoHistorial"])->latest()->get()->toArray();
        return $this->sendResponse($p_q_r_eje_tematicos, trans('data_obtained_successfully'));
    }

    /**
     * Obtiene todos los elementos existentes en estado activo, esto para el formulario de PQRS
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @return Response
     */
    public function allRadicacion(Request $request) {
        $query = $request->input('query');
        // Obtiene los ejes temáticos en estado activo, esto aplica para el listado de radicación de PQRS
        $p_q_r_eje_tematicos = PQREjeTematico::where('nombre','like','%'.$query.'%')->where("estado", "Activo")->get()->toArray();
        return $this->sendResponse($p_q_r_eje_tematicos, trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatePQREjeTematicoRequest $request
     *
     * @return Response
     */
    public function store(CreatePQREjeTematicoRequest $request) {

        $input = $request->all();
        // Asigna el ID del usuario en sesión a la variable users_id
        $input["users_id"] = Auth::user()->id;
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $pQREjeTematico = $this->pQREjeTematicoRepository->create($input);
            // Condición para validar si existe algún registro de dependencias
            if (!empty($input['ejetematico_has_dependencias'])) {
                // Ciclo para recorrer todos los registros de dependencias
                foreach($input['ejetematico_has_dependencias'] as $option){

                    $dependencia = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    PQREjeTematicoDependencias::create([
                        'dependencias_id' => $dependencia->dependencias_id,
                        'pqr_eje_tematico_id' => $pQREjeTematico->id 
                        ]);
                }
            }
            // Asigna a la foranea en el historial del eje temático, el id del registro principal del eje temático
            $input["pqr_eje_tematico_id"] = $pQREjeTematico->id;
            // Crea historial del eje temático
            PQREjeTematicoHistorial::create($input);
            // Invoca las relaciones del eje temático
            $pQREjeTematico->ejetematicoHasDependencias;
            $pQREjeTematico->pqrEjeTematicoHistorial;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($pQREjeTematico->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQREjeTematicoController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQREjeTematicoController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
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
     * @param UpdatePQREjeTematicoRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePQREjeTematicoRequest $request) {

        $input = $request->all();

        /** @var PQREjeTematico $pQREjeTematico */
        $pQREjeTematico = $this->pQREjeTematicoRepository->find($id);

        if (empty($pQREjeTematico)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $pQREjeTematico = $this->pQREjeTematicoRepository->update($input, $id);
            // Condición para validar si existe algún registro de dependencias
            if (!empty($input['ejetematico_has_dependencias'])) {
                // Eliminar los registros de las dependencias según el id del registro principal del eje temático
                PQREjeTematicoDependencias::where('pqr_eje_tematico_id', $pQREjeTematico->id)->delete();
                // Ciclo para recorrer todos los registros de dependencias
                foreach($input['ejetematico_has_dependencias'] as $option){

                    $dependencia = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    PQREjeTematicoDependencias::create([
                        'dependencias_id' => $dependencia->dependencias_id,
                        'pqr_eje_tematico_id' => $pQREjeTematico->id
                        ]);
                }
            }
            // Asigna a la foranea en el historial del eje temático, el id del registro principal del eje temático
            $input["pqr_eje_tematico_id"] = $pQREjeTematico->id;
            // Crea historial del eje temático
            PQREjeTematicoHistorial::create($input);
            // Invoca las relaciones del eje temático
            $pQREjeTematico->ejetematicoHasDependencias;
            $pQREjeTematico->pqrEjeTematicoHistorial;
            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($pQREjeTematico->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQREjeTematicoController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQREjeTematicoController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '. $e->getLine().' Codigo: '.($pQREjeTematico['codigo'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un PQREjeTematico del almacenamiento
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
        // Se eliminan primero todas las dependencias asociadas a este eje temático
        PQREjeTematicoDependencias::where("pqr_eje_tematico_id", $id)->delete();
        /** @var PQREjeTematico $pQREjeTematico */
        $pQREjeTematico = $this->pQREjeTematicoRepository->find($id);

        if (empty($pQREjeTematico)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $pQREjeTematico->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQREjeTematicoController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\PQRS\Http\Controllers\PQREjeTematicoController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '. $e->getLine().' Codigo: '.($pQREjeTematico['codigo'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('p_q_r_eje_tematicos').'.'.$fileType;

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
     * Obtiene todos los elementos existentes
     *
     * @author Seven Soluciones Informáticas S.A.S - May. 03 - 2023
     * @version 1.0.0
     *
     * @return Response
     */
    public function obtener_dependencias() {
        // Obtiene las dependencias con sus sedes
        $dependencies = Dependency::with(['headquarters'])->latest()->get();
        return $this->sendResponse($dependencies->toArray(), trans('data_obtained_successfully'));
    }
}
