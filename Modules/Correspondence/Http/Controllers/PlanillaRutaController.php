<?php

namespace Modules\Correspondence\Http\Controllers;

use App\Exports\GenericExport;
use Modules\Correspondence\Http\Requests\CreatePlanillaRutaRequest;
use Modules\Correspondence\Http\Requests\UpdatePlanillaRutaRequest;
use Modules\Correspondence\Repositories\PlanillaRutaRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Maatwebsite\Excel\Facades\Excel;
use Response;
use Auth;
use DB;
use Modules\Correspondence\Models\PlanillaRuta;
use Modules\Correspondence\Models\PlanillaRutaDependencia;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class PlanillaRutaController extends AppBaseController {

    /** @var  PlanillaRutaRepository */
    private $planillaRutaRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(PlanillaRutaRepository $planillaRutaRepo) {
        $this->planillaRutaRepository = $planillaRutaRepo;
    }

    /**
     * Muestra la vista para el CRUD de PlanillaRuta.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        return view('correspondence::planilla_rutas.index');

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
        $planilla_rutas = PlanillaRuta::with(["planillaRutaDependencias"])->get();
        return $this->sendResponse($planilla_rutas->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreatePlanillaRutaRequest $request
     *
     * @return Response
     */
    public function store(CreatePlanillaRutaRequest $request) {

        $input = $request->all();
        // Obtiene información del usuario en sesión
        $usuario = Auth::user();
        $input["users_id"] = $usuario->id;
        $input["nombre_usuario"] = $usuario->name;

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $planillaRuta = $this->planillaRutaRepository->create($input);
            // Condición para validar si existe algún registro de dependencias
            if (!empty($input['planilla_ruta_dependencias'])) {
                // Ciclo para recorrer todos los registros de dependencias
                foreach($input['planilla_ruta_dependencias'] as $option){

                    $dependencia = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    PlanillaRutaDependencia::create([
                        'correspondence_planilla_ruta_id' => $planillaRuta->id,
                        'dependencias_id' => $dependencia->dependencias_id 
                    ]);
                }
            }
            // Carga las dependencias asociadas a la ruta
            $planillaRuta->planillaRutaDependencias;
            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($planillaRuta->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaRutaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaRutaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage());
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
     * @param UpdatePlanillaRutaRequest $request
     *
     * @return Response
     */
    public function update($id, UpdatePlanillaRutaRequest $request) {

        $input = $request->all();

        /** @var PlanillaRuta $planillaRuta */
        $planillaRuta = $this->planillaRutaRepository->find($id);

        if (empty($planillaRuta)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $planillaRuta = $this->planillaRutaRepository->update($input, $id);
            // Condición para validar si existe algún registro de dependencias
            if (!empty($input['planilla_ruta_dependencias'])) {
                // Elimina los registros de las dependencias según el id del registro de la ruta
                PlanillaRutaDependencia::where('correspondence_planilla_ruta_id', $planillaRuta->id)->delete();
                // Ciclo para recorrer todos los registros de dependencias
                foreach($input['planilla_ruta_dependencias'] as $option){

                    $dependencia = json_decode($option);
                    // Se crean la cantidad de registros ingresados por el usuario
                    PlanillaRutaDependencia::create([
                        'correspondence_planilla_ruta_id' => $planillaRuta->id,
                        'dependencias_id' => $dependencia->dependencias_id 
                    ]);
                }
            }
            // Carga las dependencias asociadas a la ruta
            $planillaRuta->planillaRutaDependencias;
            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($planillaRuta->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaRutaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaRutaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine().'  Id: '.($planillaRuta['id'] ?? 'Desconocido'));
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'), 'info');
        }
    }

    /**
     * Elimina un PlanillaRuta del almacenamiento
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
        // Se eliminan primero todas las dependencias asociadas a la ruta
        PlanillaRutaDependencia::where("correspondence_planilla_ruta_id", $id)->delete();
        /** @var PlanillaRuta $planillaRuta */
        $planillaRuta = $this->planillaRutaRepository->find($id);

        if (empty($planillaRuta)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $planillaRuta->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaRutaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'), 'info');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Correspondence\Http\Controllers\PlanillaRutaController - '. (Auth::user()->name ?? 'Usuario Desconocido').' -  Error: '.$e->getMessage(). ' Linea: '.$e->getLine().'  Id: '.($planillaRuta['id'] ?? 'Desconocido'));
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
        $fileName = time().'-'.trans('planilla_rutas').'.'.$fileType;

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
