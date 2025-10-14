<?php

namespace Modules\Leca\Http\Controllers;

use DB;
use Auth;
use Flash;
use Response;
use Illuminate\Http\Request;
use App\Exports\GenericExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\AppBaseController;
use Modules\Leca\Models\ObsercacionesDuplicadoAlcalinidad;
use Modules\Leca\Repositories\ObsercacionesDuplicadoAlcalinidadRepository;
use Modules\Leca\Http\Requests\CreateObsercacionesDuplicadoAlcalinidadRequest;
use Modules\Leca\Http\Requests\UpdateObsercacionesDuplicadoAlcalinidadRequest;

/**
 * Descripcion de la clase
 *
 * @author Desarrollador Seven - 2022
 * @version 1.0.0
 */
class ObservacionesDuplicadoAlcalinidadController extends AppBaseController {

    /** @var  ObsercacionesDuplicadoAlcalinidadRepository */
    private $ObsercacionesDuplicadoAlcalinidadRepository;

    /**
     * Constructor de la clase
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     */
    public function __construct(ObsercacionesDuplicadoAlcalinidadRepository $ObsercacionesDuplicadoAlcalinidadRepo) {
        $this->ObsercacionesDuplicadoAlcalinidadRepository = $ObsercacionesDuplicadoAlcalinidadRepo;
    }

    /**
     * Muestra la vista para el CRUD de ObsercacionesDuplicadoAlcalinidad.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request) {

        $lc_ensayo_alcalinidad_id=$request['lc_ensayo_alcalinidad_id'];

        return view('leca::observaciones_duplicado_alcalinidad.index', compact('lc_ensayo_alcalinidad_id'));
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
        
        $observaciones_duplicado_alcalinidads = ObsercacionesDuplicadoAlcalinidad::where('lc_ensayo_alcalinidad_id',$request['lc_ensayo_alcalinidad_id'])->get();

        return $this->sendResponse($observaciones_duplicado_alcalinidads->toArray(), trans('data_obtained_successfully'));
    }

    /**
     * Guarda un nuevo registro al almacenamiento
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param CreateObsercacionesDuplicadoAlcalinidadRequest $request
     *
     * @return Response
     */
    public function store(CreateObsercacionesDuplicadoAlcalinidadRequest $request) {
        
        $input = $request->all();
        $user = Auth::user();

        
        $input['users_id'] = $user->id;
        $input['name_user'] = $user->name;
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Inserta el registro en la base de datos
            $ObsercacionesDuplicadoAlcalinidad = $this->ObsercacionesDuplicadoAlcalinidadRepository->create($input);

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendResponse($ObsercacionesDuplicadoAlcalinidad->toArray(), trans('msg_success_save'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObsercacionesDuplicadoAlcalinidadController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObsercacionesDuplicadoAlcalinidadController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Actualiza un registro especifico.
     *
     * @author Desarrollador Seven - 2022
     * @version 1.0.0
     *
     * @param int $id
     * @param UpdateObsercacionesDuplicadoAlcalinidadRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateObsercacionesDuplicadoAlcalinidadRequest $request) {

        $input = $request->all();

        /** @var ObsercacionesDuplicadoAlcalinidad $ObsercacionesDuplicadoAlcalinidad */
        $ObsercacionesDuplicadoAlcalinidad = $this->ObsercacionesDuplicadoAlcalinidadRepository->find($id);

        if (empty($ObsercacionesDuplicadoAlcalinidad)) {
            return $this->sendError(trans('not_found_element'), 200);
        }
        
        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Actualiza el registro
            $ObsercacionesDuplicadoAlcalinidad = $this->ObsercacionesDuplicadoAlcalinidadRepository->update($input, $id);

            // Efectua los cambios realizados
            DB::commit();
        
            return $this->sendResponse($ObsercacionesDuplicadoAlcalinidad->toArray(), trans('msg_success_update'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObsercacionesDuplicadoAlcalinidadController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObsercacionesDuplicadoAlcalinidadController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
            // Retorna error de tipo logico
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $e->getMessage(), 'error');
        }
    }

    /**
     * Elimina un ObsercacionesDuplicadoAlcalinidad del almacenamiento
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

        /** @var ObsercacionesDuplicadoAlcalinidad $ObsercacionesDuplicadoAlcalinidad */
        $ObsercacionesDuplicadoAlcalinidad = $this->ObsercacionesDuplicadoAlcalinidadRepository->find($id);

        if (empty($ObsercacionesDuplicadoAlcalinidad)) {
            return $this->sendError(trans('not_found_element'), 200);
        }

        // Inicia la transaccion
        DB::beginTransaction();
        try {
            // Elimina el registro
            $ObsercacionesDuplicadoAlcalinidad->delete();

            // Efectua los cambios realizados
            DB::commit();

            return $this->sendSuccess(trans('msg_success_drop'));
        } catch (\Illuminate\Database\QueryException $error) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObsercacionesDuplicadoAlcalinidadController - '. Auth::user()->name.' -  Error: '.$error->getMessage());
            // Retorna mensaje de error de base de datos
            return $this->sendSuccess(config('constants.support_message'). '<br>' . $error->errorInfo[2], 'error');
        } catch (\Exception $e) {
            // Devuelve los cambios realizados
            DB::rollback();
            // Inserta el error en el registro de log
            $this->generateSevenLog('module_name', 'Modules\Leca\Http\Controllers\ObsercacionesDuplicadoAlcalinidadController - '. Auth::user()->name.' -  Error: '.$e->getMessage());
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
        $fileName = time().'-'.trans('observaciones_duplicado_alcalinidads').'.'.$fileType;

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
